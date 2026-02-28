<?php

class Menu extends Controller
{
    public function index()
    {
        $menuItems = (new MenuItemModel())->getAll();
        $categories = (new CategoryModel())->getAll();
        $categories = array_reverse($categories);
       
        $this->view('menu', ['menuItems' => $menuItems, 'categories' => $categories]);
    }

}



