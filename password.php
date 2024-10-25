<?php

$data = $_POST;

$newPassword = $data['newpassword'];

$pdo = new PDO('mysql:host=localhost;dbname=flight_pool;charset=utf8', 'root', null, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);

$headers = getallheaders();
$token = isset($headers['Authorization']) ? $headers['Authorization'] : null;

$user = [];

if ($token) {
    $stmt = $pdo->prepare('SELECT first_name, last_name, phone, document_number FROM users WHERE api_token = :token');
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();
}

if (!empty($user)) {
    $stmt = $pdo->prepare("UPDATE users SET password = :newPassword WHERE api_token = :token");
    $stmt->execute(['newPassword' => password_hash($newPassword, PASSWORD_DEFAULT), 'token' => $token]);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode([
        "message" => "Пароль поменялся Ёпта"
    ]);
} else {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode([
        "error" => [
            "code" => 401,
            "message" => "Unauthorized"
        ]
    ]);
}
?>