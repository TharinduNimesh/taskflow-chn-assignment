<?php
require_once(__DIR__ . '/config/database.php');

try {
    // Get database instance
    $db = Database::getInstance();
    $connection = $db->getConnection();
    
    // Try a simple query to verify the connection
    $query = "SELECT 1";
    $result = mysqli_query($connection, $query);
    
    if ($result) {
        // If we get here, connection is successful
        echo "✅ Database connection successful!\n";
        echo "Server Info: " . mysqli_get_host_info($connection) . "\n";
        echo "Server Version: " . mysqli_get_server_info($connection) . "\n";
    }
} catch (Exception $e) {
    echo "❌ Connection failed!\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Error Code: " . mysqli_connect_errno() . "\n";
}