<?php

session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? 1 : 0;

    // Validate email and password before processing

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['invalid_email'] = "That doesn't look like an email address.";
        header("Location: index.php");
        exit();
    }

    if (strlen($password) < 8) {
        $_SESSION['invalid_password'] = "Password must be at least 8 chars long.";
        header("Location: index.php");
        exit();
    }

    check_login($email, $password, $remember);
}

if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $remember = isset($_POST['remember']) ? 1 : 0;

    // Validate username, email, password and confirm password before processing

    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
        $_SESSION['invalid_username'] = "Username can only contain letters, numbers, underscores and hyphens.";
        header("Location: signup.php");
        exit();
    }
    
    if (strlen($username) < 6) {
        $_SESSION['invalid_username'] = "Username must be at least 6 characters long.";
        header("Location: signup.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['invalid_email'] = "That doesn't look like an email address.";
        header("Location: signup.php");
        exit();
    }

    if (strlen($password) < 8) {
        $_SESSION['invalid_password'] = "Password must be at least 8 chars long.";
        header("Location: signup.php");
        exit();
    }

    if ($password !== $confirm) {
        $_SESSION['invalid_confirm'] = "Passwords do not match.";
        header("Location: signup.php");
        exit();
    }

    check_signup($username, $email, $password, $remember);
}


function check_login($email, $pass_word, $remember) {
    
    include "./includes/db.php";
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?;");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    # If E-mail is not in database
    if ($result->num_rows < 1) { 
        $_SESSION['invalid_email'] = "This email address is not registered.";
        header('Location: index.php');
        exit();
    } else {
        $row = $result->fetch_assoc();
        $hash = $row["password"]; 
        # If E-mail is correct but password is incorrect
        if (!password_verify($pass_word, $hash)) {
            $_SESSION['invalid_password'] = "Password incorrect. Please check your password and type again.";
            header('Location: index.php');
            exit();          
        } else {
            # Email and password are correct. Login and redirect to main page.
            # But first, if user checked Remember Me, set cookies
            if ($remember == 1) {
                setcookie("username", $row["username"], time() + (86400 * 30), "/");
                setcookie("email", $row["email"], time() + (86400 * 30), "/");
            }
            session_regenerate_id(true);
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['user_id'] = $row["id"];
            $_SESSION['username'] = $row["username"] ?? $user_name; 
            $_SESSION['email'] = $email;
            header('Location: dashboard.php');
            exit();
        }
    }
}

function check_signup($user_name, $email, $pass_word, $remember) {
    
    include "includes/db.php";
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?;");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $usernameTaken = $conn->prepare("SELECT username FROM users WHERE username = ?;");
    $usernameTaken->bind_param("s", $user_name);
    $usernameTaken->execute();
    $takenResult = $usernameTaken->get_result();
    if ($takenResult->num_rows > 0) {
        $_SESSION['invalid_username'] = "Username already taken.";
        header("Location: signup.php");
        exit();
    }
    if ($result->num_rows > 0) {
        $_SESSION['invalid_email'] = "E-mail already registered. Try logging in.";
        header("Location: signup.php");
        exit();
    }
    $hash = password_hash($pass_word, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?);");
    $stmt->bind_param("sss", $user_name, $email, $hash);
    $stmt->execute();
    $user_id = $conn->insert_id;
    if ($remember == 1) {
        setcookie("username", $user_name, time() + (86400 * 30), "/");
        setcookie("email", $email, time() + (86400 * 30), "/");
    }
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user_id;
    $_SESSION['loggedin'] = TRUE;
    $_SESSION['username'] = $user_name; 
    $_SESSION['email'] = $email;
    header('Location: dashboard.php');
    exit();
    $stmt->close();
    $conn->close();
}