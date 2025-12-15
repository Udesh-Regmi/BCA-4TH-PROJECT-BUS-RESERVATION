<?php
class Reservation {
    private $conn;
    private $table = "reservations";

    public $id;
    public $user_id;
    public $bus_id;
    public $seat_number;
    public $booking_date;
    public $passenger_name;
    public $passenger_phone;
    public $total_amount;
    public $status;
    public $payment_method;
    public $transaction_id;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Create new reservation with payment details
     */
    public function create($busId, $userId, $seatNumber, $bookingDate, $passengerName, $passengerPhone, $totalAmount, $transactionId = null, $paymentMethod = 'esewa') {
    error_log("Reservation::create called with params:");
    error_log("  busId: {$busId}");
    error_log("  userId: {$userId}");
    error_log("  seatNumber: {$seatNumber}");
    error_log("  bookingDate: {$bookingDate}");
    error_log("  passengerName: {$passengerName}");
    error_log("  passengerPhone: {$passengerPhone}");
    error_log("  totalAmount: {$totalAmount}");
    error_log("  transactionId: {$transactionId}");
    error_log("  paymentMethod: {$paymentMethod}");
    
    $query = "INSERT INTO " . $this->table . " 
              (user_id, bus_id, seat_number, booking_date, passenger_name, 
               passenger_phone, total_amount, status, payment_method, transaction_id) 
              VALUES (:user_id, :bus_id, :seat_number, :booking_date, :passenger_name, 
                      :passenger_phone, :total_amount, :status, :payment_method, :transaction_id)";
    
    try {
        $stmt = $this->conn->prepare($query);
        $status = 'confirmed';
        
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":bus_id", $busId);
        $stmt->bindParam(":seat_number", $seatNumber);
        $stmt->bindParam(":booking_date", $bookingDate);
        $stmt->bindParam(":passenger_name", $passengerName);
        $stmt->bindParam(":passenger_phone", $passengerPhone);
        $stmt->bindParam(":total_amount", $totalAmount);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":payment_method", $paymentMethod);
        $stmt->bindParam(":transaction_id", $transactionId);
        
        if ($stmt->execute()) {
            $lastId = $this->conn->lastInsertId();
            error_log("Reservation created successfully with ID: {$lastId}");
            return $lastId;
        } else {
            error_log("Reservation execute() returned false");
            error_log("Error info: " . print_r($stmt->errorInfo(), true));
            return false;
        }
    } catch (PDOException $e) {
        error_log("Reservation creation PDO error: " . $e->getMessage());
        error_log("SQL State: " . $e->getCode());
        return false;
    }
}
    /**
     * Create reservation from array data (legacy support)
     */
    public function createFromArray($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (user_id, bus_id, seat_number, booking_date, passenger_name, 
                   passenger_phone, total_amount, status, payment_method, transaction_id) 
                  VALUES (:user_id, :bus_id, :seat_number, :booking_date, :passenger_name, 
                          :passenger_phone, :total_amount, :status, :payment_method, :transaction_id)";
        
        $stmt = $this->conn->prepare($query);
        
        $paymentMethod = $data['payment_method'] ?? 'esewa';
        $transactionId = $data['transaction_id'] ?? null;
        
        $stmt->bindParam(":user_id", $data['user_id']);
        $stmt->bindParam(":bus_id", $data['bus_id']);
        $stmt->bindParam(":seat_number", $data['seat_number']);
        $stmt->bindParam(":booking_date", $data['booking_date']);
        $stmt->bindParam(":passenger_name", $data['passenger_name']);
        $stmt->bindParam(":passenger_phone", $data['passenger_phone']);
        $stmt->bindParam(":total_amount", $data['total_amount']);
        $stmt->bindParam(":status", $data['status']);
        $stmt->bindParam(":payment_method", $paymentMethod);
        $stmt->bindParam(":transaction_id", $transactionId);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Reservation creation error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all reservations with user and bus details
     */
    public function getAll() {
        $query = "SELECT r.*, 
                         u.name as user_name, u.email, 
                         b.bus_name, b.bus_number, b.route_from, b.route_to 
                  FROM " . $this->table . " r
                  INNER JOIN users u ON r.user_id = u.id
                  INNER JOIN buses b ON r.bus_id = b.id
                  ORDER BY r.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get reservations by user ID
     */
    public function getByUserId($userId) {
        $query = "SELECT r.*, 
                         b.bus_name, b.bus_number, b.route_from, b.route_to, 
                         b.departure_time, b.arrival_time 
                  FROM " . $this->table . " r
                  INNER JOIN buses b ON r.bus_id = b.id
                  WHERE r.user_id = :user_id
                  ORDER BY r.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get reservation by ID with full details
     */
    public function getById($id) {
        $query = "SELECT r.*, 
                         u.name as user_name, u.email, u.phone as user_phone, 
                         b.bus_name, b.bus_number, b.route_from, b.route_to, 
                         b.departure_time, b.arrival_time, b.price
                  FROM " . $this->table . " r
                  INNER JOIN users u ON r.user_id = u.id
                  INNER JOIN buses b ON r.bus_id = b.id
                  WHERE r.id = :id 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get reservation by transaction ID
     */
    public function getByTransactionId($transactionId) {
        $query = "SELECT r.*, 
                         u.name as user_name, u.email, 
                         b.bus_name, b.bus_number, b.route_from, b.route_to 
                  FROM " . $this->table . " r
                  INNER JOIN users u ON r.user_id = u.id
                  INNER JOIN buses b ON r.bus_id = b.id
                  WHERE r.transaction_id = :transaction_id 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":transaction_id", $transactionId);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update reservation status
     */
    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " 
                  SET status = :status, updated_at = CURRENT_TIMESTAMP 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Reservation status update error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update payment details
     */
    public function updatePaymentDetails($id, $transactionId, $paymentMethod = 'esewa') {
        $query = "UPDATE " . $this->table . " 
                  SET transaction_id = :transaction_id, 
                      payment_method = :payment_method,
                      updated_at = CURRENT_TIMESTAMP 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":transaction_id", $transactionId);
        $stmt->bindParam(":payment_method", $paymentMethod);
        $stmt->bindParam(":id", $id);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Payment details update error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete reservation
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Reservation deletion error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get reserved seats for a specific bus and date
     */
    public function getReservedSeats($busId, $date) {
        $query = "SELECT seat_number 
                  FROM " . $this->table . " 
                  WHERE bus_id = :bus_id 
                  AND booking_date = :date 
                  AND status != 'cancelled'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":bus_id", $busId);
        $stmt->bindParam(":date", $date);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Check if seat is available
     */
    public function isSeatAvailable($busId, $seatNumber, $date) {
        $query = "SELECT COUNT(*) as count 
                  FROM " . $this->table . " 
                  WHERE bus_id = :bus_id 
                  AND seat_number = :seat_number 
                  AND booking_date = :date 
                  AND status != 'cancelled'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":bus_id", $busId);
        $stmt->bindParam(":seat_number", $seatNumber);
        $stmt->bindParam(":date", $date);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] == 0;
    }

    /**
     * Get reservations by status
     */
    public function getByStatus($status) {
        $query = "SELECT r.*, 
                         u.name as user_name, u.email, 
                         b.bus_name, b.bus_number, b.route_from, b.route_to 
                  FROM " . $this->table . " r
                  INNER JOIN users u ON r.user_id = u.id
                  INNER JOIN buses b ON r.bus_id = b.id
                  WHERE r.status = :status
                  ORDER BY r.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get reservations by bus ID
     */
    public function getByBusId($busId) {
        $query = "SELECT r.*, 
                         u.name as user_name, u.email 
                  FROM " . $this->table . " r
                  INNER JOIN users u ON r.user_id = u.id
                  WHERE r.bus_id = :bus_id
                  ORDER BY r.booking_date DESC, r.seat_number ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":bus_id", $busId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get total reservation count
     */
    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    /**
     * Get reservation count by status
     */
    public function getCountByStatus($status) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE status = :status";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    /**
     * Get total revenue
     */
    public function getTotalRevenue() {
        $query = "SELECT SUM(total_amount) as revenue 
                  FROM " . $this->table . " 
                  WHERE status = 'confirmed'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['revenue'] ?? 0;
    }

    /**
     * Get recent reservations
     */
    public function getRecent($limit = 5) {
        $query = "SELECT r.*, 
                         u.name as user_name, 
                         b.bus_name, b.route_from, b.route_to 
                  FROM " . $this->table . " r
                  INNER JOIN users u ON r.user_id = u.id
                  INNER JOIN buses b ON r.bus_id = b.id
                  ORDER BY r.created_at DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>