<?php


class DataBase
{
    private $config = [
        'host' => '127.0.0.1',
        'user' => 'root',
        'pass' => '',
        'base' => 'a',
        'port' => '3306'
    ];

    function findByName($name) {

        // connecting
        $conn = @new mysqli(
            $this->config["host"],
            $this->config["user"],
            $this->config["pass"],
            $this->config["base"],
            $this->config["port"]);


        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, `name`, `pass` FROM `user`";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                var_dump($row);
//                echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            }
        } else {
            echo "0 results";
        }
        $conn->close();
    }

    function save(User $user){

        $name = $user->getName();
        $pass = $user->getPass();

        // config from yml


        // connecting
        $mysqli = @new mysqli(
            $this->config["host"],
            $this->config["user"],
            $this->config["pass"],
            $this->config["base"],
            $this->config["port"]);

        // test of connecting
        if ($mysqli -> connect_errno)
        {
            $code = $mysqli -> connect_errno;
            $mess = $mysqli -> connect_error;
            die("Failed to connect to MySQL: ($code) $mess\n");
        }

        // definition of query
        $query  = 'INSERT INTO user VALUES(NULL,?,?);';

        // preparing
        $stmt = @$mysqli -> prepare($query);

        // test of preparing
        if(!$stmt)
        {
            $code = $mysqli -> errno;
            $mess = $mysqli -> error;
            $mysqli -> close();
            die("Failed to prepare statement: ($code) $mess\n");
        }

        // binding
        $bind = @$stmt -> bind_param("ss", $name, $pass);

        // test of binding
        if(!$bind)
        {
            $stmt   -> close();
            $mysqli -> close();
            die("Failed to bind param.\n");
        }

        // executing query
        $exec = @$stmt -> execute();

        // checking fails
        if(!$exec)
        {
            $stmt   -> close();
            $mysqli -> close();
            die("Failed to execute prepare statement.\n");
        }

        // clearing and disconnecting
        $stmt   -> close();
        $mysqli -> close();
    }
}


class User
{
    private $id;
    private $name;
    private $pass;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function setPass($pass)
    {
        $this->pass = $pass;
    }    
}



class Render
{
    public function execute($file, $data)
    {
        $base = file_get_contents('../views/base.html');
        $content = file_get_contents('../views/'.$file);

        foreach ($data as $key => $value) {
            $content = preg_replace('/{{'.$key.'}}/',$value,$content);
        }

        $base = preg_replace('/{%BASE%}/',$content,$base);
//        var_dump($base); die;
        return $base;
    }
}

class Controller
{
    private $view;

    public function __construct()
    {
        $this->view = new Render();
    }

    public function login()
    {
        if(isset($_SESSION['name'])){
            return "logged in";
        }

        return $this->view->execute("login.html",[]);
    }
    
    public function loginCheck()
    {
        return "ok";
    }

    public function register()
    {
        if(isset($_POST['name']) && isset($_POST['pass'])) {

            $user = new User();
            $user->setName($_POST['name']);
            $user->setPass($_POST['pass']);

            $db = new DataBase();
            $db->save($user);
            $_SESSION['name'] = $user->getName();

            $res = $db->findByName($user->getName());
            var_dump($res);die;


            return "process";
        }

        return $this->view->execute("register.html",[]);
    }

    public function edit()
    {
        return "ok";
    }

    public function home()
    {
        if(isset($_SESSION['name']))
        {
            $db = new DataBase();

            // $_SESSION['id']

            $user = $db->findByName('a');

            var_dump($user);die;

            return $this->view->execute("in.html",[
                'name' => $user->getName(),
                'pass' => $user->getPass()
            ]);
        }
        return $this->view->execute("out.html",[]);

    }

    public function logout()
    {
        session_unset();

        return "logged out";
    }
}