<?php
session_start();

require_once '../.config.php';

$query = "SELECT q.question, q.subject, 
                 a.answer, a.count 
          FROM answers a 
          JOIN questions q ON q.id = a.question_id";

if ($result = mysqli_query($conn, $query)) {
  $sqlData = array();
  while ($db_field = mysqli_fetch_assoc($result)) {
    $sqlData[] = $db_field;
  }
}
$database = json_encode($sqlData);

$resultJSON = $conn->query($query);

if ($resultJSON->num_rows > 0) {
  $data = array();
  while($row = $resultJSON->fetch_assoc()) {
      $data[] = $row;
  }
  $json_data = json_encode($data, JSON_PRETTY_PRINT);

  header('Content-Type: application/json');
  header('Content-Disposition: attachment; filename="vysledky.json"');

  echo $json_data;
}

?>