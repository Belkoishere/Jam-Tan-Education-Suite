<?php
session_start();

require("../controllers/db.php");

if (!isset($_POST['PhoneNumber'], $_POST['Password'])) {
    exit('Entrez le mot de pasee et le numero de telephone');
}

$SearchAccount = "SELECT StaffID, StaffPassword, StaffFirstName 
FROM Staff WHERE StaffContact1 = ?";

$stmt = $conn->prepare($SearchAccount);
$stmt->execute([$_POST['PhoneNumber']]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);

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
        echo "Mot de passe incorrect";
    }
} else {
    echo "Compte n'existe pas";
}

$conn = null;
?>