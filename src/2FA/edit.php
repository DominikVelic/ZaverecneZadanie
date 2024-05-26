<?php


require '../header.php';
require_once '../.config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: index.php");
    exit;
}

// Check if the connection was successful
if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: " . $conn->connect_error;
    exit();
}

// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    // Sanitize input to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Fetch recipient information along with all prize details based on the provided ID
    $query = "SELECT q.id as question_id, q.question, q.subject, q.closed, q.code, q.date_created, 
                     a.id as answer_id, a.answer, a.appearance, a.count 
              FROM questions q 
              JOIN answers a ON q.id = a.question_id 
              WHERE q.code = ?";

    $result = mysqli_query($conn, $query);

    if ($result) {
        // Check if any rows were returned
        if (mysqli_num_rows($result) > 0) {
            // Display recipient information
            $row = mysqli_fetch_assoc($result);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "No ID provided in the URL.";
}

?>

<script>
    var prizeCounter = 0;

    function addAnswer() {
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
            <div class="col-12 clean-a pb-5">
                <a href="index.php" class="me-5">Home</a>
            </div>
        </div>

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
                                    <input type="text" class="form-control" id="subject" name="subject"  value="<?php echo $row['subject']; ?>">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="surname"><strong>Otvorená:</strong></label>
                                    <input type="text" class="form-control" id="closed" name="closed"  value="<?php echo $row['closed']; ?>">
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