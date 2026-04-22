<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include "../nav/nav.html";
include "Alert.html";
require("../controllers/CleanSpaces.php");
require("../controllers/db.php");
require_once("../locales/Language.php");
require_once("../locales/French.php");
require_once("../locales/Translate.php");

$French = new Translate (new French);
$AccountID = $_SESSION['AccountID'];
$Errors = [];
$Messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $Title = preg_replace('/\s+/', '', $_POST["Title"]) ?? null;
    $FirstName = cleanSpaces($_POST["FirstName"]) ?? null;
    $LastName = cleanSpaces($_POST["LastName"]) ?? null;
    $Email = preg_replace('/\s+/', '', $_POST["Email"]) ?? null;
    $PhoneNumber1 = preg_replace('/\s+/', '', $_POST["PhoneNumber1"]) ?? null;
    $PhoneNumber2 = preg_replace('/\s+/', '', $_POST["PhoneNumber2"]) ?? null;
    $Town = cleanSpaces($_POST["Town"]) ?? null;

    $FindAccount = $conn->prepare("SELECT StaffContact1, StaffID FROM Staff WHERE StaffContact1 = ?");
    $FindAccount->execute([$PhoneNumber1]);
    $PhoneMatch = $FindAccount->fetch(PDO::FETCH_ASSOC);

    //Title validation checks
    if (empty($Title)){
        $Errors["Title"] = "Please enter title";
    }
    else if (!in_array($Title, ["Mr", "Mrs", "Ms"])){
        $Errors["Title"] = "Title must be either Mr, Mrs or Ms";
    }

    //FirstName validation checks
    if (empty($FirstName)){
        $Errors["FirstName"] = "Please enter first name";
    }
    else if (strlen($FirstName) > 60){
        $Errors["FirstName"] = "First Name cannot exceed 60 characters";
    }

    //LastName validation checks
    if (empty($LastName)){
        $Errors["LastName"] = "Please enter last name";
    }
    else if (strlen($LastName) > 60){
        $Errors["LastName"] = "Last Name cannot exceed 60 characters";
    }

    //Email validation checks
    if (empty($Email)){
        
    }
    else if (filter_var($Email, FILTER_VALIDATE_EMAIL) == false){
        $Errors["Email"] = "Please enter a valid email address";
    }

    //PhoneNumber validation checks
    if (empty($PhoneNumber1)){
        $Errors["PhoneNumber1"] = "Please enter phone number 1";
    }
    else if (strlen($PhoneNumber1) != 8){
        $Errors["PhoneNumber1"] = "Phone number must be 8 digits long";
    }
    else if (!(ctype_digit($PhoneNumber1))) {
        $Errors["PhoneNumber1"] = "Phone number must only contain numbers";
    }
    else if (!empty($PhoneMatch) && $PhoneMatch["StaffID"] != $AccountID) {
        $Errors["PhoneNumber1"] = "An account already exists with this phone numebr";
    }

    if (empty($PhoneNumber2)){
        
    }
    else if (strlen($PhoneNumber2) != 8){
        $Errors["PhoneNumber2"] = "Phone number must be 8 digits long";
    }
    else if (!(ctype_digit($PhoneNumber2))) {
        $Errors["PhoneNumber2"] = "Phone number must only contain numbers";
    }

    //Town validation checks
    if (empty($Town)){
        $Errors["Town"] = "Please enter town";
    }

    if (empty($Errors)){

        try {
            $UpdateAccount = 
            "UPDATE STAFF SET StaffTitle = ?, StaffFirstName = ?, 
            StaffLastName = ?, StaffContact1 = ?, 
            StaffContact2 = ?, Email = ?, Town = ? 
            WHERE StaffID = ?";

            $stmt = $conn->prepare($UpdateAccount);
            $stmt->execute([$Title, $FirstName, $LastName, $PhoneNumber1,
            $PhoneNumber2, $Email, $Town, $AccountID]);

            $Messages["Success"] = "Successfully updated personal details";
        }
        catch (Exception $e){
            echo($e);
        }

    }

}

$SearchAccount = $conn->prepare("SELECT *
FROM Staff WHERE StaffID = ?");

$SearchAccount->execute([$AccountID]);
$Account = $SearchAccount->fetch(PDO::FETCH_ASSOC);

$conn = null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations personnelles</title>
    <style>
        #p_personal_details {
            font-weight: bold;
        }
        #s_personal_details {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/ProfilePicture.css">
    <link rel="stylesheet" href="css/Button.css">
</head>
<body>
    
    <div id="main">

        <h2>Informations personnelles</h2>

        <img class="profile-picture" id="profile-picture" src="../StaffImages/<?= htmlspecialchars($Account["StaffPicture"]);?>"> 
        
        <div style="padding-top: 20px; padding-bottom: 10px;">
            <a href="UploadProfilePicture.php" class="Button">Changer photo de profile</a>
        </div>

        <div class="form-container">
            <form action="" method="POST">
                <label for="Title">Titre</label>
                <select name="Title">
                    <option value="<?= $Account["StaffTitle"]; ?>" hidden><?= $French->Translate($Account["StaffTitle"]);?></option>
                    <option value="Mr">M</option>
                    <option value="Mrs">Mme</option>
                    <option value="Ms">Mlle</option>
                </select>
                <span style="color: red;"><?= $Errors["Title"] ?? null?></span>
                <label for="FirstName">Prénom</label>
                <input type="text" name="FirstName" value="<?= htmlspecialchars($Account["StaffFirstName"]);?>">
                <span style="color: red;"><?= $Errors["FirstName"] ?? null?></span>
                <label for="LastName">Nom de famille</label>
                <input type="text" name="LastName" value="<?= htmlspecialchars($Account["StaffLastName"]);?>">
                <span style="color: red;"><?= $Errors["LastName"] ?? null?></span>
                <label for="Email">Adresse email</label>
                <input type="text" name="Email" value="<?= htmlspecialchars($Account["Email"]);?>">
                <span style="color: red;"><?= $Errors["Email"] ?? null?></span>
                <label for="PhoneNumber1">Numéro de téléphone 1</label>
                <input type="text" name="PhoneNumber1" value="<?= htmlspecialchars($Account["StaffContact1"]);?>">
                <span style="color: red;"><?= $Errors["PhoneNumber1"] ?? null?></span>
                <label for="PhoneNumber2">Numéro de téléphone 2</label>
                <input type="text" name="PhoneNumber2" value="<?= htmlspecialchars($Account["StaffContact2"]);?>">
                <span style="color: red;"><?= $Errors["PhoneNumber2"] ?? null?></span>
                <label for="Town">Ville</label>
                <input type="text" name="Town" value="<?= htmlspecialchars($Account["Town"]);?>">
                <span style="color: red;"><?= $Errors["Town"] ?? null?></span>
                <input type="submit" value="Sauvegarde">
            </form>
        </div>
        
    </div>
<script>window.Messages = <?= json_encode($Messages, JSON_HEX_TAG); ?>;</script>
<script src="Alert.js"></script>
</body>
</html>