<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
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
    <style>
        form {
        border-radius: 5px;
        background-color: #f2f2f2;
        padding: 20px;
        }

        label {display: block;}

        input[type="text"], input[type="password"] {
        width: 100%;
        padding: 12px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        }

        input[type=checkbox] {
        /* width: 100%; */
        padding: 14px;
        margin: 8px 0;
        cursor: pointer;
        }

        input[type=submit] {
        width: 100%;
        background-color: red;
        color: white;
        padding: 14px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        }

        input[type=submit]:hover {
        background-color: green;
        }
    </style>
</head>
<body>
    <div id="main">
        <h2>Se connecter</h2>
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
            <label for="Town">Ville</label>
            <input type="text" id="Town" name="Town">
            <label for="Role">Rôle</label>
            <input type="text" id="Role" name="Role">
            <label for="Password">Mot de passe</label>
            <input type="password" name="Password">
            <label for="ShowPassword">Afficher le mot de passe</label>
            <input type="checkbox" id="ShowPassword">
            <label for="ConfirmPassword">Confirmez le mot de passe</label>
            <input type="password" id="ConfirmPassword" name="ConfirmPassword">
            <label for="ShowPassword">Afficher le mot de passe</label>
            <input type="checkbox" id="ShowPassword">
            <!-- <a href="SignUp.php">Nouvelle enregistrement</a>
            <a href="ForgotPassword.php">Mot de passe oublié</a> -->
            <input type="submit" value="Demande d’enregistrement">
        </form>
    </div>
    
    <p><?php echo(password_hash("Belko!=0;", PASSWORD_DEFAULT))?></p>
</body>
</html>