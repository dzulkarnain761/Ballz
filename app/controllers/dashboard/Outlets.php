<?php

class Outlets extends Dashboard
{
   

    public function index()
    {
        $outletModel = new OutletModel();
        $perPage = 10;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $totalItems = $outletModel->getTotal();
        $totalPages = max(1, ceil($totalItems / $perPage));
        $page = min($page, $totalPages);

        $data['outlets'] = $outletModel->getPaginated($page, $perPage);
        $data['pagination'] = [
            'page' => $page,
            'perPage' => $perPage,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'baseUrl' => ROOT . '/dashboard/outlets'
        ];
        $data['tab'] = 'outlets';
        $this->view('dashboard/index', $data);
    }

    public function add()
    {
        $this->guardGuest();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $outletModel = new OutletModel();
            $data = [
                'code' => $_POST['code'],
                'name' => $_POST['name'],
                'address' => $_POST['address'],
                'city' => $_POST['city'],
                'state' => $_POST['state'],
                'phone' => $_POST['phone'],
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            if ($outletModel->create($data)) {
                $this->returnWithSuccess('Success', 'Outlet added successfully', '/dashboard/outlets');
            } else {
                $this->returnWithErr('Error', 'Failed to add outlet');
            }
        }
    }

    public function edit($id)
    {
        $this->guardGuest();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $outletModel = new OutletModel();
            $data = [
                'code' => $_POST['code'],
                'name' => $_POST['name'],
                'address' => $_POST['address'],
                'city' => $_POST['city'],
                'state' => $_POST['state'],
                'phone' => $_POST['phone'],
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            if ($outletModel->update($id, $data)) {
                $this->returnWithSuccess('Success', 'Outlet updated successfully', '/dashboard/outlets');
            } else {
                $this->returnWithErr('Error', 'Failed to update outlet');
            }
        }
        header('Location: ' . ROOT . '/dashboard/outlets');
        exit;
    }

    public function delete($id)
    {
        $this->guardGuest();
        $outletModel = new OutletModel();
        if ($outletModel->delete($id)) {
            $this->returnWithSuccess('Success', 'Outlet deleted successfully', '/dashboard/outlets');
        } else {
            $this->returnWithErr('Error', 'Failed to delete outlet');
        }
    }
}
