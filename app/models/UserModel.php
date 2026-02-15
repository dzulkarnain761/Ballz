<?php


class UserModel
{
    use Database;

    public function getAll()
    {
        $this->query("SELECT * FROM users ORDER BY created_at DESC");
        return $this->resultSet();
    }

    public function getTotal()
    {
        $this->query("SELECT COUNT(*) as total FROM users");
        $result = $this->single();
        return $result['total'];
    }

    public function getPaginated($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $this->query("SELECT * FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $this->bind("ii", $perPage, $offset);
        return $this->resultSet();
    }

    public function getOne($id)
    {
        $this->query("SELECT * FROM users WHERE id = ?");
        $this->bind("i", $id);
        $result = $this->resultSet();
        return $result && count($result) > 0 ? $result[0] : null;
    }

    public function getByEmail($email)
    {
        $this->query("SELECT * FROM users WHERE email = ?");
        $this->bind("s", $email);
        $result = $this->resultSet();
        return $result && count($result) > 0 ? $result[0] : null;
    }

    public function create($data)
    {
        $query = "INSERT INTO users (name, email, phone, reward_points, status) VALUES (?, ?, ?, ?, ?)";
        $this->query($query);
        $this->bind("sssii", $data['name'], $data['email'], $data['phone'], $data['reward_points'], $data['status']);
        return $this->execute();
    }

    public function createFromOAuth($data)
    {
        if (!empty($data['email'])) {
            $existing = $this->getByEmail($data['email']);
            if ($existing) {
                return $existing['id'];
            }
        }

        $query = "INSERT INTO users (name, email, phone, reward_points, status) VALUES (?, ?, ?, ?, ?)";
        $this->query($query);
        $this->bind("sssii", 
            $data['name'],
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['reward_points'] ?? 0,
            $data['status'] ?? 'active'
        );

        return $this->execute() ? $this->getLastInsertId() : false;
    }

    public function update($id, $data)
    {
        $query = "UPDATE users SET name = ?, email = ?, phone = ?, reward_points = ?, status = ? WHERE id = ?";
        $this->query($query);
        $this->bind("sssisi", $data['name'], $data['email'], $data['phone'], $data['reward_points'], $data['status'], $id);
        return $this->execute();
    }

    public function delete($id)
    {
        $this->query("DELETE FROM users WHERE id = ?");
        $this->bind("i", $id);
        return $this->execute();
    }

    /**
     * Find user by OAuth provider identity
     * 
     * @param string $providerName Provider name (google, facebook)
     * @param string $providerUserId User ID from the provider
     * @return array|null User data if found
     */
    public function findByProviderIdentity($providerName, $providerUserId)
    {
        $query = "SELECT u.* FROM users u 
                  INNER JOIN user_identities ui ON u.id = ui.user_id 
                  WHERE ui.provider_name = ? AND ui.provider_user_id = ?";
        $this->query($query);
        $this->bind("ss", $providerName, $providerUserId);
        $result = $this->resultSet();
        return $result && count($result) > 0 ? $result[0] : null;
    }

    /**
     * Create user identity link
     * 
     * @param int $userId User ID
     * @param string $providerName Provider name (google, facebook)
     * @param string $providerUserId User ID from the provider
     * @return bool
     */
    public function createIdentity($userId, $providerName, $providerUserId)
    {
        $query = "INSERT INTO user_identities (user_id, provider_name, provider_user_id) VALUES (?, ?, ?)";
        $this->query($query);
        $this->bind("iss", $userId, $providerName, $providerUserId);
        return $this->execute();
    }

    /**
     * Check if user identity exists
     * 
     * @param string $providerName Provider name
     * @param string $providerUserId Provider user ID
     * @return bool
     */
    public function identityExists($providerName, $providerUserId)
    {
        $query = "SELECT id FROM user_identities WHERE provider_name = ? AND provider_user_id = ?";
        $this->query($query);
        $this->bind("ss", $providerName, $providerUserId);
        $result = $this->resultSet();
        return $result && count($result) > 0;
    }

    /**
     * Register user from OAuth and link identity
     * Returns existing user if email matches, otherwise creates new user
     * 
     * @param array $userData User data from OAuth provider
     * @param string $providerName Provider name (google, facebook)
     * @param string $providerUserId User ID from the provider
     * @return array|false User data on success, false on failure
     */
    public function registerFromProvider($userData, $providerName, $providerUserId)
    {
        // Check if identity already exists
        $existingUser = $this->findByProviderIdentity($providerName, $providerUserId);
        if ($existingUser) {
            return $existingUser;
        }

        // Check if user with same email exists
        if (!empty($userData['email'])) {
            $existingByEmail = $this->getByEmail($userData['email']);
            if ($existingByEmail) {
                // Link this provider to existing account
                $this->createIdentity($existingByEmail['id'], $providerName, $providerUserId);
                return $existingByEmail;
            }
        }

        // Create new user
        $query = "INSERT INTO users (name, email, phone, reward_points, status) VALUES (?, ?, ?, 0, 'active')";
        $this->query($query);
        $this->bind("sss", 
            $userData['name'],
            $userData['email'] ?? null,
            $userData['phone'] ?? null
        );

        if (!$this->execute()) {
            return false;
        }

        $userId = $this->getLastInsertId();

        // Create identity link
        $this->createIdentity($userId, $providerName, $providerUserId);

        // Return the newly created user
        return $this->getOne($userId);
    }
}
