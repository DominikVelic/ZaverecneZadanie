<?php

require_once '../.config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['code'])) {
        $question_code = $_POST['code'];

        $success_message = '';
        $error_message = '';

        // Get question_id
        $sql = "SELECT id FROM questions WHERE code = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $question_code);

            if ($stmt->execute()) {
                $stmt->bind_result($question_id);
                if ($stmt->fetch()) {
                    $success_message .= "ID found successfully. Question ID: " . $question_id . ". ";
                } else {
                    $error_message .= "No matching ID found for the provided question code. ";
                }
            } else {
                $error_message .= "Error finding ID. ";
            }

            $stmt->close();
        } else {
            $error_message .= "Error preparing the select statement. ";
        }

        // Delete the answers with the question_id
        $sql = "DELETE FROM answers WHERE question_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $question_id); // Assuming question_id is an integer

            if ($stmt->execute()) {
                $success_message .= "Answer(s) deleted successfully. ";
            } else {
                $error_message .= "Error deleting answer(s). ";
            }

            $stmt->close();
        } else {
            $error_message .= "Error preparing the delete statement. ";
        }

        // Finally, delete the question itself
        $sql = "DELETE FROM questions WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $question_id); // Assuming question_id is an integer

            if ($stmt->execute()) {
                $success_message .= "Question(s) deleted successfully. ";
            } else {
                $error_message .= "Error deleting question(s). ";
            }

            $stmt->close();
        } else {
            $error_message .= "Error preparing the delete statement. ";
        }

        // Output success or error message
        if (!empty($success_message)) {
            echo $success_message;
        }
        if (!empty($error_message)) {
            echo $error_message;
        }
    }
}
