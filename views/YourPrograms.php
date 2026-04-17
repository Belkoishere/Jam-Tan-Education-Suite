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
</head>
<body>
    <div id="main">
        <h2>Vos programs</h2>

        <div class="table-container">
            <?php foreach ($Programs as $Program): ?>
                <div class="table">
                    <div class="table-contents-container">
                        <p>
                            <?= htmlspecialchars($Program["ProgramName"]) ?>
                            <?= htmlspecialchars($Program["CategoryName"]) ?>
                        </p>
                        <p>
                            Nombre d'élèves: <?= htmlspecialchars($Program["NumberOfStudents"]) ?>
                        </p>
                        <a href="AddAssessment.php?program_id=<?= $Program["ProgramID"]?>">
                            <div>Ajouter une évaluation</div>
                        </a>
                        <a href="ProgramAttendance.php?program_id=<?= $Program["ProgramID"]?>">
                            <div>Faire l'appel</div>
                        </a>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

</body>
</html>