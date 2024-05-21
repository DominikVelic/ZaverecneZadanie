<?php

require_once '/var/www/node118.webte.fei.stuba.sk/115370_velic_z1/.config.php';

// Establish connection to the database
$link = mysqli_connect($hostname, $username, $password, $dbname);

// Check if the connection was successful
// Check connection
if ($link->connect_errno) {
    echo "Failed to connect to MySQL: " . $link->connect_error;
    exit();
}

// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    // Sanitize input to prevent SQL injection
    $id = mysqli_real_escape_string($link, $_GET['id']);

    // Fetch recipient information along with all prize details based on the provided ID
    $query = "SELECT CONCAT(r.name, ' ', r.surname) AS name, r.organization, pd.genre_sk, pd.genre_en, pd.language_sk, pd.language_en, r.sex, r.birth, r.death, p.year, p.contribution_en, p.contribution_sk, c.name AS country, cat.name AS category
              FROM prizes p
              LEFT JOIN receivers r ON p.person_id = r.id
              LEFT JOIN prize_details pd ON p.prize_detail_id = pd.id
              LEFT JOIN categories cat ON p.category_id = cat.id
              LEFT JOIN countries c ON r.country_id = c.id
              WHERE r.id = '$id'";
} else {
    echo "No ID provided in the URL.";
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receiver info</title>
    <link rel="stylesheet" href="../115370_velic_z1/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .clean-a a {
            color: rgb(0, 0, 0);
            text-decoration: none;
        }
    </style>

</head>

<body>

    <?php
    // Execute the query
    $result = mysqli_query($link, $query);

    // Check if the query was successful
    if ($result) {
        // Check if any rows were returned
        if (mysqli_num_rows($result) > 0) {
            // Display recipient information
            $row = mysqli_fetch_assoc($result);
            $name = isset($row['name']) && $row['name'] !== null ? $row['name'] : "";
            $organization = isset($row['organization']) && $row['organization'] !== null ? $row['organization'] : "";
            $nameToDisplay = $name . ' ' . $organization;
    ?>

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
                            <h1 class="card-title">
                                <?php
                                echo "<p>" . $nameToDisplay . "</p>";
                                ?>
                                </h5>
                                <div class="card-text">
                                    <?php

                                    if ($row['sex'] !== null && $row['sex'] !== '') {
                                        echo "<p><strong>Sex:</strong> " . $row['sex'] . "</p>";
                                    }

                                    echo "<p><strong>Birth:</strong> " . $row['birth'] . "</p>";

                                    if ($row["death"] !== null) {
                                        echo "<p><strong>Death:</strong> " . $row['death'] . "</p>";
                                    }
                                    echo "<p><strong>Country:</strong> " . $row['country'] . "</p>";

                                    do { ?>
                                        <div class="card mb-2 p-2">
                                            <?php
                                            echo "<p><strong>Category:</strong> " . $row['category'] . "</p>";
                                            echo "<p><strong>Year:</strong> " . $row['year'] . "</p>";

                                            if ($row['language_sk'] !== null && $row['language_sk'] != '') {
                                                echo "<p><strong>Lang_sk:</strong> " . $row['language_sk'] . "</p>";
                                            }
                                            if ($row['language_en'] !== null && $row['language_en'] != '') {
                                                echo "<p><strong>Lang_en:</strong> " . $row['language_en'] . "</p>";
                                            }
                                            if ($row['genre_sk'] !== null && $row['genre_sk'] != '') {
                                                echo "<p><strong>Genre_sk:</strong> " . $row['genre_sk'] . "</p>";
                                            }
                                            if ($row['genre_en'] !== null && $row['genre_en'] != '') {
                                                echo "<p><strong>Genre_en:</strong> " . $row['genre_en'] . "</p>";
                                            }

                                            echo "<p><strong>Contribution (Slovak):</strong> " . $row['contribution_sk'] . "</p>";
                                            echo "<p><strong>Contribution (English):</strong> " . $row['contribution_en'] . "</p>";  ?>
                                        </div>

                            <?php
                                    } while ($row = mysqli_fetch_assoc($result));
                                } else {
                                    echo "No recipient found with the provided ID.";
                                }
                            } else {
                                echo "Error: " . mysqli_error($link);
                            }
                            ?>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>

<?php
mysqli_close($link);

?>