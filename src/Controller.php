<?php

class Controller
{
    private $view;

    public function __construct()
    {
        $this->view = new Render();
    }

    public function login()
    {
        if(isset($_SESSION['id'])){
            header('Location: /');
        }

        return $this->view->execute("login.html",['error' => '']);
    }
    
    public function loginCheck()
    {
        if (isset($_POST['name']) && isset($_POST['pass'])) {

            $db = new DataBase();
            $user = $db->findByNameAndPass($_POST['name'],$_POST['pass']);
//            var_dump();die;
            if($user->getName()) {
                $_SESSION['id'] = $user->getId();
                header('Location: /');
            } else {
                return $this->view->execute("login.html",['error' => 'Incorrect Data']);
            }
        }
        throw new Exception("Method not valid or form not complete!");
    }

    public function register()
    {
        if(isset($_POST['name']) && isset($_POST['pass'])) {

            $user = new User();
            $user->setName($_POST['name']);
            $user->setPass($_POST['pass']);

            $db = new DataBase();
            $id = $db->save($user);
            $_SESSION['id'] = $id;

            header('Location: /');
        }

        return $this->view->execute("register.html",[]);
    }

    public function edit()
    {
        $db = new DataBase();
        $user = $db->findById($_SESSION['id']);

        if(isset($_POST['name']) && isset($_POST['pass'])) {

            $user->setName($_POST['name']);
            $user->setPass($_POST['pass']);
            $db->update($user);
            
            header('Location: /');
        }

        return $this->view->execute("edit.html",[
            'name' => $user->getName(),
            'pass' => $user->getPass()
        ]);
    }

    public function home()
    {
        if(isset($_SESSION['id']))
        {
            $db = new DataBase();
            $user = $db->findById($_SESSION['id']);

            return $this->view->execute("in.html",[
                'id' => $user->getId(),
                'name' => $user->getName(),
                'pass' => $user->getPass()
            ]);
        }
        return $this->view->execute("out.html",[]);
    }

    public function logout()
    {
        session_unset();

        header('Location: /');
    }
}