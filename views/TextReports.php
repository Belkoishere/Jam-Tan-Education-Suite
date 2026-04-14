<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

$ProgramID = $_GET['id'] ?? null;
$ProgramName = $_GET['name'] ?? '';
$CategoryName = $_GET['category'] ?? '';

// Filters:
$InMonth = $_GET['Month'] ?? '';
$InYear = $_GET['Year'] ?? '';

require("../controllers/db.php");
include "../nav/nav.html";

$GetYears = "SELECT DISTINCT Year(StudentAttendance.AttendanceDate) AS AttendanceYear
FROM Program 
INNER JOIN Enrollment ON Program.ProgramID = Enrollment.ProgramID
INNER JOIN StudentAttendance ON Enrollment.EnrollmentID = StudentAttendance.EnrollmentID
WHERE Program.ProgramID = ?";

$stmt = $conn->prepare($GetYears);
$stmt->execute([$ProgramID]);

$Years = $stmt->fetchAll(PDO::FETCH_ASSOC);

$GetDates = "SELECT StudentAttendance.AttendanceDate
FROM Program 
INNER JOIN Enrollment ON Program.ProgramID = Enrollment.ProgramID
INNER JOIN StudentAttendance ON Enrollment.EnrollmentID = StudentAttendance.EnrollmentID
WHERE Program.ProgramID = :id";

$params = ['id' => $ProgramID];

if ($InMonth !== '') {
    $GetDates .= " AND MONTH(StudentAttendance.AttendanceDate) = :in_month";

    $params['in_month'] = $InMonth;
}

if ($InYear !== '') {
    $GetDates .= " AND YEAR(StudentAttendance.AttendanceDate) = :in_year";
    
    $params['in_year'] = $InYear;
}

$stmt1 = $conn->prepare($GetDates);
$stmt1->execute($params);

$Dates = $stmt1->fetchAll(PDO::FETCH_ASSOC);

$NumRows = count($Dates);

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique de présence</title>
    <!-- <link rel="stylesheet" href="../nav/nav.css"> -->
    <style>
        #p_attendance_history {
            font-weight: bold;
        }
        #s_attendance_history {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="Result.css">
    <link rel="stylesheet" href="form.css">
</head>
<body>

    <a href="AttendanceHistory.php">
            <img src="../icons/navigation-back-arrow-svgrepo-com.svg" 
            alt="back icon" class="back-icon">
    </a>

    <div id="main">

        <h2>
            <?= htmlspecialchars("Rapports de fréquentation $CategoryName $ProgramName ")?>
        </h2>   
        
        <div class="form-container">
            <form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
                <label for="Year">Année</label>
                <select name="Year">
                    <?php foreach ($Years as $Year): ?>
                        <option value="<?= htmlspecialchars($Year["AttendanceYear"])?>"
                        <?= (($_GET['InYear'] ?? '') == $Year["AttendanceYear"]) ? 'selected' : ''?>>
                            <?= htmlspecialchars($Year["AttendanceYear"])?>
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
                <input type="text" name="id" value="<?=$ProgramID?>" style="display: none;">
                <input type="text" name="name" value="<?=$ProgramName?>" style="display: none;">
                <input type="text" name="category" value="<?=$CategoryName?>" style="display: none;">
            </form>
        </div>

        <p>Resultas <?= htmlspecialchars($NumRows);?></p>

        <div class="results-container">
            <?php foreach ($Dates as $Date): ?>
                <a href="TextReport.php?date=<?= $Date["AttendanceDate"]?>&id=<?=$ProgramID?>&name=<?= $ProgramName?>&category=<?= $CategoryName?>" class="result">

                    <p class="text">
                        <?= htmlspecialchars($Date["AttendanceDate"])?>
                    </p>
                    
                    <img src="../icons/arrow-next-svgrepo-com.svg" alt="forward-icon" class="forward-icon">

                </a>
            <?php endforeach?>
        </div>

    </div>
</body>
</html>
