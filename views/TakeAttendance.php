<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include "../nav/nav.html";
include "Alert.html";
require("../controllers/db.php");
require("../controllers/YourProgramsData.php");

$Messages = [];

if (isset($_GET["Warning"])) {
    $Messages["Warning"] = "La présence a déjà été prise pour aujourd'hui" ;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faire l'appel</title>
    <!-- <link rel="stylesheet" href="../nav/nav.css"> -->
    <style>
        #p_take_attendance {
            font-weight: bold;
        }
        #s_take_attendance {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="css/Result.css">
</head>
<body>
    <div id="main">
        <h2>Faire l'appel</h2>
        <div class="results-container">
            <?php foreach ($Programs as $Program): ?>
                <a href="ProgramAttendance.php?program_id=<?= $Program["ProgramID"]?>" class="result">

                    <p class="text">
                    <?= htmlspecialchars($Program["CategoryName"])?>
                    <?= htmlspecialchars($Program["ProgramName"])?>
                    </p>
                    
                    <img src="../icons/arrow-next-svgrepo-com.svg" alt="forward-icon" class="forward-icon">

                </a>
            <?php endforeach?>
        </div>
    </div>
<script>window.Messages = <?= json_encode($Messages, JSON_HEX_TAG); ?>;</script>
<script src="Alert.js"></script>
</body>
</html>