<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

require("../controllers/YourProgramsData.php");
include "../nav/nav.html";
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
    <link rel="stylesheet" href="css/Result.css">
</head>
<body>
    <div id="main">
        <h2>Historique de présence</h2>
        <div class="results-container">
            <?php foreach ($Programs as $Program): ?>

                <div class="result">

                    <p class="text">
                        <?= htmlspecialchars($Program["CategoryName"])?>
                        <?= htmlspecialchars($Program["ProgramName"])?>
                    </p>
                    
                    <a href="ChartReports.php?program_id=<?= $Program["ProgramID"]?>">
                        <img src="../icons/bar-chart-svgrepo-com.svg" alt="bar chart icon" class="forward-icon">
                    </a>

                    <a href="TextReports.php?program_id=<?= $Program["ProgramID"]?>">
                        <img src="../icons/report-text-svgrepo-com.svg" alt="text report icon" class="forward-icon">
                    </a>

                </div>
                    
            <?php endforeach?>
        </div>
        
    </div>
</body>
</html>