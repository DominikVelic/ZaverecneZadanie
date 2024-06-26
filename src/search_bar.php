<div class="form-container">
    <!-- Add search bar for 5-character code -->
    <h1><?php echo $lang['search_bar_text']; ?></h1>
    <form id='search_form' method="post" class="needs-validation" novalidate>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" name="code" id="code" maxlength="5" pattern="\d{5}" required>
            <label for="code" class="form-label"><?php echo $lang['enter_5-digit_code_text']; ?></label>
            <?php
            if (isset($_SESSION['error'])) {
                echo '<div class="text-danger">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            ?>
        </div>
        <div class="row">
            <div class="col mb-3">
                <button type="submit" class="btn btn-primary">Vyhľadať</button>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById("search_form").addEventListener("submit", function(event) {
        event.preventDefault();
        var code = $('#code').val();
        fetchQuestion(code);

        // Clear the code from the URL path
        clearCodeFromUrl();
    });

    document.addEventListener('DOMContentLoaded', function() {
        const codeFromUrl = getCodeFromUrl();
        if (codeFromUrl) {
            document.getElementById('code').value = codeFromUrl;
            fetchQuestion(codeFromUrl);
        }
    });
</script>