<?php

class Orders extends Dashboard
{

    public function index()
    {
        $orderModel = new OrderModel();
        $data['orders'] = $orderModel->getAll();
        $data['tab'] = 'orders';
        $this->view('dashboard/index', $data);
    }

    public function status($id)
    {
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
