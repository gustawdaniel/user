<?php

require '../src/App/Routing.php';
require '../src/Controller/Controller.php';

//spl_autoload_register(function ($class_name) {
//    include $class_name . '.php';
//});

function process()
{
    try {
        $action = (new Routing())->redirect();
        // execute method action on controller object
        $html = (new Controller())->{$action}();
        return $html;

    } catch (Exception $e) {
        return "Exception: " . $e->getMessage();
    }
}

session_start();
echo process();