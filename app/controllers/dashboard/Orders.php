<?php

class Orders extends Dashboard
{

    public function index()
    {
        $orderModel = new OrderModel();
        $perPage = 10;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $totalItems = $orderModel->getTotal();
        $totalPages = max(1, ceil($totalItems / $perPage));
        $page = min($page, $totalPages);

        $data['orders'] = $orderModel->getPaginated($page, $perPage);
        $data['pagination'] = [
            'page' => $page,
            'perPage' => $perPage,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'baseUrl' => ROOT . '/dashboard/orders'
        ];
        $data['tab'] = 'orders';
        $this->view('dashboard/index', $data);
    }

    public function status($id)
    {
        $this->guardGuest();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $orderModel = new OrderModel();
            $status = $_POST['status'];
            if ($orderModel->updateStatus($id, $status)) {
                $this->returnWithSuccess('Success', 'Order status updated', '/dashboard/orders');
            } else {
                $this->returnWithErr('Error', 'Failed to update status');
            }
        }
    }
}
