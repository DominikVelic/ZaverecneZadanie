<?php
session_start();
require "../header.php";

?>

<body>
    <main>
        <div class="form-container">
            <h1><?php echo $lang['register_text']; ?></h1>
            <form id='register_form' method="post">
                <div class="row">
                    <div class="mb-3 col">
                        <label for="firstname" class="form-label"><?php echo $lang['name_text']; ?></label>
                        <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Erik" required>
                        <div class="invalid-feedback">
                            <?php echo $lang['invalid_feedback_name'] ?>
                        </div>
                    </div>
                    <div class="mb-3 col">
                        <label for="lastname" class="form-label"><?php echo $lang['surname_text']; ?></label>
                        <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Prdár" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail:</label>
                    <div class="input-group">
                        <div class="input-group-text">@</div>
                        <input type="email" class="form-control" name="email" id="email" placeholder="erik.prdar@example.com" required>
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="login" id="login" placeholder="prdar" required>
                    <label for="login" class="form-label"><?php echo $lang['login_name_text']; ?></label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="password" id="password" placeholder="prdar" required>
                    <label for="password" class="form-label"><?php echo $lang['password_text']; ?>:</label>
                </div>
                <button type="submit" class="btn btn-primary"><?php echo $lang['register_text']; ?></button>
                <div id="alert" class="alert alert-danger mt-3" style="display: none;" role="alert"></div>
                <div id="qr_code_container" class="mt-3" style="display: none;">
                    <p><?php echo $lang['scan_qr_code'] ?></p>
                    <img id="qr_code" alt="QR kód pre aplikáciu Authenticator" class="img-fluid">
                    <p class="mt-3"><?php echo $lang['now_login'] ?><a href="login.php" class="btn btn-primary"><?php echo $lang['login_text'] ?></a></p>
                </div>
            </form>
            <p class="mt-3"><?php echo $lang['have_account_text']; ?> <a href="login.php"><?php echo $lang['login_here_text']; ?></a></p>
        </div>
    </main>

    <?php require "../footer.php" ?>

    <script>
        document.getElementById('register_form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission

            var formData = new FormData(this);

            fetch("register.php", {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    var qrcode = data.qrcode;
                    var errmsg = data.errmsg;

                    if (errmsg) {
                        document.getElementById('alert').style.display = 'block';
                        document.getElementById('alert').innerHTML = errmsg;
                    }

                    if (qrcode) {
                        document.getElementById('alert').style.display = 'none';
                        document.getElementById('qr_code_container').style.display = 'block';
                        document.getElementById('qr_code').src = qrcode;
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>

</html>