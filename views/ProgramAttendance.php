<?php

session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include "../nav/nav.html";
require("../controllers/db.php");

$ProgramID = $_GET['id'] ?? '';
$ProgramName = $_GET['name'] ?? '';
$CategoryName = $_GET['category'] ?? '';

$Attendances = $_POST["Attendance"] ?? '';
$Reasons = $_POST["Reason"] ?? '';
$EnrollmentIDs = $_POST["EnrollmentID"] ?? '';

$GetStudents = $conn->prepare("SELECT Student.StudentID, 
Student.StudentFirstName, 
Student.StudentLastName,
Student.StudentPicture,
Enrollment.EnrollmentID
FROM
Enrollment
INNER JOIN Student ON Enrollment.StudentID = Student.StudentID
WHERE Enrollment.ProgramID = ?");

$GetStudents->execute([$ProgramID]);
$Students = $GetStudents->fetchAll(PDO::FETCH_ASSOC);

$InsertAttendance = "INSERT INTO StudentAttendance (EnrollmentID, Attendance, Reason)
VALUES";

if($Attendances !== "" && $Reasons !== "" && $EnrollmentIDs !== ""){

    for ($i = 0; $i < count($Attendance); $i ++){
        $InsertAttendance .= " ($EnrollmentIDs[$i], $Attendance[$i], $Reasons[$i]),";
    }

    substr($InsertAttendance, 0, -1);

    $stmt = $conn-prepare($InsertAttendance);
    $stmt->execute();

    $conn = null;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registre de présence</title>
    <style>
        #p_take_attendance {
            font-weight: bold;
        }
        #s_take_attendance {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="ProgramAttendance.css">
</head>
<body>

    <a href="TakeAttendance.php">
        <img src="../icons/navigation-back-arrow-svgrepo-com.svg" alt="back icon" class="back-icon">
    </a>

    <div id="main">

        <h2>
            <?= "Registre de présence " . htmlspecialchars($CategoryName . " " . $ProgramName . " " . date("d/m/y"))?>
        </h2>

        <div class="form-container">
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                <table>
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Présence</th>
                        <th>Raison</th>
                    </tr>
                <?php foreach ($Students as $Student):?>
                    <tr>
                        <td>
                            <div style="width: 200px; height: 200px; overflow: hidden;">
                                <img style="width: 200px height: auto; margin: -13px 0 0 -25px;" 
                                src="../StudentImages/<?= htmlspecialchars($Student["StudentPicture"]) ?>.jpg" 
                                alt="">
                            </div>
                        </td>
                        <td>
                            <input style="display: none" type="text" name="EnrollmentID" value="<?= htmlspecialchars($Student["EnrollmentID"])?>">
                            <?= htmlspecialchars($Student["StudentLastName"] . " " . $Student["StudentFirstName"])?>
                        </td>
                        <td>
                            <select name="Attendance">
                                <option value="Present">Présent</option>
                                <option value="Absent">Absent</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="Reason">
                        </td>
                    </tr>
                <?php endforeach?>
                </table>
                <input type="submit" value="Complète">
            </form>
        </div>
    </div>
</body>
</html>