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
        $Messages["Warning"] = "Aucune image téléchargée ou erreur de téléchargement";
    } else {

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/heic'];
        $allowedExts  = ['jpg', 'jpeg', 'png', 'gif', 'heic'];

        $fileTmp  = $_FILES['Image']['tmp_name'];
        $fileSize = $_FILES['Image']['size'];
        $fileName = $_FILES['Image']['name'];

        $fileType = mime_content_type($fileTmp);
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validate MIME
        if (!in_array($fileType, $allowedTypes)) {
            $Messages["Warning"] = "Seuls les fichiers JPG, PNG et GIF sont autorisés";
        }

        // Validate extension
        if (!in_array($ext, $allowedExts)) {
            $Messages["Warning"] = "Extension de fichier non valide";
        }

        // Validate size
        if ($fileSize > 2 * 1024 * 1024) {
            $Messages["Warning"] = "La taille du fichier dépasse 2 Mo";
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

                if ($oldPhoto && $oldPhoto !== 'default.jpg') {
                    $oldFilePath = $uploadDir . $oldPhoto;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $stmt = $conn->prepare("UPDATE Staff SET StaffPicture = ? WHERE StaffID = ?");
                if ($stmt->execute([$newFileName, $AccountID])) {
                    $Messages["Success"] = "Photo de profil mise à jour";
                } else {
                    $Messages["Warning"] = "Erreur lors de la mise à jour de la photo de profil";
                }

            } else {
                $Messages["Warning"] = "Échec du déplacement du fichier téléchargé";
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
    
    <a href="PersonalDetails.php">
        <img src="../icons/navigation-back-arrow-svgrepo-com.svg" 
        alt="Back icon" class="back-icon">
    </a>

    <div id="main">

        <h2>Changer photo de profile</h2>

        <img id="profile-picture" class="profile-picture" src="<?= $CurrentImage?>" style="margin-bottom: 20px;" onerror="this.onerror=null; this.src='../StaffImages/default.jpg';"> 

        <div class="form-container">
            <form method="post" enctype="multipart/form-data">
                <label class="Button">
                    Choisir une image
                    <input 
                        type="file" 
                        name="Image" 
                        id="Image"
                        accept="image/*"
                        style="position:absolute; left:-9999px;"
                        onchange="preview()">
                </label>
                <input type="submit" value="Enregistrer">
            </form>
        </div>
         
    </div>
</body>
<script src="UploadProfilePicture.js"></script>
<script>window.Messages = <?= json_encode($Messages, JSON_HEX_TAG); ?>;</script>
<script src="Alert.js"></script>
</html>