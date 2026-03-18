<?php
session_start();

require("../controllers/db.php");
$AccountID = $_SESSION['AccountID'];

$Title = $_POST["Title"];
$FirstName = $_POST["FirstName"];
$LastName = $_POST["LastName"];
$Email = $_POST["Email"];
$PhoneNumber1 = $_POST["PhoneNumber1"];
$PhoneNumber2 = $_POST["PhoneNumber2"];
$Town = $_POST["Town"];

$UpdateAccount = 
"UPDATE STAFF SET StaffTitle = ?, StaffFirstName = ?, 
StaffLastName = ?, StaffContact1 = ?, 
StaffContact2 = ?, Email = ?, Town = ? 
WHERE StaffID = ?";

$stmt = $conn->prepare($UpdateAccount);
$stmt->execute([$Title, $FirstName, $LastName, $PhoneNumber1,
$PhoneNumber2, $Email, $Town, $AccountID]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);

$conn = null;

header("Location: ../views/PersonalDetails.php")
?>