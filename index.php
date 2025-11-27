<?php
session_start();

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

if (isset($_SESSION['loggedin'])) {
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Job Tracker</title>
        <link rel=stylesheet href="assets/css/index.css">
        <script class="validate">

            async function valEmail(email) {

                if (email.length == 0) {
                    document.getElementById('email-error').innerHTML = "";
                    return;
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
                    console.error('Request failed', err.message);
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
                    console.error('Request failed', err.message);
                }
            }


        </script>
    </head>

    <body>

        <?php include "includes/header.php" ?>

        <main>
            <div class="login-box">
                <form action="loading.php" method="POST">
                    <h2>Login</h2>
                    <div class="user-input">
                        <span class="icon">
                            <ion-icon name="mail-open-outline"></ion-icon>
                        </span>
                        <input type="email" name="email" id="email" onkeyup="valEmail(this.value)" required>
                        <label for="email">Email</label>
                        <span class="asterisk">* </span>
                        <span class="error-message" id="email-error"></span>
                    </div>
                    <div class="user-input">
                        <span class="icon">
                            <ion-icon name="lock-closed-outline"></ion-icon>
                        </span>
                        <input type="password" name="password" id="password" onkeyup="valPassword(this.value)" required minlength="8">
                        <label for="password">Password</label>
                        <span class="asterisk">* </span>
                        <span class="error-message" id="password-error"></span>
                    </div>
                    <div class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember"> Remember me</label>
                    </div>
                    <button type="submit" name="login" id="btn">Login</button>
                    <div class="signup-account">
                        <p>Don't have an account? <a href="signup.php">Create one</a></p>
                    </div>
                </form>
            </div>
        </main>

        <?php include "includes/footer.php" ?>

        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    </body>
</html>