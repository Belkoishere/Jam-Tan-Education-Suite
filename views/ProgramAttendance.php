<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

require("../controllers/db.php");

$ProgramID = $_GET['program_id'] ?? null;
$Messages = [];

$GetDates = $conn->prepare("SELECT StudentAttendance.AttendanceDate
FROM StudentAttendance
INNER JOIN Enrollment
ON StudentAttendance.EnrollmentID = Enrollment.EnrollmentID 
WHERE Enrollment.ProgramID = ?
AND StudentAttendance.AttendanceDate = CURRENT_DATE()");

$GetDates->execute([$ProgramID]);
$Dates = $GetDates->fetchAll(PDO::FETCH_ASSOC);

if (count($Dates) > 1){
    header('Location: TakeAttendance.php?Warning');
    exit;
}

include("../nav/nav.html");
include("Alert.html");

$Complete = false;

// Counters
$Present = 0;
$Absent = 0;
$Mpresent = 0;
$Mabsent = 0;
$Fpresent = 0;
$Fabsent = 0;

$LastPage = $_SERVER['HTTP_REFERER'];

$LastPages = ["http://localhost/Jam-Tan-Education-Suite/views/YourPrograms.php", 
"http://localhost/Jam-Tan-Education-Suite/views/AllPrograms.php", 
"http://localhost/Jam-Tan-Education-Suite/views/TakeAttendance.php"];

$LastSignificantPage = "";

if (in_array($LastPage, $LastPages) && !isset($_SESSION["LastPageProgramAttendance"])) {
    $_SESSION["LastPageProgramAttendance"] = $LastPage;
    $LastSignificantPage = $LastPage;
}
else if (in_array($LastPage, $LastPages) && isset($_SESSION["LastPageProgramAttendance"])) {
    $_SESSION["LastPageProgramAttendance"] = $LastPage;
    $LastSignificantPage = $LastPage;
}
else if (isset($_SESSION["LastPageProgramAttendance"]) && in_array($_SESSION["LastPageProgramAttendance"], $LastPages)) {
    $LastSignificantPage = $_SESSION["LastPageProgramAttendance"];
}
else {
    $_SESSION["LastPageProgramAttendance"] = $LastPages[2];
    $LastSignificantPage = $LastPages[2];
}

$GetProgram = $conn->prepare("SELECT Program.ProgramName, ProgramCategory.CategoryName
FROM Program
INNER JOIN ProgramCategory
ON Program.CategoryID = ProgramCategory.CategoryID
WHERE Program.ProgramID = ?");

$GetProgram->execute([$ProgramID]);
$Program = $GetProgram->fetch(PDO::FETCH_ASSOC);

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
    $Attendances = $_POST["Attendance"] ?? [];
    $Reasons = $_POST["Reason"] ?? [];

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
            try {
                $stmt = $conn->prepare($sql);
                $stmt->execute($params);
                $Complete = true;
                $Messages["Success"] = "Register added successfully";
            }
            catch (Exception $e){
                $Messages["Warning"] = $e;
            }
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

    <link rel="stylesheet" href="css/FormTable.css">
    <link rel="stylesheet" href="css/ProfilePictureSmall.css">

    <style>
        #p_take_attendance, #s_take_attendance {
            font-weight: bold;
        }
    </style>
</head>
<body>

<a href="<?= $LastSignificantPage?>">
    <img src="../icons/navigation-back-arrow-svgrepo-com.svg" 
         alt="back icon" class="back-icon">
</a>

<div id="main">

    <h2>
        <?= htmlspecialchars("Registre de présence " . $Program["CategoryName"] . " " . $Program["ProgramName"] . " " . date("d/m/y")) ?>
    </h2>

    <form action="" method="POST" style="<?= $Complete ? 'display:none;' : '' ?>">

        <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>Image</th>
                <th>Nom</th>
                <th>Présence</th>
                <th>Raison</th>
            </tr>
            </thead>

            <?php foreach ($Students as $Student): ?>
                <tbody>
                <tr>
                    <td>
                        <img 
                            src="../StudentImages/<?= htmlspecialchars($Student["StudentPicture"]) ?>.jpg"
                            alt=""
                            class="profile-picture"
                        >
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
                </tbody>
            <?php endforeach; ?>

        </table>
        </div>

        <input type="submit" value="Complète">

    </form>

    <!-- RESULTS -->
    <div class="results-container" style="<?= $Complete ? 'display:block;' : 'display:none;' ?>">
        <h2>Résultats</h2>

        <p>Total Présent: <?= $Present ?></p>
        <p>Total Absent: <?= $Absent ?></p>

        <p>Garçons Présents: <?= $Mpresent ?></p>
        <p>Garçons Absents: <?= $Mabsent ?></p>

        <p>Filles Présentes: <?= $Fpresent ?></p>
        <p>Filles Absentes: <?= $Fabsent ?></p>

        <a href="TakeAttendance.php" class="Button">Prenez une autre registre</a>
        <a href="Dashboard.php" class="Button">Tableau de bord</a>
    </div>

</div>
<script>window.Messages = <?= json_encode($Messages, JSON_HEX_TAG); ?>;</script>
<script src="Alert.js"></script>
</body>
</html>