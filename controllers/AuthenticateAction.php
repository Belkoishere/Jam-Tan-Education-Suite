<?php
// Start the session
session_start();

require("../helpers/db.php")

//check if the data from the login form was submitted, isset() will check if the data exists
if (!isset($_POST['username'], $_POST['password'])) {
    // Could not get the data that should have been sent
    exit('Please fill both the username and password fields!');
}

// Prepare our SQL, which will prevent SQL injection
if ($stmt = $conn->prepare('SELECT id, password FROM account WHERE username = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database
    $stmt->store_result();
    
    // Check if account exists with the input username
    if ($stmt->num_rows > 0) {
        // Account exists, so bind the results to variables
        $stmt->bind_result($id, $password);
        $stmt->fetch();

        // Note: remember to use password_hash in your registration file to store the hashed passwords
        $verify = password_verify($_POST['password'], $password);
        
        if ($verify) {
            // password is correct! User has logged in!
            // Regenerate the session ID to prevent session fixation attacks
            session_regenerate_id();
            // Declare session variables (they basically act like cookies but the data is remembered on the server)
            $_SESSION['account_loggedin'] = TRUE;
            $_SESSION['account_name'] = $_POST['username'];
            $_SESSION['account_id'] = $id;
            // If successful redirect user to home.php
            header('Location: home.php');
            exit;
        } else {
            // Incorrect password
            echo "Incorrect username and/or password! 0";
        }
    } else {
        // Incorrect username
        echo 'Incorrect username and/or password! 1';
    }

    // Close the prepared statement
    $stmt->close();
}
?>