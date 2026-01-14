<?php

class OutletModel
{
    use Database;

    public function __construct()
    {
        $this->connect();
    }

    public function getAll()
    {
        $this->query("SELECT * FROM outlets ORDER BY name ASC");
        return $this->resultSet();
    }

    public function create($data)
    {
        $this->query("INSERT INTO outlets (code, name, address, city, state, phone, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $this->bind("ssssssi", $data['code'], $data['name'], $data['address'], $data['city'], $data['state'], $data['phone'], $data['is_active']);
        return $this->execute();
    }

    public function update($id, $data)
    {
        $this->query("UPDATE outlets SET code = ?, name = ?, address = ?, city = ?, state = ?, phone = ?, is_active = ? WHERE id = ?");
        $this->bind("ssssssii", $data['code'], $data['name'], $data['address'], $data['city'], $data['state'], $data['phone'], $data['is_active'], $id);
        return $this->execute();
    }

    public function delete($id)
    {
        return $this->deleteById('outlets', $id);
    }
}
