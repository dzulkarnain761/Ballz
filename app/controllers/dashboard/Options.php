<?php

class Options extends Dashboard
{
    

    public function index()
    {
        $optionModel = new MenuOptionModel();
        $itemModel = new MenuItemModel();
       
        $data['groups'] = $optionModel->getGroups();
        $data['items'] = $itemModel->getAll();
        $data['tab'] = 'options';
        $this->view('dashboard/index', $data);
    }

    public function group_add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $optionModel = new MenuOptionModel();
            $data = [
                'menu_item_id' => $_POST['menu_item_id'],
                'name' => $_POST['name'],
                'is_required' => isset($_POST['is_required']),
                'min_select' => $_POST['min_select'],
                'max_select' => $_POST['max_select'],
                'sort_order' => $_POST['sort_order']
            ];
            if ($optionModel->createGroup($data)) {
                $this->returnWithSuccess('Success', 'Option Group added', '/dashboard/options');
            }
        }
    }

    public function group_edit($id)
    {
        $optionModel = new MenuOptionModel();
        $itemModel = new MenuItemModel();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'menu_item_id' => $_POST['menu_item_id'],
                'name' => $_POST['name'],
                'is_required' => isset($_POST['is_required']),
                'min_select' => $_POST['min_select'],
                'max_select' => $_POST['max_select'],
                'sort_order' => $_POST['sort_order']
            ];
            if ($optionModel->updateGroup($id, $data)) {
                $this->returnWithSuccess('Updated', 'Option Group updated', '/dashboard/options');
            }
        }
        $data['group'] = $optionModel->getGroup($id);
        $data['options'] = $optionModel->getOptions($id);
        $data['items'] = $itemModel->getAll();
        $data['tab'] = 'group_edit';
        $this->view('dashboard/index', $data);
    }

    public function group_delete($id)
    {
        $optionModel = new MenuOptionModel();
        if ($optionModel->deleteGroup($id)) {
            $this->returnWithSuccess('Deleted', 'Option Group removed', '/dashboard/options');
        }
    }

    public function option_add($group_id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $optionModel = new MenuOptionModel();
            $data = [
                'option_group_id' => $group_id,
                'name' => $_POST['name'],
                'price_modifier' => $_POST['price_modifier'],
                'is_default' => isset($_POST['is_default']),
                'sort_order' => $_POST['sort_order']
            ];
            if ($optionModel->createOption($data)) {
                $this->returnWithSuccess('Success', 'Option added', '/dashboard/group_edit/'.$group_id);
            }
        }
    }

    public function option_delete($id, $group_id)
    {
        $optionModel = new MenuOptionModel();
        if ($optionModel->deleteOption($id)) {
            $this->returnWithSuccess('Deleted', 'Option removed', '/dashboard/group_edit/'.$group_id);
        }
    }
}
