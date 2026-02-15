<?php

class Vouchers extends Dashboard
{

    public function index()
    {
        $voucherModel = new VoucherModel();
        $data['vouchers'] = $voucherModel->getAll();
        $data['tab'] = 'vouchers';
        $this->view('dashboard/index', $data);
    }

    public function add()
    {
        $this->guardGuest();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $voucherModel = new VoucherModel();
            $data = [
                'code' => $_POST['code'],
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'discount_type' => $_POST['discount_type'],
                'discount_value' => $_POST['discount_value'],
                'min_order_amount' => $_POST['min_order_amount'],
                'start_date' => $_POST['start_date'] ?: null,
                'end_date' => $_POST['end_date'] ?: null,
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            if ($voucherModel->create($data)) {
                $this->returnWithSuccess('Success', 'Voucher added successfully', '/dashboard/vouchers');
            } else {
                $this->returnWithErr('Error', 'Failed to add voucher');
            }
        }
    }

    public function edit($id)
    {
        $this->guardGuest();
        $voucherModel = new VoucherModel();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'code' => $_POST['code'],
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'discount_type' => $_POST['discount_type'],
                'discount_value' => $_POST['discount_value'],
                'min_order_amount' => $_POST['min_order_amount'],
                'start_date' => $_POST['start_date'] ?: null,
                'end_date' => $_POST['end_date'] ?: null,
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            if ($voucherModel->update($id, $data)) {
                $this->returnWithSuccess('Success', 'Voucher updated successfully', '/dashboard/vouchers');
            } else {
                $this->returnWithErr('Error', 'Failed to update voucher');
            }
        }
        $data['voucher'] = $voucherModel->findById('vouchers', $id);
        $data['tab'] = 'voucher_edit';
        $this->view('dashboard/index', $data);
    }

    public function delete($id)
    {
        $this->guardGuest();
        $voucherModel = new VoucherModel();
        if ($voucherModel->delete($id)) {
            $this->returnWithSuccess('Success', 'Voucher deleted successfully', '/dashboard/vouchers');
        } else {
            $this->returnWithErr('Error', 'Failed to delete voucher');
        }
    }
}
