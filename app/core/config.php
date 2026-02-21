<?php
$serverName = $_SERVER['SERVER_NAME'];
$isNgrok = (strpos($serverName, 'ngrok') !== false);
$isDevelopment = ($serverName == 'localhost' || $serverName == '127.0.0.1' || strpos($serverName, '192.168.') === 0);

if ($isDevelopment) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'ballz');
    define('ROOT', 'http://' . $serverName . '/Ballz');
} elseif ($isNgrok) {
    // Ngrok tunneling - use local database but ngrok URL
    // Always use HTTPS for ngrok (ngrok terminates SSL, so $_SERVER['HTTPS'] may not be set)
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'ballz');
    define('ROOT', 'https://' . $serverName . '/Ballz');
} else {
    define('DB_HOST', $serverName);
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'ballz');
    define('ROOT', 'https://yourdomain.com/Ballz');
}

/**
 * API Key Configuration
 * Generate new keys using: bin2hex(random_bytes(32))
 */
define('API_KEYS', [
    // 'key_name' => 'api_key_value'
    'mobile_app' => 'bz_de3cf14781aaefcaa070cb33c9bb57153cd35c2b3e95d6534a5de395e5456b6c',
    'web_client' => 'bz_42e0d8eaf5245c80f289d87f18f2034b2a30a2c6d72be1c099579615db12357f',
]);

// Public endpoints that don't require API key authentication
define('API_PUBLIC_ENDPOINTS', [
    'menu',
    'outlets',
    'vouchers',
    'rewards',
]);

/**
 * JWT Configuration
 * Generate a new secret using: bin2hex(random_bytes(32))
 */
define('JWT_SECRET', 'bz_jwt_a7c9f2e1d4b8036f5e9a1c7d2b4e8f0a3d6c9b2e5f8a1d4c7b0e3f6a9d2c5b');

// Endpoints that require JWT token (user-level auth) instead of API key
define('JWT_PROTECTED_ENDPOINTS', [
    'users',
    'orders',
    'reward-transactions',
]);

// Endpoints that are auth endpoints (require API key, issue JWT)
define('JWT_AUTH_ENDPOINTS', [
    'login',
    'auth',
    'refresh-token',
    'logout',
]);
