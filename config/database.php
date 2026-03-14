<?php
class Database {

    private $db_name = "bus_reservation";
    private $username = "root";
    private $password = "Udesh_1109";
    private $socket = "/opt/lampp/var/mysql/mysql.sock";

    private $conn;

    public function getConnection() {

        try {

            $dsn = "mysql:unix_socket={$this->socket};dbname={$this->db_name};charset=utf8";

            $this->conn = new PDO($dsn, $this->username, $this->password);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $e) {

            die("Database connection failed: " . $e->getMessage());
        }

        return $this->conn;
    }
}