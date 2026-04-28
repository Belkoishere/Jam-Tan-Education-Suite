<?php
//Password: MeOhMy!2017202
session_start();

if (isset($_SESSION['AccountLoggedIn'])) {
    header('Location: Dashboard.php');
    exit;
}

include "Alert.html";
include "../topBar/nav.html";
require("../controllers/db.php");

$Errors = [];
$Messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    if (empty($_POST['PhoneNumber'])) {
        $Errors["PhoneNumber"] = "Entrez le numéro de téléphone";
    }
    
    if (empty($_POST['Password'])){
        $Errors["Password"] = "Entrez le mot de passe";
    }

    $SearchAccount = $conn->prepare("SELECT StaffID, StaffPassword, StaffFirstName, StaffAccessLevel
    FROM Staff WHERE StaffContact1 = ?");

    $SearchAccount->execute([$_POST['PhoneNumber']]);

    $row = $SearchAccount->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $id = $row['StaffID'];
        $password = $row['StaffPassword'];
        $name = $row['StaffFirstName'];
        $access = $row['StaffAccessLevel'];

        if (password_verify($_POST['Password'], $password)) {
            session_regenerate_id();
            $_SESSION['AccountLoggedIn'] = TRUE;
            $_SESSION['AccountName'] = $name;
            $_SESSION['AccountID'] = $id;
            $_SESSION['AccessLevel'] = $access;

            if ($access == "Teacher"){
                header('Location: /Jam-Tan-Education-Suite/views/Dashboard.php');
                exit;
            }
            else if ($access == "Administrator"){
                header('Location: /Jam-Tan-Education-Suite/views/AdminDashboard.php');
                exit;
            }
            else {
                $Messages["Error"] = "Niveau d'accès non défini pour le compte";
            }
            
        } else {
            $Errors["Password"] = "Mot de passe incorrect";
        }
    } else {
        $Errors["PhoneNumber"] = "Aucun compte n'existe avec ce numéro de téléphone";
    }

}

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter</title>
    <link rel="stylesheet" href="css/form.css">
</head>
<body>
    
    <div id="main">
        <h2>Se connecter</h2>
        <form action="" method="POST">
            <label for="PhoneNumber">Numéro de téléphone</label>
            <input type="text" name="PhoneNumber" value="<?= htmlspecialchars($_POST['PhoneNumber'] ?? null)?>">
            <span style="color: red;"><?= $Errors["PhoneNumber"] ?? null?></span>
            <label for="Password">Mot de passe</label>
            <input type="password" name="Password" value="<?= htmlspecialchars($_POST['Password'] ?? null)?>">
            <span style="color: red;"><?= $Errors["Password"] ?? null?></span>
            <input type="submit" value="Se connecter">
        </form>
    </div>

<script>window.Messages = <?= json_encode($Messages, JSON_HEX_TAG); ?>;</script>
<script src="Alert.js"></script>
</body>
</html>