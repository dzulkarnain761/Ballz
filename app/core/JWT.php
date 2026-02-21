<?php

/**
 * Simple JWT (JSON Web Token) implementation using HMAC-SHA256
 * No external dependencies required
 */
class JWT
{
    /**
     * Create a JWT access token
     * 
     * @param array $payload Data to encode (e.g., user_id, email)
     * @return string Encoded JWT token
     */
    public static function createAccessToken($payload)
    {
        $payload['iat'] = time();
        $payload['exp'] = time() + (defined('ACCESS_TOKEN_LIFETIME') ? ACCESS_TOKEN_LIFETIME : 900);
        $payload['type'] = 'access';

        return self::encode($payload);
    }

    /**
     * Create a JWT refresh token
     * Returns both the raw token (to send to client) and its SHA-256 hash (to store in DB)
     * 
     * @param int $userId
     * @return array ['token' => string, 'token_hash' => string, 'expires_at' => string]
     */
    public static function createRefreshToken($userId)
    {
        $lifetime = defined('REFRESH_TOKEN_LIFETIME') ? REFRESH_TOKEN_LIFETIME : 2592000;
        $expiresAt = date('Y-m-d H:i:s', time() + $lifetime);

        // Generate a cryptographically secure random token
        $rawToken = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $rawToken);

        return [
            'token' => $rawToken,
            'token_hash' => $tokenHash,
            'user_id' => $userId,
            'expires_at' => $expiresAt
        ];
    }

    /**
     * Decode and validate a JWT token
     * 
     * @param string $token The JWT string
     * @return array|false Decoded payload or false if invalid
     */
    public static function decode($token)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        list($headerB64, $payloadB64, $signatureB64) = $parts;

        // Verify signature
        $secret = defined('JWT_SECRET') ? JWT_SECRET : '';
        $expectedSignature = self::base64UrlEncode(
            hash_hmac('sha256', "$headerB64.$payloadB64", $secret, true)
        );

        if (!hash_equals($expectedSignature, $signatureB64)) {
            return false;
        }

        // Decode payload
        $payload = json_decode(self::base64UrlDecode($payloadB64), true);
        if (!$payload) {
            return false;
        }

        // Check expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false;
        }

        return $payload;
    }

    /**
     * Validate an access token and return its payload
     * 
     * @param string $token
     * @return array|false
     */
    public static function validateAccessToken($token)
    {
        $payload = self::decode($token);

        if (!$payload) {
            return false;
        }

        // Must be an access token
        if (!isset($payload['type']) || $payload['type'] !== 'access') {
            return false;
        }

        return $payload;
    }

    /**
     * Hash a refresh token for DB comparison
     * 
     * @param string $rawToken
     * @return string SHA-256 hash
     */
    public static function hashRefreshToken($rawToken)
    {
        return hash('sha256', $rawToken);
    }

    /**
     * Encode a payload into a JWT string
     * 
     * @param array $payload
     * @return string
     */
    private static function encode($payload)
    {
        $secret = defined('JWT_SECRET') ? JWT_SECRET : '';

        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload = json_encode($payload);

        $headerB64 = self::base64UrlEncode($header);
        $payloadB64 = self::base64UrlEncode($payload);

        $signature = hash_hmac('sha256', "$headerB64.$payloadB64", $secret, true);
        $signatureB64 = self::base64UrlEncode($signature);

        return "$headerB64.$payloadB64.$signatureB64";
    }

    /**
     * Base64 URL-safe encode
     * 
     * @param string $data
     * @return string
     */
    private static function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Base64 URL-safe decode
     * 
     * @param string $data
     * @return string
     */
    private static function base64UrlDecode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
