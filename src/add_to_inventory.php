<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to database
    $conn = new mysqli("localhost", "root", "", "fleurchase_db");

    if ($conn->connect_error) {
        echo json_encode([
            "status" => "error",
            "message" => "Database connection failed"
        ]);
        exit;
    }

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
        echo json_encode([
            "status" => "error",
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
        echo json_encode([
            "status" => "success",
            "message" => "Flower added successfully",
            "insert_id" => $stmt->insert_id
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Insert failed"
        ]);
    }

    $stmt->close();
    $conn->close();

} else {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Only POST method allowed"
    ]);
}
?>
