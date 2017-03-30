<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 30.03.17
 * Time: 08:34
 */
class DataBase
{
//    private $conn;

    private $config = [
        'host' => '127.0.0.1',
        'user' => 'root',
        'pass' => '',
        'base' => 'a',
        'port' => '3306'
    ];

    private function generalFind($sql,$params)
    {
        $user = null;
        try {
            $conn = new PDO("mysql:host=".$this->config['host'].";dbname=".$this->config['base'],
                $this->config['user'], $this->config['pass']);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare($sql);
            $stmt->execute($params);

            $data = $stmt->fetchObject();

            $user = new User();
            $user->setId($data->id);
            $user->setName($data->name);
            $user->setPass($data->pass);
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
        return $user;
    }

    public function findByNameAndPass($name,$pass) {
        $sql = "SELECT id, name, pass FROM user WHERE name=:name AND pass=:pass";
        $params = [
            ':name'=>$name,
            ':pass'=>$pass
        ];
        return $this->generalFind($sql,$params);
    }

    public function findById($id) {
        $sql = "SELECT id, name, pass FROM user WHERE id=:id";
        $params = [
            ':id'=>$id
        ];
        return $this->generalFind($sql,$params);
    }

    public function save(User $user)
    {
        $id=null;
        try {
            $conn = new PDO("mysql:host=".$this->config['host'].";dbname=".$this->config['base'],
                $this->config['user'], $this->config['pass']);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     // prepare sql and bind parameters
            $stmt = $conn->prepare("INSERT INTO user (name, pass) VALUES (:name, :pass)");
            $stmt->bindParam(':name', $user->getName());
            $stmt->bindParam(':pass', $user->getPass());

            $stmt->execute();
            $id = $conn->lastInsertId();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
        return $id;
    }

    public function update(User $user)
    {
        try {
            $conn = new PDO("mysql:host=".$this->config['host'].";dbname=".$this->config['base'],
                $this->config['user'], $this->config['pass']);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("UPDATE user SET name=:name, pass=:pass WHERE id=:id");
            $stmt->bindParam(':id', $user->getId());
            $stmt->bindParam(':name', $user->getName());
            $stmt->bindParam(':pass', $user->getPass());
            // Prepare statement

            // execute the query
            $stmt->execute();
        }
        catch(PDOException $e)
        {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
        return $user;
    }
}