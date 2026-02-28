<?php
// Detect environment automatically
$isDevelopment = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1');

// Database configuration (usually same for XAMPP)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ballz');

// Detect protocol properly (important behind Cloudflare Tunnel)
$protocol = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
) ? "https://" : "http://";

// Define root dynamically
define('ROOT', $protocol . $_SERVER['HTTP_HOST'] . '/Ballz');

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
    'payment-methods',
    'payment-callback',
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
    'payments',
]);

// Endpoints that are auth endpoints (require API key, issue JWT)
define('JWT_AUTH_ENDPOINTS', [
    'login',
    'auth',
    'refresh-token',
    'logout',
]);
