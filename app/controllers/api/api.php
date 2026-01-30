<?php

class Api 
{
    private $responseData = [];
    private $statusCode = 200;
    private $rateLimitRequests = 100; 
    private $rateLimitWindow = 3600;  // 1 hour
    protected $method;
    protected $endpoint;
    protected $resourceId;
    private $requestData = [];
    protected $apiKeyName = null;  // Name of the validated API key
    protected $requiresApiKey = true; // Whether endpoints require API key by default


    public function __construct()
    {
        // Set CORS headers
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Max-Age: 3600');
        
        // Handle preflight requests
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
       
        $this->checkRateLimit();
        $this->parseRequest();
        $this->validateApiKey();
    }

    /**
     * Parse incoming REST request
     * Extracts HTTP method, endpoint, and resource ID
     */
    private function parseRequest()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->requestData = $this->parseRequestBody();
        
        // Parse endpoint from query string or path
        $endpoint = $_GET['endpoint'] ?? $_GET['action'] ?? null;
        
        // If not in query string, try to parse from URI path
        if (!$endpoint) {
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            // Remove the script path and split by /
            $path = trim(str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $uri), '/');
            if ($path) {
                $parts = explode('/', $path);
                // Skip 'api' and version parts (e.g., 'v1'), get the actual endpoint
                if (count($parts) >= 3 && $parts[0] === 'api') {
                    $this->endpoint = $parts[2] ?? null;
                    $this->resourceId = $parts[3] ?? null;
                    return;
                }
            }
        }
        
        if ($endpoint) {
            $parts = explode('/', trim($endpoint, '/'));
            $this->endpoint = $parts[0] ?? null;
            $this->resourceId = $parts[1] ?? null;
        }
    }

    /**
     * Validate API key from request headers
     * Checks Authorization header for Bearer token or X-API-Key header
     * 
     * @return void
     */
    private function validateApiKey()
    {
        // Check if endpoint is public (doesn't require API key)
        if ($this->isPublicEndpoint()) {
            return;
        }

        $apiKey = $this->extractApiKey();

        if (!$apiKey) {
            $this->sendUnauthorized('API key is required. Use Authorization: Bearer <key> or X-API-Key header.');
        }

        // Validate against configured API keys
        $validKey = $this->findValidApiKey($apiKey);

        if (!$validKey) {
            $this->sendUnauthorized('Invalid API key.');
        }

        $this->apiKeyName = $validKey;
    }

    /*
     * Extract API key from request headers
     * Supports: Authorization: Bearer <key> or X-API-Key: <key>
     * 
     * @return string|null
     */
    private function extractApiKey()
    {
        // Check Authorization header (Bearer token)
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        
        if (preg_match('/^Bearer\s+(.+)$/i', $authHeader, $matches)) {
            return trim($matches[1]);
        }

        // Check X-API-Key header
        if (!empty($_SERVER['HTTP_X_API_KEY'])) {
            return trim($_SERVER['HTTP_X_API_KEY']);
        }

        // Check query parameter as fallback (less secure, but useful for testing)
        if (!empty($_GET['api_key'])) {
            return trim($_GET['api_key']);
        }

        return null;
    }

    /**
     * Find and validate API key against configured keys
     * Uses timing-safe comparison to prevent timing attacks
     * 
     * @param string $providedKey
     * @return string|null Key name if valid, null otherwise
     */
    private function findValidApiKey($providedKey)
    {
        if (!defined('API_KEYS') || !is_array(API_KEYS)) {
            return null;
        }

        foreach (API_KEYS as $keyName => $keyValue) {
            // Use hash_equals for timing-safe comparison
            if (hash_equals($keyValue, $providedKey)) {
                return $keyName;
            }
        }

        return null;
    }

    /**
     * Check if current endpoint is public (no API key required)
     * 
     * @return bool
     */
    protected function isPublicEndpoint()
    {
        if (!$this->requiresApiKey) {
            return true;
        }

        if (defined('API_PUBLIC_ENDPOINTS') && is_array(API_PUBLIC_ENDPOINTS)) {
            return in_array($this->endpoint, API_PUBLIC_ENDPOINTS);
        }

        return false;
    }

    /**
     * Get the name of the authenticated API key
     * 
     * @return string|null
     */
    protected function getApiKeyName()
    {
        return $this->apiKeyName;
    }

    /**
     * Parse request body based on HTTP method and content type
     * Handles JSON, form data, and query parameters
     * 
     * @return array
     */
    private function parseRequestBody()
    {
        $data = [];
        
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if (strpos($contentType, 'application/json') !== false) {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true) ?? [];
        } else {
            $data = $_POST ?? [];
        }
        
        return $data;
    }

    /**
     * Route the request to appropriate handler based on HTTP method
     * 
     * @param string $endpoint
     * @param string $method
     * @param mixed $resourceId
     */
    public function route($endpoint, $method = null, $resourceId = null)
    {
        if (!$endpoint) {
            $this->sendError('API endpoint not specified', 400);
        }

        $method = $method ?? $this->method;
        $resourceId = $resourceId ?? $this->resourceId;

        switch ($method) {
            case 'GET':
                if ($resourceId) {
                    $this->handleGet($endpoint, $resourceId);
                } else {
                    $this->handleGetList($endpoint);
                }
                break;
            case 'POST':
                $this->handlePost($endpoint);
                break;
            case 'PUT':
                $this->handlePut($endpoint, $resourceId);
                break;
            case 'PATCH':
                $this->handlePatch($endpoint, $resourceId);
                break;
            case 'DELETE':
                $this->handleDelete($endpoint, $resourceId);
                break;
            default:
                $this->sendError('HTTP method not allowed', 405);
        }
    }

    /**
     * Handle GET request for single resource
     * Override in subclass to implement
     * 
     * @param string $endpoint
     * @param mixed $resourceId
     */
    protected function handleGet($endpoint, $resourceId)
    {
        $this->sendError('GET method not implemented for ' . $endpoint, 501);
    }

    /**
     * Handle GET request for resource list
     * Override in subclass to implement
     * 
     * @param string $endpoint
     */
    protected function handleGetList($endpoint)
    {
        $this->sendError('GET method not implemented for ' . $endpoint, 501);
    }

    /**
     * Handle POST request to create resource
     * Override in subclass to implement
     * 
     * @param string $endpoint
     */
    protected function handlePost($endpoint)
    {
        $this->sendError('POST method not implemented for ' . $endpoint, 501);
    }

    /**
     * Handle PUT request to replace resource
     * Override in subclass to implement
     * 
     * @param string $endpoint
     * @param mixed $resourceId
     */
    protected function handlePut($endpoint, $resourceId)
    {
        $this->sendError('PUT method not implemented for ' . $endpoint, 501);
    }

    /**
     * Handle PATCH request to partially update resource
     * Override in subclass to implement
     * 
     * @param string $endpoint
     * @param mixed $resourceId
     */
    protected function handlePatch($endpoint, $resourceId)
    {
        $this->sendError('PATCH method not implemented for ' . $endpoint, 501);
    }

    /**
     * Handle DELETE request to remove resource
     * Override in subclass to implement
     * 
     * @param string $endpoint
     * @param mixed $resourceId
     */
    protected function handleDelete($endpoint, $resourceId)
    {
        $this->sendError('DELETE method not implemented for ' . $endpoint, 501);
    }

    public function index()
    {
        $this->sendError('API endpoint not specified', 400);
    }

    /**
     * Private method to validate AJAX request and origin
     * Ensures API is only accessible via AJAX from the same website
     */
   

    /**
     * Private method to check and enforce rate limiting
     * Uses session-based storage for request tracking
     * 
     * @throws Exception if rate limit exceeded
     */
    private function checkRateLimit()
    {
        $clientIp = $this->getClientIp();
        $rateLimitKey = 'api_rate_limit_' . $clientIp;
        $requestCountKey = 'api_request_count_' . $clientIp;
        
        // Initialize session array if not exists
        if (!isset($_SESSION[$rateLimitKey])) {
            $_SESSION[$rateLimitKey] = time();
            $_SESSION[$requestCountKey] = 0;
        }
        
        $timeWindow = time() - $_SESSION[$rateLimitKey];
        
        // Reset counter if time window has passed
        if ($timeWindow > $this->rateLimitWindow) {
            $_SESSION[$rateLimitKey] = time();
            $_SESSION[$requestCountKey] = 0;
        }
        
        // Increment request count
        $_SESSION[$requestCountKey]++;
        
        // Check if limit exceeded
        if ($_SESSION[$requestCountKey] > $this->rateLimitRequests) {
            $retryAfter = $this->rateLimitWindow - $timeWindow;
            header('X-RateLimit-Limit: ' . $this->rateLimitRequests);
            header('X-RateLimit-Remaining: 0');
            header('X-RateLimit-Reset: ' . ($this->rateLimitWindow - $timeWindow));
            header('Retry-After: ' . ceil($retryAfter));
            
            $this->sendError(
                'Rate limit exceeded. Maximum ' . $this->rateLimitRequests . ' requests per ' . 
                $this->rateLimitWindow . ' seconds allowed. Try again in ' . ceil($retryAfter) . ' seconds.',
                429
            );
        }
        
        // Send rate limit headers
        header('X-RateLimit-Limit: ' . $this->rateLimitRequests);
        header('X-RateLimit-Remaining: ' . ($this->rateLimitRequests - $_SESSION[$requestCountKey]));
        header('X-RateLimit-Reset: ' . (time() + ($this->rateLimitWindow - $timeWindow)));
    }

    /**
     * Getter method for request data
     * 
     * @return array
     */
    protected function getRequestData()
    {
        return $this->requestData;
    }

    /**
     * Get specific request parameter
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function getParam($key, $default = null)
    {
        return $this->requestData[$key] ?? $default;
    }

    /**
     * Get current HTTP method
     * 
     * @return string
     */
    protected function getMethod()
    {
        return $this->method;
    }

    /**
     * Get current endpoint
     * 
     * @return string
     */
    protected function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Get current resource ID
     * 
     * @return mixed
     */
    protected function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * Get query parameter
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function getQuery($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Validate required request parameters
     * 
     * @param array $required
     * @return bool
     * @throws Exception
     */
    protected function validateRequired($required = [])
    {
        $missing = [];
        
        foreach ($required as $field) {
            if (!isset($this->requestData[$field]) || $this->requestData[$field] === '') {
                $missing[] = $field;
            }
        }
        
        if (!empty($missing)) {
            $this->sendValidationError([
                'missing_fields' => $missing,
                'message' => 'Required fields: ' . implode(', ', $missing)
            ]);
        }
        
        return true;
    }

    /**
     * Set HTTP status code for response
     * 
     * @param int $code
     */
    protected function setStatusCode($code)
    {
        $this->statusCode = $code;
    }

    /**
     * Get current HTTP status code
     * 
     * @return int
     */
    protected function getStatusCode()
    {
        return $this->statusCode;
    }
    /**
     * Send successful response (HTTP 200)
     * 
     * @param mixed $data
     * @param string $message
     */
    protected function sendResponse($data = null, $message = 'Success')
    {
        $this->statusCode = 200;
        $this->sendRestResponse($data, $message, $this->statusCode);
    }

    /**
     * Send created response (HTTP 201)
     * 
     * @param mixed $data
     * @param string $message
     */
    protected function sendCreated($data = null, $message = 'Resource created successfully')
    {
        $this->statusCode = 201;
        $this->sendRestResponse($data, $message, $this->statusCode);
    }

    /**
     * Send accepted response (HTTP 202)
     * 
     * @param mixed $data
     * @param string $message
     */
    protected function sendAccepted($data = null, $message = 'Request accepted')
    {
        $this->statusCode = 202;
        $this->sendRestResponse($data, $message, $this->statusCode);
    }

    /**
     * Send no content response (HTTP 204)
     */
    protected function sendNoContent()
    {
        header('Content-Type: application/json');
        http_response_code(204);
        exit;
    }

    /**
     * Send error response with appropriate HTTP status code
     * 
     * @param string $message
     * @param int $code
     */
    protected function sendError($message, $code = 400)
    {
        $this->sendRestResponse(null, $message, $code, true);
    }

    /**
     * Send conflict response (HTTP 409)
     * 
     * @param string $message
     */
    protected function sendConflict($message)
    {
        $this->sendRestResponse(null, $message, 409, true);
    }

    /**
     * Send unauthorized response (HTTP 401)
     * 
     * @param string $message
     */
    protected function sendUnauthorized($message = 'Unauthorized')
    {
        $this->sendRestResponse(null, $message, 401, true);
    }

    /**
     * Send forbidden response (HTTP 403)
     * 
     * @param string $message
     */
    protected function sendForbidden($message = 'Forbidden')
    {
        $this->sendRestResponse(null, $message, 403, true);
    }

    /**
     * Send not found response (HTTP 404)
     * 
     * @param string $message
     */
    protected function sendNotFound($message = 'Resource not found')
    {
        $this->sendRestResponse(null, $message, 404, true);
    }

    /**
     * Send validation error response (HTTP 422)
     * 
     * @param array $errors
     */
    protected function sendValidationError($errors = [])
    {
        header('Content-Type: application/json');
        http_response_code(422);

        $response = [
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $errors,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Generic REST response method
     * 
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @param bool $isError
     */
    private function sendRestResponse($data, $message, $code, $isError = false)
    {
        header('Content-Type: application/json');
        http_response_code($code);

        $response = [
            'status' => $isError ? 'error' : 'success',
            'message' => $message,
            'code' => $code,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        if (!$isError) {
            $response['data'] = $data;
        }

        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Private method to get client IP address
     * Handles proxy and private networks
     * 
     * @return string
     */
    private function getClientIp()
    {
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            // Cloudflare
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // IPs passed from proxy
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED'])) {
            return $_SERVER['HTTP_X_FORWARDED'];
        } elseif (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['HTTP_FORWARDED'])) {
            return $_SERVER['HTTP_FORWARDED'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        return 'unknown';
    }

    /**
     * Private method to validate encryption key for security
     * 
     * @param string $key
     * @return bool
     */
    private function validateEncryptionKey($key)
    {
        // Check if key matches session encryption key
        if (isset($_SESSION['encrypt_key']) && hash_equals($_SESSION['encrypt_key'], $key)) {
            return true;
        }
        return false;
    }
}
