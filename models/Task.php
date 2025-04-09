<?php

require_once(__DIR__ . '/../config/database.php');

class Task {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    public function getAllTasks($userId) {
        $query = "SELECT t.*, GROUP_CONCAT(tg.name) as tags 
                 FROM tasks t 
                 LEFT JOIN task_tags tt ON t.id = tt.task_id 
                 LEFT JOIN tags tg ON tt.tag_id = tg.id 
                 WHERE t.user_id = ?
                 GROUP BY t.id 
                 ORDER BY t.created_at DESC";
        
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error fetching tasks: " . mysqli_error($this->conn));
        }
        
        $result = mysqli_stmt_get_result($stmt);
        $tasks = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $row['tags'] = $row['tags'] ? explode(',', $row['tags']) : [];
            $tasks[] = $row;
        }
        
        return $tasks;
    }

    public function getTaskById($id, $userId = null) {
        $query = "SELECT t.*, GROUP_CONCAT(tg.name) as tags 
                 FROM tasks t 
                 LEFT JOIN task_tags tt ON t.id = tt.task_id 
                 LEFT JOIN tags tg ON tt.tag_id = tg.id 
                 WHERE t.id = ? ";
        
        if ($userId !== null) {
            $query .= "AND t.user_id = ? ";
        }
        
        $query .= "GROUP BY t.id";
        
        $stmt = mysqli_prepare($this->conn, $query);
        if ($userId !== null) {
            mysqli_stmt_bind_param($stmt, 'ii', $id, $userId);
        } else {
            mysqli_stmt_bind_param($stmt, 'i', $id);
        }
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error fetching task: " . mysqli_error($this->conn));
        }
        
        $result = mysqli_stmt_get_result($stmt);
        $task = mysqli_fetch_assoc($result);
        
        if ($task) {
            $task['tags'] = $task['tags'] ? explode(',', $task['tags']) : [];
        }
        
        return $task;
    }

    public function createTask($title, $description, $category, $priority, $userId) {
        $title = mysqli_real_escape_string($this->conn, $title);
        $description = mysqli_real_escape_string($this->conn, $description);
        $category = mysqli_real_escape_string($this->conn, $category);
        $priority = mysqli_real_escape_string($this->conn, $priority);
        
        $query = "INSERT INTO tasks (title, description, category, priority, user_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 'ssssi', $title, $description, $category, $priority, $userId);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error creating task: " . mysqli_error($this->conn));
        }
        
        return mysqli_insert_id($this->conn);
    }
}