

<?php
class Database {
    private $host = "localhost";
    private $db_name = "bus_reservation";
    private $username = "root";        // Your MySQL username
    private $password = "Udesh_1109";  
    private $conn = null;

    public function getConnection() {
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>