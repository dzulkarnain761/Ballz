<?php

class RewardModel
{
    use Database;

    public function __construct()
    {
        $this->connect();
    }

    /**
     * Get all reward items with their associated menu item details
     * 
     * @return array
     */
    public function getAll()
    {
        $this->query("SELECT ri.*, mi.name as item_name, mi.description as item_description, 
                             mi.price as item_price, mi.img_path as item_img_path, 
                             mc.name as category_name
                      FROM reward_items ri
                      LEFT JOIN menu_items mi ON ri.menu_items_id = mi.id
                      LEFT JOIN menu_categories mc ON mi.category_id = mc.id
                      WHERE mi.is_active = 1
                      ORDER BY ri.required_points ASC");
        return $this->resultSet();
    }

    /**
     * Get a specific reward item by ID with menu item details
     * 
     * @param int $id
     * @return object|null
     */
    public function getById($id)
    {
        $this->query("SELECT ri.*, mi.name as item_name, mi.description as item_description, 
                             mi.price as item_price, mi.img_path as item_img_path,
                             mc.name as category_name
                      FROM reward_items ri
                      LEFT JOIN menu_items mi ON ri.menu_items_id = mi.id
                      LEFT JOIN menu_categories mc ON mi.category_id = mc.id
                      WHERE ri.id = ?");
        $this->bind("i", $id);
        return $this->single();
    }

    /**
     * Get reward item by menu item ID
     * 
     * @param int $menuItemId
     * @return object|null
     */
    public function getByMenuItemId($menuItemId)
    {
        $this->query("SELECT ri.*, mi.name as item_name, mi.description as item_description, 
                             mi.price as item_price, mi.img_path as item_img_path,
                             mc.name as category_name
                      FROM reward_items ri
                      LEFT JOIN menu_items mi ON ri.menu_items_id = mi.id
                      LEFT JOIN menu_categories mc ON mi.category_id = mc.id
                      WHERE ri.menu_items_id = ?");
        $this->bind("i", $menuItemId);
        return $this->single();
    }

    /**
     * Create a new reward item
     * 
     * @param array $data
     * @return bool
     */
    public function create($data)
    {
        $this->query("INSERT INTO reward_items (menu_items_id, required_points) VALUES (?, ?)");
        $this->bind("ii", $data['menu_items_id'], $data['required_points']);
        return $this->execute();
    }

    /**
     * Update a reward item
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $this->query("UPDATE reward_items SET menu_items_id = ?, required_points = ? WHERE id = ?");
        $this->bind("iii", $data['menu_items_id'], $data['required_points'], $id);
        return $this->execute();
    }

    /**
     * Delete a reward item
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->deleteById('reward_items', $id);
    }
}
