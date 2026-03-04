<?php
// Start the session
session_start();

require("../helpers/db.php");

//Check if the data from the login form was submitted
if (!isset($_POST['PhoneNumber'], $_POST['Password'])) {
    //Could not get the data that should have been sent
    exit('Entrez le mot de pasee et le numero de telephone!');
}

// Prepare our SQL, which will prevent SQL injection
if ($stmt = $conn->prepare('SELECT StaffID, StaffPassword, StaffFirstName FROM Staff WHERE StaffContact1 = ?')) {
    // Bind StaffContact1 Parameter as string
    $stmt->bind_param('s', $_POST['PhoneNumber']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database
    $stmt->store_result();
    
    // Check if account exists with the input phone number
    if ($stmt->num_rows > 0) {
        // Account exists, so bind the results to variables
        $stmt->bind_result($id, $password, $name);
        $stmt->fetch();

        // Use password_verify to verify password hash
        $verify = password_verify($_POST['Password'], $password);
        
        if ($verify) {
            // password is correct! User has logged in!
            // Regenerate the session ID to prevent session fixation attacks
            session_regenerate_id();
            // Declare session variables 
            $_SESSION['account_loggedin'] = TRUE;
            $_SESSION['account_name'] = $name;
            $_SESSION['account_id'] = $id;
            // If successfull redirect user to their dashboard
            header('Location: /Jam-Tan-Education-Suite/views/Dashboard.php');
            exit;
        } else {
            // Incorrect password
            echo "Mot de passe incorrect! 0";
        }
    } else {
        // Staff record does not exist
        echo 'Numero de telephone et/ou mot de passe incorrect! 1';
    }

    // Close the prepared statement
    $stmt->close();
}
?>