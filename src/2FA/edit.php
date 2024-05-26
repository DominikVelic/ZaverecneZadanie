<?php


require '../header.php';
require_once '../.config.php';


// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    // Sanitize input to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Prepare the query with a placeholder
    $query = "SELECT q.id as question_id, q.question, q.subject, q.closed, q.code, q.date_created, 
                     a.id as answer_id, a.answer, a.appearance, a.count 
              FROM questions q 
              JOIN answers a ON q.id = a.question_id 
              WHERE q.code = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $query);

    // Bind the 'id' parameter to the placeholder
    mysqli_stmt_bind_param($stmt, "s", $id);

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

<script>
    var prizeCounter = 0;

    function addNewAnswer() {
        var container = document.getElementById("prize_container");
        var clone = container.querySelector(".prize-section").cloneNode(true);

        // Clear input values in the cloned section
        var inputs = clone.querySelectorAll("input, select");
        inputs.forEach((input) => (input.value = ""));

        // Generate a unique ID for the cloned div
        var newId = "prize" + prizeCounter;
        clone.id = newId;

        // Increment the counter for the next clone
        prizeCounter++;

        // Append the delete button to the cloned div
        var deleteButton = document.createElement("button");
        deleteButton.textContent = "Vymazať odpoveď";
        deleteButton.setAttribute("type", "button");
        deleteButton.classList.add("btn", "btn-outline-danger");
        deleteButton.onclick = function() {
            this.parentElement.remove();
            prizeCounter--;
        };
        clone.appendChild(deleteButton);

        // Append the cloned div to a parent container
        container.appendChild(clone);
    }
</script>

<body>

    <div class="container">
        <div class="row">
            <div class="card col-12 mb-5 bg-dark text-white">
                <div class="card-body m-5">
                    <form action="add.php" method="post">
                        <div class="card-text">
                            <div id="card-inside">
                                <div class="form-group mb-3">
                                    <label for="name"><strong>Otázka:</strong></label>
                                    <input type="text" class="form-control" id="question" name="question" value="<?php echo $row['question']; ?>">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="surname"><strong>Predmet:</strong></label>
                                    <input type="text" class="form-control" id="subject" name="subject" value="<?php echo $row['subject']; ?>">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="surname"><strong>Otvorená:</strong></label>
                                    <input type="text" class="form-control" id="closed" name="closed" value="<?php echo $row['closed']; ?>">
                                </div>

                                <div id="prizes_container">
                                    <?php
                                    do { ?>

                                        <div id="prize0" class="card p-2 mb-2 prize-section">
                                            <div class="card-text">
                                                <div class="form-group mb-3">
                                                    <label for="year"><strong>Odpoveď:</strong></label>
                                                    <input type="text" class="form-control" id="answer" name="answers[]" value="<?php echo $row['answer[]']; ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                    <?php
                                    } while ($row = mysqli_fetch_assoc($result));

                                    ?>

                                </div>
                                <br>
                            </div>
                            <button type="submit" class="btn btn-primary">Odoslať</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require '../footer.php'; ?>

</body>

</html>