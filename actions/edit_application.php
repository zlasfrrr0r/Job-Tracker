<?php
session_start();

include "../includes/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = intval($_SESSION['user_id']);
    $id = intval($_POST['id']);
    $company = trim($_POST['company']);
    $role_title = trim($_POST['role_title']);
    $salary_rate = trim($_POST['salary_rate']);
    $advert_link = trim($_POST['advert_link']);
    $response = $_POST['response'];
    $interview_stage = $_POST['interview_stage'];
    $interview_date = !empty($_POST['interview_date']) ? $_POST['interview_date'] : '';
    $offer = $_POST['offer'];

    $stmt = $conn->prepare("UPDATE applications
                            SET company=?, role_title=?, salary_rate=?, advert_link=?, response=?, interview_stage=?, interview_date=?, offer=?
                            WHERE id=? AND user_id=?;");
    $stmt->bind_param("ssssssssii", $company, $role_title, $salary_rate, $advert_link, $response, $interview_stage, $interview_date, $offer, $id, $user_id);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php?msg=Application+updated+successfully");
    } else {
        echo "Error:" . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}