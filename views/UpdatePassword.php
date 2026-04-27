<?php
//Password: Meohmy!2010089
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

if ($_SESSION['AccessLevel'] == "Teacher"){
    include "../nav/nav.html";
}
else if ($_SESSION['AccessLevel'] == "Administrator") {
    include "../nav/admin_nav.html";
}

include "Alert.html";
require("../controllers/db.php");
require("../controllers/ContainsAll.php");

$AccountID = $_SESSION['AccountID'];

$Errors = [];
$Messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $CurrentPassword = $_POST["CurrentPassword"] ?? null;
    $NewPassword = $_POST["NewPassword"] ?? null;
    $ConfirmNewPassword = $_POST["ConfirmNewPassword"] ?? null;

    $FindCurrentPassword = $conn->prepare("SELECT StaffPassword
    FROM Staff WHERE StaffID = ?");

    $FindCurrentPassword->execute([$AccountID]);

    $FoundPassword = $FindCurrentPassword->fetch(PDO::FETCH_ASSOC);

    //CurrentPassword validation
    if (empty($CurrentPassword)){
        $Errors["CurrentPassword"] = "Please enter current passowrd";
    }
    else if(!password_verify($CurrentPassword, $FoundPassword["StaffPassword"])){
        $Errors["CurrentPassword"] = "Input current password does not match current password";
    }

    //NewPassword validation
    if (empty($NewPassword)){
        $Errors["NewPassword"] = "Please enter new password";
    } 
    else if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{12,}$/', $NewPassword)){
        $Errors["NewPassword"] = "New password must contain at least one number, lower case letter
        uppercase letter and special charachter and must be atleast 12 characters long";
    }
    else if ($NewPassword != $ConfirmNewPassword){
        $Errors["NewPassword"] = "Confirmed password does not match new password";
    }

    if (empty($Errors)){

        try {
            $HashedNewPassword = password_hash($NewPassword, PASSWORD_DEFAULT);

            $UpdatePassword = 
            $conn->prepare("UPDATE STAFF SET StaffPassword = ? 
            WHERE StaffID = ?");

            $UpdatePassword->execute([$HashedNewPassword, $AccountID]);

            $Messages["Success"] = "Password updated successfully"; 
        }
        catch (Exception $e){
            $Messages["Warning"] = $e;
        }
    }

}

$conn = null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer votre mot de passe</title>
    <style>
        #p_update_password {
            font-weight: bold;
        }
        #s_update_password {
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="css/form.css">
</head>
<body>
    <div id="main">
        <h2>Changer votre mot de passe</h2>

        <form action="" method="POST">
            <label for="CurrentPassword">Mot de passe</label>
            <input type="password" name="CurrentPassword" value="<?= htmlspecialchars($_POST['CurrentPassword'] ?? "")?>">
            <span style="color: red;"><?= $Errors["CurrentPassword"] ?? null?></span>
            <label for="NewPassword">Nouveau mot de passe</label>
            <input type="password" name="NewPassword" value="<?= htmlspecialchars($_POST['NewPassword'] ?? "")?>">
            <span style="color: red;"><?= $Errors["NewPassword"] ?? null?></span>
            <label for="ConfirmNewPassword">Confirmer le nouveau mot de passe</label>
            <input type="password" name="ConfirmNewPassword" value="<?= htmlspecialchars($_POST['ConfirmNewPassword'] ?? "")?>">
            <span style="color: red;"><?= $Errors["ConfirmNewPassword"] ?? null?></span>
            <input type="submit" value="Changer">
        </form>
    </div>

<script>window.Messages = <?= json_encode($Messages, JSON_HEX_TAG); ?>;</script>
<script src="Alert.js"></script>
</body>
</html>