<?php

class Auth extends Controller
{

    public function __construct()
    {

        $this->viewFolder = 'auth';
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
        $this->view("register");
    }

    public function logout()
    {
        session_destroy();
        redirect("auth/login");
    }
}
