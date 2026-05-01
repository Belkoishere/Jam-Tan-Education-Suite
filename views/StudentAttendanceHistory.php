<?php

session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include("../nav/nav.html");
require("../controllers/db.php");

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

$GetYears = "SELECT DISTINCT Year(StudentAttendance.AttendanceDate) AS AttendanceYear
FROM Enrollment 
INNER JOIN StudentAttendance ON Enrollment.EnrollmentID = StudentAttendance.EnrollmentID
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

$GetAttendances = "SELECT StudentAttendance.Attendance, StudentAttendance.AttendanceDate,
StudentAttendance.Reason 
FROM Enrollment
INNER JOIN StudentAttendance ON Enrollment.EnrollmentID = StudentAttendance.EnrollmentID
WHERE Enrollment.StudentID = :stid
AND Enrollment.ProgramID = :prid";

if ($InMonth !== '') {
    $GetAttendances .= " AND MONTH(StudentAttendance.AttendanceDate) = :in_month";

    $params['in_month'] = $InMonth;
}

if ($InYear !== '') {
    $GetAttendances .= " AND YEAR(StudentAttendance.AttendanceDate) = :in_year";
    
    $params['in_year'] = $InYear;
}

$stmt1 = $conn->prepare($GetAttendances);
$stmt1->execute($params);

$Attendances = $stmt1->fetchAll(PDO::FETCH_ASSOC);

$NumRows = count($Attendances);

$conn = null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports de présence</title>
</head>
<link rel="stylesheet" href="css/form.css">
<link rel="stylesheet" href="css/Table.css">
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
        <h2><?= $Student["StudentFirstName"] . " " . $Student["StudentLastName"]?> - Rapport de présence <?= $Program["CategoryName"] . " " . $Program["ProgramName"]?></h2>

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
                <input type="text" name="student_id" value="<?=$StudentID?>" style="display: none;">
                <input type="text" name="program_id" value="<?=$ProgramID?>" style="display: none;">
                <input type="text" name="last_page" value="<?=$LastPage?>" style="display: none;">
                <input type="text" name="double_last_page" value="<?=$DoubleLastPage?>" style="display: none;">
            </form>
        </div>

        <p>Resultas: <?= $NumRows?></p>
        
        <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>Date</th>
                <th>Absent / Présent</th>
                <th>Raison</th>
            </tr>
            </thead>
            <?php foreach($Attendances as $Attendance): ?>
                <tbody>
                <tr>
                    <td><?= htmlspecialchars($Attendance["AttendanceDate"]);?></td>
                    <td><?= htmlspecialchars($Attendance["Attendance"]);?></td>
                    <td><?= htmlspecialchars($Attendance["Reason"]);?></td>
                </tr>
                </tbody>
            <?php endforeach;?>
        </table>
        </div>
    </div>

</body>
</html>