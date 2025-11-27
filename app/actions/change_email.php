<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['invalid_new_email'])) {
    $msg = $_SESSION['invalid_new_email'];
    $msg = addslashes($msg);
    echo "<script>alert('$msg');</script>";
    unset($_SESSION['invalid_new_email']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_email'])) {

    $user_id = $_SESSION['user_id'];
    $oldEmail = $_SESSION['email'];
    $newEmail = $_POST['new_email'];

    if ($oldEmail == $newEmail) {
        $_SESSION['invalid_new_email'] = "New email matches old one.";
        header("Location: change_email.php");
        exit();
    }

    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['invalid_new_email'] = "That doesn't look like an email address.";
        header("Location: change_email.php");
        exit();
    }

    include "../includes/db.php";

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?;");
    $stmt->bind_param("s", $newEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['invalid_new_email'] = "Email already registered.";
        header("Location: change_email.php");
        exit();
    } else {
        $stmt2 = $conn->prepare("UPDATE users
                                SET email=?
                                WHERE id=?;");
        $stmt2->bind_param("si", $newEmail, $user_id);
        $stmt2->execute();
        $_SESSION['email'] = $newEmail;
        $_SESSION['email_changed'] = "Email has changed successfully.";
        header("Location: ../profile.php");
        $stmt->close();
        $stmt2->close();
        $conn->close();
    }
}
?>

<html>
    <head>
        <title>Change email</title>
        <style>
            /* To be continued */
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Change your email</h2>
            <form action="change_email.php" method="POST">
                <div class="new-email">
                    <label for="email">New email: </label>
                    <input type="email" name="new_email" id="email" onkeyup="valEmail(this.value);" required>
                    <span id="asterisk"> * </span>
                    <span id="error-msg"></span>
                </div><br>
                <button type="submit" name="submit" id="btn">Update email</button>
            </form>
        </div>

        <script>

            async function valEmail(email) {
                if (email.length == 0) {
                    document.getElementById('error-msg').innerHTML = "";
                    return;
                }

                try {
                     
                    const response = await fetch('..val/validation.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoder'
                        },
                        body: new URLSearchParams({
                            email: email
                        })
                    });

                    const result = await response.text();
                    document.getElementById('error-msg').innerHTML = result;

                } catch (err) {
                    console.error('Request failed ', err.message);
                }
            }
        </script>
    </body>
</html>