<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../.config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check connection
if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: " . $link->connect_error;
    exit();
}

// Retrieve form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data
    $question = isset($_POST['question']) ? $_POST['question'] : null;
    $subject = isset($_POST['subject']) ? $_POST['subject'] : null;
    $answer = isset($_POST['answer']) ? $_POST['answer'] : array();
} else {
    echo json_encode(array('POST' => false));
    header("Location: addForm.php");
    exit();
}

if ($death == '') {
    $death = null;
}


$characters = '0123456789';
$charactersLength = strlen($characters);

for(;;){
    $randomCode = '';

    for ($i = 0; $i < 5; $i++) {
        $randomCode .= $characters[rand(0, $charactersLength - 1)];
    }

    $query = "SELECT * FROM questions WHERE code = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("i", $randomCode);
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        break;
    }

}

    $query = "INSERT INTO questions (question,subject,closed,code) VALUES (?,?,?,?)";
    $stmt = $link->prepare($query);
    $stmt->bind_param("ssii", $question, $subject, 0, $random);
    if ($stmt->execute()) {
        echo json_encode(array("Execute succesful"));
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();

    $query = "SELECT id FROM questions WHERE code = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("i", $randomCode);
    $result = $stmt->get_result();

$j = 0;

for ($i = 0; $i < count($categories); $i++) {
        $prizeDetailId = null;
        $query = "INSERT INTO answers (answer,count,question_id) VALUES (?,?,?)";
        $stmt = $link->prepare($query);
        $stmt->bind_param("sii", $answer[$i], 0, $result);
        if ($stmt->execute()) {
            echo json_encode(array("Execute succesful"));
        } else {
            echo json_encode("Error: " . $stmt->error);
        }
    $stmt->close();
}

mysqli_close($link);
header("Location: receiver.php?id=$id");
exit();