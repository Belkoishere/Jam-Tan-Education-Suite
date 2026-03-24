<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include "../nav/nav.html";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une évaluation</title>
    <!-- <link rel="stylesheet" href="../nav/nav.css"> -->
    <style>
        #p_add_assessment {
            font-weight: bold;
        }
        #s_add_assessment {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div id="main">
        <h2>Ajouter une évaluation</h2>
    </div>
</body>
</html>