<?php

/**
 * OAuth Model - Handles OAuth providers and accounts
 */
class OAuthModel
{
    use Database;

    public function getOrCreateProvider($name, $issuerUrl = null)
    {
        $this->query("SELECT * FROM oauth_providers WHERE name = ?");
        $this->bind("s", $name);
        $result = $this->resultSet();
        
        if ($result && count($result) > 0) {
            return $result[0];
        }

        $this->query("INSERT INTO oauth_providers (name, issuer_url) VALUES (?, ?)");
        $this->bind("ss", $name, $issuerUrl);
        
        if ($this->execute()) {
            return [
                'id' => $this->getLastInsertId(),
                'name' => $name,
                'issuer_url' => $issuerUrl
            ];
        }
        return null;
    }

    public function getAccountByProvider($providerId, $providerUserId)
    {
        $query = "SELECT oa.*, u.* FROM oauth_accounts oa 
                  JOIN users u ON oa.user_id = u.id 
                  WHERE oa.provider_id = ? AND oa.provider_user_id = ?";
        $this->query($query);
        $this->bind("is", $providerId, $providerUserId);
        $result = $this->resultSet();
        return $result && count($result) > 0 ? $result[0] : null;
    }

    public function createAccount($data)
    {
        $query = "INSERT INTO oauth_accounts 
                  (user_id, provider_id, provider_user_id, access_token, refresh_token, token_expires_at) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        
        $this->query($query);
        $this->bind("iissss", 
            $data['user_id'],
            $data['provider_id'],
            $data['provider_user_id'],
            $data['access_token'],
            $data['refresh_token'] ?? null,
            $data['token_expires_at'] ?? null
        );

        return $this->execute();
    }

    public function updateAccountTokens($accountId, $accessToken, $refreshToken = null, $expiresAt = null)
    {
        $query = "UPDATE oauth_accounts 
                  SET access_token = ?, refresh_token = ?, token_expires_at = ?, updated_at = NOW() 
                  WHERE id = ?";
        $this->query($query);
        $this->bind("sssi", $accessToken, $refreshToken, $expiresAt, $accountId);
        return $this->execute();
    }

    public function getUserAccounts($userId)
    {
        $query = "SELECT oa.*, op.name as provider_name 
                  FROM oauth_accounts oa 
                  JOIN oauth_providers op ON oa.provider_id = op.id 
                  WHERE oa.user_id = ?";
        $this->query($query);
        $this->bind("i", $userId);
        return $this->resultSet();
    }

    public function unlinkProvider($userId, $providerId)
    {
        $this->query("DELETE FROM oauth_accounts WHERE user_id = ? AND provider_id = ?");
        $this->bind("ii", $userId, $providerId);
        return $this->execute();
    }
}
