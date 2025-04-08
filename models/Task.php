<?php

require_once(__DIR__ . '/../config/database.php');

class Task {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    public function getAllTasks() {
        $query = "SELECT t.*, GROUP_CONCAT(tg.name) as tags 
                 FROM tasks t 
                 LEFT JOIN task_tags tt ON t.id = tt.task_id 
                 LEFT JOIN tags tg ON tt.tag_id = tg.id 
                 GROUP BY t.id 
                 ORDER BY t.created_at DESC";
        
        $result = mysqli_query($this->conn, $query);
        
        if (!$result) {
            throw new Exception("Error fetching tasks: " . mysqli_error($this->conn));
        }
        
        $tasks = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $row['tags'] = $row['tags'] ? explode(',', $row['tags']) : [];
            $tasks[] = $row;
        }
        
        return $tasks;
    }

    public function getTaskById($id) {
        $id = mysqli_real_escape_string($this->conn, $id);
        
        $query = "SELECT t.*, GROUP_CONCAT(tg.name) as tags 
                 FROM tasks t 
                 LEFT JOIN task_tags tt ON t.id = tt.task_id 
                 LEFT JOIN tags tg ON tt.tag_id = tg.id 
                 WHERE t.id = ? 
                 GROUP BY t.id";
        
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        
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
}