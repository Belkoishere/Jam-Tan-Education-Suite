<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include "../nav/nav.html";
require("../controllers/YourProgramsData.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vos évaluations</title>
    <!-- <link rel="stylesheet" href="../nav/nav.css"> -->
    <style>
        #p_your_assessments {
            font-weight: bold;
        }
        #s_your_assessments {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="Result.css">
</head>
<body>
    <div id="main">
        <h2>Vos évaluations</h2>

        <div class="results-container">
            <?php foreach ($Programs as $Program): ?>
                <a href="ProgramAssessments.php?id=<?= $Program["ProgramID"]?>
                &name=<?= $Program["ProgramName"]?>&category=<?= $Program["CategoryName"]?>" class="result">

                    <p class="text">
                    <?= htmlspecialchars($Program["CategoryName"])?>
                    <?= htmlspecialchars($Program["ProgramName"])?>
                    </p>
                    
                    <img src="../icons/arrow-next-svgrepo-com.svg" alt="forward-icon" class="forward-icon">

                </a>
            <?php endforeach?>
        </div>
    </div>
</body>
</html>