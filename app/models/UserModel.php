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
        $res = $this->query("SELECT * FROM users WHERE id = ?", [$id]);
        return $res ? $res[0] : null;
    }

    public function create($data)
    {
        $query = "INSERT INTO users (name, email, phone, reward_points, status) VALUES (?, ?, ?, ?, ?)";
        $this->query($query);
        $this->bind("sssii", $data['name'], $data['email'], $data['phone'], $data['reward_points'], $data['status']);
        return $this->execute();
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
