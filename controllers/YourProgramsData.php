<?php
require("../controllers/db.php");
$AccountID = $_SESSION['AccountID'];

$stmt = 
$conn->prepare("SELECT COUNT(*) AS NumberOfStudents, program.ProgramName,
program.ProgramID, ProgramCategory.CategoryName
FROM Student
INNER JOIN Enrollment ON Student.StudentID = Enrollment.StudentID
INNER JOIN Program ON Enrollment.ProgramID = Program.ProgramID
INNER JOIN Assignment ON Program.ProgramID = Assignment.ProgramID
INNER JOIN ProgramCategory ON Program.CategoryID = ProgramCategory.CategoryID
WHERE assignment.StaffID = :id
GROUP BY Program.ProgramID;");

$stmt->execute(['id' => $AccountID]);
$Programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$conn = null;

?>