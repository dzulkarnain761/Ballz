<?php

class Items extends Dashboard
{
   
    public function index()
    {
        $itemModel = new MenuItemModel();
        $categoryModel = new CategoryModel();
        $data['items'] = $itemModel->getAll();
        $data['categories'] = $categoryModel->getAll();
        $data['tab'] = 'items';
        $this->view('dashboard/index', $data);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $itemModel = new MenuItemModel();
            $data = [
                'category_id' => $_POST['category_id'],
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            if ($itemModel->create($data)) {
                $this->returnWithSuccess('Success', 'Item added successfully', '/dashboard/items');
            } else {
                $this->returnWithErr('Error', 'Failed to add item');
            }
        }
    }

    public function edit($id)
    {
        $itemModel = new MenuItemModel();
        $categoryModel = new CategoryModel();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'category_id' => $_POST['category_id'],
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            if ($itemModel->update($id, $data)) {
                $this->returnWithSuccess('Success', 'Item updated successfully', '/dashboard/items');
            } else {
                $this->returnWithErr('Error', 'Failed to update item');
            }
        }
        $data['item'] = $itemModel->findById('menu_items', $id);
        $data['categories'] = $categoryModel->getAll();
        $data['tab'] = 'item_edit';
        $this->view('dashboard/index', $data);
    }

    public function delete($id)
    {
        $itemModel = new MenuItemModel();
        if ($itemModel->delete($id)) {
            $this->returnWithSuccess('Success', 'Item deleted successfully', '/dashboard/items');
        } else {
            $this->returnWithErr('Error', 'Failed to delete item');
        }
    }
}
