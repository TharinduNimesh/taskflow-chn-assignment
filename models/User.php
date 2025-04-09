<?php

require_once(__DIR__ . '/../config/database.php');

class User {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    public function authenticate($email, $password) {
        $email = mysqli_real_escape_string($this->conn, $email);
        
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error authenticating user: " . mysqli_error($this->conn));
        }
        
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); // Remove password from session data
            return $user;
        }
        
        return null;
    }

    public function register($name, $username, $email, $password) {
        $name = mysqli_real_escape_string($this->conn, $name);
        $username = mysqli_real_escape_string($this->conn, $username);
        $email = mysqli_real_escape_string($this->conn, $email);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Check if email or username already exists
        $query = "SELECT id FROM users WHERE email = ? OR username = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 'ss', $email, $username);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error checking user existence: " . mysqli_error($this->conn));
        }
        
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            throw new Exception("Email or username already exists");
        }
        
        // Insert new user
        $query = "INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 'ssss', $name, $username, $email, $hashedPassword);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error creating user: " . mysqli_error($this->conn));
        }
        
        return mysqli_insert_id($this->conn);
    }
}