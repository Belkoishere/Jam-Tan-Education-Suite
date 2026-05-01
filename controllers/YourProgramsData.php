<?php
require("../controllers/db.php");
$AccountID = $_SESSION['AccountID'];

$stmt = 
$conn->prepare("SELECT
Program.ProgramName, Program.ProgramID, 
ProgramCategory.CategoryName,
(SELECT COUNT(*) FROM Enrollment WHERE Enrollment.ProgramID = Program.ProgramID) AS NumberOfStudents
FROM Program
INNER JOIN Assignment ON Program.ProgramID = Assignment.ProgramID
INNER JOIN ProgramCategory ON Program.CategoryID = ProgramCategory.CategoryID
WHERE Assignment.StaffID = :id");

$stmt->execute(['id' => $AccountID]);
$Programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$conn = null;

?>