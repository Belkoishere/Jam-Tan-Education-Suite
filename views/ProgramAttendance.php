<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include("../nav/nav.html");
require("../controllers/db.php");

$Complete = false;

// Counters
$Present = 0;
$Absent = 0;
$Mpresent = 0;
$Mabsent = 0;
$Fpresent = 0;
$Fabsent = 0;

// Validate GET
$ProgramID = $_GET['id'] ?? null;
$ProgramName = $_GET['name'] ?? '';
$CategoryName = $_GET['category'] ?? '';

if ($LastPage == ''){
    $LastPage = "TakeAttendance.php";
}

if (!$ProgramID) {
    die("Invalid Program ID");
}

// Fetch students
$GetStudents = $conn->prepare("
    SELECT 
        Student.StudentID,
        Student.StudentFirstName,
        Student.StudentLastName,
        Student.StudentPicture,
        Student.StudentGender,
        Enrollment.EnrollmentID
    FROM Enrollment
    INNER JOIN Student 
        ON Enrollment.StudentID = Student.StudentID
    WHERE Enrollment.ProgramID = ?
");

$GetStudents->execute([$ProgramID]);
$Students = $GetStudents->fetchAll(PDO::FETCH_ASSOC);

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $EnrollmentIDs = $_POST["EnrollmentID"] ?? [];
    $Attendances   = $_POST["Attendance"] ?? [];
    $Reasons       = $_POST["Reason"] ?? [];

    if (!empty($EnrollmentIDs) && !empty($Attendances)) {

        $sql = "INSERT INTO StudentAttendance (EnrollmentID, Attendance, Reason) VALUES ";
        $params = [];

        foreach ($Students as $i => $Student) {

            if (!isset($EnrollmentIDs[$i], $Attendances[$i], $Reasons[$i])) {
                continue;
            }

            $attendance = $Attendances[$i];
            $reason     = $Reasons[$i];
            $enrollID   = $EnrollmentIDs[$i];

            // Build query safely
            $sql .= "(?, ?, ?),";
            $params[] = $enrollID;
            $params[] = $attendance;
            $params[] = $reason;

            // Count totals
            if ($attendance === "Present") {
                $Present++;

                if ($Student["StudentGender"] === "Male") {
                    $Mpresent++;
                } elseif ($Student["StudentGender"] === "Female") {
                    $Fpresent++;
                }

            } elseif ($attendance === "Absent") {
                $Absent++;

                if ($Student["StudentGender"] === "Male") {
                    $Mabsent++;
                } elseif ($Student["StudentGender"] === "Female") {
                    $Fabsent++;
                }
            }
        }

        // Remove last comma
        $sql = rtrim($sql, ',');

        if (!empty($params)) {
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $Complete = true;
        }
    }
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registre de présence</title>

    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="ProgramAttendance.css">

    <style>
        #p_take_attendance, #s_take_attendance {
            font-weight: bold;
        }
    </style>
</head>
<body>

<a href="<?=htmlspecialchars($_SERVER['HTTP_REFERER'])?>">
    <img src="../icons/navigation-back-arrow-svgrepo-com.svg" 
         alt="back icon" class="back-icon">
</a>

<div id="main">

    <h2>
        <?= htmlspecialchars("Registre de présence $CategoryName $ProgramName " . date("d/m/y")) ?>
    </h2>

    <!-- FORM -->
    <div class="form-container" style="<?= $Complete ? 'display:none;' : '' ?>">

        <form action="" method="POST">

            <table>
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Présence</th>
                    <th>Raison</th>
                </tr>

                <?php foreach ($Students as $Student): ?>
                <tr>
                    <td>
                        <div style="width:200px;height:200px;overflow:hidden;">
                            <img 
                                style="width:200px; height:auto; margin:-13px 0 0 -25px;" 
                                src="../StudentImages/<?= htmlspecialchars($Student["StudentPicture"]) ?>.jpg"
                                alt=""
                            >
                        </div>
                    </td>

                    <td>
                        <input type="hidden" 
                               name="EnrollmentID[]" 
                               value="<?= htmlspecialchars($Student["EnrollmentID"]) ?>">

                        <?= htmlspecialchars($Student["StudentLastName"] . " " . $Student["StudentFirstName"]) ?>
                    </td>

                    <td>
                        <select name="Attendance[]">
                            <option value="Present">Présent</option>
                            <option value="Absent">Absent</option>
                        </select>
                    </td>

                    <td>
                        <input type="text" name="Reason[]">
                    </td>
                </tr>
                <?php endforeach; ?>

            </table>

            <input type="submit" value="Complète">
        </form>
    </div>

    <!-- RESULTS -->
    <div class="results-container" style="<?= $Complete ? 'display:block;' : 'display:none;' ?>">
        <h2>Résultats</h2>

        <p>Total Présent: <?= $Present ?></p>
        <p>Total Absent: <?= $Absent ?></p>

        <p>Garçons Présents: <?= $Mpresent ?></p>
        <p>Garçons Absents: <?= $Mabsent ?></p>

        <p>Filles Présentes: <?= $Fpresent ?></p>
        <p>Filles Absentes: <?= $Fabsent ?></p>

        <a href="TakeAttendance.php">Prenez une autre registre</a>
        <a href="Dashboard.php">Tableau de bord</a>
    </div>

</div>

</body>
</html>