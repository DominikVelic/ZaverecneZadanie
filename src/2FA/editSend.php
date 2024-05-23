<?php

session_start();


if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../.config.php';

// Retrieve form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $question = isset($_POST['question']) ? $_POST['question'] : null;
    $subject = isset($_POST['subject']) ? $_POST['subject'] : null;
    $closed = isset($_POST['closed']) ? $_POST['closed'] : null;
    $answer = isset($_POST['answer']) ? $_POST['answer'] : array();
} else {

    echo json_encode(array('POST' => false));
    header("Location: addForm.php");
    exit();
}
    
    $query = "UPDATE questions SET question = ?,subject = ?,closed = ? WHERE $id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssii", $question, $subject, $closed, $id);
    if ($stmt->execute()) {
        echo json_encode(array("Execute succesful"));
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();


for ($i = 0; $i < count($answer); $i++) {
        $query = "UPDATE answers SET answer=? where question_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $answer[$i], $id);
        if ($stmt->execute()) {
            echo json_encode(array("Execute succesful"));
        } else {
            echo json_encode("Error: " . $stmt->error);
        }
    $stmt->close();
}

mysqli_close($conn);
header("Location: receiver.php?id=$id");
exit();
