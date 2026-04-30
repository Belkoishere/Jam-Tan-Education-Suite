<?php
require("../controllers/db.php");
$AccountID = $_SESSION['AccountID'];

$stmt = 
$conn->prepare("SELECT COUNT(*)-1 AS NumberOfStudents, program.ProgramName,
program.ProgramID, ProgramCategory.CategoryName
FROM ProgramCategory 
INNER JOIN Program ON Program.CategoryID = ProgramCategory.CategoryID
INNER JOIN Assignment ON Program.ProgramID = Assignment.ProgramID
LEFT JOIN Enrollment ON Program.ProgramID = Enrollment.ProgramID
LEFT JOIN Student ON Enrollment.StudentID = Student.StudentID
WHERE assignment.StaffID = :id
GROUP BY Program.ProgramID");

$stmt->execute(['id' => $AccountID]);
$Programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$conn = null;

?>