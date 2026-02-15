<?php

class Categories extends Dashboard
{
  
    public function index()
    {
        $categoryModel = new CategoryModel();
        $data['categories'] = $categoryModel->getAll();
        $data['tab'] = 'categories';
        $this->view('dashboard/index', $data);
    }

    public function add()
    {
        $this->guardGuest();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $categoryModel = new CategoryModel();
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description']
            ];
            if ($categoryModel->create($data)) {
                $this->returnWithSuccess('Success', 'Category added successfully', '/dashboard/categories');
            } else {
                $this->returnWithErr('Error', 'Failed to add category');
            }
        }
    }

    public function edit($id)
    {
        $this->guardGuest();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $categoryModel = new CategoryModel();
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description']
            ];
            if ($categoryModel->update($id, $data)) {
                $this->returnWithSuccess('Success', 'Category updated successfully', '/dashboard/categories');
            } else {
                $this->returnWithErr('Error', 'Failed to update category');
            }
        }
        header('Location: ' . ROOT . '/dashboard/categories');
        exit;
    }

    public function delete($id)
    {
        $this->guardGuest();
        $categoryModel = new CategoryModel();
        if ($categoryModel->delete($id)) {
            $this->returnWithSuccess('Success', 'Category deleted successfully', '/dashboard/categories');
        } else {
            $this->returnWithErr('Error', 'Failed to delete category');
        }
    }
}
