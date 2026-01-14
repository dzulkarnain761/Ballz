<?php

class App
{
    private $controller = "Home";
    private $method = "index";
    private $params = [];

    private $publicRoutes = [
        'login',
        'register',
        'forgot-password',
        'reset-password'
    ];

    private function splitURL()
    {
        $URL = $_GET['url'] ?? 'home';
        $URL = explode("/", trim($URL, '/'));

        if (isset($URL[0]) && strtolower($URL[0]) === 'Insket-service') {
            array_shift($URL);
        }

        if (empty($URL) || empty($URL[0])) {
            $URL = ['home'];
        }

        return $URL;
    }

    public function loadController()
    {
        $URL = $this->splitURL();

        $var1 = $URL[0] ?? null;
        $var2 = $URL[1] ?? null;
        $var3 = $URL[2] ?? null;

        $controllerFound = false;
        $controllerPath = "";
        $actualController = "";
        $className = "";

        if ($var1 && is_dir("../app/controllers/" . $var1)) {

            if (!$var2) {
                $controllerPath = "../app/controllers/" . $var1 . "/home.php";
                if (file_exists($controllerPath)) {
                    $actualController = "home";
                    $className = "Home";
                    $controllerFound = true;
                    $this->params = array_slice($URL, 1);
                }
            } else {

                $controllerPath = "../app/controllers/" . $var1 . "/" . $var2 . ".php";
                if (file_exists($controllerPath)) {
                    $actualController = $var2;
                    $className = ucfirst($var2);
                    $controllerFound = true;

                    if ($var3) {
                        $this->params = array_slice($URL, 2);
                    }
                } else {
                    $parentControllerPath = "../app/controllers/" . $var1 . "/" . $var1 . ".php";
                    if (file_exists($parentControllerPath)) {
                        require_once $parentControllerPath;
                        $parentClassName = ucfirst($var1);
                        if (class_exists($parentClassName) && method_exists($parentClassName, $var2)) {
                            $controllerPath = $parentControllerPath;
                            $actualController = $var1;
                            $className = $parentClassName;
                            $controllerFound = true;
                            $this->method = $var2;

                            if ($var3) {
                                $this->params = array_slice($URL, 2);
                            }
                        }
                    }
                }
            }
        }

        if (!$controllerFound && $var1) {
            $controllerPath = "../app/controllers/" . $var1 . ".php";
            if (file_exists($controllerPath)) {
                $actualController = $var1;
                $className = ucfirst($var1);
                $controllerFound = true;

                if ($var2) {
                    $this->params = array_slice($URL, 2);
                } else {
                    $this->params = array_slice($URL, 1);
                }
            }
        }

        if (!$controllerFound) {
            require_once "../app/controllers/_404.php";
            $this->controller = "_404";
            $controller = new $this->controller;
            call_user_func_array([$controller, $this->method], $this->params);
            return;
        }

        if (is_dir("../app/controllers/" . $var1)) {
            $parentClassPath = "../app/controllers/" . $var1 . "/" . $var1 . ".php";
            if (file_exists($parentClassPath)) {
                require_once $parentClassPath;
            }
        }

        require_once $controllerPath;
        $this->controller = $className;
        $controller = new $this->controller;

        if (is_dir("../app/controllers/" . $var1)) {

            if (!$var2) {
                $this->method = "index";
                $this->params = [];
            } else if ($var3 && method_exists($controller, $var3)) {

                $this->method = $var3;
                $this->params = array_slice($URL, 3);
            } else {

                $this->params = array_slice($URL, 2);
            }
        } else {
            if ($var2 && method_exists($controller, $var2)) {
                $this->method = $var2;
                $this->params = array_slice($URL, 2);
            } else {
                $this->params = array_slice($URL, 1);
            }
        }
        call_user_func_array([$controller, $this->method], $this->params);
    }
}
