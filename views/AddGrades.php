<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include("../nav/nav.html");
include("Alert.html");
require("../controllers/db.php");

$AssessmentID = $_GET['assessment_id'] ?? null;

$InEnrollmentIDs = $_POST["EnrollmentID"] ?? [];
$InGrades = $_POST["Grade"] ?? [];
$InFeedbacks = $_POST["Feedback"] ?? [];

$Messages = [];

$GetAssessment = $conn->prepare("SELECT Assessment.AssessmentName, Assessment.MaxGrade,
Assessment.PassGrade, Program.ProgramName, Program.ProgramID, ProgramCategory.CategoryName
FROM Assessment
INNER JOIN Program
ON Assessment.ProgramID = Program.ProgramID
INNER JOIN ProgramCategory
ON Program.CategoryID = ProgramCategory.CategoryID
WHERE Assessment.AssessmentID = ?");

$GetAssessment->execute([$AssessmentID]);
$Assessment = $GetAssessment->fetch(PDO::FETCH_ASSOC);

$params = [];

if(!empty($InGrades)) {

for ($i = 0; $i < count($InGrades); $i++) {
    $InEnrollmentID = $InEnrollmentIDs[$i];
    $InAssessmentID = $AssessmentID;
    $InGrade = $InGrades[$i];
    $InFeedback = $InFeedbacks[$i];
    $InPass = "Pass";

    if ($InGrade === "" || $InGrade === null) {
        continue;
    }

    if ($InGrade < $Assessment["PassGrade"]){
        $InPass = "Fail";
    }

    $params[] = [$InEnrollmentID, $InAssessmentID, $InGrade, $InFeedback, $InPass];
}

$placeholders = [];
$values = [];
foreach ($params as $row) {
    $placeholders[] = "(?, ?, ?, ?, ?)";
    $values[] = $row[0];
    $values[] = $row[1];
    $values[] = $row[2];
    $values[] = $row[3];
    $values[] = $row[4];
}

try {
    $SaveGrades = "INSERT INTO Grade (EnrollmentID, AssessmentID, Grade, Feedback, Pass) 
    VALUES " . implode(", ", $placeholders) . " ON DUPLICATE KEY UPDATE
    Grade = VALUES(Grade), Pass = VALUES(Pass), Feedback = VALUES(Feedback)";

    $stmt1 = $conn->prepare($SaveGrades);
    $stmt1->execute($values);

    $Messages["Success"] = "Grades saved successfully";
}
catch(Exception $e){
    $Messages["Warning"] = $e;
}

}

$GetGrades = "SELECT Student.StudentFirstName, Student.StudentLastName,
Student.StudentID, Student.StudentPicture,
Grade.Grade, Grade.Feedback, Enrollment.EnrollmentID,
Grade.Pass 
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
$stmt->execute(["assessment_id" => $AssessmentID, "program_id" => $Assessment["ProgramID"]]);

$Grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="css/form.css">
</head>
<body>

<a href="ProgramAssessments.php?program_id=<?=$Assessment["ProgramID"]?>">
    <img src="../icons/navigation-back-arrow-svgrepo-com.svg" 
         alt="back icon" class="back-icon">
</a>

<div id="main">

    <h2><?= htmlspecialchars($Assessment["CategoryName"] . " " . $Assessment["ProgramName"] . " - " . $Assessment["AssessmentName"] . " - Ajouter les notes") ?></h2>

    <div class="form-container">
        <form action="" method="POST">
            
            <table>
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Note / <?= htmlspecialchars($Assessment["MaxGrade"]);?></th>
                    <th>Réussite / Échec</th>
                    <th>Evaluation</th>
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
                            <input type="number" min="0" max="<?=$Assessment["MaxGrade"]?>" name="Grade[]" value="<?= htmlspecialchars($Grade["Grade"]);?>" placeholder="<?= htmlspecialchars($Grade["Grade"]);?>">
                        </td>

                        <td>
                            <?= htmlspecialchars($Grade["Pass"]);?>
                        </td>
                        <td>
                            <input type="text" name="Feedback[]" value="<?= htmlspecialchars($Grade["Feedback"]) ?>" placeholder="<?= htmlspecialchars($Grade["Feedback"]) ?>">
                        </td>
                    </tr>
                    <input type="hidden" value="<?= $Grade["EnrollmentID"]?>" name="EnrollmentID[]">
                <?php endforeach; ?>
            </table>
           
            <input type="hidden" value="<?= $AssessmentID?>" name="assessment_id">
            <input type="hidden" value="<?= $ProgramID?>" name="program_id">
            <input type="submit" value="Sauvegarde"></input>
        </form>
    </div>

</div>
<script>window.Messages = <?= json_encode($Messages, JSON_HEX_TAG); ?>;</script>
<script src="Alert.js"></script>
</body>
</html>