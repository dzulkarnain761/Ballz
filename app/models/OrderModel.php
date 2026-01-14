<?php

class OrderModel
{
    use Database;

    public function __construct()
    {
        $this->connect();
    }

    public function getAll()
    {
        $this->query("SELECT o.*, u.name as user_name, ot.name as outlet_name 
                      FROM orders o 
                      LEFT JOIN users u ON o.user_id = u.id 
                      JOIN outlets ot ON o.outlet_id = ot.id 
                      ORDER BY o.created_at DESC");
        return $this->resultSet();
    }

    public function updateStatus($id, $status)
    {
        $this->query("UPDATE orders SET status = ? WHERE id = ?");
        $this->bind("si", $status, $id);
        return $this->execute();
    }

    public function delete($id)
    {
        return $this->deleteById('orders', $id);
    }
}
