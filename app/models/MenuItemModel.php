<?php

class MenuItemModel
{
    use Database;

    public function __construct()
    {
        $this->connect();
    }

    public function getAll()
    {
        $this->query("SELECT m.*, c.name as category_name FROM menu_items m JOIN menu_categories c ON m.category_id = c.id ORDER BY m.id DESC");
        return $this->resultSet();
    }

    public function getById($id)
    {
        $this->query("SELECT m.*, c.name as category_name FROM menu_items m JOIN menu_categories c ON m.category_id = c.id WHERE m.id = ?");
        $this->bind("i", $id);
        return $this->single();
    }

    public function create($data)
    {
        $this->query("INSERT INTO menu_items (category_id, name, description, price, is_active) VALUES (?, ?, ?, ?, ?)");
        $this->bind("issdi", $data['category_id'], $data['name'], $data['description'], $data['price'], $data['is_active']);
        return $this->execute();
    }

    public function update($id, $data)
    {
        $this->query("UPDATE menu_items SET category_id = ?, name = ?, description = ?, price = ?, is_active = ? WHERE id = ?");
        $this->bind("issdii", $data['category_id'], $data['name'], $data['description'], $data['price'], $data['is_active'], $id);
        return $this->execute();
    }

    public function delete($id)
    {
        return $this->deleteById('menu_items', $id);
    }
}
