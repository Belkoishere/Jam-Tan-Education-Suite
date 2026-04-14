<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include "../nav/nav.html";
require("../controllers/db.php");

$StaffID = $_SESSION["AccountID"];

$GetCurrentImage = $conn->prepare("SELECT Staff.StaffPicture FROM Staff WHERE Staff.StaffID = ?");
$GetCurrentImage->execute([$StaffID]);

$CurrentImage = "../StaffImages/" . $GetCurrentImage->fetch(PDO::FETCH_ASSOC)["StaffPicture"];

$conn = null;
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
    <link rel="stylesheet" href="ProfilePicture.css">
</head>
<body>
    
    <a href="PersonalDetails.php">
        <img src="../icons/navigation-back-arrow-svgrepo-com.svg" 
        alt="Back icon" class="back-icon">
    </a>

    <div id="main">

        <h2>Changer photo de profile</h2>

        <img id="profile-picture" src="<?= $CurrentImage?>"> 

        <form action="../controllers/UploadProfilePictureAction.php" method="post" enctype="multipart/form-data">
            <label for="Image">Choisir un image</label>
            <input id="Image" type="file" name="Image" onchange="preview()" accept="image/*" style="display: none;"></input>
            <input type="submit" value="Sauvegarde">
        </form>
        
    </div>
</body>
<script src="UploadProfilePicture.js"></script>
</html>