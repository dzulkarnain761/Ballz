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

/**
 * Log API access for security monitoring
 */
function logApiAccess($endpoint, $success = true, $message = '')
{
    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'endpoint' => $endpoint,
        'success' => $success,
        'message' => $message,
        'session_id' => session_id()
    ];

    // Log to file (create logs directory if not exists)
    $logDir = __DIR__ . '/../../logs';
    if (!file_exists($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $logFile = $logDir . '/api_access_' . date('Y-m-d') . '.log';
    file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
}

/**
 * Calculate number of nights between check-in and check-out dates
 */
function calcNumOfNight($checkInDate, $checkOutDate, $type = null)
{
    if (strpos($checkInDate, '/') !== false) {
        $checkInDate = DateTime::createFromFormat('d/m/Y', $checkInDate);
        $checkOutDate = DateTime::createFromFormat('d/m/Y', $checkOutDate);
    } else {
        $checkInDate = DateTime::createFromFormat('Y-m-d', $checkInDate);
        $checkOutDate = DateTime::createFromFormat('Y-m-d', $checkOutDate);
    }

    $interval = $checkInDate->diff($checkOutDate);
    $num_of_night = $interval->days;

    if($type == 'hall'){
        $num_of_night = $num_of_night + 1;
    }

    return $num_of_night;
}

function validate($data)
{

    return trim(htmlspecialchars($data));
}

function validateBeforePayment($fullname, $email, $phonenumber)
{
    $fullName = validate($_POST['full_name']);
    $email = validate($_POST['form-email']);
    $phoneNumber = validate($_POST['phone_number']);


    if (strlen($fullName) < 3) {
        $_SESSION['err'] = "Nama perlu lebih dari 3 perkataan.";
        echo "<script>window.history.back();</script>";
        exit();
    }

    if (!preg_match('/^[0-9]{10,15}$/', $phoneNumber)) {
        $_SESSION['err'] = "Sila beri nombor telefon yang sah dan tanpa dash (-).";
        echo "<script>window.history.back();</script>";
        exit();
    }
    $_SESSION['cust_name'] = htmlspecialchars($fullName);
    $_SESSION['form-email'] = htmlspecialchars($email);
    $_SESSION['phone_number'] = htmlspecialchars($phoneNumber);
}


function translateStatus($type)
{
    $translations = [
        'pending' => 'Dalam Proses',
        'accepted' => 'Diterima',
        'approved' => 'Diluluskan',
        'rejected' => 'Ditolak',
        'updated' => 'Dikemas Kini',
        'paid' => 'Dibayar',
        'completed' => 'Selesai',
        'unpaid' => 'Belum Dibayar',
        'expired' => 'Tamat Tempoh'
    ];

    return $translations[$type] ?? ucfirst(str_replace('_', ' ', $type));
}

// ==================== OAuth 2.0 Functions ====================

function generateOAuthState()
{
    $state = bin2hex(random_bytes(16));
    $_SESSION['oauth_state'] = $state;
    $_SESSION['oauth_state_time'] = time();
    return $state;
}

function verifyOAuthState($state)
{
    if (!isset($_SESSION['oauth_state']) || !isset($_SESSION['oauth_state_time'])) {
        return false;
    }

    if (time() - $_SESSION['oauth_state_time'] > OAUTH_STATE_LIFETIME) {
        unset($_SESSION['oauth_state'], $_SESSION['oauth_state_time']);
        return false;
    }

    $valid = hash_equals($_SESSION['oauth_state'], $state);
    unset($_SESSION['oauth_state'], $_SESSION['oauth_state_time']);
    return $valid;
}

function buildOAuthUrl($provider)
{
    if (!isset(OAUTH_PROVIDERS[$provider])) {
        return null;
    }

    $config = OAUTH_PROVIDERS[$provider];
    $state = generateOAuthState();
    
    $params = [
        'client_id' => $config['client_id'],
        'redirect_uri' => $config['redirect_uri'],
        'response_type' => 'code',
        'state' => $state,
        'scope' => implode(' ', $config['scopes'])
    ];

    if ($provider === 'google') {
        $params['access_type'] = 'offline';
        $params['prompt'] = 'consent';
    }

    return $config['auth_url'] . '?' . http_build_query($params);
}

function exchangeOAuthCode($provider, $code)
{
    if (!isset(OAUTH_PROVIDERS[$provider])) {
        return ['success' => false, 'error' => 'Invalid provider'];
    }

    $config = OAUTH_PROVIDERS[$provider];
    
    $postData = [
        'client_id' => $config['client_id'],
        'client_secret' => $config['client_secret'],
        'code' => $code,
        'redirect_uri' => $config['redirect_uri'],
        'grant_type' => 'authorization_code'
    ];

    $ch = curl_init($config['token_url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        return ['success' => false, 'error' => 'Token exchange failed'];
    }

    $data = json_decode($response, true);
    
    if (!isset($data['access_token'])) {
        return ['success' => false, 'error' => 'No access token received'];
    }

    return [
        'success' => true,
        'access_token' => $data['access_token'],
        'refresh_token' => $data['refresh_token'] ?? null,
        'expires_in' => $data['expires_in'] ?? null,
        'id_token' => $data['id_token'] ?? null
    ];
}

function getOAuthUserInfo($provider, $accessToken)
{
    if (!isset(OAUTH_PROVIDERS[$provider])) {
        return ['success' => false, 'error' => 'Invalid provider'];
    }

    $config = OAUTH_PROVIDERS[$provider];
    
    $ch = curl_init($config['user_info_url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Accept: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        return ['success' => false, 'error' => 'Failed to fetch user info'];
    }

    $data = json_decode($response, true);
    return ['success' => true, 'user' => normalizeOAuthUser($provider, $data)];
}

function normalizeOAuthUser($provider, $data)
{
    $user = [
        'provider_user_id' => null,
        'email' => null,
        'name' => null,
        'picture' => null
    ];

    switch ($provider) {
        case 'google':
            $user['provider_user_id'] = $data['id'] ?? null;
            $user['email'] = $data['email'] ?? null;
            $user['name'] = $data['name'] ?? null;
            $user['picture'] = $data['picture'] ?? null;
            break;
        case 'facebook':
            $user['provider_user_id'] = $data['id'] ?? null;
            $user['email'] = $data['email'] ?? null;
            $user['name'] = $data['name'] ?? null;
            $user['picture'] = "https://graph.facebook.com/{$data['id']}/picture?type=large";
            break;
    }

    return $user;
}

function generateAccessToken()
{
    return bin2hex(random_bytes(32));
}

function generateRefreshToken()
{
    return bin2hex(random_bytes(32));
}

function isAuthenticated()
{
    return isset($_SESSION['user_id']) && isset($_SESSION['access_token']);
}

function getCurrentUser()
{
    if (!isAuthenticated()) {
        return null;
    }

    $sessionModel = new AuthSessionModel();
    $validation = $sessionModel->validateToken($_SESSION['access_token']);
    
    if (!$validation['valid']) {
        clearAuthSession();
        return null;
    }

    return $validation['user'];
}

function setAuthSession($user, $accessToken, $refreshToken = null)
{
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['access_token'] = $accessToken;
    
    if ($refreshToken) {
        $_SESSION['refresh_token'] = $refreshToken;
    }
}

function clearAuthSession()
{
    if (isset($_SESSION['access_token'])) {
        $sessionModel = new AuthSessionModel();
        $sessionModel->revokeSessionByToken($_SESSION['access_token']);
    }

    unset(
        $_SESSION['user_id'],
        $_SESSION['user_name'],
        $_SESSION['user_email'],
        $_SESSION['access_token'],
        $_SESSION['refresh_token']
    );
}
