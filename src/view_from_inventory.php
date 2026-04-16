<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
// FIXED: Added GET to allowed methods
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Database connection 
    include 'db_connection.php';

    $stmt = $conn->prepare("SELECT * FROM inventory");
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "code" => 500,
            "message" => "Internal server error: Query failed"
        ]);
        $conn->close(); 
        exit;
    }

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "code" => 200,
        "inventory" => $result->fetch_all(MYSQLI_ASSOC)
    ]);

    // close connections
    $stmt->close();
    $conn->close();

} else {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "code" => 405,
        "message" => "Only GET method allowed"
    ]);
}
?>