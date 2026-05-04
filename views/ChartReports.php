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

$ProgramID = $_GET['program_id'] ?? null;

$GetYears = "SELECT DISTINCT Year(StudentAttendance.AttendanceDate) AS AttendanceYear
FROM Program 
INNER JOIN Enrollment ON Program.ProgramID = Enrollment.ProgramID
INNER JOIN StudentAttendance ON Enrollment.EnrollmentID = StudentAttendance.EnrollmentID
WHERE Program.ProgramID = ?";

$stmt2 = $conn->prepare($GetYears);
$stmt2->execute([$ProgramID]);

$Years = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$Yearsarr = [];

foreach($Years as $i => $Y){
    $Yearsarr[$i] = $Y["AttendanceYear"];
}

$InYear = $_GET['InYear'] ?? reset($Yearsarr);

$GetProgram = $conn->prepare("SELECT Program.ProgramName, ProgramCategory.CategoryName
FROM Program 
INNER JOIN ProgramCategory 
ON Program.CategoryID = ProgramCategory.CategoryID
WHERE Program.ProgramID = ?");

$GetProgram->execute([$ProgramID]);
$Program = $GetProgram->fetch(PDO::FETCH_ASSOC);

$GetMonthAverages = "SELECT ROUND(AVG(StudentAttendance.Attendance-1) * 100, 0) AS AverageAttendance, 
MONTH(StudentAttendance.AttendanceDate) AS M
FROM StudentAttendance
INNER JOIN Enrollment ON StudentAttendance.EnrollmentID = Enrollment.EnrollmentID
WHERE Enrollment.ProgramID = :program_id
AND YEAR(StudentAttendance.AttendanceDate) = :in_year
GROUP BY MONTH(StudentAttendance.AttendanceDate)";

$stmt = $conn->prepare($GetMonthAverages);
$stmt->execute(["program_id" => $ProgramID, "in_year" => $InYear]);

$MonthAverages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$GetWeekAverages = "SELECT ROUND(AVG(StudentAttendance.Attendance-1) * 100, 0) AS AverageAttendance, 
WEEK(StudentAttendance.AttendanceDate) AS M
FROM StudentAttendance
INNER JOIN Enrollment ON StudentAttendance.EnrollmentID = Enrollment.EnrollmentID
WHERE Enrollment.ProgramID = :program_id
AND YEAR(StudentAttendance.AttendanceDate) = :in_year
GROUP BY WEEK(StudentAttendance.AttendanceDate)";

$stmt0 = $conn->prepare($GetWeekAverages);
$stmt0->execute(["program_id" => $ProgramID, "in_year" => $InYear]);

$WeekAverages = $stmt0->fetchAll(PDO::FETCH_ASSOC);

$GetOverallAverage = "SELECT ROUND(AVG(StudentAttendance.Attendance-1) * 100, 0) AS AverageAttendance, MONTH(StudentAttendance.AttendanceDate) AS M
FROM StudentAttendance
INNER JOIN Enrollment ON StudentAttendance.EnrollmentID = Enrollment.EnrollmentID
WHERE Enrollment.ProgramID = ?
AND YEAR(StudentAttendance.AttendanceDate) = ?";

$stmt1 = $conn->prepare($GetOverallAverage);
$stmt1->execute([$ProgramID, $InYear]);

$OverallAverage = $stmt1->fetch(PDO::FETCH_ASSOC);

$conn = null;

$French = new Translate (new French);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique de presences</title>
</head>
<style>
    #p_attendance_history {
            font-weight: bold;
        }
    #s_attendance_history {
        font-weight: bold;
    }
</style>
<link rel="stylesheet" href="css/form.css">
<link rel="stylesheet" href="css/Table.css">
<link rel="stylesheet" href="css/CircularProgressBar.css">
<body>
    
<a href="AttendanceHistory.php">
    <img src="../icons/navigation-back-arrow-svgrepo-com.svg" 
    alt="back icon" class="back-icon">
</a>

<div id="main">
    
    <h2>
        <?= htmlspecialchars("Rapport de fréquentation " . $Program["CategoryName"] . " " . $Program["ProgramName"] . " (" . $InYear . ")")?>
    </h2> 

    <div class="form-container">
        <form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
            <label for="InYear">Année</label>
            <select name="InYear">
                <?php foreach ($Years as $Year): ?>
                    <option value="<?= htmlspecialchars($Year["AttendanceYear"])?>"
                    <?= (($_GET['InYear'] ?? '') == $Year["AttendanceYear"]) ? 'selected' : ''?>>
                        <?= htmlspecialchars($Year["AttendanceYear"])?>
                    </option>
                <?php endforeach ?>
            </select>
            <input type="hidden" name="program_id" value="<?= $ProgramID?>">
            <input type="submit" value="Cherche">
        </form>
    </div>
    
    <p>Moyenne générale: </p>
    <div class="circular-progress" 
        data-inner-circle-color="white" 
        data-percentage="<?= htmlspecialchars($OverallAverage["AverageAttendance"])?>" 
        data-progress-color="<?php if (htmlspecialchars($OverallAverage["AverageAttendance"]) <= 65){?>red<?php } 
        else if ($OverallAverage["AverageAttendance"] <= 85){?>orange<?php } else{?>green<?php }?>" 
        data-bg-color="black"
        style="margin-bottom: 20px;">
        <div class="inner-circle"></div>
        <p class="percentage">0%</p>
    </div>

    <div style="padding-bottom: 30px;">
        <canvas id="myChart" style="width: 100%;"></canvas>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mois</th>
                    <th>Fréquentation moyenne</th>
                </tr>
            </thead>
            <?php foreach ($MonthAverages as $Average): ?>
                <tbody>
                    <tr>
                        <td><?= $French->Translate(htmlspecialchars($Average["M"]))?></td>
                        <td><?= htmlspecialchars($Average["AverageAttendance"]) . "%"?></td>
                    </tr>
                </tbody>
            <?php endforeach?>
        </table>
    </div>

    

</div>

<script src="../controllers/chart.umd.js"></script>
<script>
    const ctx = document.getElementById('myChart');
    const AverageAttendances = <?= json_encode($MonthAverages, JSON_HEX_TAG); ?>;
    console.log(AverageAttendances);

    var Months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    const FullAverages = new Array(12).fill(null);

    for (let i = 0; i < AverageAttendances.length; i++) {
        const monthIndex = AverageAttendances[i].M - 1;
        FullAverages[monthIndex] = AverageAttendances[i].AverageAttendance;
    }

    // for(let j = 0; j < Months.length; j ++){
    //     FullAverages[j] = null;
    // }

    // for (let k = 0; k < MonthIndexes.length; k ++){
    //     FullAverages[MonthIndexes[k]] = Averages[k];
    //     console.log(FullAverages[MonthIndexes[k]]);
    // }

    new Chart(ctx, {
        type: 'line',
        data: {
        labels: Months,
        datasets: [{
            label: 'Fréquentation Moyenne par mois (%) (' + <?= $InYear ?> + ")",
            data: FullAverages,
            borderWidth: 1
        }]
        },
        options: {
        scales: {
            y: {
            beginAtZero: true
            }
        }
        }
    });
</script>
<script src="css/CircularProgressBar.js"></script>
</body>
</html>