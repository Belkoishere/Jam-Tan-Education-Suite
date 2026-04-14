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

$GetAverages = "SELECT ROUND(AVG(StudentAttendance.Attendance-1) * 100, 0) AS AverageAttendance, MONTH(StudentAttendance.AttendanceDate) AS M
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
<script src="path/to/chartjs/dist/chart.umd.min.js"></script>
<script>
    const myChart = new Chart(ctx, {...});
</script>
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
    
<a href="<?= $_SERVER['HTTP_REFERER'] ?? '#'?>">
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
        </form>
    </div>
    
    <p>Moyenne générale: <?= htmlspecialchars($OverallAverage["AverageAttendance"]) . "%"?></p>
    
    <p>Moyenne par mois <?= "(" . date("Y") . ")"?>: </p>

    <table>
        <tr>
            <th>Mois</th>
            <th>Fréquentation moyenne</th>
        </tr>
        <?php foreach ($Averages as $Average): ?>
            <tr>
                <td><?= $French->Translate(htmlspecialchars(DateTime::createFromFormat('!m', $Average["M"])->format('F')))?></td>
                <td><?= htmlspecialchars($Average["AverageAttendance"]) . "%"?></td>
            </tr>
        <?php endforeach?>
    </table>



</div>

</body>
</html>