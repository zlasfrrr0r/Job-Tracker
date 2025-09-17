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
            function valEmail(email) {
                const errorMsg = document.getElementById('error-msg');
                if (email.length == 0) {
                    errorMsg.innerHTML = "";
                    return;
                } else {
                    xhttp = new XMLHttpRequest();
                    xhttp.open("POST", "../val/validation.php", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            errorMsg.innerHTML = this.responseText;
                        }
                    }
                    xhttp.send("email=" + encodeURIComponent(email));
                }
            }
        </script>
    </body>
</html>