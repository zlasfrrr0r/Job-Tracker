<?php
session_start();

include "../includes/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $company = trim($_POST['company']);
    $role_title = trim($_POST['role_title']);
    $salary_rate = trim($_POST['salary_rate']);
    $advert_link = trim($_POST['advert_link']);
    $response = $_POST['response'];
    $interview_stage = $_POST['interview_stage'];
    $interview_date = !empty($_POST['interview_date']) ? $_POST['interview_date'] : NULL;
    $offer = $_POST['offer'];

    $stmt = $conn->prepare("INSERT INTO applications (user_id, company, role_title, salary_rate, advert_link, response, interview_stage, interview_date, offer)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);");
    $stmt->bind_param("issssssss", $user_id, $company, $role_title, $salary_rate, $advert_link, $response, $interview_stage, $interview_date, $offer);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php?msg=Application+added+successfully");
    } else {
        echo "Error:" . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}