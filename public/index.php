<?php
//exemple https://devclass.com.br/curso/show/12/21
require __DIR__ . "/../bootstrap.php";

use core\Controller;
use core\Method;
try {
    $controller = new Controller();
    $controller->load();
    // dd($controller);

    $method = new Method();
    $method = $method->load($controller);

    $controller->$method();

} catch (Exception $e) {
    dd($e->getMessage());
}