<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../.config.php';

// Initialize an array to store messages
$response = [];

// Retrieve form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data
    $question = isset($_POST['question']) ? $_POST['question'] : null;
    $subject = isset($_POST['subject']) ? $_POST['subject'] : null;
    $answer = isset($_POST['answer']) ? $_POST['answer'] : array();
} else {
    $response['POST'] = false;
    header("Location: addForm.php");
    exit();
}

$characters = '123456789';
$charactersLength = strlen($characters);

for (;;) {
    $randomCode = '';
    for ($i = 0; $i < 5; $i++) {
        $randomCode .= $characters[rand(0, $charactersLength - 1)];
    }

    $query = "SELECT * FROM questions WHERE code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $randomCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        break;
    }
}

$query = "INSERT INTO questions (question,subject,code) VALUES (?,?,?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $question, $subject, $randomCode);
if ($stmt->execute()) {
    $response['message'] = "Execute successful";
} else {
    $response['error'] = "Error: " . $stmt->error;
}
$stmt->close();

$query = "SELECT id FROM questions WHERE code = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $randomCode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the ID from the result set
    $row = $result->fetch_assoc();
    $question_id = $row['id'];

    // Now use the $question_id in the INSERT query
    for ($i = 0; $i < count($answer); $i++) {
        $count = 0;  // Assuming $count should be initialized to 0
        $query = "INSERT INTO answers (answer, count, question_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sii", $answer[$i], $count, $question_id);

        if ($stmt->execute()) {
            $response['message'] = "Execute successful";
        } else {
            $response['error'] = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
} else {
    $response['error'] = "Error: No question found with the given code";
}

$result->free();
$conn->close();

// Avoid any output before this line to prevent header errors
header("Location: /index.php");
exit();
