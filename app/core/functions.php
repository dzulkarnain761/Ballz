<?php

function cout(...$items)
{
    $exit = true;
    if (is_bool(end($items))) {
        $exit = array_pop($items);
    }

    echo "<pre>";
    foreach ($items as $item) {
        print_r($item);
        echo "\n";
    }
    echo "</pre>";
    if ($exit) {
        exit;
    }
}

spl_autoload_register(function ($className) {
    $directories = [
        "../app/models/",
        "../app/controllers/",
    ];

    foreach ($directories as $directory) {
        $filePath = $directory . $className . ".php";
        if (file_exists($filePath)) {
            require_once $filePath;
            return;
        }
    }
});

function csrfInput() {
    if (!isset($_SESSION['csrf_token'])) {
        createCRFT();
    }
    $token = $_SESSION['csrf_token'];
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

function createCRFT()
{
    $token = bin2hex(random_bytes(16));
    $_SESSION['csrf_token'] = $token;
}

function checkCRFT()
{
    if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST' && checkCRFT();
}

function allowedValues($values)
{
    return function($input) use ($values) {
        return in_array($input, $values, true);
    };
}

function isGet(){
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

function formatDate($date)
{
    $dateTime = new DateTime($date);
    return $dateTime->format('d/m/Y');
}

function isLoggedIn()
{
    return isset($_SESSION['id_admin']) || isset($_SESSION['no_gaji']);
}

function isAdmin()
{
    return isset($_SESSION['role']);
}

function capitalizeFirstTwoWords($string)
{
    $words = explode(' ', trim($string));
    $result = [];

    for ($i = 0; $i < min(2, count($words)); $i++) {
        if (!empty($words[$i])) {
            $result[] = ucfirst(strtolower($words[$i]));
        }
    }

    return implode(' ', $result);
}

function firstWord($string)
{
    $words = explode(' ', trim($string));
    return !empty($words[0]) ? ucfirst(strtolower($words[0])) : '';
}

function redirect($url)
{
    header("Location: " . ROOT ."/$url");
    exit;
}

function getMalaysiaDate()
{
    date_default_timezone_set('Asia/Kuala_Lumpur');
    return date('d/m/Y');
}

function getInitials($name)
{
    $words = explode(' ', trim($name));
    $initials = '';

    foreach ($words as $word) {
        if (!empty($word)) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
    }

    return $initials;
}

function encryptData($data, $key)
{
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($data, 'AES-128-CBC', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

function decryptData($data, $key)
{
    $decoded = base64_decode($data);
    if (strlen($decoded) < 16) {
        return false;
    }
    $iv = substr($decoded, 0, 16);
    $encrypted = substr($decoded, 16);
    $decrypted = openssl_decrypt($encrypted, 'AES-128-CBC', $key, 0, $iv);
    return $decrypted !== false ? $decrypted : false;
}

/**
 * Generate secure API key for internal use
 */
function generateApiKey($identifier = '')
{
    $baseString = 'eperubatan_internal_api_' . date('Y-m-d') . $identifier;
    return hash('sha256', $baseString);
}

/**
 * Validate API request origin and security
 */
function validateApiSecurity()
{
    // Check if request is from same server
    $allowedHosts = ['localhost', '127.0.0.1', $_SERVER['SERVER_NAME']];
    $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '';
    $httpHost = $_SERVER['HTTP_HOST'] ?? '';

    if (!in_array($remoteAddr, ['127.0.0.1', '::1']) && !in_array($httpHost, $allowedHosts)) {
        return false;
    }

    // Check request method
    if (!in_array($_SERVER['REQUEST_METHOD'], ['POST'])) {
        return false;
    }

    return true;
}

/**
 * Sanitize API input data
 */
function sanitizeApiInput($data)
{
    if (is_array($data)) {
        return array_map('sanitizeApiInput', $data);
    }

    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}



function validate($data)
{

    return trim(htmlspecialchars($data));
}





