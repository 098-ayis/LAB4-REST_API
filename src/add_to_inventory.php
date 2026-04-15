<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //database connection
    include 'db_connection.php';
    
    // Capture incoming JSON data
    $inputData = file_get_contents("php://input");
    $request = json_decode($inputData, true);

    // Fallback for form-data
    if (!$request) {
        $request = $_POST;
    }

    // Validate required fields
    if (
        !isset($request['flower_name']) ||
        !isset($request['flower_image']) ||
        !isset($request['stock']) ||
        !isset($request['base_price_per_stem']) ||
        !isset($request['date_arrived']) ||
        !isset($request['shelf_life'])
    ) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "code" => 400,
            "message" => "Missing required fields"
        ]);
        exit;
    }

    // Prepare insert statement
    $stmt = $conn->prepare("INSERT INTO inventory 
        (flower_name, flower_image, stock, base_price_per_stem, date_arrived, shelf_life) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ssidsi",
        $request['flower_name'],
        $request['flower_image'],
        $request['stock'],
        $request['base_price_per_stem'],
        $request['date_arrived'],
        $request['shelf_life']
    );

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode([
            "status" => "success",
            "code" => 201,
            "message" => "Flower added successfully",
            "insert_id" => $stmt->insert_id
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "code" => 500,
            "message" => "Insert failed"
        ]);
    }

    $stmt->close();
    $conn->close();

} else {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "code" => 405,
        "message" => "Only POST method allowed"
    ]);
}
?>
