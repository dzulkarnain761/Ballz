<?php

class Images extends Dashboard
{
   

    public function index()
    {
        $imageModel = new MenuImageModel();
        $itemModel = new MenuItemModel();
        $data['images'] = $imageModel->getAll();
        $data['items'] = $itemModel->getAll();
        $data['tab'] = 'images';
        $this->view('dashboard/index', $data);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $imageModel = new MenuImageModel();
            $data = [
                'menu_item_id' => $_POST['menu_item_id'],
                'image_url' => $_POST['image_url'],
                'alt_text' => $_POST['alt_text'],
                'is_primary' => isset($_POST['is_primary']) ? 1 : 0,
                'sort_order' => $_POST['sort_order']
            ];
            if ($imageModel->create($data)) {
                $this->returnWithSuccess('Success', 'Image added successfully', '/dashboard/images');
            } else {
                $this->returnWithErr('Error', 'Failed to add image');
            }
        }
    }

    public function edit($id)
    {
        $imageModel = new MenuImageModel();
        $itemModel = new MenuItemModel();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'menu_item_id' => $_POST['menu_item_id'],
                'image_url' => $_POST['image_url'],
                'alt_text' => $_POST['alt_text'],
                'is_primary' => isset($_POST['is_primary']) ? 1 : 0,
                'sort_order' => $_POST['sort_order']
            ];
            if ($imageModel->update($id, $data)) {
                $this->returnWithSuccess('Success', 'Image updated successfully', '/dashboard/images');
            } else {
                $this->returnWithErr('Error', 'Failed to update image');
            }
        }
        $data['image'] = $imageModel->findById('menu_images', $id);
        $data['items'] = $itemModel->getAll();
        $data['tab'] = 'image_edit';
        $this->view('dashboard/index', $data);
    }

    public function delete($id)
    {
        $imageModel = new MenuImageModel();
        if ($imageModel->delete($id)) {
            $this->returnWithSuccess('Success', 'Image deleted successfully', '/dashboard/images');
        } else {
            $this->returnWithErr('Error', 'Failed to delete image');
        }
    }
}
