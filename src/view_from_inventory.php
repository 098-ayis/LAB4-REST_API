<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //database connection
    include 'db_connection.php';

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
