<?php

class Vouchers extends Dashboard
{

    public function index()
    {
        $voucherModel = new VoucherModel();
        $perPage = 10;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $totalItems = $voucherModel->getTotal();
        $totalPages = max(1, ceil($totalItems / $perPage));
        $page = min($page, $totalPages);

        $data['vouchers'] = $voucherModel->getPaginated($page, $perPage);
        $data['pagination'] = [
            'page' => $page,
            'perPage' => $perPage,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'baseUrl' => ROOT . '/dashboard/vouchers'
        ];
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
            if ($voucherModel->update($id, $data)) {
                $this->returnWithSuccess('Success', 'Voucher updated successfully', '/dashboard/vouchers');
            } else {
                $this->returnWithErr('Error', 'Failed to update voucher');
            }
        }
        header('Location: ' . ROOT . '/dashboard/vouchers');
        exit;
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
