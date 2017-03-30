<?php

class Routing
{
    public function redirect()
    {
        switch ($_SERVER["REQUEST_URI"]) {
            case "/" :
                return "home";
            case "/login" :
                return "login";
            case "/loginCheck" :
                return "loginCheck";
            case "/register" :
                return "register";
            case "/edit" :
                return "edit";
            case "/logout" :
                return "logout";
            default :
                throw new Exception("Route not found", 404);
        }
    }
}