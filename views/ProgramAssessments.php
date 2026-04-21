<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

$ProgramID = $_GET['program_id'] ?? null;

// Filters:
$InMonth = $_GET['Month'] ?? '';
$InYear = $_GET['Year'] ?? '';
$InUpcoming = $_GET['Upcoming'] ?? '';

require("../controllers/db.php");
include "../nav/nav.html";

$GetProgram = $conn->prepare("SELECT Program.ProgramName, ProgramCategory.CategoryName
FROM Program
INNER JOIN ProgramCategory 
ON Program.CategoryID = ProgramCategory.CategoryID
WHERE Program.ProgramID = ?");

$GetProgram->execute([$ProgramID]);
$Program = $GetProgram->fetch(PDO::FETCH_ASSOC);

$GetYears = "SELECT DISTINCT Year(Assessment.AssessmentPublishDate) AS AssessmentYear
FROM Assessment 
WHERE Assessment.ProgramID = ?";

$stmt = $conn->prepare($GetYears);
$stmt->execute([$ProgramID]);

$Years = $stmt->fetchAll(PDO::FETCH_ASSOC);

$GetAssessments = "SELECT Assessment.AssessmentID, Assessment.AssessmentName, Assessment.AssessmentDueDate, 
Assessment.AssessmentPublishDate, Assessment.MaxGrade, AssessmentType.TypeName 
FROM Assessment
INNER JOIN AssessmentType
ON Assessment.TypeID = AssessmentType.TypeID
WHERE Assessment.ProgramID = :id";

$params = ['id' => $ProgramID];

if ($InMonth !== '') {
    $GetAssessments .= " AND MONTH(Assessment.AssessmentPublishDate) = :in_month";

    $params['in_month'] = $InMonth;
}

if ($InYear !== '') {
    $GetAssessments .= " AND YEAR(Assessment.AssessmentPublishDate) = :in_year";
    
    $params['in_year'] = $InYear;
}

if ($InUpcoming !== '') {
    if ($InUpcoming == 'Upcoming'){
        $GetAssessments .= " AND Assessment.AssessmentDueDate >= CURRENT_DATE";
    }
    if ($InUpcoming == 'Past'){
        $GetAssessments .= " AND Assessment.AssessmentDueDate < CURRENT_DATE";
    }
}

$stmt1 = $conn->prepare($GetAssessments);
$stmt1->execute($params);

$Assessments = $stmt1->fetchAll(PDO::FETCH_ASSOC);

$NumRows = count($Assessments);

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluations</title>
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
    <link rel="stylesheet" href="form.css">
</head>
<body>

    <a href="YourAssessments.php">
        <img src="../icons/navigation-back-arrow-svgrepo-com.svg" 
        alt="back icon" class="back-icon">
    </a>

    <div id="main">

        <h2>
            <?= htmlspecialchars("Évaluations - " . $Program["CategoryName"] . " " . $Program["ProgramName"])?>
        </h2>   
        
        <a href="AddAssessment.php?id=<?= $ProgramID?>">Ajouter une evaluation</a>

        <div class="form-container">
            <form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
                <label for="Year">Année</label>
                <select name="Year">
                    <?php foreach ($Years as $Year): ?>
                        <option value="<?= htmlspecialchars($Year["AssessmentYear"])?>"
                        <?= (($_GET['InYear'] ?? '') == $Year["AssessmentYear"]) ? 'selected' : ''?>>
                            <?= htmlspecialchars($Year["AssessmentYear"])?>
                        </option>
                    <?php endforeach ?>
                </select>
                <label for="Month">Mois</label>
                <select name="Month">
                    <option value="">Tout</option>
                    <option value="1" <?= (($_GET['Month'] ?? '') == '1') ? 'selected' : ''?>>Janvier</option>
                    <option value="2" <?= (($_GET['Month'] ?? '') == '2') ? 'selected' : ''?>>Février</option>
                    <option value="3" <?= (($_GET['Month'] ?? '') == '3') ? 'selected' : ''?>>Mars</option>
                    <option value="4" <?= (($_GET['Month'] ?? '') == '4') ? 'selected' : ''?>>Avril</option>
                    <option value="5" <?= (($_GET['Month'] ?? '') == '5') ? 'selected' : ''?>>Mai</option>
                    <option value="6" <?= (($_GET['Month'] ?? '') == '6') ? 'selected' : ''?>>Juin</option>
                    <option value="7" <?= (($_GET['Month'] ?? '') == '7') ? 'selected' : ''?>>Juillet</option>
                    <option value="8" <?= (($_GET['Month'] ?? '') == '8') ? 'selected' : ''?>>Août</option>
                    <option value="9" <?= (($_GET['Month'] ?? '') == '9') ? 'selected' : ''?>>Septembre</option>
                    <option value="10" <?= (($_GET['Month'] ?? '') == '10') ? 'selected' : ''?>>Octobre</option>
                    <option value="11" <?= (($_GET['Month'] ?? '') == '11') ? 'selected' : ''?>>Novembre</option>
                    <option value="12" <?= (($_GET['Month'] ?? '') == '12') ? 'selected' : ''?>>Décembre</option>
                </select>
                <input type="text" name="id" value="<?=$ProgramID?>" style="display: none;">
                <label for="Past">Passe / À venir</label>
                <select name="Upcoming" id="">
                    <option value="" >Tout</option>
                    <option value="Upcoming" <?= (($_GET['Upcoming'] ?? '') == 'Upcoming') ? 'selected' : ''?>>À venir</option>
                    <option value="Past" <?= (($_GET['Upcoming'] ?? '') == 'Past') ? 'selected' : ''?>>Passe</option>
                </select>
                <input type="submit" value="Filtre"></input>
            </form>
        </div>

        <p>Resultas <?= htmlspecialchars($NumRows);?></p>

        <div class="table-container">
            <?php foreach ($Assessments as $Assessment): ?>
                <div class="table">
                    <div class="table-contents-container">
                        <p><?= htmlspecialchars($Assessment["AssessmentName"])?></p>
                        <p>Date de publication: <?= htmlspecialchars($Assessment["AssessmentPublishDate"])?></p>
                        <p>Date limite: <?= htmlspecialchars($Assessment["AssessmentDueDate"])?></p>
                        <p>Points maximum: <?= htmlspecialchars($Assessment["MaxGrade"])?></p>
                        <p>Type: <?= htmlspecialchars($Assessment["TypeName"])?></p>
                        <a href="ViewGrades.php?assessment_id=<?= $Assessment["AssessmentID"]?>">Voir les notes</a>
                        <a href="AddGrades.php?assessment_id=<?= $Assessment["AssessmentID"]?>">Ajouter les notes</a>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</body>
</html>
