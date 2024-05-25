<?php
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

require '../header.php';

?>

<body>
  <div class="container">
    <div class="row">
      <div class="col">
        <h1>Questions</h1>
      </div>
    </div>
    <div class="row">
      <table id="questions" class="compact stripe">
        <thead>
          <tr>
            <th>id</th>
            <th>question</th>
            <th>subject</th>
            <th>date_created</th>
            <th>user</th>
            <th>code</th>
            <th>QR code</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
  </div>

  </div>


  <?php require '../footer.php' ?>
  <script>
    var jsonData = <?php echo $database; ?>;
    var table;
    $(document).ready(function() {
      var table = $('#questions').DataTable({
        data: jsonData,
        columns: [{
            data: 'id',
            visible: false
          },
          {
            data: 'question',
            visible: true,
          },
          {
            data: 'subject',
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
          {
            data: 'code',
            visible: true,
            render: function(data, type, row, meta) {
              return '<a href="/index.php/' + encodeURIComponent(row.code) + '" class="btn btn-info">' + data + '</a>';
            }
          },
          {
            data: 'qr_code',
            visible: true,
            render: function(data, type, row, meta) {
              var qrCodeId = 'qrcode-' + meta.row; // Unique ID for each QR code
              return `<div id="${qrCodeId}" class="qrcode"></div>`;
            }
          }
        ],
        responsive: true,
        drawCallback: function(settings) {
          var api = this.api();
          api.rows().every(function(rowIdx, tableLoop, rowLoop) {
            var data = this.data();
            var qrCodeId = 'qrcode-' + rowIdx;
            var qrCodeUrl = '/index.php/' + data.code;
            new QRCode(document.getElementById(qrCodeId), qrCodeUrl);
          });
        }
      });
    });
  </script>
</body>

</html>