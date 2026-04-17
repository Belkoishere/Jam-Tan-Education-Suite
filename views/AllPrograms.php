<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include "../nav/nav.html";
require("../controllers/db.php");

$AccountID = $_SESSION['AccountID'];

$GetCategories = 
$conn->prepare("SELECT ProgramCategory.CategoryID, ProgramCategory.CategoryName
FROM ProgramCategory");

$GetCategories->execute();
$Categories = $GetCategories->fetchAll(PDO::FETCH_ASSOC);

$InCategory = $_GET['InCategory'] ?? '';
$ProgramName = $_GET['Name'] ?? '';

$GetAllPrograms = 
"SELECT COUNT(student.StudentID) AS NumberOfStudents, program.ProgramName,
program.ProgramID, ProgramCategory.CategoryName
FROM programcategory
INNER JOIN program ON programcategory.CategoryID = program.CategoryID
LEFT JOIN enrollment ON program.ProgramID = enrollment.ProgramID
LEFT JOIN student ON student.studentID = enrollment.studentID
WHERE 1 = 1";

$params = [];

if ($InCategory !== '') {
    $GetAllPrograms .= " AND Program.CategoryID = :cid";

    $params['cid'] = $InCategory;
}

if ($ProgramName !== '') {
    // Only show students enrolled in a selected program
    $GetAllPrograms .= " AND Program.ProgramName LIKE :program";
    
    $params['program'] = "%$ProgramName%";
}

$GetAllPrograms .= " GROUP BY Program.ProgramID";

$stmt = $conn->prepare($GetAllPrograms);
$stmt->execute($params);
$Programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$NumRows = count($Programs);

$GetYourPrograms = $conn->prepare("SELECT Assignment.ProgramID 
FROM Assignment
WHERE Assignment.StaffID = ?");

$GetYourPrograms->execute([$AccountID]);
$YourPrograms = $GetYourPrograms->fetchAll(PDO::FETCH_ASSOC);
// extract program id from results
$YourIds = array_column($YourPrograms, 'ProgramID');

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tous les programmes</title>
    <!-- <link rel="stylesheet" href="../nav/nav.css"> -->
    <style>
        #p_all_programs {
            font-weight: bold;
        }
        #s_all_programs {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="form.css">
</head>
<body>
    <div id="main">
        <h2>Tous les programmes</h2>

        <div class="form-container">
            <form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
                <label for="InCategory">Categorie</label>
                <select name="InCategory">
                    <option value="">Tout</option>
                    <?php foreach ($Categories as $Category): ?>
                        <option value="<?= htmlspecialchars($Category["CategoryID"])?>"
                        <?= (($_GET['InCategory'] ?? '') == $Category["CategoryID"]) ? 'selected' : ''?>>
                            <?= htmlspecialchars($Category["CategoryName"])?>
                        </option>
                    <?php endforeach ?>
                </select>
                <label for="Name">Nom du programme</label>
                <input type="text" name="Name" value="<?= htmlspecialchars($_GET['Name'] ?? '')?>">
                <input type="submit" value="Chercher">
            </form>
        </div>

        <p><?= "Résultats: " . htmlspecialchars($NumRows) ?></p>

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
                        <!-- restrict access to programs based on whether user is assigned to those programs
                         or not -->
                        <?php if (in_array($Program["ProgramID"], $YourIds)){ ?>
                            <a href="AddAssessment.php?program_id=<?= htmlspecialchars($Program["ProgramID"]) ?>">
                                <div>Ajouter une évaluation</div>
                            </a>
                        <?php }?>
                        <a href="ProgramAttendance.php?program_id=<?= htmlspecialchars($Program["ProgramID"]) ?>">
                            <div>Faire l'appel</div>
                        </a>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</body>
</html>