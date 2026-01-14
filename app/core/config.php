<?php
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'ballz');
    define('ROOT', 'http://localhost/Ballz');
} else {
    define('DB_HOST', '');
    define('DB_USER', '');
    define('DB_PASS', '');
    define('DB_NAME', '');
    define('ROOT', 'https://domain/app-name');
}
