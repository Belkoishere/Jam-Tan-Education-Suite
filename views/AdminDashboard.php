<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}
else if ($_SESSION['StaffAccessL'] != "Administrator"){
    header("Location: TeacherDashbaord.php");
}

include "../nav/admin_nav.html";

$AccountName = $_SESSION['AccountName'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="css/Dashboard.css">
    <link rel="stylesheet" href="css/CircularProgressBar.css">
    <link rel="stylesheet" href="css/Table.css">
    <link rel="stylesheet" href="css/Badges.css">
    <link rel="stylesheet" href="css/Card.css">
    <style>
        #p_dashboard {
            font-weight: bold;
        }
        #s_dashboard {
            font-weight: bold;
        }
    </style>
</head>
<body>
    
    <div id="main">
        <h2><?php echo "Bienvenue " . $AccountName;?></h2>
    </div>

<script src="css/CircularProgressBar.js"></script>
</body>
</html>