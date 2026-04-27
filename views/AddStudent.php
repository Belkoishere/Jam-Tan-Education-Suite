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
    <title>Ajouter un élève</title>
    <style>
        #p_add_student {
            font-weight: bold;
        }
        #s_add_student {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/Button.css">
</head>
<body>
    <div id="main">
        <h2>Ajouter un élève</h2>
        <div class="form-container">
            <form action="/Jam-Tan-Education-Suite/controllers/CreateAccountAction.php" method="POST">
            <label for="Gender">Genre</label>
            <select>
                <option value="Male">Mâle</option>
                <option value="Female">Femelle</option>
            </select>
            <label for="FirstName">Prénom</label>
            <input type="text" name="FirstName">
            <label for="LastName">Nom de famille</label>
            <input type="text" name="LastName">
            <label for="Contact1">Contact 1</label>
            <input type="text" name="Contact1">
            <label for="Contact2">Contact 2 (facultatif)</label>
            <input type="text" name="Contact2">
            <label for="Town">Ville</label>
            <input type="text" name="Town">
            <label for="FatherFirstName">Prénom du père</label>
            <input type="text" name="FatherFirstName">
            <label for="FatherLastName">Nom de famille du père</label>
            <input type="text" name="FatherLastName">
            <label for="MotherFirstName">Prénom de la mère</label>
            <input type="text" name="MotherFirsName">
            <label for="MotherLastName">Nom de famille de la mère</label>
            <input type="text" name="MotherLastName">
            <label for="StudentPicture" class="Button">Ajouter photo de profile (facultatif)</label>
            <input id="Image" type="file" name="StudentPicture" onchange="preview()" accept="image/*" style="display: none;">
            <label for="BirthDate">Date de naissance</label>
            <input type="date" name="BirthDate">
            <input type="submit" value="Ajouter">
        </form>
        </div>
        
    </div>

</body>
</html>