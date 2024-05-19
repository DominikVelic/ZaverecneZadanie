<?php

session_start();
require "language_change.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/main.css">
</head>

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