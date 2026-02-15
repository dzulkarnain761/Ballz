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
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && checkCRFT()) {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $_SESSION['error'] = 'Please enter both username and password.';
                redirect('auth/login');
            }

            $adminModel = new AdminModel();
            $admin = $adminModel->getByUsername($username);

            if ($admin && password_verify($password, $admin['password'])) {
                unset($_SESSION['guest']);
                $_SESSION['id_admin'] = $admin['id'];
                $_SESSION['username'] = $admin['username'];
                $_SESSION['role'] = 'admin';
                redirect('dashboard/index');
            } else {
                $_SESSION['error'] = 'Invalid username or password.';
                redirect('auth/login');
            }
        }

        $this->view("login");
    }

   
    public function guest()
    {
        $_SESSION['guest'] = true;
        $_SESSION['username'] = 'Guest';
        redirect('dashboard/index');
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
