<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    //database connection
    include 'db_connection.php';

    $inputData = file_get_contents("php://input");
    $request = json_decode($inputData, true); 

    $inventory_id = $request['inventory_id'] ?? '';

    // Validate id
    if (empty($inventory_id)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error", 
            "code" => 400, 
            "message" => "Invalid input data"
        ]);
        exit;
    }

    $check_stmt = $conn->prepare("SELECT inventory_id FROM inventory WHERE inventory_id= ?");
    $check_stmt->bind_param("i", $inventory_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    //check if record of product exists
    if($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode([
            "status" => "error", 
            "code" => 404, 
            "message" => "Record not found"
        ]);
        $check_stmt->close();
        exit;
    }
    $check_stmt->close();

    //delete record on database
    $stmt = $conn->prepare("DELETE FROM inventory WHERE inventory_id = ?");
    $stmt->bind_param("i", $inventory_id);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "code" => 200,
            "message" => "Product deleted successfully"
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "code" => 500,
            "message" => "Failed to delete product"
        ]);
    }

    // Close connections
    $stmt->close();
    $conn->close();
    
} else {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "code" => 405,
        "message" => "Only DELETE method allowed"
    ]);
}

?>
