<?php

class PaymentModel
{
    use Database;

    public function __construct()
    {
        $this->connect();
    }

    /**
     * Create a new payment record
     * 
     * @param array $data (order_id, user_id, amount, payment_method, payment_ref)
     * @return int|false The new payment ID or false on failure
     */
    public function create($data)
    {
        $this->query("INSERT INTO payments (order_id, user_id, amount, payment_method, payment_ref, status, bank_code, expires_at) 
                      VALUES (?, ?, ?, ?, ?, 'pending', ?, ?)");
        $this->bind(
            "iidsss" . "s",
            $data['order_id'],
            $data['user_id'],
            $data['amount'],
            $data['payment_method'],
            $data['payment_ref'],
            $data['bank_code'] ?? null,
            $data['expires_at']
        );
        if ($this->execute()) {
            return $this->getLastInsertId();
        }
        return false;
    }

    /**
     * Get payment by ID
     * 
     * @param int $id
     * @return array|null
     */
    public function getById($id)
    {
        $this->query("SELECT p.*, o.order_type, o.final_total as order_total, o.status as order_status
                      FROM payments p
                      JOIN orders o ON p.order_id = o.id
                      WHERE p.id = ?");
        $this->bind("i", $id);
        $result = $this->resultSet();
        return $result && count($result) > 0 ? $result[0] : null;
    }

    /**
     * Get payment by payment reference
     * 
     * @param string $paymentRef
     * @return array|null
     */
    public function getByRef($paymentRef)
    {
        $this->query("SELECT p.*, o.order_type, o.final_total as order_total, o.status as order_status
                      FROM payments p
                      JOIN orders o ON p.order_id = o.id
                      WHERE p.payment_ref = ?");
        $this->bind("s", $paymentRef);
        $result = $this->resultSet();
        return $result && count($result) > 0 ? $result[0] : null;
    }

    /**
     * Get payment by order ID
     * 
     * @param int $orderId
     * @return array|null
     */
    public function getByOrderId($orderId)
    {
        $this->query("SELECT * FROM payments WHERE order_id = ? ORDER BY created_at DESC LIMIT 1");
        $this->bind("i", $orderId);
        $result = $this->resultSet();
        return $result && count($result) > 0 ? $result[0] : null;
    }

    /**
     * Update payment status
     * 
     * @param int $id
     * @param string $status (pending, processing, success, failed, expired)
     * @param string|null $gatewayResponse Optional response from gateway
     * @return bool
     */
    public function updateStatus($id, $status, $gatewayResponse = null)
    {
        if ($gatewayResponse) {
            $this->query("UPDATE payments SET status = ?, gateway_response = ?, updated_at = NOW() WHERE id = ?");
            $this->bind("ssi", $status, $gatewayResponse, $id);
        } else {
            $this->query("UPDATE payments SET status = ?, updated_at = NOW() WHERE id = ?");
            $this->bind("si", $status, $id);
        }
        return $this->execute();
    }

    /**
     * Get payments by user ID
     * 
     * @param int $userId
     * @return array
     */
    public function getByUserId($userId)
    {
        $this->query("SELECT p.*, o.order_type, o.final_total as order_total
                      FROM payments p
                      JOIN orders o ON p.order_id = o.id
                      WHERE p.user_id = ?
                      ORDER BY p.created_at DESC");
        $this->bind("i", $userId);
        return $this->resultSet();
    }

    /**
     * Check if order already has a successful payment
     * 
     * @param int $orderId
     * @return bool
     */
    public function hasSuccessfulPayment($orderId)
    {
        $this->query("SELECT COUNT(*) as total FROM payments WHERE order_id = ? AND status = 'success'");
        $this->bind("i", $orderId);
        $result = $this->single();
        return $result['total'] > 0;
    }

    /**
     * Check if order has a pending/processing payment
     * 
     * @param int $orderId
     * @return array|null Returns the active payment or null
     */
    public function getActivePayment($orderId)
    {
        $this->query("SELECT * FROM payments WHERE order_id = ? AND status IN ('pending', 'processing') ORDER BY created_at DESC LIMIT 1");
        $this->bind("i", $orderId);
        $result = $this->resultSet();
        return $result && count($result) > 0 ? $result[0] : null;
    }

    /**
     * Expire payments that have exceeded their expiry time
     * 
     * @return int Number of expired payments
     */
    public function expireOldPayments()
    {
        $this->query("UPDATE payments SET status = 'expired', updated_at = NOW() 
                      WHERE status IN ('pending', 'processing') AND expires_at < NOW()");
        $this->execute();
        return $this->rowCount();
    }

    /**
     * Get all payments (for admin)
     * 
     * @return array
     */
    public function getAll()
    {
        $this->query("SELECT p.*, u.name as user_name, o.order_type
                      FROM payments p
                      LEFT JOIN users u ON p.user_id = u.id
                      JOIN orders o ON p.order_id = o.id
                      ORDER BY p.created_at DESC");
        return $this->resultSet();
    }

    /**
     * Generate a unique payment reference
     * Format: BZ-{METHOD}-{TIMESTAMP}-{RANDOM}
     * 
     * @param string $method Payment method (fpx/duitnow)
     * @return string
     */
    public static function generatePaymentRef($method)
    {
        $prefix = strtoupper($method) === 'DUITNOW' ? 'DN' : 'FPX';
        return 'BZ-' . $prefix . '-' . date('YmdHis') . '-' . strtoupper(bin2hex(random_bytes(4)));
    }
}
