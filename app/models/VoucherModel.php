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
}
