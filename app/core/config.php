<?php
$serverName = $_SERVER['SERVER_NAME'];
$isDevelopment = ($serverName == 'localhost' || $serverName == '127.0.0.1' || strpos($serverName, '192.168.') === 0);

if ($isDevelopment) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'ballz');
    define('ROOT', 'http://' . $serverName . '/Ballz');
} else {
    define('DB_HOST', 'your_production_host');
    define('DB_USER', 'your_prod_user');
    define('DB_PASS', 'your_prod_password');
    define('DB_NAME', 'your_prod_db');
    define('ROOT', 'https://yourdomain.com/Ballz');
}
