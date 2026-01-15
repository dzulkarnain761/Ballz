<?php

class Home extends Controller
{
    public function index()
    {
        $menuItems = (new MenuItemModel())->getAll();   
        $this->view('homepage');
    }

}
