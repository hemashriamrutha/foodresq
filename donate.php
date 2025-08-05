<?php
include 'db_connect.php';

// Get the posted data from the frontend
$data = json_decode(file_get_contents('php://input'), true);

// Sanitize and prepare data
$foodName = $conn->real_escape_string($data['foodName']);
$category = $conn->real_escape_string($data['category']);
$quantity = $conn->real_escape_string($data['quantity']);
$expiryDate = $conn->real_escape_string($data['expiryDate']);
$location = $conn->real_escape_string($data['location']);
$contactInfo = $conn->real_escape_string($data['contactInfo']);

// SQL query to insert data
$sql = "INSERT INTO donations (food_name, category, quantity, expiry_date, location, contact_info)
        VALUES ('$foodName', '$category', '$quantity', '$expiryDate', '$location', '$contactInfo')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["message" => "Donation posted successfully"]);
} else {
    echo json_encode(["error" => "Error: " . $conn->error]);
}

$conn->close();
?>
