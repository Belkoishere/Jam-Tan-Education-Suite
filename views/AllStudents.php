<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include "../nav/nav.html";
require("../controllers/db.php");

$AccountID = $_SESSION['AccountID'];

$GetPrograms = 
$conn->prepare("SELECT Program.ProgramID, Program.ProgramName, 
ProgramCategory.CategoryName
FROM Program
INNER JOIN ProgramCategory ON Program.CategoryID = ProgramCategory.CategoryID");

$GetPrograms->execute();
$Programs = $GetPrograms->fetchAll(PDO::FETCH_ASSOC);

$InProgram = $_GET['InProgram'] ?? '';
$AtRisk = $_GET['AtRisk'] ?? '';
$Name = $_GET['Name'] ?? '';

$GetStudents = "SELECT TIMESTAMPDIFF(YEAR, Student.StudentBirthDate, CURDATE()) AS Age,
Student.StudentID, Student.StudentFirstName, Student.StudentLastName, Contact1, Contact2, 
Student.StudentGender, Student.StudentPicture,
GROUP_CONCAT(DISTINCT CONCAT(Program.ProgramName, ' ', ProgramCategory.CategoryName) 
ORDER BY Program.ProgramName SEPARATOR ', ') AS Programs
FROM student
INNER JOIN Enrollment ON Student.StudentID = Enrollment.StudentID
INNER JOIN Program ON Enrollment.ProgramID = Program.ProgramID
INNER JOIN ProgramCategory ON Program.CategoryID = programcategory.CategoryID
WHERE 1 = 1";

$params = [];

if ($Name !== '') {
    $GetStudents .= " AND CONCAT(Student.StudentFirstName, ' ',
    Student.StudentLastName) LIKE :sname";

    $params['sname'] = "%$Name%";
}

if ($InProgram !== '') {
    // Only show students enrolled in a selected program
    $GetStudents .= " AND Student.StudentID IN (
    SELECT Enrollment.StudentID
    FROM Enrollment
    JOIN Program ON Enrollment.ProgramID = Program.ProgramID
    WHERE Program.ProgramID = :program)";
    
    $params['program'] = $InProgram;
}

if ($AtRisk == 'Yes') {
    $GetStudents .= " AND Student.StudentID IN 
    (SELECT StudentID FROM StudentsAtRisk)";
}

if ($AtRisk == 'No') {
    $GetStudents .= " AND Student.StudentID NOT IN 
    (SELECT StudentID FROM StudentsAtRisk)";
}

// Group by student id to avoid the aggregate function GROUP_CONCAT from operating
// on the entire result set and treating it as single group
$GetStudents .= " GROUP BY Student.StudentID";

$stmt = $conn->prepare($GetStudents);
$stmt->execute($params);

$Students = $stmt->fetchAll(PDO::FETCH_ASSOC);

$NumRows = count($Students);

$conn = null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tous les élèves</title>
    <style>
        #p_all_students {
            font-weight: bold;
        }
        #s_all_students {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="StudentResult.css">
</head>
<body>
    <div id="main">
        <h2>Tous les élèves</h2>

        <div class="form-container">
            <form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
                <label for="InProgram">Programme</label>
                <select name="InProgram">
                    <option value="">Tout</option>
                    <?php foreach ($Programs as $Program): ?>
                        <option value="<?= htmlspecialchars($Program["ProgramID"])?>"
                        <?= (($_GET['InProgram'] ?? '') == $Program["ProgramID"]) ? 'selected' : ''?>>
                            <?= htmlspecialchars($Program["ProgramName"])?>
                            <?= htmlspecialchars($Program["CategoryName"])?>
                        </option>
                    <?php endforeach ?>
                </select>
                <label for="AtRisk">À risque</label>
                <select name="AtRisk">
                    <option value="">Tout</option>
                    <option value="No" <?= (($_GET['AtRisk'] ?? '') == 'No') ? 'selected' : ''?>>Non</option>
                    <option value="Yes" <?= (($_GET['AtRisk'] ?? '') == 'Yes') ? 'selected' : ''?>>Oui</option>
                </select>
                <label for="Name">Nom de l'élève</label>
                <input type="text" name="Name" value="<?= htmlspecialchars($_GET['Name'] ?? '')?>">
                <input type="submit" value="Chercher">
            </form>
        </div>
        
        <p><?= "Résultats: " . htmlspecialchars($NumRows) ?></p>
        
        <div class="table-container">
            <?php foreach ($Students as $Student): ?>
                <div class="table">
                    <div class="table-contents-container">
                        <img src="../StudentImages/<?= htmlspecialchars($Student["StudentPicture"]) ?>.jpg" 
                        alt="Image of <?= htmlspecialchars($Student["StudentFirstName"]) ?>">
                        <p>
                            <?= htmlspecialchars($Student["StudentFirstName"]) ?>
                        </p>
                        <p>
                            <?= htmlspecialchars($Student["StudentLastName"]) ?>
                        </p>
                        <p>
                            <?= htmlspecialchars($Student["Age"]) ?>
                        </p>
                        <p>
                            <?= htmlspecialchars($Student["StudentGender"]) ?>
                        </p>
                        <p>
                            <?= "Conact 1: " . htmlspecialchars($Student["Contact1"]) ?>
                        </p>
                        <p>
                            <?= "Conact 2: " . htmlspecialchars($Student["Contact2"]) ?>
                        </p>
                        <p>
                            <?= htmlspecialchars($Student["Programs"]) ?>
                        </p>
                        <a href="Student.php?student_id=<?= $Student["StudentID"]?>"><div class="table-btn">Voir</div></a>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

</body>
</html>