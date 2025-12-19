<?php
// Database Connection Config
// Update these values with your Hostinger Database Credentials
$host = 'localhost';
$dbname = 'u123456789_marian_shrine'; // Example: u123456789_dbname
$username = 'u123456789_user';        // Example: u123456789_dbuser
$password = 'YourStrongPassword123!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Enable error reporting for debugging (Disable in production if preferred)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Basic security headers for all API responses
    header("Access-Control-Allow-Origin: *"); // Adjust for production security if needed
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    exit();
}
?>
