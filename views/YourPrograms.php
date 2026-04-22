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
    <title>Vos programs</title>
    <style>
        #p_your_programs {
            font-weight: bold;
        }
        #s_your_programs {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="css/Program.css">
</head>
<body>
    <div id="main">
        <h2>Vos programs</h2>

        <div class="OuterContainer">
            <?php foreach ($Programs as $Program): ?>
                <div class="InnerContainer">
                    <div class="Text">
                        <p>
                            <?= htmlspecialchars($Program["ProgramName"]) ?>
                            <?= htmlspecialchars($Program["CategoryName"]) ?>
                        </p>
                        <p>
                            Nombre d'élèves: <?= htmlspecialchars($Program["NumberOfStudents"]) ?>
                        </p>
                    </div>

                    <div class="Buttons">
                        <a href="AddAssessment.php?program_id=<?= $Program["ProgramID"]?>">
                            <div class="Button">Ajouter une évaluation</div>
                        </a>
                        <a href="ProgramAttendance.php?program_id=<?= $Program["ProgramID"]?>">
                            <div class="Button">Faire l'appel</div>
                        </a>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

</body>
</html>