<?php
// We need to use sessions, so you should always initialize sessions using the below function
session_start();
// If the user is logged in, redirect to the home page
if (isset($_SESSION['account_loggedin'])) {
    header('Location: home.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login">

        <h1>Register</h1>

        <form action="create_account.php" method="post" class="form login-form">

            <label class="form-label" for="username">Username</label>
            <div class="form-group">
                <input class="form-input" type="text" name="username" placeholder="Username" id="username" required>
            </div>

            <label class="form-label" for="username">Email</label>
            <div class="form-group">
                <input class="form-input" type="text" name="email" placeholder="Email address" id="email" required>
            </div>

            <label class="form-label" for="password">Password</label>
            <div class="form-group mar-bot-5">
                <input class="form-input" type="password" name="password" placeholder="Password" id="password" required>
            </div>

            <button class="btn blue" type="submit">Register</button>

            <p class="register-link">Already have an account? <a href="index.php" class="form-link">Login</a></p>

        </form>

    </div>
</body>
</html>