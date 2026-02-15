
<?php

class Dashboard extends Controller
{
    public function __construct()
    {
      
    }

    /**
     * Block guests from performing write operations
     */
    protected function guardGuest()
    {
        if (isGuest()) {
            $_SESSION['error'] = 'Guests are not allowed to perform this action. Please login as admin.';
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    public function index()
    {
        $data['tabs'] = 'index';
        $this->view('dashboard/index', $data);
    }

}