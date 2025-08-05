<?php
include 'db_connect.php';

// Get the posted data (which will be the donation ID)
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

// Check if the ID is valid
if (!empty($id) && is_numeric($id)) {
    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE donations SET is_claimed = 1 WHERE id = ?");
    $stmt->bind_param("i", $id); // "i" means the parameter is an integer

    if ($stmt->execute()) {
        // If the update was successful
        echo json_encode(["message" => "Donation claimed successfully"]);
    } else {
        // If the update failed
        echo json_encode(["error" => "Failed to claim donation"]);
    }

    $stmt->close();
} else {
    // If the ID was invalid or not provided
    echo json_encode(["error" => "Invalid donation ID"]);
}

$conn->close();
?>