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
$conn->prepare("SELECT Program.ProgramID, Program.ProgramName, ProgramCategory.CategoryName
FROM Staff
INNER JOIN Assignment ON Staff.StaffID = Assignment.StaffID
INNER JOIN Program ON Assignment.ProgramID = Program.ProgramID 
INNER JOIN ProgramCategory ON Program.CategoryID = ProgramCategory.CategoryID");

$GetPrograms->execute();
$Programs = $GetPrograms->fetchAll(PDO::FETCH_ASSOC);

$InProgram = $_GET['InProgram'] ?? '';
$AtRisk = $_GET['AtRisk'] ?? '';
$Name = $_GET['Name'] ?? '';

$GetStudents = "SELECT TIMESTAMPDIFF(YEAR, Student.StudentBirthDate, CURDATE()) AS Age,
Student.StudentID, Student.StudentFirstName, Student.StudentLastName, Contact1, Contact2, 
Student.StudentGender, Student.StudentPicture,
GROUP_CONCAT(DISTINCT Program.ProgramName, ' ', ProgramCategory.CategoryName 
ORDER BY Program.ProgramName SEPARATOR ', ') AS Programs,
IF (Student.StudentID IN 
(SELECT StudentID FROM StudentsRiskLevel WHERE RiskLevel = 'High Risk'), TRUE, FALSE) AS AtHighRisk,
IF (Student.StudentID IN 
(SELECT StudentID FROM StudentsRiskLevel WHERE RiskLevel = 'Moderate Risk'), TRUE, FALSE) AS AtModerateRisk
FROM Student
INNER JOIN Enrollment ON Student.StudentID = Enrollment.StudentID
INNER JOIN Program ON Enrollment.ProgramID = Program.ProgramID 
INNER JOIN ProgramCategory ON Program.CategoryID = ProgramCategory.CategoryID
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
    (SELECT StudentID FROM StudentsRiskLevel WHERE RiskLevel = 'High Risk' OR RiskLevel = 'Moderate Risk')";
}

if ($AtRisk == 'No') {
    $GetStudents .= " AND Student.StudentID NOT IN 
    (SELECT StudentID FROM StudentsRiskLevel WHERE RiskLevel = 'High Risk' OR RiskLevel = 'Moderate Risk')";
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
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/StudentResult.css">
    <link rel="stylesheet" href="css/Card.css">
    <link rel="stylesheet" href="css/ProfilePictureSmall.css">
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
        
        <p class="number-results">Résultats: <?= htmlspecialchars($NumRows) ?></p>
        
        <div class="card-container">
            <?php foreach ($Students as $Student): ?>
                <div class="card">
                    <table>
                        <tr>
                            <td>
                                <img class="profile-picture" src="../StudentImages/<?= htmlspecialchars($Student["StudentPicture"]) ?>.jpg" 
                                alt="Image of <?= htmlspecialchars($Student["StudentFirstName"]) ?>">
                                <?php if ($Student["AtHighRisk"] == true) {?>
                                    <img style="width: 25px" src="../icons/alert-triangle-svgrepo-com.svg" alt="">
                                <?php } else if ($Student["AtModerateRisk"] == true) {?>
                                    <img style="width: 25px" src="../icons/alert-triangle-svgrepo-orange-com.svg" alt="">
                                <?php } ?>
                            </td>
                            <td>
                                <p class="student-name">
                                    <?= htmlspecialchars($Student["StudentLastName"] . " " . $Student["StudentFirstName"]) ?>
                                </p>
                                <p>
                                    <?= htmlspecialchars($Student["Age"]) . " / " . $Student["StudentGender"] ?>
                                </p>
                                <p>
                                    <?= "Contact 1: " . htmlspecialchars($Student["Contact1"]) ?>
                                </p>
                                <p>
                                    <?= "Contact 2: " . htmlspecialchars($Student["Contact2"]) ?>
                                </p>
                                <p>
                                    <?= "Programme(s): " . htmlspecialchars($Student["Programs"]) ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <a href="Student.php?student_id=<?= $Student["StudentID"]?>" class="card-btn">Voir</a>
                </div>
            <?php endforeach ?>
        </div>

</body>
</html>