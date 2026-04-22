<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include("../nav/nav.html");
require("../controllers/db.php");

// Validate GET
$AssessmentID = $_GET['assessment_id'] ?? null;

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

// Get Grades
$GetGrades = $conn->prepare("
    SELECT 
        Student.StudentFirstName, Student.StudentLastName,
        Student.StudentPicture,
        Grade.Grade, Grade.Feedback, Grade.Pass
    FROM Assessment
    INNER JOIN Grade
        ON Assessment.AssessmentID = Grade.AssessmentID
    INNER JOIN Enrollment
        ON Grade.EnrollmentID = Enrollment.EnrollmentID
    INNER JOIN Student
        ON Enrollment.StudentID = Student.StudentID
    WHERE Assessment.AssessmentID = ?
");

$GetGrades->execute([$AssessmentID]);
$Grades = $GetGrades->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="css/Table.css">
    <link rel="stylesheet" href="css/ProfilePicture.css">
</head>
<body>

<a href="ProgramAssessments.php?program_id=<?= $Assessment["ProgramID"]?>">
    <img src="../icons/navigation-back-arrow-svgrepo-com.svg" 
         alt="back icon" class="back-icon">
</a>

<div id="main">

    <h2><?= htmlspecialchars($Assessment["CategoryName"] . " " . $Assessment["ProgramName"] . " - " . $Assessment["AssessmentName"] . " - Voir les notes") ?></h2>

        <table>
            <tr>
                <th>Image</th>
                <th>Nom</th>
                <th>Note / <?= htmlspecialchars($Assessment["MaxGrade"]);?></th>
                <th>Réussite / Échec</th>
                <th>Evaluation</th>
            </tr>

            <?php foreach ($Grades as $Grade): ?>
            <tr style="background-color: white !important;">
                <td>
                        <img  
                            class="profile-picture"
                            src="../StudentImages/<?= htmlspecialchars($Grade["StudentPicture"]) ?>.jpg"
                            alt="">
                </td>

                <td>
                    <?= htmlspecialchars($Grade["StudentLastName"] . " " . $Grade["StudentFirstName"]) ?>
                </td>

                <td>
                    <?= htmlspecialchars($Grade["Grade"]);?>
                </td>

                <td>
                    <?= htmlspecialchars($Grade["Pass"]);?>
                </td>
                <td>
                    <?= htmlspecialchars($Grade["Feedback"])?>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>
    </div>
</div>

</body>
</html>