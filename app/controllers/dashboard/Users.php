<?php

class Users extends Dashboard
{
  

    public function index()
    {
        $userModel = new UserModel();
        $perPage = 10;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $totalItems = $userModel->getTotal();
        $totalPages = max(1, ceil($totalItems / $perPage));
        $page = min($page, $totalPages);

        $data['users'] = $userModel->getPaginated($page, $perPage);
        $data['pagination'] = [
            'page' => $page,
            'perPage' => $perPage,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'baseUrl' => ROOT . '/dashboard/users'
        ];
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
