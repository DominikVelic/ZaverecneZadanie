<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5 p-5">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><?php echo $lang['header_title'] ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav text-white ms-5">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#"><?php echo $lang['home_link_text'] ?></a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $lang['language_dropdown'] ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item">SK</a></li>
                            <li><a class="dropdown-item">EN</a></li>
                        </ul>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
</header>