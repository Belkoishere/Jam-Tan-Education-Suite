<?php

require("../controllers/db.php");

$AccountID = $_SESSION['AccountID'];

$Program = $_GET['Program'] ?? '';
$AtRisk = $_GET['AtRisk'] ?? '';
$Name = $_GET['Name'] ?? '';

$sql = "SELECT Student.StudentID, Student.StudentFirstName, Student.StudentLastName, 
Student.StudentBirthDate, Student.StudentTown, Student.StudentGender,
Student.StudentSchool_Year, Student.StudentPicture, Student.Contact1, 
Student.Contact2, Student.FatherFirst_Name, Student.FatherLast_Name, 
Student.MotherFirst_Name, Student.MotherLast_Name 
FROM Assignment
INNER JOIN Program ON Assignment.ProgramID = Program.ProgramID  
INNER JOIN Enrollment ON Program.ProgramID = Enrollment.ProgramID
INNER JOIN Student ON Enrollment.StudentID = Student.StudentID
WHERE Assignment.StaffID = :id";

$params = ['id' => $AccountID];

if ($Name !== '') {
    $sql .= " AND CONCAT(Student.StudentFirstName, ' ', Student.StudentLastName) LIKE :name";
    $params['name'] = "%$Name%";
}

if ($Program !== '') {
    $sql .= " AND Program.ProgramID = :program";
    $params['program'] = $Program;
}

if ($AtRisk !== '') {
    $sql .= " AND Student.StudentID IN (SELECT StudentID FROM StudentsAtRisk)";
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);

$Students = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>