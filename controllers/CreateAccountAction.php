<?php 
//connect to MySQL and handle errors
require("../helpers/db.php");

//take username, email and hashed password
$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

if (!isset($username, $email, $_POST['password'])) {
    exit('Please fill both the username and password fields!');
}

//input username, email and hashed password into account only if account does not already exist
if ($search = $conn->prepare('SELECT username, email FROM account WHERE email = ?')){
    $search->bind_param('s', $email);
    $search->execute();
    $search->store_result();

    if ($search->num_rows == 0){
        $timestamp = date("Y-m-d H:i:s", time());

        $insert = $conn->prepare("INSERT INTO account (id, username, password, email, registered) VALUES (NULL, ?, ?, ?, ?)");
        
        $insert->bind_param("ssss", $username, $password, $email, $timestamp);

        $insert->execute();

        header("Location: index.php");

    } else {
        exit("account with email: $email already exists");
    }
}

//close connections
$search->close();
$insert->close();
$conn->close();

?>