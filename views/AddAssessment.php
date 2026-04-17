<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include "../nav/nav.html";
require("../controllers/YourProgramsData.php");
require("../controllers/db.php");

$ProgramID = $_GET["id"] ?? null;

$InName = $_POST["Name"] ?? null;
$InStartDate = $_POST["StartDate"] ?? "";
$InDueDate = $_POST["DueDate"] ?? null;
$InMaxGrade = $_POST["MaxGrade"] ?? null;
$InPassGrade = $_POST["PassGrade"] ?? null;
$InTypeID = $_POST["TypeID"] ?? null;
$InProgramID = $_POST["ProgramID"] ?? null;

$GetAssessmentTypes = $conn->prepare("SELECT * FROM AssessmentType");

$GetAssessmentTypes->execute();
$Types = $GetAssessmentTypes->fetchAll(PDO::FETCH_ASSOC);

if (isset($InProgramID)){

    $InsertAssessment = $conn->prepare("INSERT INTO Assessment (AssessmentName, AssessmentStartDate,
    AssessmentDueDate, MaxGrade, PassGrade, TypeID, ProgramID)
    VALUES (?, ?, ?, ?, ?, ?, ?)");

    $InsertAssessment->execute([$InName, $InStartDate, $InDueDate, $InMaxGrade, $InPassGrade,
    $InTypeID, $InProgramID]);

}

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une évaluation</title>
    <!-- <link rel="stylesheet" href="../nav/nav.css"> -->
    <style>
        #p_add_assessment {
            font-weight: bold;
        }
        #s_add_assessment {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="form.css">
</head>
<body>
    <div id="main">
        <h2>Ajouter une évaluation</h2>

        <div class="form-container">
            <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                <label for="ProgramID">Programme</label>
                <select name="ProgramID" id="">
                    <?php foreach ($Programs as $Program): ?>
                        <?php if($Program["ProgramID"] == $ProgramID) {?>
                            <option value="<?= htmlspecialchars($Program["ProgramID"])?>" selected>
                                <?= htmlspecialchars($Program["CategoryName"] . " " . $Program["ProgramName"])?>
                            </option>
                        <?php } else {?>
                            <option value="<?= htmlspecialchars($Program["ProgramID"])?>">
                                <?= htmlspecialchars($Program["CategoryName"] . " " . $Program["ProgramName"])?>
                            </option>
                        <?php }?>
                    <?php endforeach ?>
                </select>
                <label for="Name">Titre</label>
                <input type="text" name="AssessmentName">
                <label for="TypeID">Type</label>
                <select name="TypeID" id="">
                    <?php foreach ($Types as $Type): ?>
                        <option value="<?= htmlspecialchars($Type["TypeID"])?>">
                            <?= htmlspecialchars($Type["TypeName"])?>
                        </option>
                    <?php endforeach ?>
                </select>
                <label for="StartDate">Date de début</label>
                <input type="date" name="StartDate">
                <label for="DueDate">Date Limite</label>
                <input type="date" name="DueDate">
                <label for="MaxGrade">Points maximums</label>
                <input type="number" name="MaxGrade">
                <label for="PassGrade">Points de réussite</label>
                <input type="number" name="PassGrade">

                <input type="submit" value="Ajouter">
            </form>
        </div>
    </div>
</body>
</html>