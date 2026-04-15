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
     //DATABASE CONNECTION
     include 'db_connection.php';

     $inputData = file_get_contents("php://input");
     $request = json_decode($inputData, true); 

     if (!$request) {
        $request = $_POST;
    }

     $username = $request['user_email'] ?? '';
     $pass     = $request['user_pass'] ?? '';

    // Validate input
    if (empty($username) || empty($pass)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error", 
            "code" => 400, 
            "message" => "Username and password are required"
        ]);
        exit;
    }

    $check_stmt = $conn->prepare("SELECT * FROM user WHERE user_email= ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ($pass === $row['user_pass']) {
            echo json_encode([
                "status" => "success", 
                "code" => 200, 
                "message" => "Login successful",
                "full_name" => $row['first_name'] . " " . $row['last_name']
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                "status" => "error",
                "code" => 401,
                "message" => "Incorrect password"
            ]);
        }
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "code" => 404,
            "message" => "Username does not exist"
        ]);
    }

    // Close connections
    $check_stmt->close();
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
