<?php
include 'db_connect.php';

header('Content-Type: application/json');

$sql = "SELECT id, food_name, category, quantity, expiry_date, location, created_at FROM donations WHERE is_claimed = 0 ORDER BY expiry_date ASC";
$result = $conn->query($sql);

$donations = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $donations[] = $row;
    }
}

echo json_encode($donations);

$conn->close();
?>
