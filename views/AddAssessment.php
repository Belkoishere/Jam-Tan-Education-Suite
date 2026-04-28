<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include "../nav/nav.html";
include "Alert.html";
require("../controllers/YourProgramsData.php");
require("../controllers/db.php");
require_once("../locales/Language.php");
require_once("../locales/French.php");
require_once("../locales/Translate.php");

$French = new Translate (new French);

$ProgramID = $_GET["id"] ?? null;

$GetAssessmentTypes = $conn->prepare("SELECT * FROM AssessmentType");

$GetAssessmentTypes->execute();
$Types = $GetAssessmentTypes->fetchAll(PDO::FETCH_ASSOC);

$Errors = [];
$Messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $InName = preg_replace('/\s+/', '', $_POST["AssessmentName"]) ?? null;
    $InStartDate = preg_replace('/\s+/', '', $_POST["StartDate"]) ?? null;
    $InDueDate = preg_replace('/\s+/', '', $_POST["DueDate"]) ?? null;
    $InMaxGrade = intVal($_POST["MaxGrade"]) ?? null;
    $InPassGrade = intVal($_POST["PassGrade"]) ?? null;
    $InTypeID = $_POST["TypeID"] ?? null;
    $InProgramID = $_POST["ProgramID"] ?? null;  

    //Name validation checks
    if (empty($InName)) {
        $Errors["AssessmentName"] = "Entrez le titre";
    }
    else if (strlen($InName) > 100) {
        $Errors["AssessmentName"] = "Le titre doit comporter 100 caractères ou moins";
    }

    //Due date validation checks
    if(empty($InDueDate)){
        $Errors["DueDate"] = "Entrez la date limite";
    }
    else if (!(new DateTime($InDueDate) instanceof DateTime)) {
        $Errors["DueDate"] = "La date limite doit etre une date";
    }
    else if (new DateTime($InDueDate) <= new DateTime("today")) {
        $Errors["DueDate"] = "La date limite doit être dans le futur";
    }

    //InStartDate validation checks
    if (empty($InStartDate)){
        
    }
    else if(!(new DateTime($InStartDate) instanceof DateTime)){
        $Errors["StartDate"] = "La date de début doit être une date";
    }
    else if (new DateTime($InStartDate) < new DateTime("today")) {
        $Errors["StartDate"] = "La date de début doit être aujourd'hui ou dans le futur";
    }
    else if(new DateTime($InStartDate) > new DateTime($InDueDate)){
        $Errors["StartDate"] = "La date de début ne peut pas être postérieure à la date d'échéance";
    }

    //InMaxGrade validation checks
    if (empty($InMaxGrade)){
        $Errors["MaxGrade"] = "Entrez la note maximale";
    }
    else if(!is_int($InMaxGrade)){
        $Errors["MaxGrade"] = "La note maximale doit etre un nombre";
    }
    else if($InMaxGrade <= 0){
        $Errors["StartDate"] = "La note maximale doit être supérieure à 0";
    }

    //InPassGrade validation checks
    if (empty($InPassGrade)){
        $Errors["PassGrade"] = "Entrez la note de passage";
    }
    else if(!is_int($InPassGrade)){
        $Errors["PassGrade"] = "La note de passage doit etre un nombre";
    }
    else if($InPassGrade <= 0){
        $Errors["PassGrade"] = "La note de passage doit être supérieure à 0";
    }

    if (empty($Errors)){
        $InsertAssessment = $conn->prepare("INSERT INTO Assessment (AssessmentName, AssessmentStartDate,
        AssessmentDueDate, MaxGrade, PassGrade, TypeID, ProgramID)
        VALUES (?, ?, ?, ?, ?, ?, ?)");

        try {
            $InsertAssessment->execute([$InName, $InStartDate, $InDueDate, $InMaxGrade, $InPassGrade,
            $InTypeID, $InProgramID]);

            $Messages["Success"] = "Évaluation ajoutée";
        }
        catch(Exception $e){
            $Messages["Warning"] = $e->getMessage();
        }
    }
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
    <link rel="stylesheet" href="css/form.css">
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
                <label for="AssessmentName">Titre</label>
                <input type="text" name="AssessmentName" value="<?= htmlspecialchars($_POST['AssessmentName'] ?? '')?>">
                <span style="color: red;"><?= $Errors["AssessmentName"] ?? null?></span>
                <label for="TypeID">Type</label>
                <select name="TypeID" id="">
                    <?php foreach ($Types as $Type): ?>
                        <option value="<?= htmlspecialchars($Type["TypeID"])?>"
                        <?= (($_POST['TypeID'] ?? '') == $Type["TypeID"]) ? 'selected' : ''?>>
                            <?= htmlspecialchars($French->Translate($Type["TypeName"]))?>
                        </option>
                    <?php endforeach ?>
                </select>
                <label for="StartDate">Date de début</label>
                <input type="date" name="StartDate" value="<?= htmlspecialchars($_POST['StartDate'] ?? null)?>">
                <span style="color: red;"><?= $Errors["StartDate"] ?? null?></span>
                <label for="DueDate">Date Limite</label>
                <input type="date" name="DueDate" value="<?= htmlspecialchars($_POST['DueDate'] ?? null)?>">
                <span style="color: red;"><?= $Errors["DueDate"] ?? null?></span>
                <label for="MaxGrade">Points maximums</label>
                <input type="number" name="MaxGrade" min="1" value="<?= htmlspecialchars($_POST['MaxGrade'] ?? null)?>">
                <span style="color: red;"><?= $Errors["MaxGrade"] ?? null?></span>
                <label for="PassGrade">Points de réussite</label>
                <input type="number" name="PassGrade" min="0" value="<?= htmlspecialchars($_POST['PassGrade'] ?? null)?>">
                <span style="color: red;"><?= $Errors["PassGrade"] ?? null?></span>
                <input type="submit" value="Ajouter">
            </form>
        </div>
    </div>

<script>window.Messages = <?= json_encode($Messages, JSON_HEX_TAG); ?>;</script>
<script src="Alert.js"></script>
</body>
</html>