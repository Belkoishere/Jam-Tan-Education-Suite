<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

include "../nav/nav.html";
include "Alert.html";
require("../controllers/db.php");

$AccountID = $_SESSION["AccountID"];
$Messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_FILES['Image']) || $_FILES['Image']['error'] !== UPLOAD_ERR_OK) {
        $Messages["Warning"] = "No file uploaded or upload error";
    } else {

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $allowedExts  = ['jpg', 'jpeg', 'png', 'gif'];

        $fileTmp  = $_FILES['Image']['tmp_name'];
        $fileSize = $_FILES['Image']['size'];
        $fileName = $_FILES['Image']['name'];

        $fileType = mime_content_type($fileTmp);
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validate MIME
        if (!in_array($fileType, $allowedTypes)) {
            $Messages["Warning"] = "Only JPG, PNG, and GIF files are allowed";
        }

        // Validate extension
        if (!in_array($ext, $allowedExts)) {
            $Messages["Warning"] = "Invalid file extension";
        }

        // Validate size
        if ($fileSize > 2 * 1024 * 1024) {
            $Messages["Warning"] = "File size exceeds 2MB.";
        }

        if (!isset($Messages["Warning"])) {

            $uploadDir = "../StaffImages/";

            $stmt = $conn->prepare("SELECT StaffPicture FROM Staff WHERE StaffID = ?");
            $stmt->execute([$AccountID]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $oldPhoto = $user['StaffPicture'] ?? null;

            $newFileName = uniqid("profile_", true) . "." . $ext;
            $uploadPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmp, $uploadPath)) {

                if ($oldPhoto && $oldPhoto !== 'default.png') {
                    $oldFilePath = $uploadDir . $oldPhoto;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $stmt = $conn->prepare("UPDATE Staff SET StaffPicture = ? WHERE StaffID = ?");
                if ($stmt->execute([$newFileName, $AccountID])) {
                    $Messages["Success"] = "Profile photo updated successfully!";
                } else {
                    $Messages["Warning"] = "Error updating database.";
                }

            } else {
                $Messages["Warning"] = "Failed to move uploaded file.";
            }
        }
    }
}

$stmt = $conn->prepare("SELECT StaffPicture FROM Staff WHERE StaffID = ?");
$stmt->execute([$AccountID]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);


$CurrentImage = "../StaffImages/" . ($result['StaffPicture']);

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

        <form action="" method="post" enctype="multipart/form-data">
            <label for="Image">Choisir un image</label>
            <input id="Image" type="file" name="Image" onchange="preview()" accept="image/*" style="display: none;">
            <input type="submit" value="Sauvegarde">
        </form>
        
    </div>
</body>
<script src="UploadProfilePicture.js"></script>
<script>window.Messages = <?= json_encode($Messages, JSON_HEX_TAG); ?>;</script>
<script src="Alert.js"></script>
</html>