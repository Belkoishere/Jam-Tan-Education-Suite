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
    <link rel="stylesheet" href="css/Dashboard.css">
    <link rel="stylesheet" href="css/CircularProgressBar.css">
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

    <div class="dashboard">

        <!-- Summary Row -->
        <div class="summary-row">

            <!-- Attendance Card -->
            <div class="card">
                <h3>Frequentation moyenne (Janvier)</h3>

                <div class="program-rows">

                    <?php foreach($AverageAttendance as $Average): ?>

                        <div class="program-row">
                            <span class="program-name"><?= htmlspecialchars($Average["CategoryName"] . " " . $Average["ProgramName"])?></span>
                            <div class="circular-progress" 
                                data-inner-circle-color="white" 
                                data-percentage="<?= htmlspecialchars($Average["AverageAttendance"])?>" 
                                data-progress-color="<?php if (htmlspecialchars($Average["AverageAttendance"]) < 50){?>red<?php } else {?>green<?php } ?>"
                                data-bg-color="black">

                                <div class="inner-circle"></div>
                                <p class="percentage">0%</p>
                                <span class="attendance-delta <?php if ($Average["Difference"] > 0){?>positive<?php }else if ($Average["Difference"] == 0){?>
                                stable<?php} else{ ?>negative<?php }?>">
                                <?php if ($Average["Difference"] > 0){echo "+";}else if($Average["Difference"] == 0){echo "stable";}else{echo "-";}?>
                                <?= htmlspecialchars($Average["Difference"])?>%</span>
                            </div>
                        </div>

                    <?php endforeach?>

                    <a href="AttendanceHistory.php" class="card-btn">Historique de presence</a>
                </div>
            </div>

            <!-- Risk Summary Card -->
            <div class="card">

            <h3>Eleves a risque</h3>

            <table>
                <thead>
                    <tr>
                        <th>Programme</th>
                        <th>Nombre a risque haut</th>
                        <th>Nombre a risque modere</th>
                        <th>% a risk</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($RiskSummaries as $Summary): ?>
                        <tr>
                            <td><?= htmlspecialchars($Summary["CategoryName"] . " " . $Summary["ProgramName"])?></td>
                            <td class="bade badge-high"><?= htmlspecialchars($Summary["HighRisk"])?></td>
                            <td class="bade badge-moderate"><?= htmlspecialchars($Summary["ModerateRisk"])?></td>

                            <td class="<?php if($Summary["PercentageAtRisk"] < 25){?>bade badge-low<?php } 
                            else if ($Summary["PercentageAtRisk"] < 50) {?>bade badge-moderate<?php } else {?>
                            badge badge-high<?php }?>">
                                <?= htmlspecialchars($Summary["PercentageAtRisk"]) . " %"?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <a href="YourStudents.php" class="card-btn">Vos eleves</a>
            </div>

            <!-- Highest Risk Students -->
            <div class="card">
            <h3>Eleves les plus a risque</h3>

            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Programme</th>
                        <th>Frequentation</th>
                        <th>Taut de reussite</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($StudentsAtRisk as $Student): ?>
                        <tr>
                            <td><?= htmlspecialchars($Student["StudentLastName"]) . " " . $Student["StudentFirstName"] ?></td>
                            <td><?= htmlspecialchars($Student["CategoryName"]) . " - " . $Student["ProgramName"]?></td>
                            
                            <td class="<?php if($Student["AverageAttendance"] >= 85){?>bade badge-low<?php } 
                            else if ($Student["AverageAttendance"] >= 65) {?>bade badge-moderate<?php } else {?>
                            badge badge-high<?php }?>">
                                <?= htmlspecialchars($Student["AverageAttendance"]) . " %"?></td>

                            <td class="<?php if($Student["PassRate"] = 100){?>bade badge-low<?php } 
                            else if ($Student["PassRate"] >= 90) {?>bade badge-moderate<?php } else {?>
                            badge badge-high<?php }?>">
                                <?= htmlspecialchars($Student["PassRate"]) . " %"?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <a href="YourStudents.php" class="card-btn">Vos eleves</a>
            </div>

            <!-- Upcoming Assessments -->
            <div class="card">
                <h3>Evaluations a venir</h3>
                <table>
                    <tbody>
                        <?php foreach ($UpcomingAssessments as $Assessment): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($Assessment["CategoryName"] . " " . $Assessment["ProgramName"]) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($Assessment["AssessmentName"]) . ": " ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($Assessment["AssessmentDueDate"]) ?>
                            </td>
                            <td>
                                <div class="assessment-tag<?php if($Assessment["DaysRemaining"] >= 25){?> assessment-tag-far<?php } 
                            else if ($Assessment["DaysRemaining"] >= 15) {?> assessment-tag-middle<?php } else {?>
                             assessment-tag-close<?php }?>">
                             <?php if ($Assessment["DaysRemaining"] == 1){?>
                                Demain
                             <?php } else{?>
                             <?= htmlspecialchars($Assessment["DaysRemaining"] . " jours")?>
                            <?php }?>
                            </div>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <a href="YourAssessments.php" class="card-btn">Vos evaluations</a>
            </div>

        </div>

    </div>
    <script src="css/CircularProgressBar.js"></script>
</body>
</html>