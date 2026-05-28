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

$French = new Translate (new French);

$ProgramID = $_GET['program_id'] ?? null;
$Date = $_GET['date'] ?? null;

$GetAttendance = $conn->prepare("SELECT StudentAttendance.AttendanceDate,
Program.ProgramName, ProgramCategory.CategoryName
FROM StudentAttendance
INNER JOIN Enrollment
ON StudentAttendance.EnrollmentID = Enrollment.EnrollmentID
INNER JOIN Program
ON Enrollment.ProgramID = Program.ProgramID
INNER JOIN ProgramCategory
ON Program.CategoryID = ProgramCategory.CategoryID
WHERE StudentAttendance.AttendanceDate = :attendance_date
AND Program.ProgramID = :program_id
GROUP BY StudentAttendance.AttendanceDate");

$GetAttendance->execute(["attendance_date" => $Date, "program_id" => $ProgramID]);
$AttendanceInfo = $GetAttendance->fetch(PDO::FETCH_ASSOC);

$GetAttendances = "SELECT StudentAttendance.Attendance, 
Student.StudentFirstName, Student.StudentLastName,
Student.StudentPicture, Student.StudentGender,
StudentAttendance.Reason
FROM Student INNER JOIN Enrollment
ON Student.StudentID = Enrollment.StudentID
INNER JOIN StudentAttendance
ON Enrollment.EnrollmentID = StudentAttendance.EnrollmentID
WHERE StudentAttendance.AttendanceDate = :attendance_date
AND Enrollment.ProgramID = :program_id";

$stmt = $conn->prepare($GetAttendances);
$stmt->execute(["attendance_date" => $Date, "program_id" => $ProgramID]);

$Attendances = $stmt->fetchAll(PDO::FETCH_ASSOC);

$Statistics = 
["Present" => ["Name" => "Present", "Score" => 0, "Percentage" => 0], 
"Absent" => ["Name" => "Absent", "Score" => 0, "Percentage" => 0],
"Males Present" => ["Name" => "Males Present", "Score" => 0, "Percentage" => 0],
"Females Present" => ["Name" => "Females Present", "Score" => 0, "Percentage" => 0],
"Total Males" => ["Name" => "Total Males", "Score" => 0, "Percentage" => 0],
"Total Females" => ["Name" => "Total Females", "Score" => 0, "Percentage" => 0],
"Total" => ["Name" => "Total", "Score" => 0, "Percentage" => 0]];

foreach ($Attendances as $Attendance){
    if($Attendance["StudentGender"] == "Male"){
        $Statistics["Total Males"]["Score"] ++;
    }
    if($Attendance["StudentGender"] == "Female"){
        $Statistics["Total Females"]["Score"] ++;
    }
    if ($Attendance["StudentGender"] == "Male" && $Attendance["Attendance"] == "Present") {
        $Statistics["Males Present"]["Score"] ++;
        $Statistics["Present"]["Score"] ++;
    }
    if ($Attendance["StudentGender"] == "Female" && $Attendance["Attendance"] == "Present"){
        $Statistics["Females Present"]["Score"] ++;
        $Statistics["Present"]["Score"] ++;
    }

}

$Statistics["Present"]["Percentage"] = ($Statistics["Present"]["Score"]/($Statistics["Total Males"]["Score"] + $Statistics["Total Females"]["Score"]))*100;
$Statistics["Absent"]["Percentage"] = (100 - $Statistics["Present"]["Percentage"]);
$Statistics["Total"]["Score"] = ($Statistics["Total Males"]["Score"] + $Statistics["Total Females"]["Score"]);

if($Statistics["Total Males"]["Score"] != 0){
    $Statistics["Males Present"]["Percentage"] = ($Statistics["Males Present"]["Score"]/$Statistics["Total Males"]["Score"])*100;
}
if($Statistics["Total Females"]["Score"] != 0){
    $Statistics["Females Present"]["Percentage"] = ($Statistics["Females Present"]["Score"]/$Statistics["Total Females"]["Score"])*100;
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique de présence</title>
    <link rel="stylesheet" href="css/Table.css">
    <link rel="stylesheet" href="css/ProfilePictureSmall.css">
</head>
<style>
    #p_attendance_history {
            font-weight: bold;
        }
    #s_attendance_history {
        font-weight: bold;
    }
</style>
<body>

    <a href="<?= $_SERVER['HTTP_REFERER'] ?? '#'?>">
        <img src="../icons/navigation-back-arrow-svgrepo-com.svg" 
        alt="back icon" class="back-icon">
    </a>

    <div id="main">

        <h2>
            <?= htmlspecialchars("Rapport de fréquentation " . $AttendanceInfo["CategoryName"] . " " . $AttendanceInfo["ProgramName"] . " " . $AttendanceInfo["AttendanceDate"])?>
        </h2> 

        <div class="table-container">
        <table>            
                <tr>
                    <td>
                        <?= htmlspecialchars($Statistics["Present"]["Name"])?>
                    </td>
                    <td>
                        <?= htmlspecialchars($Statistics["Present"]["Score"]) . "/" . $Statistics["Total"]["Score"]?>
                    </td>
                    <td>
                        <?= htmlspecialchars($Statistics["Present"]["Percentage"]) . "%"?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= htmlspecialchars($Statistics["Absent"]["Name"])?>
                    </td>
                    <td>
                        <?= htmlspecialchars($Statistics["Absent"]["Score"]) . "/" . $Statistics["Total"]["Score"]?>
                    </td>
                    <td>
                        <?= htmlspecialchars($Statistics["Absent"]["Percentage"]) . "%"?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= htmlspecialchars($Statistics["Males Present"]["Name"])?>
                    </td>
                    <td>
                        <?= htmlspecialchars($Statistics["Males Present"]["Score"]) . "/" . $Statistics["Total Males"]["Score"]?>
                    </td>
                    <td>
                        <?= htmlspecialchars($Statistics["Males Present"]["Percentage"]) . "%"?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= htmlspecialchars($Statistics["Females Present"]["Name"])?>
                    </td>
                    <td>
                        <?= htmlspecialchars($Statistics["Females Present"]["Score"]) . "/" . $Statistics["Total Females"]["Score"]?>
                    </td>
                    <td>
                        <?= htmlspecialchars($Statistics["Females Present"]["Percentage"]) . "%"?>
                    </td>
                </tr>
        </table>
        </div>

        <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>Image</th>
                <th>Nom</th>
                <th>Présent / Absent</th>
                <th>Raison</th>
            </tr>
            </thead>

            <?php foreach ($Attendances as $Attendance): ?>
            <tbody>
            <tr>
                <td>
                        <img 
                            class="profile-picture"
                            src="../StudentImages/<?= htmlspecialchars($Attendance["StudentPicture"]) ?>.jpg"
                            alt=""
                        >
                </td>

                <td>
                    <?= htmlspecialchars($Attendance["StudentLastName"] . " " . $Attendance["StudentFirstName"]) ?>
                </td>

                <td>
                    <?= htmlspecialchars($French->Translate($Attendance["Attendance"]))?>
                </td>

                <td>
                    <?= htmlspecialchars($Attendance["Reason"])?>
                </td>
            </tr>
            </tbody>
            <?php endforeach; ?>

        </table>
        </div>
    </div>

</body>
</html>