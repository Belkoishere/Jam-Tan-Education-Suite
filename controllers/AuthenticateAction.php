<?php
session_start();

require("../helpers/db.php");

if (!isset($_POST['PhoneNumber'], $_POST['Password'])) {
    exit('Entrez le mot de pasee et le numero de telephone');
}

$SearchAccount = "SELECT StaffID, StaffPassword, StaffFirstName 
FROM Staff WHERE StaffContact1 = ?";

if ($stmt = $conn->prepare($SearchAccount)) {
    $stmt->bind_param('s', $_POST['PhoneNumber']);
    $stmt->execute();
    $stmt->store_result();
    $NumRows = $stmt->num_rows;
    
    if ($NumRows == 1) {
        $stmt->bind_result($id, $password, $name);
        $stmt->fetch();

        $verify = password_verify($_POST['Password'], $password);
        
        if ($verify) {
            session_regenerate_id();
            $_SESSION['AccountLoggedIn'] = TRUE;
            $_SESSION['AccountName'] = $name;
            $_SESSION['AccountID'] = $id;
            header('Location: /Jam-Tan-Education-Suite/views/Dashboard.php');
            exit;
        } else {
            echo "Mot de passe incorrect";
        }
    } 
    else if ($NumRows > 1){
        echo 'Un compte avec le meme numero de telephone existe';
    }
    else if ($NumRows < 1){
        echo "Compte n'existe pas"
    }

    $stmt->close();
}
?>