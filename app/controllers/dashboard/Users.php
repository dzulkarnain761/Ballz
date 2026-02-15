<?php

class Users extends Dashboard
{
  

    public function index()
    {
        $userModel = new UserModel();
        $data['users'] = $userModel->getAll();
        $data['tab'] = 'users';
        $this->view('dashboard/index', $data);
    }

    public function add()
    {
        $this->guardGuest();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = new UserModel();
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'reward_points' => $_POST['reward_points'],
                'status' => $_POST['status']
            ];
            if ($userModel->create($data)) {
                $this->returnWithSuccess('Success', 'Customer added successfully', '/dashboard/users');
            } else {
                $this->returnWithErr('Error', 'Failed to add customer');
            }
        }
    }

    public function edit($id)
    {
        $this->guardGuest();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = new UserModel();
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'reward_points' => $_POST['reward_points'],
                'status' => $_POST['status']
            ];
            if ($userModel->update($id, $data)) {
                $this->returnWithSuccess('Success', 'Customer updated successfully', '/dashboard/users');
            } else {
                $this->returnWithErr('Error', 'Failed to update customer');
            }
        }
        header('Location: ' . ROOT . '/dashboard/users');
        exit;
    }

    public function delete($id)
    {
        $this->guardGuest();
        $userModel = new UserModel();
        if ($userModel->delete($id)) {
            $this->returnWithSuccess('Success', 'Customer deleted successfully', '/dashboard/users');
        } else {
            $this->returnWithErr('Error', 'Failed to delete customer');
        }
    }
}
