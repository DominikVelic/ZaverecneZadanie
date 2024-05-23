<?php
session_start();
require_once '../.config.php';
header('Content-Type: application/json');

$response = [];

if (isset($_POST['answerId']) && $_POST['answerId'] !== '') {
    $answer_id = mysqli_real_escape_string($conn, $_POST['answerId']);
    
    // Check if the user has already voted on this question
    if (!isset($_SESSION['voted_questions'])) {
        $_SESSION['voted_questions'] = [];
    }
    
    if (in_array($answer_id, $_SESSION['voted_questions'])) {
        $response['error'] = "You have already voted on this question.";
    } else {
        // Update the vote count for the answer
        $query = "UPDATE answers SET count = count + 1 WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $answer_id);
            if (mysqli_stmt_execute($stmt)) {
                // Get the new vote count
                $query = "SELECT SUM(count) as new_vote_count FROM answers WHERE question_id = (SELECT question_id FROM answers WHERE id = ?)";
                $stmt = mysqli_prepare($conn, $query);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $answer_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $row = mysqli_fetch_assoc($result);
                    $response['success'] = true;
                    $response['new_vote_count'] = $row['new_vote_count'];
                    
                    // Store the voted question in session
                    $_SESSION['voted_questions'][] = $answer_id;
                } else {
                    $response['error'] = "Failed to retrieve new vote count: " . mysqli_error($conn);
                }
            } else {
                $response['error'] = "Failed to update vote count: " . mysqli_error($conn);
            }
        } else {
            $response['error'] = "Query preparation failed: " . mysqli_error($conn);
        }
    }
} else {
    $response['error'] = "No Answer ID provided";
}

echo json_encode($response);
?>