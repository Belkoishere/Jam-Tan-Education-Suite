<?php
// We need to use sessions, so you should always initialize sessions using the below function
session_start();
// If the user is logged in, redirect to the home page
if (isset($_SESSION['AccountLoggedIn'])) {
    header('Location: Dashboard.php');
    exit;
}

include "../topBar/nav.html";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter</title>
    <link rel="stylesheet" href="form.css">
</head>
<body>
    <div id="main">
        <h2>Se connecter</h2>
        <form action="/Jam-Tan-Education-Suite/controllers/AuthenticateAction.php" method="POST">
            <label for="PhoneNumber">Numéro de téléphone</label>
            <input type="text" name="PhoneNumber">
            <label for="Password">Mot de passe</label>
            <input type="password" name="Password">
            <label for="ShowPassword">Afficher le mot de passe</label>
            <input type="checkbox" id="ShowPassword">
            <!-- <a href="SignUp.php">Nouvelle enregistrement</a>
            <a href="ForgotPassword.php">Mot de passe oublié</a> -->
            <input type="submit" value="Se connecter">
        </form>
    </div>

    <!--Password: Belko!=0; $2y$10$bSsVHG4J8ShyffKvmoLi7.0sGhDAsHBY4H6OZcoi7RqVMJZvOTJWy -->
</body>
</html>