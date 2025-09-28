<?php

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $verdict = validate_username($username);
    echo $verdict;
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $verdict = validate_email($email);
    echo $verdict;
}

if (isset($_POST['password'])) {
    $password = $_POST['password'];
    $verdict = validate_password($password);
    echo $verdict;
}

function validate_username($username) {
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
        return "Username can only contain letters, numbers, underscores and hyphens.";
    } elseif(strlen($username) < 6) {
        return "Username must be at least 6 characters long.";
    } else {
        return "";
    }
}

function validate_email($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "That doesn't look like an email address";
    }
}

function validate_password($password) {
    if (strlen($password) < 8 && strlen($password > 0)) {
        return "Password must be at least 8 chars long.";
    } else {
        return "";
    }
}