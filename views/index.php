<?php
//Password: MeOhMy!2017202
session_start();

if (isset($_SESSION['AccountLoggedIn'])) {
    header('Location: Dashboard.php');
    exit;
}

include "../topBar/nav.html";
require("../controllers/db.php");

$Errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    if (empty($_POST['PhoneNumber'])) {
        $Errors["PhoneNumber"] = "Please enter your phone number";
    }
    
    if (empty($_POST['Password'])){
        $Errors["Password"] = "Please enter your password";
    }

    $SearchAccount = $conn->prepare("SELECT StaffID, StaffPassword, StaffFirstName 
    FROM Staff WHERE StaffContact1 = ?");

    $SearchAccount->execute([$_POST['PhoneNumber']]);

    $row = $SearchAccount->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $id = $row['StaffID'];
        $password = $row['StaffPassword'];
        $name = $row['StaffFirstName'];

        if (password_verify($_POST['Password'], $password)) {
            session_regenerate_id();
            $_SESSION['AccountLoggedIn'] = TRUE;
            $_SESSION['AccountName'] = $name;
            $_SESSION['AccountID'] = $id;

            header('Location: /Jam-Tan-Education-Suite/views/Dashboard.php');
            exit;
        } else {
            $Errors["Password"] = "Incorrect password";
        }
    } else {
        $Errors["PhoneNumber"] = "Account with this phone number does not exist";
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
    <link rel="stylesheet" href="form.css">
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

</body>
</html>