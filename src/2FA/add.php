<?php
session_start();

require '../header.php';
?>

<body>

    <div class="container">


        <div class="row">
            <div class="card col-12 mb-5 bg-dark text-white">
                <div class="card-body m-5">
                    <form action="addSend.php" method="post">
                        <div class="card-text">
                            <div id="card-inside">
                                <div class="form-group mb-3">
                                    <label for="question"><strong><?php echo $lang['question'] ?></strong></label>
                                    <input type="text" class="form-control" id="question" name="question">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="subject"><strong><?php echo $lang['subject'] ?></strong></label>
                                    <input type="text" class="form-control" id="subject" name="subject">
                                </div>

                                <div id="prize_container" class="text-dark">
                                    <div id="prize0" class="card p-2 mb-2 prize-section">
                                        <div class="card-text">
                                            <div class="form-group mb-3">
                                                <label for="year"><strong><?php echo $lang['answer'] ?></strong></label>
                                                <input type="text" class="form-control" id="answer" name="answer[]" required>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <br>
                            </div>

                            <button type="button" id="addFields" class="btn btn-success"><?php echo $lang['add_answer'] ?></button>
                            <button type="submit" class="btn btn-primary"><?php echo $lang['send'] ?></button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require '../footer.php'; ?>

    <script>
        $(document).ready(function() {
            $('#addFields').on('click', function() {
                addNewAnswer();
            });
        });
    </script>
</body>

</html>