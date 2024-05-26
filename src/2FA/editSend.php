<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: /index.php");
    exit;
}

require_once '../.config.php';

// Retrieve form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data
    $id = isset($_POST['question_id']) ? $_POST['question_id'] : null;
    $question = isset($_POST['question']) ? $_POST['question'] : null;
    $subject = isset($_POST['subject']) ? $_POST['subject'] : null;
    $answer = isset($_POST['answer']) ? $_POST['answer'] : array();
    $closed = isset($_POST['closed']) ? $_POST['closed'] : 0; // Přidána kontrola proměnné $closed
} else {
    header("Location: addForm.php");
    exit();
}

// První SQL dotaz na aktualizaci otázky
$query = "UPDATE questions SET question = ?, subject = ?, closed = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssii", $question, $subject, $closed, $id);
if (!$stmt->execute()) {
    echo "Error: " . $stmt->error;
    $stmt->close();
    mysqli_close($conn);
    exit();
}
$stmt->close();

// Druhý SQL dotaz na získání ID odpovědí
$query = "SELECT id FROM answers WHERE question_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    if ($result->num_rows > 0) {
        $ids = array();
        while ($row = $result->fetch_assoc()) {
            $ids[] = $row['id'];
        }
    } else {
        echo "No results found.";
        $stmt->close();
        mysqli_close($conn);
        exit();
    }
} else {
    echo "Error: " . $conn->error;
    $stmt->close();
    mysqli_close($conn);
    exit();
}
$stmt->close();

// Třetí SQL dotaz na aktualizaci odpovědí
for ($i = 0; $i < count($ids); $i++) {
    $query = "UPDATE answers SET answer = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $answer[$i], $ids[$i]);
    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
        $stmt->close();
        mysqli_close($conn);
        exit();
    }
    $stmt->close();
}

mysqli_close($conn);

// Přesměrování až po dokončení všech výstupů
header("Location: /index.php");
exit();
?>