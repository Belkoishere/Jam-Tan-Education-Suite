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
    <title>Modifier les comptes</title>
    <style>
        #p_edit_accounts {
            font-weight: bold;
        }
        #s_edit_accounts {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="css/Table.css">
    <link rel="stylesheet" href="css/Badges.css">
</head>
<body>
    <div id="main">
        <h2>Modifier les comptes</h2>
    </div>
</body>
</html>