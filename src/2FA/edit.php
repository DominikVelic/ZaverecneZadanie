<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: /questions/questionList.php");
    exit;
}

require '../header.php';
require_once '../.config.php';


// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {

    // Sanitize input to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Prepare the query with a placeholder
    $query = "SELECT q.id as question_id, q.question, q.subject, q.code, q.closed, 
                     a.id as answer_id, a.answer, a.appearance, a.count 
              FROM questions q 
              LEFT JOIN answers a ON q.id = a.question_id 
              WHERE q.code = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $query);

    // Bind the 'id' parameter to the placeholder
    mysqli_stmt_bind_param($stmt, "i", $id);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        // Check if any rows were returned
        if (mysqli_num_rows($result) > 0) {
            // Display recipient information
            $row = mysqli_fetch_assoc($result);
        } else {
            echo "No results found.";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo "No ID provided in the URL.";
}


?>

<body>

    <div class="container">
        <div class="row">
            <div class="card col-12 mb-5 bg-dark text-white">
                <div class="card-body m-5">
                    <form action="/2FA/editSend.php" method="post">
                        <div class="card-text">
                            <div id="card-inside">
                                <div class="form-group mb-3">
                                    <input type="hidden" class="form-control" id="question_id" name="question_id" value="<?php echo $row['question_id']; ?>">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="question"><strong><?php echo $lang['question'] ?></strong></label>
                                    <input type="text" class="form-control" id="question" name="question" value="<?php echo $row['question']; ?>">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="subject"><strong><?php echo $lang['subject'] ?></strong></label>
                                    <input type="text" class="form-control" id="subject" name="subject" value="<?php echo $row['subject']; ?>">
                                </div>

                                <label for="year"><strong><?php echo $lang['answers'] ?></strong></label>

                                <div id="prizes_container">
                                    <?php
                                    do { ?>

                                        <div id="prize0" class="card p-2 mb-2 prize-section">
                                            <div class="card-text">
                                                <div class="form-group mb-3">
                                                    <input type="text" class="form-control" id="answer" name="answer[]" value="<?php echo $row['answer']; ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                    <?php
                                    } while ($row = mysqli_fetch_assoc($result));

                                    ?>

                                </div>
                                <br>
                            </div>
                            <button type="submit" class="btn btn-primary"><?php echo $lang['send'] ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require '../footer.php'; ?>

</body>

</html>