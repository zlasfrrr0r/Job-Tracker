<?php
session_start();

include "../includes/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $id = intval($_POST['id']);

    $stmt = $conn->prepare("DELETE FROM applications
                    WHERE id=? AND user_id=?;");

    $stmt->bind_param("ii", $id, $user_id);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php?msg=Application+deleted+successfully");
    } else {
        echo "Error:" . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}