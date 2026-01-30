<?php

class Controller
{

    protected $viewFolder = '';

    public function view($name, $data = [])
    {
        
        $viewPath = $this->viewFolder ? $this->viewFolder . '/' : '';
        $filename = "../app/views/" . $viewPath . $name . ".view.php";

        if (file_exists($filename)) {
            if (!empty($data) && is_array($data)) {
                extract($data);
            }
            require_once $filename;
        } else {
            require_once "../app/views/_404.view.php";
        }
        
        cout(getenv('PASSWORD_ENV'));
        exit;
    }

    public function viewPdf($name, $data = [])
    {

        $filename = "../app/pdf/" . $name . ".php";
        if (file_exists($filename)) {
            if (!empty($data) && is_array($data)) {
                extract($data);
            }
            require_once(__DIR__ . '/../../public/inc/TCPDF/tcpdf.php');
            
            require_once $filename;
        } else {
            // Handle PDF file not found
            header('HTTP/1.0 404 Not Found');
            echo "PDF template not found: " . $name;
            exit;
        }
    }




public function returnWithErr($title, $msg, $header = null)
    {
        $_SESSION['error'] = [
            'title' => $title,
            'message' => $msg
        ];
        if ($header !== null) {
            header("Location: " . ROOT . $header);
        } else {
            $redirectTo = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : (defined('ROOT') ? ROOT : '/');
            header("Location: " . $redirectTo);
        }
        exit;
    }

    public function returnWithSuccess($title, $msg, $header = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['success'] = [
            'title' => $title,
            'message' => $msg
        ];
        if ($header !== null) {
            header("Location: " . ROOT . $header);
        } else {
            $redirectTo = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : (defined('ROOT') ? ROOT : '/');
            header("Location: " . $redirectTo);
        }
        exit;
    }

}
