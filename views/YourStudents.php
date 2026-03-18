<?php
session_start();
include "../nav/nav.html";
require("../controllers/YourStudentsData.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vos élèves</title>
    <style>
        #p_your_students {
            font-weight: bold;
        }
        #s_your_students {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div id="main">
        <h2>Vos élèves</h2>
        <?php foreach ($Programs as $Program): ?>
            <p>
                <?= htmlspecialchars($Program["ProgramID"])?>
                <?= htmlspecialchars($Program["ProgramName"])?>
            </p>
        <?php endforeach ?>  
    </div>
</body>
</html>