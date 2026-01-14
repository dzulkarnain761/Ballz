<?php

class MenuImageModel
{
    use Database;

    public function __construct()
    {
        $this->connect();
    }

    public function getAll()
    {
        $this->query("SELECT mi.*, m.name as item_name 
                      FROM menu_images mi 
                      JOIN menu_items m ON mi.menu_item_id = m.id 
                      ORDER BY mi.id DESC");
        return $this->resultSet();
    }

    public function create($data)
    {
        $this->query("INSERT INTO menu_images (menu_item_id, image_url, alt_text, is_primary, sort_order) VALUES (?, ?, ?, ?, ?)");
        $this->bind("issii", $data['menu_item_id'], $data['image_url'], $data['alt_text'], $data['is_primary'], $data['sort_order']);
        return $this->execute();
    }

    public function update($id, $data)
    {
        $this->query("UPDATE menu_images SET menu_item_id = ?, image_url = ?, alt_text = ?, is_primary = ?, sort_order = ? WHERE id = ?");
        $this->bind("issiii", $data['menu_item_id'], $data['image_url'], $data['alt_text'], $data['is_primary'], $data['sort_order'], $id);
        return $this->execute();
    }

    public function delete($id)
    {
        return $this->deleteById('menu_images', $id);
    }
}
