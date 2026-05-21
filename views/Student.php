<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

require("../controllers/db.php");

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

$StudentID = filter_input(INPUT_GET, 'student_id', FILTER_VALIDATE_INT);

if ($StudentID === false || $StudentID === null) {
    header("Location: " . $LastSignificantPage);
    exit;
}

$Student = $conn->prepare("SELECT COUNT(*) AS NumbStudents FROM Student WHERE Student.StudentID = ?");
$Student->execute([$StudentID]);
$NumbStudents = $Student->fetch(PDO::FETCH_ASSOC);

if($NumbStudents["NumbStudents"] != 1) {
    header("Location: " . $LastSignificantPage);
}

include("../nav/nav.html");

$Attributes = [];
$Programs = [];
$Attendances = [];
$Grades = [];

$StudentInfo = $conn->prepare("CALL StudentInfo(?)");
$StudentInfo->execute([$StudentID]);

if ($StudentInfo) {

    $Attributes = $StudentInfo->fetch(PDO::FETCH_ASSOC);
    $StudentInfo->nextRowset();

    $Programs = $StudentInfo->fetchAll(PDO::FETCH_ASSOC);
    $StudentInfo->nextRowset();

    $Attendances = $StudentInfo->fetchAll(PDO::FETCH_ASSOC);
    $StudentInfo->nextRowset();

    $Grades = $StudentInfo->fetchAll(PDO::FETCH_ASSOC);
    $StudentInfo->nextRowset();

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
    <link rel="stylesheet" href="css/AddBtn.css">
    <link rel="stylesheet" href="css/ProfilePicture.css">
    <link rel="stylesheet" href="css/CircularProgressBarSmall.css">
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

        <div style="padding-bottom: 20px;">

        <img class="profile-picture" src="<?= htmlspecialchars("../StudentImages/" . $Attributes["StudentPicture"] . ".jpg")?>" alt="">

        <p>Âge/Sexe: <?= htmlspecialchars($Attributes["Age"] . "/" . $Attributes["StudentGender"])?></p>

        <p>Date de naissance: <?= htmlspecialchars($Attributes["StudentBirthDate"])?></p>

        <p>Ville de résidence: <?= htmlspecialchars($Attributes["StudentTown"])?></p>

        <p>Contact 1: <?= htmlspecialchars($Attributes["Contact1"])?></p>

        <p>Contact 2: <?= htmlspecialchars($Attributes["Contact2"])?></p>

        <p>Père: <?= htmlspecialchars($Attributes["FatherFirst_Name"] . " " . $Attributes["FatherLast_Name"])?></p>

        <p>Mère: <?= htmlspecialchars($Attributes["MotherFirst_Name"] . " " . $Attributes["MotherLast_Name"])?></p>

        <p>Classe: <?= htmlspecialchars($Attributes["StudentSchool_Year"])?></p>

        </div>

        <p>Programmes:</p>

        <?php if(count($Programs) >= 1) {?>
        <div class="table-container">
        <table>
            <tr>
                <th>Programme</th>
                <th>Date d'inscription</th>
            </tr>
            <?php foreach($Programs as $Program): ?>

                <tr>
                    <td><?= htmlspecialchars($Program["CategoryName"] . " " . $Program["ProgramName"])?></td>
                    <td><?= htmlspecialchars($Program["EnrollmentDate"])?></td>
                </tr>

            <?php endforeach;?>
        </table>
        </div>

        <?php } else {?>
                <p>Inscrit à aucun programme</p>
        <?php }?>

        <p>Présence:</p>
        
        <?php if(count($Attendances) >= 1) {?>
        <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>Programme</th>
                <th>Fréquentation moyenne <?= "(" . date("Y") . ")"?></th>
                <th></th>
            </tr>
            </thead>
            <?php foreach($Attendances as $Attendance):?>
                <tbody>
                <tr>
                    <td><?= htmlspecialchars($Attendance["CategoryName"] . " - " . $Attendance["ProgramName"]) ?></td>
                    <td>
                        <div class="circular-progress" 
                            data-inner-circle-color="white" 
                            data-percentage="<?= htmlspecialchars($Attendance["AverageAttendance"])?>" 
                            data-progress-color="<?php if (htmlspecialchars($Attendance["AverageAttendance"]) <= 65){?>red<?php } 
                            else if ($Attendance["AverageAttendance"] <= 85) {?>orange<?php } else { ?>green<?php }?>"
                            data-bg-color="black">

                            <div class="inner-circle"></div>
                            <p class="percentage">0%</p>
                
                        </div>
                    </td>
                    <td>
                        
                        <a href="StudentAttendanceHistory.php?student_id=<?= $StudentID ?>&program_id=<?= $Attendance["ProgramID"]?>" class="add-btn">
                                Rapports
                        </a>
                        
                    </td>
                </tr>
                </tbody>
            <?php endforeach;?>    
        </table>
        </div>
        <?php } else {?>
                <p>Aucune présence enregistrée</p>
        <?php }?>

        <p>Notes:</p>
        
        <?php if(count($Grades) >= 1) {?>
        <div class="table-container">
        <table>
            <tr>
                <th>Programme</th>
                <th>Taux de réussite <?= "(" . date("Y") . ")" ?></th>
            </tr>

            <?php foreach($Grades as $Grade):?>

                <tr>
                    <td><?= htmlspecialchars($Grade["CategoryName"] . " - " . $Grade["ProgramName"])?></td>
                    <td>
                        <div class="circular-progress" 
                            data-inner-circle-color="white" 
                            data-percentage="<?= htmlspecialchars($Grade["PassRate"])?>" 
                            data-progress-color="<?php if (htmlspecialchars($Grade["PassRate"]) <= 65){?>red<?php } 
                            else if ($Grade["PassRate"] <= 85) {?>orange<?php } else { ?>green<?php }?>"
                            data-bg-color="black">

                            <div class="inner-circle"></div>
                            <p class="percentage">0%</p>
                
                        </div>
                    </td>
                    <td>
                        <a href="StudentGradeHistory.php?student_id=<?= $StudentID ?>&program_id=<?= $Grade["ProgramID"]?>" class="add-btn">
                                Rapports   
                        </a>
                    </td>
                </tr>

            <?php endforeach;?>
        </table>
        </div>
        <?php } else {?>
                <p>Aucune note enregistrée</p>
        <?php }?>

    </div>
<script src="css/CircularProgressBar.js"></script>
</body>
</html>