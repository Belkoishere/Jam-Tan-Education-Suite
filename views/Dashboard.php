<?php
session_start();

include "../nav/nav.html";

if (!isset($_SESSION['account_loggedin'])) {
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <!-- <link rel="stylesheet" href="../nav/nav.css"> -->
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
        <h1><?php echo "Bienvenue " . $_SESSION['account_name'];?></h1>
    </div>
</body>
</html>