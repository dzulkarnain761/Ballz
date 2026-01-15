<?php


class UserModel
{
    use Database;

    public function getAll()
    {
        $this->query("SELECT * FROM users ORDER BY created_at DESC");
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
}
