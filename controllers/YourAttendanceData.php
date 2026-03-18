<?php
require("../controllers/db.php");
$AccountID = $_SESSION['AccountID'];

$YourAttendance = [];

$stmt = $conn->query("CALL YourAttendance($AccountID)");

// Your attendance
$YourAttendance = $stmt->fetchall(PDO::FETCH_ASSOC);

$conn = null;

?>