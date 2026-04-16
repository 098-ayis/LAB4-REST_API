<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //database connection
    include 'db_connection.php';

    $inputData = file_get_contents("php://input");
    $request = json_decode($inputData, true); 

    // Fallback for form-data
    if (!$request) {
            $request = $_POST;
    }

    $email      = $request['user_email'] ?? '';
    $pass       = $request['user_pass'] ?? '';
    $first_name = $request['first_name'] ?? '';
    $last_name  = $request['last_name'] ?? '';
    $contact    = $request['contact'] ?? '';
    $role       = $request['user_role'] ?? 'admin';

    // Validate input
    if (empty($email) || empty($pass) || empty($first_name) || empty($last_name)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error", 
            "code" => 400, 
            "message" => "Invalid input: All fields are required"
        ]);
        exit;
    }

    //check password length
    if(strlen($pass) < 8) {
        http_response_code(400);
        echo json_encode(["status" => "error", "code" => 400, "message" => "Password is too short (minimun 8 characters)"]);
        exit;
    }

    //check if email already exists
    $check_stmt = $conn->prepare("SELECT user_email FROM user WHERE user_email= ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if($result -> num_rows > 0) {
        http_response_code(409);
        echo json_encode([
            "status" => "error", 
            "code" => 409, 
            "message" => "Username is already taken"
        ]);
        $check_stmt->close();
        exit;
    }
    $check_stmt->close();

    $stmt = $conn->prepare("INSERT INTO user (user_email, user_pass, user_role, first_name, last_name, contact) 
                            VALUES (?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssssss", $email, $pass, $role, $first_name, $last_name, $contact);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode([
            "status" => "success",
            "code" => 201,
            "message" => "User created successfully"
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "code" => 500,
            "message" => "Database error: Failed to create user"
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
        "message" => "Only POST method allowed"
    ]);
}

?>