<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include("../nav/nav.html");
require("../controllers/db.php");

$StudentID = $_GET['student_id'] ?? null;

$LastPage = $_SERVER['HTTP_REFERER'] ?? null;

$LastPages = ["http://localhost/Jam-Tan-Education-Suite/views/YourStudents.php",
"http://localhost/Jam-Tan-Education-Suite/views/AllStudents.php"];

$LastSignificantPage = "";

if (in_array($LastPage, $LastPages) && !isset($_SESSION["LastPageStudent"])) {
    $_SESSION["LastPageStudent"] = $LastPage;
    $LastSignificantPage = $LastPage;
}
else if (in_array($LastPage, $LastPages) && isset($_SESSION["LastPageStudent"])) {
    $_SESSION["LastPageStudent"] = $LastPage;
    $LastSignificantPage = $LastPage;
}
else if (isset($_SESSION["LastPageStudent"]) && in_array($_SESSION["LastPageStudent"], $LastPages)) {
    $LastSignificantPage = $_SESSION["LastPageStudent"];
}
else {
    $_SESSION["LastPageStudent"] = $LastPages[1];
    $LastSignificantPage = $LastPages[1];
}

$Data = $conn->query("CALL StudentInfo($StudentID)");

$Attributes = [];
$Programs = [];
$Attendances = [];
$Grades = [];

if ($Data) {

    $Attributes = $Data->fetch(PDO::FETCH_ASSOC);
    $Data->nextRowset();

    $Programs = $Data->fetchAll(PDO::FETCH_ASSOC);
    $Data->nextRowset();

    $Attendances = $Data->fetchAll(PDO::FETCH_ASSOC);
    $Data->nextRowset();

    $Grades = $Data->fetchAll(PDO::FETCH_ASSOC);
    $Data->nextRowset();

}

$conn = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éleve</title>
    <link rel="stylesheet" href="css/Table.css">
    <link rel="stylesheet" href="css/Button.css">
    <link rel="stylesheet" href="css/ProfilePicture.css">
</head>
<style>
<?php if ($LastSignificantPage == "http://localhost/Jam-Tan-Education-Suite/views/YourStudents.php"){ ?>
    
    #p_your_students, #s_your_students {
        font-weight: bold;
    }

<?php } ?>
<?php if($LastSignificantPage == "http://localhost/Jam-Tan-Education-Suite/views/AllStudents.php"){ ?>

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
        <h2><?= htmlspecialchars($Attributes["StudentFirstName"] . " " . $Attributes["StudentLastName"])?></h2>

        <img class="profile-picture" src="<?= htmlspecialchars("../StudentImages/" . $Attributes["StudentPicture"] . ".jpg")?>" alt="">

        <p>Âge/Sexe: <?= htmlspecialchars($Attributes["Age"] . "/" . $Attributes["StudentGender"])?></p>

        <p>Date de naissance: <?= htmlspecialchars($Attributes["StudentBirthDate"])?></p>

        <p>Ville: <?= htmlspecialchars($Attributes["StudentTown"])?></p>

        <p>Contact 1: <?= htmlspecialchars($Attributes["Contact1"])?></p>

        <p>Contact 2: <?= htmlspecialchars($Attributes["Contact2"])?></p>

        <p>Père: <?= htmlspecialchars($Attributes["FatherFirst_Name"] . " " . $Attributes["FatherLast_Name"])?></p>

        <p>Mère: <?= htmlspecialchars($Attributes["MotherFirst_Name"] . " " . $Attributes["MotherLast_Name"])?></p>

        <p>Classe: <?= htmlspecialchars($Attributes["StudentSchool_Year"])?></p>

        <p>Programmes:</p>

        <table>
            <tr>
                <th>Programme</th>
                <th>Date d'inscription</th>
            </tr>
            <?php foreach($Programs as $Program): ?>

                <tr>
                    <td><?= htmlspecialchars($Program["ProgramName"] . " " . $Program["CategoryName"])?></td>
                    <td><?= htmlspecialchars($Program["EnrollmentDate"])?></td>
                </tr>

            <?php endforeach;?>
        </table>

        <p>Présence:</p>

        <table>
            <tr>
                <th>Programme</th>
                <th>Fréquentation moyenne <?= "(" . date("Y") . ")"?></th>
                <th></th>
            </tr>
            <?php foreach($Attendances as $Attendance):?>

                <tr>
                    <td><?= htmlspecialchars($Attendance["ProgramName"] . $Attendance["CategoryName"])?></td>
                    <td><?= htmlspecialchars($Attendance["AverageAttendance"] . "%")?></td>
                    <td>
                        <a href="StudentAttendanceHistory.php?student_id=<?= $StudentID ?>&program_id=<?= $Attendance["ProgramID"]?>">
                            <div class="Button">
                                Rapports
                            </div>
                        </a>
                    </td>
                </tr>

            <?php endforeach;?>    
        </table>

        <p>Notes:</p>

        <table>
            <tr>
                <th>Programme</th>
                <th>Note moyenne <?= "(" . date("Y") . ")" ?></th>
            </tr>

            <?php foreach($Grades as $Grade):?>

                <tr>
                    <td><?= htmlspecialchars($Grade["ProgramName"] . $Grade["CategoryName"])?></td>
                    <td><?= htmlspecialchars($Grade["AverageGrade"] . "%")?></td>
                    <td>
                        <a href="StudentGradeHistory.php?student_id=<?= $StudentID ?>&program_id=<?= $Grade["ProgramID"]?>">
                            <div class="Button">
                                Rapports
                            </div>    
                        </a>
                    </td>
                </tr>

            <?php endforeach;?>
        </table>
    </div>

    
</body>
</html>