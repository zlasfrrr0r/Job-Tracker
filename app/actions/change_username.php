<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['invalid_new_username'])) {
    $msg = $_SESSION['invalid_new_username'];
    $msg = addslashes($msg);
    echo "<script>alert('$msg');</script>";
    unset($_SESSION['invalid_new_username']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_username'])) {

    $user_id = $_SESSION['user_id'];
    $currentUsername = $_SESSION['username'];
    $newUsername = $_POST['new_username'];

    if ($currentUsername == $newUsername) {
        $_SESSION['invalid_new_username'] = "New username matches old one.";
        header("Location: change_username.php");
        exit();
    }

    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $newUsername)) {
        $_SESSION['invalid_new_username'] = "Username can only contain letters, numbers, underscores and hyphens.";
        header("Location: change_username.php");
        exit();
    }

    if (strlen($newUsername) < 6) {
        $_SESSION['invalid_new_username'] = "Username must be at least 6 characters long.";
        header("Location: change_username.php");
        exit();
    }

    include "../includes/db.php";
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?;");
    $stmt->bind_param("s", $newUsername);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['invalid_new_username'] = "Username already taken.";
        header("Location: change_username.php");
        exit();
    } else {
        $stmt2 = $conn->prepare("UPDATE users
                                SET username=?
                                WHERE id=?;");
        $stmt2->bind_param("si", $newUsername, $user_id);
        $stmt2->execute();
        $_SESSION['username'] = $newUsername;
        $_SESSION['username_changed'] = "Username successfully changed.";
        header("Location: ../profile.php");
        exit();
        $stmt->close();
        $stmt2->close();
        $conn->close();
    }
}

?>

<html>
    <head>
        <title>Change username</title>
        <style>
            /* To be continued */
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Change your username</h2>
            <form action="change_username.php" method="POST">
                <div class="new-username">
                    <label for="username">New username: </label>
                    <input type="text" name="new_username" id="username" onkeyup="valUsername(this.value);" required>
                    <span class="asterisk"> *</span>
                    <span class="error-msg" id="error-msg"></span>
                </div><br>
                <button type="submit" name="submit" id="btn">Update username</button>
            </form>
        </div>

        <script>

            async function valUsername(username) {
                
                if (username.length == 0) {
                    document.getElementById('error-msg').innerHTML = "";
                    return;
                }

                try {
                    
                    const response = await fetch('..val/validation.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                        body: new URLSearchParams({
                            username: username
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