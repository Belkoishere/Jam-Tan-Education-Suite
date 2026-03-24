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
    <title>Changer votre mot de passe</title>
    <!-- <link rel="stylesheet" href="../nav/nav.css"> -->
    <style>
        #p_update_password {
            font-weight: bold;
        }
        #s_update_password {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="form.css">
</head>
<body>
    <div id="main">
        <h2>Changer votre mot de passe</h2>

        <form action="../controllers/UpdatePasswordAction.php" method="POST">
            <label for="CurrentPassword">Mot de passe</label>
            <input type="password" name="CurrentPassword">
            <label for="NewPassword">Nouveau mot de passe</label>
            <input type="password" name="NewPassword">
            <label for="ConfirmNewPassword">Confirmer le nouveau mot de passe</label>
            <input type="password" name="ConfirmNewPassword">
            <input type="submit" value="Changer">
        </form>
    </div>
</body>
</html>