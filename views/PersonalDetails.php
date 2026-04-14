<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include "../nav/nav.html";
require("../controllers/PersonalDetailsData.php");
require_once("../locales/Language.php");
require_once("../locales/French.php");
require_once("../locales/Translate.php");

$French = new Translate (new French);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations personnelles</title>
    <!-- <link rel="stylesheet" href="../nav/nav.css"> -->
    <style>
        #p_personal_details {
            font-weight: bold;
        }
        #s_personal_details {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="PersonalDetails.css">
    <link rel="stylesheet" href="ProfilePicture.css">
</head>
<body>
    
    <div id="main">

        <h2>Informations personnelles</h2>

        <img id="profile-picture" src="../StaffImages/<?= htmlspecialchars($row["StaffPicture"]);?>"> 
        <a href="UploadProfilePicture.php">Changer photo de profile</a>

        <div class="form-container">
            <form action="../controllers/PersonalDetailsAction.php" method="POST">
                <label for="Title">Titre</label>
                <select name="Title">
                    <option value="<?= $row["StaffTitle"]; ?>" hidden><?= $French->Translate($row["StaffTitle"]);?></option>
                    <option value="Mr">M</option>
                    <option value="Mrs">Mme</option>
                    <option value="Ms">Mlle</option>
                </select>
                <label for="FirstName">Prénom</label>
                <input type="text" name="FirstName" value="<?= htmlspecialchars($row["StaffFirstName"]);?>">
                <label for="LastName">Nom de famille</label>
                <input type="text" name="LastName" value="<?= htmlspecialchars($row["StaffLastName"]);?>">
                <label for="Email">Adresse email</label>
                <input type="text" name="Email" value="<?= htmlspecialchars($row["Email"]);?>">
                <label for="PhoneNumber1">Numéro de téléphone 1</label>
                <input type="text" name="PhoneNumber1" value="<?= htmlspecialchars($row["StaffContact1"]);?>">
                <label for="PhoneNumber2">Numéro de téléphone 2</label>
                <input type="text" name="PhoneNumber2" value="<?= htmlspecialchars($row["StaffContact2"]);?>">
                <label for="Town">Ville</label>
                <input type="text" name="Town" value="<?= htmlspecialchars($row["Town"]);?>">
                <input type="submit" value="Sauvegarde">
            </form>
        </div>
        
    </div>
</body>
</html>