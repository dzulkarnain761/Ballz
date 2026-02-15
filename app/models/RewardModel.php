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

    // ─── Reward Transactions ───

    /**
     * Get all reward transactions (most recent first)
     * 
     * @return array
     */
    public function getAllTransactions()
    {
        $this->query("SELECT rt.*, u.name as user_name, u.email as user_email
                      FROM reward_transactions rt
                      JOIN users u ON rt.user_id = u.id
                      ORDER BY rt.created_at DESC");
        return $this->resultSet();
    }

    /**
     * Get a specific reward transaction by ID
     * 
     * @param int $id
     * @return array|null
     */
    public function getTransactionById($id)
    {
        $this->query("SELECT rt.*, u.name as user_name, u.email as user_email
                      FROM reward_transactions rt
                      JOIN users u ON rt.user_id = u.id
                      WHERE rt.id = ?");
        $this->bind("i", $id);
        $result = $this->resultSet();
        return $result && count($result) > 0 ? $result[0] : null;
    }

    /**
     * Get all reward transactions for a specific user
     * 
     * @param int $userId
     * @return array
     */
    public function getTransactionsByUserId($userId)
    {
        $this->query("SELECT rt.*
                      FROM reward_transactions rt
                      WHERE rt.user_id = ?
                      ORDER BY rt.created_at DESC");
        $this->bind("i", $userId);
        return $this->resultSet();
    }

    /**
     * Create a reward transaction (earn or redeem)
     * 
     * @param array $data (user_id, order_id, points, type)
     * @return int|false The new transaction ID or false on failure
     */
    public function createTransaction($data)
    {
        $this->query("INSERT INTO reward_transactions (user_id, order_id, points, type) 
                      VALUES (?, ?, ?, ?)");
        $this->bind(
            "iiis",
            $data['user_id'],
            $data['order_id'] ?? null,
            $data['points'],
            $data['type']
        );
        if ($this->execute()) {
            return $this->getLastInsertId();
        }
        return false;
    }
}
