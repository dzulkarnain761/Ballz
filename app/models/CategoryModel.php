<?php

class CategoryModel
{
    use Database;

    public function __construct()
    {
        $this->connect();
    }

    public function getAll()
    {
        $this->query("SELECT * FROM menu_categories ORDER BY id DESC");
        return $this->resultSet();
    }

    public function create($data)
    {
        $this->query("INSERT INTO menu_categories (name, description) VALUES (?, ?)");
        $this->bind("ss", $data['name'], $data['description']);
        return $this->execute();
    }

    public function update($id, $data)
    {
        $this->query("UPDATE menu_categories SET name = ?, description = ? WHERE id = ?");
        $this->bind("ssi", $data['name'], $data['description'], $id);
        return $this->execute();
    }

    public function delete($id)
    {
        return $this->deleteById('menu_categories', $id);
    }
}
