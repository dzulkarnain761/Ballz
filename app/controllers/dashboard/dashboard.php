
<?php

class Dashboard extends Controller
{
    public function __construct()
    {
      
    }

    public function index()
    {
        $data['tabs'] = 'index';
        $this->view('dashboard/index', $data);
    }

}