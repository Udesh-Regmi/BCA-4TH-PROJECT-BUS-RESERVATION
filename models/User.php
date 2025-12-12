    <?php
class User {
    private $conn;
    private $table = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $phone;
    public $role;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Create new user
     */
    public function create($name, $email, $password, $phone, $role = 'user') {
        $query = "INSERT INTO " . $this->table . " 
                  (name, email, password, phone, role) 
                  VALUES (:name, :email, :password, :phone, :role)";
        
        $stmt = $this->conn->prepare($query);
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Bind parameters
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":role", $role);
        
        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("User creation error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Login user
     */
    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }

    /**
     * Get user by ID
     */
    public function getById($id) {
        $query = "SELECT id, name, email, phone, role, created_at, updated_at 
                  FROM " . $this->table . " 
                  WHERE id = :id 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get user by email
     */
    public function getByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all users
     */
    public function getAll() {
        $query = "SELECT id, name, email, phone, role, created_at, updated_at 
                  FROM " . $this->table . " 
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get users by role
     */
    public function getByRole($role) {
        $query = "SELECT id, name, email, phone, role, created_at, updated_at 
                  FROM " . $this->table . " 
                  WHERE role = :role 
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":role", $role);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update user
     */
    public function update($id, $name, $email, $phone) {
        $query = "UPDATE " . $this->table . " 
                  SET name = :name, email = :email, phone = :phone, updated_at = CURRENT_TIMESTAMP 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":id", $id);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("User update error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update password
     */
    public function updatePassword($id, $newPassword) {
        $query = "UPDATE " . $this->table . " 
                  SET password = :password, updated_at = CURRENT_TIMESTAMP 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":id", $id);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Password update error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete user
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("User deletion error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if email exists
     */
    public function emailExists($email, $excludeId = null) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        
        if ($excludeId) {
            $query .= " AND id != :id";
        }
        
        $query .= " LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        
        if ($excludeId) {
            $stmt->bindParam(":id", $excludeId);
        }
        
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    /**
     * Get total user count
     */
    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    /**
     * Get user count by role
     */
    public function getCountByRole($role) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE role = :role";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":role", $role);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    /**
     * Search users
     */
    public function search($keyword) {
        $query = "SELECT id, name, email, phone, role, created_at, updated_at 
                  FROM " . $this->table . " 
                  WHERE name LIKE :keyword OR email LIKE :keyword 
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(":keyword", $keyword);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>