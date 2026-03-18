<?php
require("../controllers/db.php");
$AccountID = $_SESSION['AccountID'];

$SearchAccount = "SELECT StaffFirstName, StaffLastName, StaffTitle, Town, StaffContact1,
StaffContact2, Email, StaffPicture 
FROM Staff WHERE StaffID = $AccountID";

$stmt = $conn->query($SearchAccount);

$row = $stmt->fetch(PDO::FETCH_ASSOC);

$conn = null;
?>