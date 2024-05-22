<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: index.php");
    exit;
}

//require_once '/var/www/node118.webte.fei.stuba.sk/115370_velic_z1/.config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$link = mysqli_connect("localhost", "xvladare", "", $dbname);

// Check connection
if ($link->connect_errno) {
    echo "Failed to connect to MySQL: " . $link->connect_error;
    exit();
}

// Retrieve form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data
    $name = isset($_POST['question']) ? $_POST['question'] : null;
    $surname = isset($_POST['answer']) ? $_POST['answer'] : null;
    $contribution_sk = isset($_POST['answer']) ? $_POST['answer'] : array();
} else {
    echo json_encode(array('POST' => false));
    header("Location: addForm.php");
    exit();
}

if ($death == '') {
    $death = null;
}


$query = "SELECT * FROM countries WHERE name = ?";
$stmt = $link->prepare($query);
$stmt->bind_param("s", $country);
if ($stmt->execute()) {
    echo json_encode(array("Execute successful"));
} else {
    echo "Error: " . $stmt->error;
}
$result = $stmt->get_result();

// If country doesnt exist, insert it into database
if ($result->num_rows == 0) {
    $query = "INSERT INTO countries (name) VALUES (?)";
    $stmt = $link->prepare($query);
    $stmt->bind_param("s", $country);
    if ($stmt->execute()) {
        echo json_encode(array("Execute succesful"));
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Check if prize_details already exist
for ($i = 0; $i < max(count($language_en), count($language_sk), count($genre_sk), count($genre_en)); $i++) {
    $query = "SELECT id
            FROM prize_details pd
            WHERE pd.language_sk = ? AND pd.language_en = ? AND pd.genre_sk = ? AND pd.genre_en = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("ssss", $language_sk[$i], $language_en[$i], $genre_en[$i], $genre_sk[$i]);
    if ($stmt->execute()) {
        echo json_encode(array("Execute succesful"));
    } else {
        echo "Error: " . $stmt->error;
    }
    $result = $stmt->get_result();

    // If prize_detail doesnt exist, insert it into database
    if ($result->num_rows == 0) {
        $query = "INSERT INTO prize_details (language_sk,language_en,genre_sk,genre_en) VALUES (?,?,?,?)";
        $stmt = $link->prepare($query);
        $stmt->bind_param("ssss", $language_sk[$i], $language_en[$i], $genre_en[$i], $genre_sk[$i]);
        if ($stmt->execute()) {
            echo json_encode(array("Execute succesful"));
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    $stmt->close();
}

$j = 0;
for ($i = 0; $i < count($categories); $i++) {
    // Get the ID of the inserted/updated prize_detail
    if ($categories[$i] == "5") {
        $query = "INSERT INTO prizes(year,contribution_sk,contribution_en,person_id,category_id,prize_detail_id) VALUES (?,?,?,?,?,?)";
        $stmt = $link->prepare($query);
        $stmt->bind_param("sssiii", $years[$i], $contribution_sk[$i], $contribution_en[$i], $id, $categories[$i], $prize_detail_ids[$j]);
        if ($stmt->execute()) {
            echo json_encode(array("Execute succesful"));
        } else {
            echo json_encode("Error: " . $stmt->error);
        }
        $j++;
    } else {
        $prizeDetailId = null;
        $query = "INSERT INTO prizes(year,contribution_sk,contribution_en,person_id,category_id,prize_detail_id) VALUES (?,?,?,?,?,?)";
        $stmt = $link->prepare($query);
        $stmt->bind_param("sssiii", $years[$i], $contribution_sk[$i], $contribution_en[$i], $id, $categories[$i], $prize_detail_Id);
        if ($stmt->execute()) {
            echo json_encode(array("Execute succesful"));
        } else {
            echo json_encode("Error: " . $stmt->error);
        }
    }

    $stmt->close();
}

mysqli_close($link);
header("Location: receiver.php?id=$id");
exit();