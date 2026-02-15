<?php

class VoucherModel
{
    use Database;

    public function __construct()
    {
        $this->connect();
    }

    public function getAll()
    {
        $this->query("SELECT * FROM vouchers ORDER BY id DESC");
        return $this->resultSet();
    }

    public function getTotal()
    {
        $this->query("SELECT COUNT(*) as total FROM vouchers");
        $result = $this->single();
        return $result['total'];
    }

    public function getPaginated($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $this->query("SELECT * FROM vouchers ORDER BY id DESC LIMIT ? OFFSET ?");
        $this->bind("ii", $perPage, $offset);
        return $this->resultSet();
    }

    public function getById($id)
    {
        $this->query("SELECT * FROM vouchers WHERE id = ?");
        $this->bind("i", $id);
        return $this->single();
    }

    public function getRulesByVoucherId($voucherId)
    {
        $this->query("SELECT vr.*, mc.name as category_name 
                      FROM voucher_rules vr 
                      LEFT JOIN menu_categories mc ON vr.category_id = mc.id 
                      WHERE vr.voucher_id = ?");
        $this->bind("i", $voucherId);
        return $this->resultSet();
    }

    public function create($data)
    {
        $this->query("INSERT INTO vouchers (code, name, description, discount_type, discount_value, min_order_amount, start_date, end_date, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $this->bind("ssssddssi", $data['code'], $data['name'], $data['description'], $data['discount_type'], $data['discount_value'], $data['min_order_amount'], $data['start_date'], $data['end_date'], $data['is_active']);
        return $this->execute();
    }

    public function update($id, $data)
    {
        $this->query("UPDATE vouchers SET code = ?, name = ?, description = ?, discount_type = ?, discount_value = ?, min_order_amount = ?, start_date = ?, end_date = ?, is_active = ? WHERE id = ?");
        $this->bind("ssssddssii", $data['code'], $data['name'], $data['description'], $data['discount_type'], $data['discount_value'], $data['min_order_amount'], $data['start_date'], $data['end_date'], $data['is_active'], $id);
        return $this->execute();
    }

    public function delete($id)
    {
        return $this->deleteById('vouchers', $id);
    }

    /**
     * Get a voucher by its code
     * 
     * @param string $code
     * @return array|null
     */
    public function getByCode($code)
    {
        $this->query("SELECT * FROM vouchers WHERE code = ?");
        $this->bind("s", $code);
        return $this->single();
    }

    /**
     * Validate a voucher for use in an order
     * Checks: exists, active, within date range, meets min order amount
     * 
     * @param string $code Voucher code
     * @param float $subtotal Order subtotal
     * @return array ['valid' => bool, 'voucher' => array|null, 'error' => string|null]
     */
    public function validateForOrder($code, $subtotal)
    {
        $voucher = $this->getByCode($code);

        if (!$voucher) {
            return ['valid' => false, 'voucher' => null, 'error' => 'Voucher not found'];
        }

        if (!$voucher['is_active']) {
            return ['valid' => false, 'voucher' => $voucher, 'error' => 'Voucher is inactive'];
        }

        $today = date('Y-m-d');
        if ($voucher['start_date'] && $today < $voucher['start_date']) {
            return ['valid' => false, 'voucher' => $voucher, 'error' => 'Voucher is not yet valid'];
        }
        if ($voucher['end_date'] && $today > $voucher['end_date']) {
            return ['valid' => false, 'voucher' => $voucher, 'error' => 'Voucher has expired'];
        }

        if ($voucher['min_order_amount'] && $subtotal < $voucher['min_order_amount']) {
            return ['valid' => false, 'voucher' => $voucher, 'error' => 'Minimum order amount of RM' . number_format($voucher['min_order_amount'], 2) . ' not met'];
        }

        return ['valid' => true, 'voucher' => $voucher, 'error' => null];
    }

    /**
     * Calculate the discount amount for a voucher given a subtotal
     * 
     * @param array $voucher
     * @param float $subtotal
     * @return float
     */
    public function calculateDiscount($voucher, $subtotal)
    {
        if ($voucher['discount_type'] === 'fixed') {
            return min($voucher['discount_value'], $subtotal);
        } elseif ($voucher['discount_type'] === 'percentage') {
            return round($subtotal * ($voucher['discount_value'] / 100), 2);
        }
        return 0;
    }
}
