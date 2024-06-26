<?php


$file = __DIR__ . "/language/language_file.php";

if (file_exists($file)) {
    include($file);
} else {
    echo "Warning: Language file not found.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/main.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5 p-5">
            <div class="container-fluid">
                <a class="navbar-brand" href="/index.php"><?php echo $lang['header_title']; ?></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav text-white ms-5">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/index.php"><?php echo $lang['home_link_text']; ?></a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) : ?>
                            <?php if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) : ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/2FA/admin.php"><?php echo $lang['admin_text']; ?></a>
                                </li>
                            <?php endif; ?>
                            <!-- Logout Button -->
                            <li class="nav-item">
                                <a class="nav-link" href="/questions/questionList.php"><?php echo $lang['show_questions_text']; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/2FA/add.php"><?php echo $lang['create_question_text']; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/2FA/password_change.php"><?php echo $lang['password_change_text']; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/2FA/logout.php"><?php echo $lang['logout_text']; ?></a>
                            </li>
                        <?php else : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/questions/questionList.php"><?php echo $lang['show_questions_text']; ?></a>
                            </li>
                            <!-- Login Button -->
                            <li class="nav-item">
                                <a class="nav-link" href="/2FA/login.php"><?php echo $lang['login_text']; ?></a>
                            </li>
                            <!-- Register Button -->
                            <li class="nav-item">
                                <a class="nav-link" href="/2FA/register_form.php"><?php echo $lang['register_text']; ?></a>
                            </li>
                        <?php endif; ?>
                        <!-- Language Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo $lang['language_dropdown']; ?>
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