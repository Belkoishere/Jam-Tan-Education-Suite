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

$GetAssessmentTypes = $conn->prepare("SELECT * FROM AssessmentType");

$GetAssessmentTypes->execute();
$Types = $GetAssessmentTypes->fetchAll(PDO::FETCH_ASSOC);

$Errors = [];

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
        $Errors["AssessmentName"] = "Please enter the title";
    }
    else if (strlen($InName) > 100) {
        $Errors["AssessmentName"] = "The title must have 100 charachters or less";
    }

    //Due date validation checks
    if(empty($InDueDate)){
        $Errors["DueDate"] = "Please enter a due date";
    }
    else if (!(new DateTime($InDueDate) instanceof DateTime)) {
        $Errors["DueDate"] = "The due date must be a date";
    }
    else if (new DateTime($InDueDate) <= new DateTime("today")) {
        $Errors["DueDate"] = "The due date must be in the future";
    }

    //InStartDate validation checks
    if (empty($InStartDate)){
        
    }
    else if(!(new DateTime($InStartDate) instanceof DateTime)){
        $Errors["StartDate"] = "The start date must be a date";
    }
    else if (new DateTime($InStartDate) < new DateTime("today")) {
        $Errors["StartDate"] = "The start date must be today or in the future";
    }
    else if(new DateTime($InStartDate) > new DateTime($InDueDate)){
        $Errors["StartDate"] = "The start date cannot be later than the due date";
    }

    //InMaxGrade validation checks
    if (empty($InMaxGrade)){
        $Errors["MaxGrade"] = "Please enter the Maximum grade";
    }
    else if(!is_int($InMaxGrade)){
        $Errors["MaxGrade"] = "Maximum grade must be a number";
    }
    else if($InMaxGrade <= 0){
        $Errors["StartDate"] = "The maximum grade must be greater than 0";
    }

    //InPassGrade validation checks
    if (empty($InPassGrade)){
        $Errors["PassGrade"] = "Please enter the pass grade";
    }
    else if(!is_int($InPassGrade)){
        $Errors["PassGrade"] = "Pass grade must be a number";
    }
    else if($InPassGrade <= 0){
        $Errors["PassGrade"] = "The pass grade must be greater than 0";
    }

    if (empty($Errors)){
        $InsertAssessment = $conn->prepare("INSERT INTO Assessment (AssessmentName, AssessmentStartDate,
        AssessmentDueDate, MaxGrade, PassGrade, TypeID, ProgramID)
        VALUES (?, ?, ?, ?, ?, ?, ?)");

        try {
            $InsertAssessment->execute([$InName, $InStartDate, $InDueDate, $InMaxGrade, $InPassGrade,
            $InTypeID, $InProgramID]);
        }
        catch(Exception $e){
            echo 'Message: ' . $e->getMessage();
        }
    }

    foreach($Errors as $Error){
        echo($Error);
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
                <label for="AssessmentName">Titre</label>
                <input type="text" name="AssessmentName" value="<?= htmlspecialchars($_POST['AssessmentName'] ?? '')?>">
                <span style="color: red;"><?= $Errors["AssessmentName"] ?? null?></span>
                <label for="TypeID">Type</label>
                <select name="TypeID" id="">
                    <?php foreach ($Types as $Type): ?>
                        <option value="<?= htmlspecialchars($Type["TypeID"])?>"
                        <?= (($_POST['TypeID'] ?? '') == $Type["TypeID"]) ? 'selected' : ''?>>
                            <?= htmlspecialchars($Type["TypeName"])?>
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
</body>
</html>