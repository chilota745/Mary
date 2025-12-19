<?php
session_start();
include 'db_connect.php';

$method = $_SERVER['REQUEST_METHOD'];

// GET: Fetch all images
if ($method === 'GET') {
    $stmt = $pdo->query("SELECT * FROM gallery_images ORDER BY date DESC, id DESC");
    $images = $stmt->fetchAll();
    echo json_encode($images);
    exit;
}

// POST: Upload new image (Protected)
if ($method === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(["error" => "Unauthorized"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"));
    
    if (!isset($data->title) || !isset($data->url) || !isset($data->date)) {
         http_response_code(400);
         echo json_encode(["error" => "Missing required fields"]);
         exit;
    }

    $stmt = $pdo->prepare("INSERT INTO gallery_images (title, date, url, description) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$data->title, $data->date, $data->url, $data->description ?? ''])) {
        echo json_encode(["status" => "success", "id" => $pdo->lastInsertId()]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to save to database"]);
    }
    exit;
}
?>
