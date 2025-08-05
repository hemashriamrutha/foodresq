<?php
header('Content-Type: application/json');
include 'db_connect.php';

// Initialize the response array
$response = [];

// 1. Get Core Statistics
$stats_sql = "SELECT
    (SELECT COUNT(*) FROM donations) AS total_donations,
    (SELECT COUNT(*) FROM donations WHERE is_claimed = 1) AS total_claimed";

$stats_result = $conn->query($stats_sql);
$response['stats'] = $stats_result->fetch_assoc();


// 2. Get Recent Donations (last 10)
$recent_sql = "SELECT id, food_name, category, location, is_claimed, created_at FROM donations ORDER BY created_at DESC LIMIT 10";
$recent_result = $conn->query($recent_sql);
$response['recent_donations'] = [];
while($row = $recent_result->fetch_assoc()) {
    $response['recent_donations'][] = $row;
}


// 3. Get Data for Donations Per Month Chart
$chart_sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(id) AS count 
              FROM donations 
              GROUP BY month 
              ORDER BY month ASC";
$chart_result = $conn->query($chart_sql);
$response['chart_data'] = [];
while($row = $chart_result->fetch_assoc()) {
    $response['chart_data'][] = $row;
}


// Send the combined response
echo json_encode($response);

$conn->close();
?>