<?php
header("Content-Type: application/json");
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

if (!$username || !$password) {
    echo json_encode(["status" => "error", "message" => "Missing fields"]);
    exit;
}

$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "invalid", "message" => "User not found"]);
    exit;
}

$row = $result->fetch_assoc();

if (password_verify($password, $row['password'])) {
    echo json_encode(["status" => "success", "message" => "Login success"]);
} else {
    echo json_encode(["status" => "invalid", "message" => "Wrong password"]);
}
?>