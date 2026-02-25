<?php
//exemple https://devclass.com.br/curso/show/12/21
require __DIR__ . "/../bootstrap.php";

use core\Controller;

try {
    $controller = new Controller();
    $controller->load();
    dd($controller);

} catch (Exception $e) {
    dd($e->getMessage());
}