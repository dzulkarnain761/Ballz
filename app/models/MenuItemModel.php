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
        $this->query("SELECT m.*, c.name as category_name, 
            COALESCE(NULLIF(m.img_path, ''), 'placeholder_food.png') as img_path 
            FROM menu_items m 
            JOIN menu_categories c ON m.category_id = c.id 
            ORDER BY m.id DESC");
        return $this->resultSet();
    }

    public function getTotal()
    {
        $this->query("SELECT COUNT(*) as total FROM menu_items");
        $result = $this->single();
        return $result['total'];
    }

    public function getPaginated($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $this->query("SELECT m.*, c.name as category_name, 
            COALESCE(NULLIF(m.img_path, ''), 'placeholder_food.png') as img_path 
            FROM menu_items m 
            JOIN menu_categories c ON m.category_id = c.id 
            ORDER BY m.id DESC LIMIT ? OFFSET ?");
        $this->bind("ii", $perPage, $offset);
        return $this->resultSet();
    }

    public function getById($id)
    {
        $this->query("SELECT m.*, c.name as category_name,
        COALESCE(NULLIF(m.img_path, ''), 'placeholder_food.png') as img_path FROM menu_items m JOIN menu_categories c ON m.category_id = c.id WHERE m.id = ?");
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
