<?php

require("../controllers/db.php");
$AccountID = $_SESSION['AccountID'];

$GetStudents = "SELECT StaffFirstName, StaffLastName, StaffTitle, Town, StaffContact1,
StaffContact2, Email, StaffPicture 
FROM Staff WHERE StaffID = $AccountID";

$stmt = $conn->query($GetStudents);

$row = $stmt->fetch(PDO::FETCH_ASSOC);

$conn = null;

?>