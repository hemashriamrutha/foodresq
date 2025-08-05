<?php
include 'db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

// Security: Ensure ID is a valid number
if (!empty($id) && is_numeric($id)) {
    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM donations WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Donation deleted successfully"]);
    } else {
        echo json_encode(["error" => "Failed to delete donation"]);
    }
    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid ID"]);
}
$conn->close();
?>