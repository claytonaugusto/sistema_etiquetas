<?php

namespace core;

use app\classes\Uri;
use app\exceptions\ControllerNotExistException;
use Exception;

class Controller
{
    private $uri;
    private $controller;
    private $namespace;
    private $folders = [
        "app\controllers\admin",
        "app\controllers\portal"
    ];

    public function __construct()
    {
        $this->uri = Uri::uri();
    }

    //carregamento do controller
    public function load()
    {

        if ($this->isHome()) {
            return $this->controllerHome();
        }
        return $this->controllerNotHome();
    }

    private function controllerHome()
    {
        if (!$this->controllerExist('HomeController')) {
            throw new ControllerNotExistException("Esse controller não existe");
        }
        return $this->instatiateController();
    }

    private function controllerNotHome()
    {

        $uri = trim($this->uri, '/');
        $segments = explode('/', $uri);

        $controllerName = ucfirst($segments[0]) . 'Controller';
        $action = $segments[1] ?? 'index';

        if(!$this->controllerExist($controllerName)) {
            throw new ControllerNotExistException("Esse controller não existe");
        }

        $controller = $this->instatiateController();
        if(!method_exists($controller, $action)){
            throw new Exception("O método {$action} não existe no controller");
        }

        return $controller->$action();
    }

    private function getControllerNotHome()
    {

        if (substr_count($this->uri, '/') > 1) {
            list($controller, $method) = array_values(array_filter(explode('/', $this->uri)));
            return ucfirst($controller) . 'Controller';
        }

        return ucfirst(ltrim($this->uri, '/')) . 'Controller';
    }

    //verifica se está na home do app
    public function isHome()
    {
        return ($this->uri == '/');
    }


    private function controllerExist($controller)
    {

        $controllerExists = false;

        foreach ($this->folders as $folder) {
            if (class_exists($folder . '\\' . $controller)) {
                $controllerExists = true;
                $this->namespace = $folder;
                $this->controller = $controller;
            }
        }

        return $controllerExists;
    }

    private function instatiateController()
    {
        $controller = $this->namespace . '\\' . $this->controller;
        return new $controller;
    }
}
