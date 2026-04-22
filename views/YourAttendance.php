<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include "../nav/nav.html";
require("../controllers/YourAttendanceData.php");
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
</head>
<body>
    <div id="main">
        <h2>Votre présence</h2>

            <?php if(count($YourAttendance) > 1) { ?>
            <table>
                <tr>
                    <th>Programme</th>
                    <th>Fréquentation moyenne</th>
                </tr>
                <?php foreach ($YourAttendance as $AttendanceRow): ?>
                    <tr>
                        <td><?= htmlspecialchars($AttendanceRow["ProgramName"]) ?></td>
                        <td><?= htmlspecialchars($AttendanceRow["AverageAttendance"]) . "%"?></td>
                    </tr>
                <?php endforeach ?>
            </table>
            <?php } else {?>
                <p>Aucun presence registre</p>
            <?php } ?>
    </div>
</body>
</html>