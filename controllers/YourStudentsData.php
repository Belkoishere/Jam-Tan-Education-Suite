<?php
require("../controllers/db.php");
$AccountID = $_SESSION['AccountID'];

$stmt = 
$conn->prepare("SELECT Program.ProgramID, Program.ProgramName
FROM Staff
INNER JOIN Assignment ON Staff.StaffID = Assignment.StaffID
INNER JOIN Program ON Assignment.ProgramID = Program.ProgramID 
WHERE Staff.StaffID = :id");

$stmt->execute(['id' => $AccountID]);
$Programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$conn = null;

?>