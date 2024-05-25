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
  <div class="modal fade" id="qrModal" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="qrModalLabel"><?php echo $lang['qr_code_dt'] ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="qrCodeContainer"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $lang['close_text'] ?></button>
        </div>

      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col">
        <h1><?php echo $lang['questions_dt'] ?></h1>
      </div>

      </div>
    <div class="row">
      <table id="questions" class="compact stripe">
        <thead>
          <tr>
            <th>ID</th>
            <th><?php echo $lang['question_dt'] ?></th>
            <th><?php echo $lang['subject_dt'] ?></th>
            <th><?php echo $lang['date_created_dt'] ?></th>
            <th><?php echo $lang['user_dt'] ?></th>
            <th><?php echo $lang['code_dt'] ?></th>
            <th><?php echo $lang['qr_code_dt'] ?></th>
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
              return `<button class="btn btn-primary show-qr-code" data-qr="${data}" data-toggle="modal" data-target="#qrModal">Show QR Code</button>`;
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
    $('#questions').on('click', '.show-qr-code', function() {

      var qrData = $(this).data('qr');
      $('#qrCodeContainer').empty();
      new QRCode(document.getElementById('qrCodeContainer'), qrData);
    });
    // Add custom CSS to center row data and increase font size
    $('#questions').css('text-align', 'center');
    $('#questions th, #questions td').css('font-size', '16px'); // Adjust font size as needed

  </script>
</body>

</html>