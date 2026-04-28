<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include("../nav/admin_nav.html");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un programme</title>
    <style>
        #p_create_program {
            font-weight: bold;
        }
        #s_create_program {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="css/Table.css">
    <link rel="stylesheet" href="css/Badges.css">
</head>
<body>
    <div id="main">
        <h2>Créer un programme</h2>
    </div>
</body>
</html>