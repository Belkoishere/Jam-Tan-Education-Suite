<?php

session_start();

require("../controllers/db.php");
$AccountID = $_SESSION['AccountID'];

$CurrentPassword = $_POST["CurrentPassword"];
$NewPassword = $_POST["NewPassword"];
$ConfirmNewPassword = $_POST["ConfirmNewPassword"];

$FindCurrentPassword = "SELECT StaffPassword
FROM Staff WHERE StaffID = ?";

$PasswordResult = $conn->prepare($FindCurrentPassword);
$PasswordResult->execute([$AccountID]);

$FoundPassword = $PasswordResult->fetch(PDO::FETCH_ASSOC);

if(!password_verify($CurrentPassword, $FoundPassword["StaffPassword"])){
    echo ("Password does not match current password " . $FoundPassword["StaffPassword"]. " " . $CurrentPassword);
}
else if ($NewPassword == $ConfirmNewPassword) {

    $HashedNewPassword = password_hash($NewPassword, PASSWORD_DEFAULT);

    $UpdatePassword = 
    "UPDATE STAFF SET StaffPassword = ? 
    WHERE StaffID = ?";

    $stmt = $conn->prepare($UpdatePassword);
    $stmt->execute([$HashedNewPassword, $AccountID]);

    header("Location: ../views/UpdatePassword.php");
}
else{
    echo ("Confirmed password does not match new password");
}

$conn = null;

?>