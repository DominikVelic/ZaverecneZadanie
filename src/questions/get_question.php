<?php
session_start();
require_once '../.config.php';

$response = [];

if (isset($_GET['code']) && $_GET['code'] !== '') {
    // Sanitize input to prevent SQL injection
    $code = mysqli_real_escape_string($conn, $_GET['code']);

    if (!isset($code) || !preg_match('/^[a-zA-Z0-9]{5}$/', $code)) {
        echo json_encode(array("error" => "Code is not a valid 5-digit alphanumeric number"));
        exit();
    }


    $query = "SELECT q.id as question_id, q.question, q.subject, q.closed, q.code, q.date_created, 
                     a.id as answer_id, a.answer, a.appearance, a.count 
              FROM questions q 
              JOIN answers a ON q.id = a.question_id 
              WHERE q.code = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $code);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $question = null;

            while ($row = mysqli_fetch_assoc($result)) {
                if (!$question) {
                    $question = [
                        'id' => $row['question_id'],
                        'question' => $row['question'],
                        'subject' => $row['subject'],
                        'closed' => $row['closed'],
                        'code' => $row['code'],
                        'date_created' => $row['date_created'],
                        'answers' => [] // Initialize the answers array inside the question
                    ];
                }
                $question['answers'][] = [
                    'id' => $row['answer_id'],
                    'answer' => $row['answer'],
                    'appearance' => $row['appearance'],
                    'count' => $row['count']
                ];
            }

            $response['question'] = $question;
        } else {
            $response['error'] = "No questions found for the provided code";
        }
    } else {
        $response['error'] = "Query preparation failed: " . mysqli_error($conn);
    }
} else {
    $response['error'] = "No Code provided";
}

echo json_encode($response);
