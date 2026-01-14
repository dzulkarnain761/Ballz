<?php

class Outlets extends Dashboard
{
   

    public function index()
    {
        $outletModel = new OutletModel();
        $data['outlets'] = $outletModel->getAll();
        $data['tab'] = 'outlets';
        $this->view('dashboard/index', $data);
    }

    public function add()
    {
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
        $outletModel = new OutletModel();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        $data['outlet'] = $outletModel->findById('outlets', $id);
        $data['tab'] = 'outlet_edit';
        $this->view('dashboard/index', $data);
    }

    public function delete($id)
    {
        $outletModel = new OutletModel();
        if ($outletModel->delete($id)) {
            $this->returnWithSuccess('Success', 'Outlet deleted successfully', '/dashboard/outlets');
        } else {
            $this->returnWithErr('Error', 'Failed to delete outlet');
        }
    }
}
