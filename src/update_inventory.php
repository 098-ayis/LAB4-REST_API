<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    //database connection
    include 'db_connection.php';

    $inputData = file_get_contents("php://input");
    $request = json_decode($inputData, true); 

    $inventory_id = $request['inventory_id'] ?? '';
    $flower_name  = $request['flower_name'] ?? '';
    $flower_image = $request['flower_image'] ?? '';
    $stock        = $request['stock'] ?? '';
    $price        = $request['base_price_per_stem'] ?? '';
    $date_arrived = $request['date_arrived'] ?? '';
    $shelf_life   = $request['shelf_life'] ?? '';
    
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

    //check if record of product exists
    $check_stmt = $conn->prepare("SELECT * FROM inventory WHERE inventory_id= ?");
    $check_stmt->bind_param("i", $inventory_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

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

    $stmt = $conn->prepare("UPDATE inventory SET
                            flower_name = ?,
                            flower_image = ?,
                            stock = ?,
                            base_price_per_stem = ?,
                            date_arrived = ?,
                            shelf_life = ?
                            WHERE inventory_id = ?");

    $stmt->bind_param("ssdisii", $flower_name, $flower_image, $stock, $price, $date_arrived, $shelf_life, $inventory_id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "code" => 200,
            "message" => "Inventory updated successfully"
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "code" => 500,
            "message" => "Failed to update inventory"
        ]);
    }

    $stmt->close();
    $conn->close();
    
} else {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "code" => 405,
        "message" => "Only PUT method allowed"
    ]);
}

?>
