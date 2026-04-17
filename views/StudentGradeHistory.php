<?php

session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include("../nav/nav.html");
require("../controllers/db.php");
require_once("../locales/Language.php");
require_once("../locales/French.php");
require_once("../locales/Translate.php");

$French = new Translate (new French);

$StudentID = $_GET["student_id"];
$ProgramID = $_GET["program_id"];

// Filters:
$InMonth = $_POST['Month'] ?? '';
$InYear = $_POST['Year'] ?? '';

$OriginPage = "http://localhost/Jam-Tan-Education-Suite/views/AllStudents.php";

if(isset($_SESSION["LastPageStudent"])){
    $OriginPage = $_SESSION["LastPageStudent"];
}

$LastSignificantPage = "http://localhost/Jam-Tan-Education-Suite/views/Student.php?student_id=" . $StudentID;

$GetYears = "SELECT DISTINCT Year(Grade.GradeDate) AS GradeYear
FROM Enrollment 
INNER JOIN Grade ON Enrollment.EnrollmentID = Grade.EnrollmentID
WHERE Enrollment.StudentID = :stid
AND Enrollment.ProgramID = :prid";

$params = ["stid" => $StudentID,
"prid" => $ProgramID];

$stmt = $conn->prepare($GetYears);
$stmt->execute($params);

$Years = $stmt->fetchAll(PDO::FETCH_ASSOC);

$GetStudent = $conn->prepare("SELECT Student.StudentFirstName, Student.StudentLastName
FROM Student
WHERE Student.StudentID = ?");

$GetStudent->execute([$StudentID]);

$Student = $GetStudent->fetch(PDO::FETCH_ASSOC);

$GetProgram = $conn->prepare("SELECT Program.ProgramName, ProgramCategory.CategoryName
FROM Program
INNER JOIN ProgramCategory ON Program.CategoryID = ProgramCategory.CategoryID
WHERE Program.ProgramID = ?");

$GetProgram->execute([$ProgramID]);

$Program = $GetProgram->fetch(PDO::FETCH_ASSOC);

$GetGrades = "SELECT Grade.Grade, Grade.GradeDate, Grade.Feedback, Grade.Pass,
Assessment.MaxGrade, Assessment.PassGrade
FROM Enrollment
INNER JOIN Grade ON Enrollment.EnrollmentID = Grade.EnrollmentID
INNER JOIN Assessment ON Grade.AssessmentID = Assessment.AssessmentID
WHERE Enrollment.StudentID = :stid
AND Enrollment.ProgramID = :prid";

if ($InMonth !== '') {
    $GetGrades .= " AND MONTH(Grade.GradeDate) = :in_month";

    $params['in_month'] = $InMonth;
}

if ($InYear !== '') {
    $GetGrades .= " AND YEAR(Grade.GradeDate) = :in_year";
    
    $params['in_year'] = $InYear;
}

$stmt1 = $conn->prepare($GetGrades);
$stmt1->execute($params);

$Grades = $stmt1->fetchAll(PDO::FETCH_ASSOC);

$NumRows = count($Grades);

$conn = null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports de présence</title>
</head>
<link rel="stylesheet" href="form.css">
<style>
    <?php if ($OriginPage == "http://localhost/Jam-Tan-Education-Suite/views/YourStudents.php"){ ?>
    
    #p_your_students, #s_your_students {
        font-weight: bold;
    }

<?php } ?>
<?php if ($OriginPage == "http://localhost/Jam-Tan-Education-Suite/views/AllStudents.php"){ ?>

    #p_all_students, #s_all_students {
        font-weight: bold;
    }

<?php } ?>
</style>
<body>
    
    <a href="<?= htmlspecialchars($LastSignificantPage)?>">
    <img src="../icons/navigation-back-arrow-svgrepo-com.svg" 
         alt="back icon" class="back-icon">
    </a>

    <div id="main">

        <h2><?= $Student["StudentFirstName"] . " " . $Student["StudentLastName"]?> - Rapport de notes <?= $Program["CategoryName"] . " " . $Program["ProgramName"]?></h2>

        <div class="form-container">
            <form action="" method="POST">
                <label for="Year">Année</label>
                <select name="Year">
                    <?php foreach ($Years as $Year): ?>
                        <option value="<?= htmlspecialchars($Year["GradeYear"])?>"
                        <?= (($_GET['InYear'] ?? '') == $Year["GradeYear"]) ? 'selected' : ''?>>
                            <?= htmlspecialchars($Year["GradeYear"])?>
                        </option>
                    <?php endforeach ?>
                </select>
                <label for="Month">Mois</label>
                <select name="Month">
                    <option value="">Tout</option>
                    <option value="1" <?= (($_GET['Month'] ?? '') == '1') ? 'selected' : ''?>>Janvier</option>
                    <option value="2" <?= (($_GET['Month'] ?? '') == '2') ? 'selected' : ''?>>Février</option>
                    <option value="3" <?= (($_GET['Month'] ?? '') == '3') ? 'selected' : ''?>>Mars</option>
                    <option value="4" <?= (($_GET['Month'] ?? '') == '4') ? 'selected' : ''?>>Avril</option>
                    <option value="5" <?= (($_GET['Month'] ?? '') == '5') ? 'selected' : ''?>>Mai</option>
                    <option value="6" <?= (($_GET['Month'] ?? '') == '6') ? 'selected' : ''?>>Juin</option>
                    <option value="7" <?= (($_GET['Month'] ?? '') == '7') ? 'selected' : ''?>>Juillet</option>
                    <option value="8" <?= (($_GET['Month'] ?? '') == '8') ? 'selected' : ''?>>Août</option>
                    <option value="9" <?= (($_GET['Month'] ?? '') == '9') ? 'selected' : ''?>>Septembre</option>
                    <option value="10" <?= (($_GET['Month'] ?? '') == '10') ? 'selected' : ''?>>Octobre</option>
                    <option value="11" <?= (($_GET['Month'] ?? '') == '11') ? 'selected' : ''?>>Novembre</option>
                    <option value="12" <?= (($_GET['Month'] ?? '') == '12') ? 'selected' : ''?>>Décembre</option>
                </select>
                <input type="submit" value="Filtre"></input>
                <input type="text" name="student_id" value="<?=$StudentID?>" style="display: none;">
                <input type="text" name="program_id" value="<?=$ProgramID?>" style="display: none;">
                <input type="text" name="last_page" value="<?=$LastPage?>" style="display: none;">
                <input type="text" name="double_last_page" value="<?=$DoubleLastPage?>" style="display: none;">
            </form>
        </div>

        <p>Resultas: <?= $NumRows?></p>

        <table>
            <tr>
                <th>Date</th>
                <th>Note</th>
                <th>Note de passage</th>
                <th>Réussite / Échec</th>
                <th>Retour d'évaluation</th>
            </tr>
            <?php foreach($Grades as $Grade): ?>
                <tr>
                    <td><?= htmlspecialchars($Grade["GradeDate"]);?></td>
                    <td><?= htmlspecialchars($Grade["Grade"] . "/" . $Grade["MaxGrade"]);?></td>
                    <td><?= htmlspecialchars($Grade["PassGrade"]);?></td>
                    <td><?= htmlspecialchars($French->Translate($Grade["Pass"]));?></td>
                    <td><?= htmlspecialchars($Grade["Feedback"]);?></td>
                </tr>
            <?php endforeach;?>
        </table>
    </div>

</body>
</html>