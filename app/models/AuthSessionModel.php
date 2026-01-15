<?php

/**
 * Auth Session Model - Manages authentication sessions
 */
class AuthSessionModel
{
    use Database;

    public function createSession($userId, $accessToken, $refreshToken = null, $expiresAt = null)
    {
        if ($expiresAt === null) {
            $expiresAt = date('Y-m-d H:i:s', time() + ACCESS_TOKEN_LIFETIME);
        }

        $query = "INSERT INTO auth_sessions (user_id, access_token, refresh_token, expires_at) VALUES (?, ?, ?, ?)";
        $this->query($query);
        $this->bind("isss", $userId, $accessToken, $refreshToken, $expiresAt);
        
        return $this->execute() ? $this->getLastInsertId() : false;
    }

    public function getSessionByToken($accessToken)
    {
        $query = "SELECT s.*, u.* FROM auth_sessions s 
                  JOIN users u ON s.user_id = u.id 
                  WHERE s.access_token = ? AND s.revoked_at IS NULL AND s.expires_at > NOW()";
        $this->query($query);
        $this->bind("s", $accessToken);
        $result = $this->resultSet();
        return $result && count($result) > 0 ? $result[0] : null;
    }

    public function getSessionByRefreshToken($refreshToken)
    {
        $query = "SELECT s.*, u.* FROM auth_sessions s 
                  JOIN users u ON s.user_id = u.id 
                  WHERE s.refresh_token = ? AND s.revoked_at IS NULL";
        $this->query($query);
        $this->bind("s", $refreshToken);
        $result = $this->resultSet();
        return $result && count($result) > 0 ? $result[0] : null;
    }

    public function updateSessionTokens($sessionId, $accessToken, $refreshToken, $expiresAt)
    {
        $query = "UPDATE auth_sessions SET access_token = ?, refresh_token = ?, expires_at = ? WHERE id = ?";
        $this->query($query);
        $this->bind("sssi", $accessToken, $refreshToken, $expiresAt, $sessionId);
        return $this->execute();
    }

    public function revokeSession($sessionId)
    {
        $this->query("UPDATE auth_sessions SET revoked_at = NOW() WHERE id = ?");
        $this->bind("i", $sessionId);
        return $this->execute();
    }

    public function revokeSessionByToken($accessToken)
    {
        $this->query("UPDATE auth_sessions SET revoked_at = NOW() WHERE access_token = ?");
        $this->bind("s", $accessToken);
        return $this->execute();
    }

    public function revokeAllUserSessions($userId)
    {
        $this->query("UPDATE auth_sessions SET revoked_at = NOW() WHERE user_id = ? AND revoked_at IS NULL");
        $this->bind("i", $userId);
        return $this->execute();
    }

    public function validateToken($accessToken)
    {
        $session = $this->getSessionByToken($accessToken);
        
        if (!$session) {
            return ['valid' => false, 'error' => 'Invalid or expired token'];
        }

        if ($session['status'] !== 'active') {
            return ['valid' => false, 'error' => 'User account is not active'];
        }

        return ['valid' => true, 'user' => $session];
    }

    public function refreshSession($refreshToken)
    {
        $session = $this->getSessionByRefreshToken($refreshToken);
        
        if (!$session) {
            return ['success' => false, 'error' => 'Invalid refresh token'];
        }

        $newAccessToken = bin2hex(random_bytes(32));
        $newRefreshToken = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', time() + ACCESS_TOKEN_LIFETIME);

        if ($this->updateSessionTokens($session['id'], $newAccessToken, $newRefreshToken, $expiresAt)) {
            return [
                'success' => true,
                'access_token' => $newAccessToken,
                'refresh_token' => $newRefreshToken,
                'expires_at' => $expiresAt
            ];
        }

        return ['success' => false, 'error' => 'Failed to refresh session'];
    }
}
