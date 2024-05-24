<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once '../.config.php'; 

        $query = "SELECT * FROM questions";

          if ($result = mysqli_query($conn, $query)) {
            $sqlData = array();
            while ($db_field = mysqli_fetch_assoc($result)) {
                $sqlData[] = $db_field;
            }
        }
          $database = json_encode($sqlData);

?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nobel Prizes</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.1/js/dataTables.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <style>
        html {
            padding: 1em;
            margin: auto;
            line-height: 1.75;
            font-size: 1.25em;
        }
        p,ul,ol {
            margin-bottom: 1em;
            color: #1d1d1d;
            font-family: sans-serif;
        }
    </style>
  </head>
  <body onload="setAll()">
    <div class="row">
        <div class="col">
          <h1>Questions</h1>
        </div>

      </div>  
    </div>
    <table id="questions" class="compact stripe">
      <thead>
        <tr>
          <th>id</th>
          <th>question</th>
          <th>subject</th>
          <th>code</th>
          <th>date_created</th>
          <th>user</th>
        </tr>
      </thead>
      <tbody>

      </tbody>  
    </table>
    <script>
        var jsonData = <?php echo $database; ?>;
        var table;
        $(document).ready(function() {
            table = $('#questions').DataTable({
                data: jsonData,
                columns: [{
                        data: 'id',
                        visible: false
                    },
                    {
                        data: 'question',
                        visible: true,
                        render: function(data, type, row, meta) {
                            return '<a href="vote.php?id=' + row.code + '" class="btn btn">' + data + '</a>';
                        }
                    },
                    {
                        data: 'subject',
                        visible: true
                    },
                    {
                        data: 'code',
                        visible: true
                    },
                    {
                        data: 'date_created',
                        visible: true,
                    },
                    {
                        data: 'user',
                        visible: true,
                    },
                ],
                responsive: true,
            });

        });
        $('#yearSwitch').on('change', function() {
                    var selectedYear = $(this).val();

                    table.column(1).visible(!selectedYear).draw();
                    table.columns(1).search(selectedYear).draw();
                });


            $('#categorySwitch').on('change', function() {
                    var selectedCategory = $(this).val();

                    table.column(2).visible(!selectedCategory).draw();
                    table.columns(2).search(selectedCategory).draw();
                });

        $('#nobel_prizes tbody').on('click', 'tr', function () {
            var rowData = table.row(this).data(); // Get data of the clicked row
            if (rowData) {
                var id = rowData['id']; // Extract the ID of the clicked row
                var url = 'receiver.php?id=' + String(id); 
                window.location.href = url;
            }
        });
    </script>
  </body>
</html>