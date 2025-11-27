<?php
session_start();

if (isset($_SESSION['invalid_username'])) {
    $msg = $_SESSION['invalid_username'];
    $msg = addslashes($msg);
    echo "<script>alert('$msg');</script>";
    unset($_SESSION['invalid_username']);
}

if (isset($_SESSION['invalid_email'])) {
    $msg = $_SESSION['invalid_email'];
    $msg = addslashes($msg);
    echo "<script>alert('$msg');</script>";
    unset($_SESSION['invalid_email']);
}

if (isset($_SESSION['invalid_password'])) {
    $msg = $_SESSION['invalid_password'];
    $msg = addslashes($msg);
    echo "<script>alert('$msg');</script>";
    unset($_SESSION['invalid_password']);
}

if (isset($_SESSION['invalid_confirm'])) {
    $msg = $_SESSION['invalid_confirm'];
    $msg = addslashes($msg);
    echo "<script>alert('$msg');</script>";
    unset($_SESSION['invalid_confirm']);
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign Up - Job Tracker</title>
        <link rel="stylesheet" href="assets/css/signup.css"> 
        <script class="validate">

            async function valUsername(username) {

                if (username.length == 0) {
                    document.getElementById('username-error').innerHTML = "";
                    return;
                }

                try {
                    
                    const response = await fetch('val/validation.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            username: username
                        })
                    });

                    const result = await response.text();
                    document.getElementById('username-error').innerHTML = result;

                } catch (err) {
                    console.error(err);
                }

            }

            async function valEmail(email) {

                if (email) {
                    document.getElementById('email-error').innerHTML = "";
                    return null;
                }

                try {

                    const response = await fetch('val/validation.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            email: email
                        })
                    });

                    const result = await response.text();
                    document.getElementById('email-error').innerHTML = result;

                } catch (err) {
                    console.error('Request failed ', err.message);
                }

            }

            async function valPassword(password) {

                if (password.length == 0) {
                    document.getElementById('password-error').innerHTML = "";
                    return;
                }

                try {
                    
                    const response = await fetch('val/validation.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            password: password
                        })
                    });

                    const result = await response.text();
                    document.getElementById('password-error').innerHTML = result;

                } catch (err) {
                    console.error('Request failed ', err.message);
                }
            }

            function onChange() {
                const password = document.getElementById('password');
                const confirm = document.getElementById('confirm');

                if (password.value == confirm.value || confirm.value == "" ) {
                    document.getElementById('confirm-error').innerHTML = "";
                } else {
                    document.getElementById('confirm-error').innerHTML = 
                    "Passwords do not match.";
                }
            }

        </script>
    </head>
    
    <body>

        <?php include "includes/header.php" ?>

        <main>
            <div class="signup-box">
                <form action="loading.php" method="post">
                    <h2>Sign Up and join jobPilot!</h2>
                    <div class="user-input">

                        <!-- Username -->

                        <span class="icon">
                            <ion-icon name="person-outline"></ion-icon>
                        </span>
                        <input type="text" name="username" id="username" onkeyup="valUsername(this.value)" required>
                        <label for="username">Username</label>
                        <span class="asterisk">* </span>
                        <span class="error-message" id="username-error"></span>
                    </div>

                        <!-- E-mail -->

                    <div class="user-input">
                        <span class="icon">
                            <ion-icon name="mail-outline"></ion-icon>
                        </span>
                        <input type="email" name="email" id="email" onkeyup="valEmail(this.value)" required>
                        <label for="email">E-mail</label>
                        <span class="asterisk">* </span>
                        <span class="error-message" id="email-error"></span>
                    </div>

                        <!-- Password -->

                    <div class="user-input">
                        <span class="icon">
                            <ion-icon name="lock-closed-outline"></ion-icon>
                        </span>
                        <input type="password" name="password" id="password" required onkeyup="valPassword(this.value); onChange()" minlength="8">
                        <label for="password">Password</label>
                        <span class="asterisk">* </span>
                        <span class="error-message" id="password-error"></span>
                    </div>

                        <!-- Confirm Password -->

                    <div class="user-input">
                        <span class="icon">
                            <ion-icon name="bag-check-outline"></ion-icon>
                        </span>
                        <input type="password" name="confirm" id="confirm" required onkeyup="onChange()" minlength="8">
                        <label for="confirm">Confirm Password</label>
                        <span class="asterisk">* </span>
                        <span class="error-message" id="confirm-error"></span>
                    </div>

                        <!-- Remember Me -->

                    <div class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember me</label>
                    </div>

                        <!-- Submit Form / Show Login -->

                    <button type="submit" name="signup" id="btn">Create account</button>

                    <p class="show-login">
                        Already a member? <a href="index.php">Login</a>
                    </p>
                </form>
            </div>
        </main>

        <?php include "includes/footer.php" ?>


        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    </body>
</html>