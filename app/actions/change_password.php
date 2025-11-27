<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['invalid_new_password'])) {
    $msg = $_SESSION['invalid_new_password'];
    $msg = addslashes($msg);
    echo "<script>alert('$msg');</script>";
    unset($_SESSION['invalid_new_password']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['current_password']) == TRUE && isset($_POST['new_password']) == TRUE && isset($_POST['confirm_password']) == TRUE) {

    $user_id = $_SESSION['user_id'];
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if (strlen($current) < 8) {
        $_SESSION['invalid_new_password'] = "Password must be at least 8 chars long.";
        header("Location: change_password.php");
        exit();
    }

    if (strlen($new) < 8) {
        $_SESSION['invalid_new_password'] = "Password must be at least 8 chars long.";
        header("Location: change_password.php");
        exit();
    }

    if ($new !== $confirm) {
        $_SESSION['invalid_new_password'] = "Passwords do not match.";
        header("Location: change_password.php");
        exit();
    }

    if ($current == $new) {
        $_SESSION['invalid_new_password'] = "New password matches old one. Try new password.";
        header("Location: change_password.php");
        exit();
    }

    include "../includes/db.php";
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?;");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];
    if (!password_verify($current, $hashed_password)) {
        $_SESSION['invalid_new_password'] = "Password incorrect. Please check your password and try again.";
        header("Location: change_password.php");
        exit();
    } else {
        $hashed_new_password = password_hash($new, PASSWORD_DEFAULT);
        $stmt2 = $conn->prepare("UPDATE users
                                SET password=?
                                WHERE id=?;");
        $stmt2->bind_param("si", $hashed_new_password, $user_id);
        $stmt2->execute();
        $_SESSION['password_changed'] = "Password has changed successfully.";
        header("Location: ../profile.php");
        $stmt->close();
        $stmt->close();
        $conn->close();
    }
}
?>

<html>
    <head>
        <title>Password change</title>
        <style>
            .asterisk , .error {
                color: red;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Change your password</h2>
            <form action="change_password.php" method="POST">
                <div class="user-input">
                    <label for="current">Current password: </label>
                    <input type="password" name="current_password" id="current" onkeyup="valPassword(this.value);" minlength="8" required>
                    <span class="asterisk"> * </span>
                    <span class="error" id="current_error"></span>
                </div>
                <div class="user_input">
                    <label for="new">New password: </label>
                    <input type="password" name="new_password" id="new" onkeyup="valPassword(this.value); onChange();" minlength="8" required>
                    <span class="asterisk"> * </span>
                    <span class="error" id="new_error"></span>
                </div>
                <div class="user_input">
                    <label for="confirm">Cofirm new password: </label>
                    <input type="password" name="confirm_password" id="confirm" onkeyup="onChange();" minlength="8" required>
                    <span class="asterisk"> * </span>
                    <span class="error" id="confirm_error"></span>
                </div>
                <button type="submit" name="submit" id="btn">Update password</button>
            </form>
        </div>

        <script>

            async function valPassword(password) {
                
                if (password.length == 0) {
                    document.getElementById('current_error').innerHTML = "";
                    return;
                }

                try {

                    const response = await fetch('..val/validation.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            password: password
                        })
                    });

                    const result = await response.text();
                    document.getElementById('current-error').innerHTML = result;

                } catch (err) {
                    console.error('Request failed ', err.message);
                }
            }

            function onChange() {
                const password = document.getElementById('new');
                const confirm = document.getElementById('confirm');

                if (password.value == confirm.value || confirm.value == "") {
                    document.getElementById('confirm_error').innerHTML = "";
                } else {
                    document.getElementById('confirm_error').innerHTML = 
                    "Passwords do not match.";
                }
            }
        </script>
    </body>
</html>