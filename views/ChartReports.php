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

$ProgramID = $_GET['id'] ?? null;
$ProgramName = $_GET['name'] ?? '';
$CategoryName = $_GET['category'] ?? '';

$GetAverages = "SELECT ROUND(AVG(StudentAttendance.Attendance-1) * 100, 0) AS AverageAttendance, 
MONTHNAME(StudentAttendance.AttendanceDate) AS M
FROM StudentAttendance
INNER JOIN Enrollment ON StudentAttendance.EnrollmentID = Enrollment.EnrollmentID
WHERE Enrollment.ProgramID = ?
GROUP BY MONTH(StudentAttendance.AttendanceDate)";

$stmt = $conn->prepare($GetAverages);
$stmt->execute([$ProgramID]);

$Averages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$GetOverallAverage = "SELECT ROUND(AVG(StudentAttendance.Attendance-1) * 100, 0) AS AverageAttendance, MONTH(StudentAttendance.AttendanceDate) AS M
FROM StudentAttendance
INNER JOIN Enrollment ON StudentAttendance.EnrollmentID = Enrollment.EnrollmentID
WHERE Enrollment.ProgramID = ?
AND YEAR(StudentAttendance.AttendanceDate) = ?";

$stmt1 = $conn->prepare($GetOverallAverage);
$stmt1->execute([$ProgramID, date("Y")]);

$OverallAverage = $stmt1->fetch(PDO::FETCH_ASSOC);

$GetYears = "SELECT DISTINCT Year(StudentAttendance.AttendanceDate) AS AttendanceYear
FROM Program 
INNER JOIN Enrollment ON Program.ProgramID = Enrollment.ProgramID
INNER JOIN StudentAttendance ON Enrollment.EnrollmentID = StudentAttendance.EnrollmentID
WHERE Program.ProgramID = ?";

$stmt2 = $conn->prepare($GetYears);
$stmt2->execute([$ProgramID]);

$Years = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$conn = null;

$French = new Translate (new French);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chart Reports</title>
</head>
<style>
    #p_attendance_history {
            font-weight: bold;
        }
    #s_attendance_history {
        font-weight: bold;
    }
</style>
<link rel="stylesheet" href="form.css">
<body>
    
<a href="AttendanceHistory.php">
    <img src="../icons/navigation-back-arrow-svgrepo-com.svg" 
    alt="back icon" class="back-icon">
</a>

<div id="main">
    
    <h2>
        <?= htmlspecialchars("Rapport de fréquentation $CategoryName $ProgramName")?>
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
            <input type="submit" value="Cherche">
        </form>
    </div>
    
    <p>Moyenne générale: <?= htmlspecialchars($OverallAverage["AverageAttendance"]) . "%"?></p>
    
    <p>Fréquentation Moyenne par mois <?= "(" . date("Y") . ")"?>: </p>

    <div>
        <canvas id="myChart"></canvas>
    </div>

    <table>
        <tr>
            <th>Mois</th>
            <th>Fréquentation moyenne</th>
        </tr>
        <?php foreach ($Averages as $Average): ?>
            <tr>
                <td><?= $French->Translate(htmlspecialchars($Average["M"]))?></td>
                <td><?= htmlspecialchars($Average["AverageAttendance"]) . "%"?></td>
            </tr>
        <?php endforeach?>
    </table>

</div>

<script src="../controllers/chart.umd.js"></script>
<script>
    const ctx = document.getElementById('myChart');
    const AverageAttendances = <?= json_encode($Averages, JSON_HEX_TAG); ?>;
    console.log(AverageAttendances);

    var Months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var InMonths = [];
    var MonthIndexes = [];
    var Averages = [];
    var FullAverages = [];

    for(i = 0; i < AverageAttendances.length; i ++){
        Averages[i] = AverageAttendances[i].AverageAttendance;
        InMonths[i] = AverageAttendances[i].M;
        MonthIndexes[i] = Months.indexOf(AverageAttendances[i].M);
    }

    for(j = 0; j < Months.length; j ++){
        FullAverages[j] = null;
    }

    for (k = 0; k < MonthIndexes.length; k ++){
        FullAverages[MonthIndexes[k]] = Averages[k];
        console.log(MonthIndexes[k]);
    }

    new Chart(ctx, {
        type: 'line',
        data: {
        labels: Months,
        datasets: [{
            label: '# of Votes',
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
</body>
</html>