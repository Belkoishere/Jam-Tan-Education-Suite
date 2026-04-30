<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include "../nav/nav.html";
require("../controllers/db.php");

$AccountID = $_SESSION["AccountID"];

$GetYourAttendance = $conn->prepare("CALL YourAttendance(?)");
$GetYourAttendance->execute([$AccountID]);

$YourAttendance = $GetYourAttendance->fetchall(PDO::FETCH_ASSOC);

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre présence</title>
    <style>
        #p_your_attendance {
            font-weight: bold;
        }
        #s_your_attendance {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="css/Table.css">
    <link rel="stylesheet" href="css/Badges.css">
</head>
<body>
    <div id="main">
        <h2>Votre présence</h2>

            <?php if(count($YourAttendance) > 1) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Programme</th>
                        <th>Fréquentation moyenne</th>
                    </tr>
                </thead>
                <tbody>
                
                <?php foreach ($YourAttendance as $AttendanceRow): ?>
                    <tr>
                        <td><?= htmlspecialchars($AttendanceRow["ProgramName"]) ?></td>
                        <td class="<?php if($AttendanceRow["AverageAttendance"] >= 85){?>badge badge-low<?php } 
                            else if ($AttendanceRow["AverageAttendance"] >= 65) {?>badge badge-moderate<?php } else {?>
                            badge badge-high<?php }?>">
                            <?= htmlspecialchars($AttendanceRow["AverageAttendance"]) . "%"?></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
            <?php } else {?>
                <p>Aucun presence registre</p>
            <?php } ?>
    </div>
</body>
</html>