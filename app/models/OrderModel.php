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

    public function getTotal()
    {
        $this->query("SELECT COUNT(*) as total FROM orders");
        $result = $this->single();
        return $result['total'];
    }

    public function getPaginated($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $this->query("SELECT o.*, u.name as user_name, ot.name as outlet_name 
                      FROM orders o 
                      LEFT JOIN users u ON o.user_id = u.id 
                      JOIN outlets ot ON o.outlet_id = ot.id 
                      ORDER BY o.created_at DESC LIMIT ? OFFSET ?");
        $this->bind("ii", $perPage, $offset);
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

    /**
     * Get orders by user ID
     * 
     * @param int $userId
     * @return array
     */
    public function getByUserId($userId)
    {
        $this->query("SELECT o.*, ot.name as outlet_name, ot.code as outlet_code
                      FROM orders o 
                      JOIN outlets ot ON o.outlet_id = ot.id 
                      WHERE o.user_id = ?
                      ORDER BY o.created_at DESC");
        $this->bind("i", $userId);
        return $this->resultSet();
    }

    /**
     * Get single order by ID
     * 
     * @param int $id
     * @return array|null
     */
    public function getById($id)
    {
        $this->query("SELECT o.*, ot.name as outlet_name, ot.code as outlet_code
                      FROM orders o 
                      JOIN outlets ot ON o.outlet_id = ot.id 
                      WHERE o.id = ?");
        $this->bind("i", $id);
        $result = $this->resultSet();
        return $result && count($result) > 0 ? $result[0] : null;
    }

    /**
     * Get order items for a specific order
     * 
     * @param int $orderId
     * @return array
     */
    public function getOrderItems($orderId)
    {
        $this->query("SELECT oi.*, mi.name as item_name, mi.description as item_description, 
                             mc.name as category_name
                      FROM order_items oi
                      JOIN menu_items mi ON oi.menu_item_id = mi.id
                      JOIN menu_categories mc ON mi.category_id = mc.id
                      WHERE oi.order_id = ?");
        $this->bind("i", $orderId);
        return $this->resultSet();
    }

    /**
     * Get vouchers applied to an order
     * 
     * @param int $orderId
     * @return array
     */
    public function getOrderVouchers($orderId)
    {
        $this->query("SELECT ov.*, v.code as voucher_code, v.name as voucher_name, 
                             v.discount_type, v.discount_value
                      FROM order_vouchers ov
                      JOIN vouchers v ON ov.voucher_id = v.id
                      WHERE ov.order_id = ?");
        $this->bind("i", $orderId);
        return $this->resultSet();
    }

    /**
     * Get full order details including items and vouchers
     * 
     * @param int $orderId
     * @return array|null
     */
    public function getOrderWithDetails($orderId)
    {
        $order = $this->getById($orderId);
        
        if (!$order) {
            return null;
        }

        $order['items'] = $this->getOrderItems($orderId);
        $order['vouchers'] = $this->getOrderVouchers($orderId);

        return $order;
    }

    /**
     * Get all orders for a user with full details
     * 
     * @param int $userId
     * @return array
     */
    public function getUserOrdersWithDetails($userId)
    {
        $orders = $this->getByUserId($userId);

        foreach ($orders as &$order) {
            $order['items'] = $this->getOrderItems($order['id']);
            $order['vouchers'] = $this->getOrderVouchers($order['id']);
        }

        return $orders;
    }

    /**
     * Create a new order
     * 
     * @param array $data Order data (user_id, outlet_id, order_type, subtotal, discount_total, final_total, status)
     * @return int|false The new order ID or false on failure
     */
    public function create($data)
    {
        $this->query("INSERT INTO orders (user_id, outlet_id, order_type, subtotal, discount_total, final_total, status) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)");
        $this->bind(
            "iisddds",
            $data['user_id'],
            $data['outlet_id'],
            $data['order_type'],
            $data['subtotal'],
            $data['discount_total'],
            $data['final_total'],
            $data['status']
        );
        if ($this->execute()) {
            return $this->getLastInsertId();
        }
        return false;
    }

    /**
     * Add an item to an order
     * 
     * @param array $data (order_id, menu_item_id, quantity, unit_price, total_price)
     * @return bool
     */
    public function addOrderItem($data)
    {
        $this->query("INSERT INTO order_items (order_id, menu_item_id, quantity, unit_price, total_price) 
                      VALUES (?, ?, ?, ?, ?)");
        $this->bind(
            "iiidd",
            $data['order_id'],
            $data['menu_item_id'],
            $data['quantity'],
            $data['unit_price'],
            $data['total_price']
        );
        return $this->execute();
    }

    /**
     * Add a voucher to an order
     * 
     * @param array $data (order_id, voucher_id, discount_applied)
     * @return bool
     */
    public function addOrderVoucher($data)
    {
        $this->query("INSERT INTO order_vouchers (order_id, voucher_id, discount_applied) 
                      VALUES (?, ?, ?)");
        $this->bind(
            "iid",
            $data['order_id'],
            $data['voucher_id'],
            $data['discount_applied']
        );
        return $this->execute();
    }

    /**
     * Add a reward transaction
     * 
     * @param array $data (user_id, order_id, points, type)
     * @return bool
     */
    public function addRewardTransaction($data)
    {
        $this->query("INSERT INTO reward_transactions (user_id, order_id, points, type) 
                      VALUES (?, ?, ?, ?)");
        $this->bind(
            "iiis",
            $data['user_id'],
            $data['order_id'],
            $data['points'],
            $data['type']
        );
        return $this->execute();
    }
}
