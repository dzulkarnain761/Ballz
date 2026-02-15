<?php

class Auth extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
     
    }

    public function index()
    {
        $this->view("login");
    }

    public function login()
    {
        $this->view("login");
    }

   
    public function register()
    {
        redirect('auth/login');
    }

    public function logout()
    {
      
        session_destroy();
        $_SESSION['success'] = 'You have been logged out';
        redirect("auth/login");
    }
}
