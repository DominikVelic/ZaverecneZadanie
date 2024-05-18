<?php

require "language_change.php"

?>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5 p-5">
        <div class="container-fluid">
            <a class="navbar-brand" href="/index.php"><?php echo $lang['header_title'] ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav text-white ms-5">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/index.php"><?php echo $lang['home_link_text'] ?></a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <!-- Login Button -->
                    <li class="nav-item">
                        <a class="nav-link" href="/2FA/login.php"><?php echo $lang['login_text'] ?></a>
                    </li>
                    <!-- Register Button -->
                    <li class="nav-item">
                        <a class="nav-link" href="/2FA/register.php"><?php echo $lang['register_text'] ?></a>
                    </li>
                    <!-- Language Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $lang['language_dropdown'] ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="javascript:updateLanguage('sk')">SK</a></li>
                            <li><a class="dropdown-item" href="javascript:updateLanguage('en')">EN</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>


<script src='/js/main.js'></script>