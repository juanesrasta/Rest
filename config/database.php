<?php

class dataBase {

    private $host = "localhost";
    private $db = "phplearning";
    private $user = "root";
    private $pass = "123456";
    private $conn;

    public function getConnection() {

        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db, $this->user, $this->pass);
            $this->conn->exec("set names utf8");
        }
        catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
        return $this->conn;

    }

}

?>