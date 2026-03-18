<?php
require("../controllers/db.php");
$AccountID = $_SESSION['AccountID'];

// variables to store results of select queries ran in the dashboard procedure
$YourPrograms = [];
$UpcomingAssessments = [];
$StudentsAtRisk = [];
$AverageAttendance = [];

$Data = $conn->query("CALL TeacherDashboard($AccountID)");

//call the serviceDetails stored procedure with an input serviceID
if ($Data) {

    // Your programs
    $YourPrograms = $Data->fetchAll(PDO::FETCH_ASSOC);
    $Data->nextRowset();

    // Upcoming assessments
    $UpcomingAssessments = $Data->fetchAll(PDO::FETCH_ASSOC);
    $Data->nextRowset();

    // Students at risk
    $StudentsAtRisk = $Data->fetchAll(PDO::FETCH_ASSOC);
    $Data->nextRowset();

    // Average attendance
    $AverageAttendance = $Data->fetchAll(PDO::FETCH_ASSOC);
    $Data->nextRowset();

}

$conn = null;
?>