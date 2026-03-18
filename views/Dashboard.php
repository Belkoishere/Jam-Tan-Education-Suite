<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include "../nav/nav.html";
require("../controllers/DashBoardData.php");
require_once("../locales/Language.php");
require_once("../locales/French.php");
require_once("../locales/Translate.php");

$French = new Translate (new French);

$AccountName = $_SESSION['AccountName'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="Dashboard.css">
    <style>
        #p_dashboard {
            font-weight: bold;
        }
        #s_dashboard {
            font-weight: bold;
        }
    </style>
</head>
<body>
    
    <div id="main">
        <h2><?php echo "Bienvenue " . $AccountName;?></h2>

        <div class="table-container">

            <div id="YourPrograms" class="table">
                <h3>Programmes que vous enseignez</h3>
                <div class="table-contents-container">
                    <?php $i = 1; foreach ($YourPrograms as $Program): ?>
                        <p>
                            <?= htmlspecialchars($i . ". " . 
                            $Program["ProgramName"]) ?>
                        </p>
                    <?php $i++; endforeach ?>
                </div>
                <a href="YourPrograms.php"><div class="table-btn">Vous programmes</div></a>
            </div>
            
            <div id="UpcomingAssessments" class="table">
                <h3>Évaluations à venir</h3>
                <div class="table-contents-container">
                    <?php $i = 1; foreach ($UpcomingAssessments as $Assessment): ?>
                        <p>
                            <?= htmlspecialchars($i . ". " . 
                            $Assessment["AssessmentName"]) . ": " ?> 
                            <?= htmlspecialchars(
                            $Assessment["AssessmentDueDate"]) ?>
                        </p>
                    <?php $i++; endforeach ?>
                </div>
                <a href="YourAssessments.php"><div class="table-btn">Vos évaluations</div></a>
            </div>

            <div id="PupilsAtRisk" class="table">
                <h3>Élèves à risque</h3>
                <div class="table-contents-container">
                    <?php $i = 1; foreach ($StudentsAtRisk as $Student): ?>
                        <p>
                            <?= htmlspecialchars($i . ". " . $Student["StudentLastName"]) ?> 
                            <?= htmlspecialchars($Student["StudentFirstName"])?>
                            <?= htmlspecialchars("(" . $Student["StudentID"]) . ")"?>
                            <?= htmlspecialchars("(" . $Student["ProgramName"]) . ")" . "<br>"?>
                            <?= htmlspecialchars("Frequentation moyenne: " . 
                            $Student["AverageAttendance"]) . "%<br>"?>
                            <?= htmlspecialchars("Taux de réussite: " . 
                            $Student["PassRate"] . "%")?>
                        </p>
                    <?php $i++; endforeach ?>
                </div>
                <a href="YourStudents.php"><div class="table-btn">Vos élèves</div></a>
            </div>

            <div id="AverageAttendance" class="table">
                <h3>Fréquentation moyenne <?php echo("(" . $French->Translate(date("F")) . ")");?></h3>
                <div class="table-contents-container">

                    <?php foreach ($AverageAttendance as $Average): ?>
                        <p>
                            <?= htmlspecialchars($Average["ProgramName"]) . "("?>
                            <?= htmlspecialchars($Average["AverageAttendance"] . "%)")?>
                        </p>
                    <?php endforeach ?>   

                </div>
                <a href="AttendanceHistory.php"><div class="table-btn">Historique de présence</div></a>
            </div>

        </div>
    </div>
</body>
</html>