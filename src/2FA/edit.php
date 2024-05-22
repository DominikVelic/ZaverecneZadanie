<?php

//require_once '/var/www/node118.webte.fei.stuba.sk/115370_velic_z1/.config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);


// session_start();

// if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
//     header("Location: index.php");
//     exit;
// }


// // Establish connection to the database
// $link = mysqli_connect($hostname, $username, $password, $dbname);

// // Check if the connection was successful
// if ($link->connect_errno) {
//     echo "Failed to connect to MySQL: " . $link->connect_error;
//     exit();
// }

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
        deleteButton.onclick = function () {
            this.parentElement.remove();
            prizeCounter--;
        };
        clone.appendChild(deleteButton);

        // Append the cloned div to a parent container
        container.appendChild(clone);
    }

</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receiver info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .clean-a a {
            color: rgb(0, 0, 0);
            text-decoration: none;
        }

        .round-background {
            border-radius: 15px;
            /* Adjust the value as needed for the desired roundness */
            background-color: rgb(240, 240, 240, 0.5);
            /* Adjust the background color as needed */
            padding: 1rem;
        }
    </style>

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-12 clean-a pb-5">
                <a href="index.php" class="me-5">Home</a>
                <a href="restricted.php">Restricted site</a>
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
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="surname"><strong>Predmet:</strong></label>
                                    <input type="text" class="form-control" id="surname" name="surname">
                                </div>

                                <div id="prize_container">
                                    <div id="prize0" class="card p-2 mb-2 prize-section">
                                        <div class="card-text">
                                            <div class="form-group mb-3">
                                                <label for="year"><strong>Odpoveď:</strong></label>
                                                <input type="text" class="form-control" id="answer" name="answers[]" required>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <br>
                            </div>

                            <button type="button" id="addFields" class="btn btn-success" onclick="addAnswer()">Pridať odpoveď</button>
                            <button type="submit" class="btn btn-primary">Odoslať</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>