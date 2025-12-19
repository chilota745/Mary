<?php
session_start();
include 'db_connect.php';

$method = $_SERVER['REQUEST_METHOD'];

// GET: Fetch all videos
if ($method === 'GET') {
    $stmt = $pdo->query("SELECT * FROM videos ORDER BY date DESC, id DESC");
    $videos = $stmt->fetchAll();
    echo json_encode($videos);
    exit;
}

// POST: Upload new video (Protected)
if ($method === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(["error" => "Unauthorized"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->title) || !isset($data->url)) {
         http_response_code(400);
         echo json_encode(["error" => "Missing required fields"]);
         exit;
    }

    // Basic embed URL processing (optional: could be more robust)
    // For now we trust the admin to paste the full correct URL or we store it as is
    $date = $data->date ?? date('Y-m-d');

    $stmt = $pdo->prepare("INSERT INTO videos (title, date, url, description) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$data->title, $date, $data->url, $data->description ?? ''])) {
         echo json_encode(["status" => "success", "id" => $pdo->lastInsertId()]);
    } else {
         http_response_code(500);
         echo json_encode(["error" => "Failed to save to database"]);
    }
    exit;
}
?>
