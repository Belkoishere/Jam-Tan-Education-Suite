<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include("../nav/nav.html");
require("../controllers/db.php");

$ProgramID = $_GET['program_id'] ?? null;
$AssessmentID = $_GET['assessment_id'] ?? null;

$InEnrollmentIDs = $_POST["EnrollmentID"] ?? [];
$InStudentIDs   = $_POST["StudentID"] ?? [];
$InGrades = $_POST["Grade"] ?? [];

$GetAssessment = $conn->prepare("SELECT Assessment.AssessmentName, Assessment.MaxGrade
FROM Assessment
WHERE Assessment.AssessmentID = ?");

$GetAssessment->execute([$AssessmentID]);
$Assessment = $GetAssessment->fetch(PDO::FETCH_ASSOC);

$GetProgram = $conn->prepare("SELECT Program.ProgramName, ProgramCategory.CategoryName
FROM Program 
INNER JOIN ProgramCategory 
ON Program.CategoryID = ProgramCategory.CategoryID
WHERE Program.ProgramID = ?");

$GetProgram->execute([$ProgramID]);
$Program = $GetProgram->fetch(PDO::FETCH_ASSOC);

$GetGrades = "SELECT Student.StudentFirstName, Student.StudentLastName,
Student.StudentID, Student.StudentPicture,
Grade.Grade, Enrollment.EnrollmentID
CASE WHEN Grade.Grade >= Assessment.PassGrade THEN 'Pass'
WHEN Grade.Grade < Assessment.PassGrade THEN 'Fail'
ELSE NULL END AS Pass 
FROM Student
INNER JOIN Enrollment
ON Student.StudentID = Enrollment.StudentID
LEFT JOIN Grade
ON Enrollment.EnrollmentID = Grade.EnrollmentID
AND Grade.AssessmentID = :assessment_id
LEFT JOIN
Assessment 
ON Grade.AssessmentID = Assessment.AssessmentID
WHERE Enrollment.ProgramID = :program_id";

$stmt = $conn->prepare($GetGrades);
$stmt->execute(["assessment_id" => $AssessmentID, "program_id" => $ProgramID]);

$Grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($InEnrollmentIDs) && !empty($InStudentIDs) && !empty($InGrades)){

    $SaveGrades = "INSERT INTO Grade (EnrollmentID, AssessmentID Grade) VALUES (?, ?, ?)";
    $params = [];

    foreach ($InGrades as $i => $Grade) {

        if (!isset($InEnrollmentIDs[$i], $InStudentIDs[$i], $InGrades[$i])) {
            continue;      
        }

        $params[] = $InEnrollmentIDs[$i];
        $params[] = $InStudentIDs[$i];
        $params[] = $InGrades[$i]; 
            
    }

    $SaveGrades .= " ON DUPLICATE KEY UPDATE
    grade = VALUES(grade);";

    if (!empty($params)) {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $Complete = true;
    }
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voir les notes</title>
    <style>
        #p_your_assessments, #s_your_assessments {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="form.css">
</head>
<body>

<a href="<?=htmlspecialchars($_SERVER['HTTP_REFERER'])?>">
    <img src="../icons/navigation-back-arrow-svgrepo-com.svg" 
         alt="back icon" class="back-icon">
</a>

<div id="main">

    <h2><?= htmlspecialchars($Program["CategoryName"] . " " . $Program["ProgramName"] . " - " . $Assessment["AssessmentName"] . " - Ajouter les notes") ?></h2>

    <div class="form-container">
        <form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
            
            <table>
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Note / <?= htmlspecialchars($Assessment["MaxGrade"]);?></th>
                    <th>Réussite / Échec</th>
                </tr>

                <?php foreach ($Grades as $Grade): ?>
                <tr>
                    <td>
                        <div style="width:125px;height:125px;overflow:hidden;">
                            <img 
                                style="width:125px; height:auto; margin:-13px 0 0 -25px;" 
                                src="../StudentImages/<?= htmlspecialchars($Grade["StudentPicture"]) ?>.jpg"
                                alt="">
                        </div>
                    </td>

                    <td>
                        <?= htmlspecialchars($Grade["StudentLastName"] . " " . $Grade["StudentFirstName"]) ?>
                    </td>

                    <td>
                        <input type="number" min="0" max="<?=$Assessment["MaxGrade"]?>" name="Grade[]" value="<?= htmlspecialchars($Grade["Grade"]);?>">
                    </td>

                    <td>
                        <?= htmlspecialchars($Grade["Pass"]);?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <input style="display:none;" type="number" value="<?= $Grade["StudentID"]?>" name="StudentID[]">
            <input style="display:none;" type="number" value="<?= $Grade["EnrollmentID"]?>" name="EnrollmentID[]">
            <input type="submit" value="Sauvegarde"></input>
        </form>
    </div>

</div>

</body>
</html>