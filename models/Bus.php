<?php
class Bus {
    private $conn;
    private $table = "buses";

    public $id;
    public $bus_number;
    public $bus_name;
    public $route_from;
    public $route_to;
    public $departure_time;
    public $arrival_time;
    public $total_seats;
    public $available_seats;
    public $price;
    public $status;

    public $image_string;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Create new bus
     */
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (bus_number, bus_name, route_from, route_to, departure_time, arrival_time, 
                   total_seats, available_seats, price, status, image_string) 
                  VALUES (:bus_number, :bus_name, :route_from, :route_to, :departure_time, 
                          :arrival_time, :total_seats, :available_seats, :price, :status, :image_string)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":bus_number", $data['bus_number']);
        $stmt->bindParam(":bus_name", $data['bus_name']);
        $stmt->bindParam(":route_from", $data['route_from']);
        $stmt->bindParam(":route_to", $data['route_to']);
        $stmt->bindParam(":departure_time", $data['departure_time']);
        $stmt->bindParam(":arrival_time", $data['arrival_time']);
        $stmt->bindParam(":total_seats", $data['total_seats']);
        $stmt->bindParam(":available_seats", $data['available_seats']);
        $stmt->bindParam(":price", $data['price']);
        $stmt->bindParam(":status", $data['status']);
        $stmt->bindParam('image_string', $data['image_string']);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Bus creation error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all buses
     */
    public function getAll($status = null) {
        $query = "SELECT * FROM " . $this->table;
        
        if ($status) {
            $query .= " WHERE status = :status";
        }
        
        $query .= " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if ($status) {
            $stmt->bindParam(":status", $status);
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get bus by ID
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get bus by bus number
     */
    public function getByBusNumber($busNumber) {
        $query = "SELECT * FROM " . $this->table . " WHERE bus_number = :bus_number LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":bus_number", $busNumber);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Search buses by route
     */
    public function search($from, $to, $date = null) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE route_from LIKE :from 
                  AND route_to LIKE :to 
                  AND status = 'active'";
        
        $stmt = $this->conn->prepare($query);
        
        $from = "%{$from}%";
        $to = "%{$to}%";
        
        $stmt->bindParam(":from", $from);
        $stmt->bindParam(":to", $to);
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update bus
     */
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET bus_number = :bus_number, 
                      bus_name = :bus_name, 
                      route_from = :route_from, 
                      route_to = :route_to, 
                      departure_time = :departure_time, 
                      arrival_time = :arrival_time, 
                      total_seats = :total_seats, 
                      available_seats = :available_seats, 
                      price = :price, 
                      status = :status,
                      image_string = :image_string,
                      updated_at = CURRENT_TIMESTAMP 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":bus_number", $data['bus_number']);
        $stmt->bindParam(":bus_name", $data['bus_name']);
        $stmt->bindParam(":route_from", $data['route_from']);
        $stmt->bindParam(":route_to", $data['route_to']);
        $stmt->bindParam(":departure_time", $data['departure_time']);
        $stmt->bindParam(":arrival_time", $data['arrival_time']);
        $stmt->bindParam(":total_seats", $data['total_seats']);
        $stmt->bindParam(":available_seats", $data['available_seats']);
        $stmt->bindParam(":price", $data['price']);
        $stmt->bindParam(":status", $data['status']);
        $stmt->bindParam('image_string', $data['image_string']);
        $stmt->bindParam(":id", $id);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Bus update error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete bus
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Bus deletion error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update available seats
     */
    public function updateSeats($busId, $seats) {
        $query = "UPDATE " . $this->table . " 
                  SET available_seats = available_seats - :seats 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":seats", $seats);
        $stmt->bindParam(":id", $busId);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Seat update error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Restore available seats (when booking is cancelled)
     */
    public function restoreSeats($busId, $seats) {
        $query = "UPDATE " . $this->table . " 
                  SET available_seats = available_seats + :seats 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":seats", $seats);
        $stmt->bindParam(":id", $busId);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Seat restore error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get buses by route
     */
    public function getByRoute($from, $to) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE route_from = :from 
                  AND route_to = :to 
                  AND status = 'active'
                  ORDER BY departure_time ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":from", $from);
        $stmt->bindParam(":to", $to);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get total bus count
     */
    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    /**
     * Get active bus count
     */
    public function getActiveCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE status = 'active'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    /**
     * Check if bus number exists
     */
    public function busNumberExists($busNumber, $excludeId = null) {
        $query = "SELECT id FROM " . $this->table . " WHERE bus_number = :bus_number";
        
        if ($excludeId) {
            $query .= " AND id != :id";
        }
        
        $query .= " LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":bus_number", $busNumber);
        
        if ($excludeId) {
            $stmt->bindParam(":id", $excludeId);
        }
        
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
?>