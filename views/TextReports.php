<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}
require("../controllers/db.php");
include "../nav/nav.html";

$ProgramID = $_GET['program_id'] ?? null;

// Filters:
$InMonth = $_POST['Month'] ?? '';
$InYear = $_POST['Year'] ?? '';

$GetProgram = $conn->prepare("SELECT Program.ProgramName, ProgramCategory.CategoryName
FROM Program 
INNER JOIN ProgramCategory 
ON Program.CategoryID = ProgramCategory.CategoryID
WHERE Program.ProgramID = ?");

$GetProgram->execute([$ProgramID]);
$Program = $GetProgram->fetch(PDO::FETCH_ASSOC);

$GetYears = "SELECT DISTINCT Year(StudentAttendance.AttendanceDate) AS AttendanceYear
FROM Enrollment 
INNER JOIN StudentAttendance ON Enrollment.EnrollmentID = StudentAttendance.EnrollmentID
WHERE Enrollment.ProgramID = ?";

$stmt = $conn->prepare($GetYears);
$stmt->execute([$ProgramID]);

$Years = $stmt->fetchAll(PDO::FETCH_ASSOC);

$GetDates = "SELECT StudentAttendance.AttendanceDate, StudentAttendance.AttendanceID
FROM Enrollment 
INNER JOIN StudentAttendance ON Enrollment.EnrollmentID = StudentAttendance.EnrollmentID
WHERE Enrollment.ProgramID = :id";

$params = ['id' => $ProgramID];

if ($InMonth !== '') {
    $GetDates .= " AND MONTH(StudentAttendance.AttendanceDate) = :in_month";

    $params['in_month'] = $InMonth;
}

if ($InYear !== '') {
    $GetDates .= " AND YEAR(StudentAttendance.AttendanceDate) = :in_year";
    
    $params['in_year'] = $InYear;
}

$GetDates .= " ORDER BY StudentAttendance.AttendanceDate desc";

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
    <title>Historiques de présence</title>
    <style>
        #p_attendance_history {
            font-weight: bold;
        }
        #s_attendance_history {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="css/Result.css">
    <link rel="stylesheet" href="css/form.css">
</head>
<body>

    <a href="AttendanceHistory.php">
            <img src="../icons/navigation-back-arrow-svgrepo-com.svg" 
            alt="back icon" class="back-icon">
    </a>

    <div id="main">

        <h2>
            <?= htmlspecialchars("Rapports de fréquentation " . $Program["CategoryName"] . " " . $Program["ProgramName"])?>
        </h2>   
        
        <div class="form-container">
            <form action="" method="POST">
                <label for="Year">Année</label>
                <select name="Year">
                    <option value="">Tout</option>
                    <?php foreach ($Years as $Year): ?>
                        <option value="<?= htmlspecialchars($Year["AttendanceYear"])?>"
                        <?= (($_POST['Year'] ?? '') == $Year["AttendanceYear"]) ? 'selected' : ''?>>
                            <?= htmlspecialchars($Year["AttendanceYear"])?>
                        </option>
                    <?php endforeach ?>
                </select>
                <label for="Month">Mois</label>
                <select name="Month">
                    <option value="">Tout</option>
                    <option value="1" <?= (($_POST['Month'] ?? '') == '1') ? 'selected' : ''?>>Janvier</option>
                    <option value="2" <?= (($_POST['Month'] ?? '') == '2') ? 'selected' : ''?>>Février</option>
                    <option value="3" <?= (($_POST['Month'] ?? '') == '3') ? 'selected' : ''?>>Mars</option>
                    <option value="4" <?= (($_POST['Month'] ?? '') == '4') ? 'selected' : ''?>>Avril</option>
                    <option value="5" <?= (($_POST['Month'] ?? '') == '5') ? 'selected' : ''?>>Mai</option>
                    <option value="6" <?= (($_POST['Month'] ?? '') == '6') ? 'selected' : ''?>>Juin</option>
                    <option value="7" <?= (($_POST['Month'] ?? '') == '7') ? 'selected' : ''?>>Juillet</option>
                    <option value="8" <?= (($_POST['Month'] ?? '') == '8') ? 'selected' : ''?>>Août</option>
                    <option value="9" <?= (($_POST['Month'] ?? '') == '9') ? 'selected' : ''?>>Septembre</option>
                    <option value="10" <?= (($_POST['Month'] ?? '') == '10') ? 'selected' : ''?>>Octobre</option>
                    <option value="11" <?= (($_POST['Month'] ?? '') == '11') ? 'selected' : ''?>>Novembre</option>
                    <option value="12" <?= (($_POST['Month'] ?? '') == '12') ? 'selected' : ''?>>Décembre</option>
                </select>
                <input type="submit" value="Filtre"></input>
                
            </form>
        </div>

        <p>Résultats: <?= htmlspecialchars($NumRows);?></p>

        <div class="results-container">
            <?php foreach ($Dates as $Date): ?>
                <a href="TextReport.php?attendance_id=<?= $Date["AttendanceID"]?>" class="result">

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
