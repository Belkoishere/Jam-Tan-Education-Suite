<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include "../nav/admin_nav.html";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte</title>
    <style>
        #p_create_account {
            font-weight: bold;
        }
        #s_create_account {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="css/form.css">
</head>
<body>
    <div id="main">
        <h2>Créer un compte</h2>
        <div class="form-container">
            <form action="/Jam-Tan-Education-Suite/controllers/CreateAccountAction.php" method="POST">
            <label for="Title">Titre</label>
            <select>
                <option value="M">M</option>
                <option value="Ms">Ms</option>
                <option value="Mlle">Mlle</option>
            </select>
            <label for="FirstName">Prénom</label>
            <input type="text"  id="FirstName">
            <label for="LastName">Nom de famille</label>
            <input type="text" id="LastName">
            <label for="Email">E-mail</label>
            <input type="text" id="Email">
            <label for="PhoneNumber1">Numéro de téléphone</label>
            <input type="text" id="PhoneNumber1" name="PhoneNumber1">
            <label for="PhoneNumber2">Numéro de téléphone 2 (facultatif)</label>
            <input type="text" id="PhoneNumber2" name="PhoneNumber2">
            <label for="Town">Ville de résidence</label>
            <input type="text" id="Town" name="Town">
            <label for="Role">Rôle</label>
            <select id="Role" name="Role">
                <option value="Teacher">Enseignant</option>
                <option value="Administrator">Administrateur</option>
            </select>
            <label for="Password">Mot de passe</label>
            <input type="password" name="Password">
            <label for="ConfirmPassword">Confirmez le mot de passe</label>
            <input type="password" id="ConfirmPassword" name="ConfirmPassword">
            <!-- <a href="SignUp.php">Nouvelle enregistrement</a>
            <a href="ForgotPassword.php">Mot de passe oublié</a> -->
            <input type="submit" value="Créer">
        </form>
        </div>
        
    </div>
    
</body>
</html>