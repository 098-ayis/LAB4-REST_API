<?php
  $conn = new mysqli("localhost", "root", "", "fleurchase_db");

    if ($conn->connect_error) {
        echo json_encode([
            "status" => "error",
            "message" => "Database connection failed"
        ]);
        exit;
    }
?>