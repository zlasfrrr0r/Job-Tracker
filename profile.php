<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['username_changed'])) {
    $msg = $_SESSION['username_changed'];
    $msg = addslashes($msg);
    echo "<script>alert('$msg');</script>";
    unset($_SESSION['username_changed']);
}

if (isset($_SESSION['email_changed'])) {
    $msg = $_SESSION['email_changed'];
    $msg = addslashes($msg);
    echo "<script>alert('$msg');</script>";
    unset($_SESSION['email_changed']);
}

if (isset($_SESSION['password_changed'])) {
    $msg = $_SESSION['password_changed'];
    $msg = addslashes($msg);
    echo "<script>alert('$msg');</script>";
    unset($_SESSION['password_changed']);
}

chmod('uploads', 755);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $file = $_FILES['avatar'];

    $allowed = ['image/png', 'image/jpeg'];
    if (in_array($file['type'], $allowed)) {
        $fileName = uniqid() . "-" . basename($file['name']);
        $path = 'uploads/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $path)) {
            $_SESSION['avatar'] = $fileName;
        }
    }
}

$username = $_SESSION['username'];
$email = $_SESSION['email'];
?>

<html>
    <head>
        <title>User Profile</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f4f4f4;
                padding: 20px;
            }

            .profile-container {
                max-width: 500px;
                margin: 0 auto;
                background: white;
                border-radius: 10px;
                padding: 20px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                text-align: center;
            }

            .avatar-wrapper {
                position: relative;
                display: inline-block;
            }

            .avatar-wrapper img {
                width: 120px;
                height: 120px;
                border-radius: 50%;
                object-fit: cover;
                cursor: pointer;
            }

            .avatar-wrapper input {
                display: none;
            }

            .user-details {
                margin-top: 20px;
                text-align: left;
            }

            .user-details p {
                font-size: 16px;
                margin: 10px 0;
            }

            .edit-links a {
                display: inline-block;
                margin-top: 10px;
                color: blue;
                text-decoration: none;
                font-size: 14px;
            }

            .edit-links a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <?php include "includes/header.php"; ?>

        <main>

            <div class="profile-container">
                <form action="profile.php" method="POST" enctype="multipart/form-data">
                    <div class="avatar-wrapper">
                        <img src="<?= isset($_SESSION['avatar']) ? 'uploads/' . $_SESSION['avatar'] : 'uploads/default-avatar.jpeg' ?>" alt="Profile Picture" id="avatarPreview"
                            onclick="document.getElementById('avatarInput').click();">
                        <input type="file" id="avatarInput" name="avatar" accept="image/png, image/jpeg">
                    </div>
                </form>
                <h2><?= htmlspecialchars($username) ?></h2>

                <div class="user-info">
                    <label for="username"><strong>Username: </strong></label><input type="text" name="username" id="username" value="<?= htmlspecialchars($username) ?>" readonly>
                    <div class="edit-links"><a href="actions/change_username.php">Change username</a></div><br><br>

                    <label for="email"><strong>Email: </strong></label><input type="text" name="email" id="email" value="<?= htmlspecialchars($email) ?>" readonly>
                    <div class="edit-links"><a href="actions/change_email.php">Change email</a></div><br><br>

                    <label for="password"><strong>Password: </strong></label><input type="text" name="password" id="password" value="********" readonly>
                    <div class="edit-links"><a href="actions/change_password.php">Change password</a></div>
                </div>
            </div>
        </main>

        <?php include "includes/footer.php"; ?>

        <script>
            const avatarInput = document.getElementById('avatarInput');
            const avatarPreview = document.getElementById('avatarPreview');

            avatarInput.addEventListener('change', function() {
                this.form.submit();
                const file = avatarInput.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        avatarPreview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            })
        </script>
    </body>
</html>