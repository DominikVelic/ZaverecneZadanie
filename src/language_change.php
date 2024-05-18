<?php

// Default to Slovak if lang parameter is not set
$lang_file = isset($_GET['lang']) ? $_GET['lang'] : 'sk';
include(__DIR__ . '/language/' . $lang_file . '.php');
