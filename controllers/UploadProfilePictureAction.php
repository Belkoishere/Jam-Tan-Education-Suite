<?php
session_start();

if (!isset($_SESSION['AccountLoggedIn'])) {
    header("Location: index.php");
    exit;
}

require("../controllers/db.php");

$userId = $_SESSION["AccountID"];

// Check if file was uploaded
if (!isset($_FILES['Image']) || $_FILES['Image']['error'] !== UPLOAD_ERR_OK) {
    die("Error: No file uploaded or upload error.");
}

// File validation
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
$fileType = mime_content_type($_FILES['Image']['tmp_name']);
if (!in_array($fileType, $allowedTypes)) {
    die("Error: Only JPG, PNG, and GIF files are allowed.");
}

// Limit file size (2MB)
if ($_FILES['Image']['size'] > 2 * 1024 * 1024) {
    die("Error: File size exceeds 2MB.");
}

$uploadDir = "../StaffImages/";

// Get current profile photo from DB
$stmt = $conn->prepare("SELECT StaffPicture FROM Staff WHERE StaffID = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
$oldPhoto = $user ['StaffPicture'] ?? null;

// Generate unique file name
$ext = pathinfo($_FILES['Image']['name'], PATHINFO_EXTENSION);
$newFileName = uniqid("profile_", true) . "." . strtolower($ext);
$uploadPath = $uploadDir . $newFileName;

// Move uploaded file
if (!move_uploaded_file($_FILES['Image']['tmp_name'], $uploadPath)) {
    die("Error: Failed to save uploaded file.");
}

// Delete old photo if it's not the default
if ($oldPhoto && $oldPhoto !== 'default.png') {
    $oldFilePath = $uploadDir . $oldPhoto;
    if (file_exists($oldFilePath)) {
        unlink($oldFilePath);
    }
}

// Save new file name in database
$stmt = $conn->prepare("UPDATE Staff SET StaffPicture = ? WHERE StaffID = ?");
if ($stmt->execute([$newFileName, $userId])) {
    echo "Profile photo updated successfully!<br>";
    echo "<img src='uploads/" . htmlspecialchars($newFileName) . "' width='150'>";
} else {
    echo "Error updating profile photo.";
}

$conn = null;

header("Location: ../views/UploadProfilePicture.php");
?>