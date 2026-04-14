<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $conn = new mysqli("localhost", "root", "", "fleurchase_db");

    if ($conn->connect_error) {
        echo json_encode([
            "status" => "error",
            "message" => "Database connection failed"
        ]);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM inventory");
    $stmt->execute();
    $result = $stmt->get_result();


    if (!$result) {
        echo json_encode([
            "status" => "error",
            "message" => "Query failed"
        ]);
        exit;
    }

    echo json_encode([
        "status" => "success",
        "data" => $result->fetch_all(MYSQLI_ASSOC)
    ]);

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Only GET method allowed"
    ]);
}
?>
