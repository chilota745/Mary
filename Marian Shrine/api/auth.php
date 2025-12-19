<?php
session_start();
include 'db_connect.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $username = $data->username ?? '';
    $password = $data->password ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        echo json_encode(["status" => "success", "message" => "Login successful"]);
    } else {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
    }
    exit;
}

if ($action === 'logout') {
    session_destroy();
    echo json_encode(["status" => "success", "message" => "Logged out"]);
    exit;
}

if ($action === 'check') {
    if (isset($_SESSION['user_id'])) {
        echo json_encode(["status" => "loggedin", "username" => $_SESSION['username']]);
    } else {
        echo json_encode(["status" => "loggedout"]);
    }
    exit;
}
?>
