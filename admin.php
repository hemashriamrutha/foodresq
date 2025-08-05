<?php
// --- START OF PHP SECURITY BLOCK ---

session_start(); // Start the session to access user data

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // If not logged in as admin, redirect to the login page
    header("Location: login.html");
    exit(); // Stop the script immediately
}

// Check if the user clicked the logout link (e.g., admin.php?logout=1)
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy all session data
    header("Location: login.html"); // Redirect to the login page
    exit(); // Stop the script
}
?> <!-- THIS IS THE IMPORTANT CLOSING TAG THAT WAS MISSING -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FoodResQ</title>
    <link rel="stylesheet" href="admin.css"> 
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <header>
        <h1>FoodResQ Admin Dashboard</h1>
        <!-- THIS PHP BLOCK DISPLAYS THE WELCOME MESSAGE AND LOGOUT LINK -->
        <p>
            Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! 
            | 
            <a href="admin.php?logout=1" style="color: white; text-decoration: underline;">Logout</a>
        </p>
    </header>

    <main class="admin-main">
        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Donations</h3>
                <p id="total-donations">0</p>
            </div>
            <div class="stat-card">
                <h3>Food Rescued (Claimed)</h3>
                <p id="total-claimed">0</p>
            </div>
            <div class="stat-card">
                <h3>Unclaimed Items</h3>
                <p id="total-unclaimed">0</p>
            </div>
        </div>

        <!-- Charts and Recent Activity -->
        <div class="dashboard-container">
            <div class="chart-container">
                <h2>Donations Per Month</h2>
                <canvas id="donationsChart"></canvas>
            </div>
            <div class="activity-container">
                <h2>Recent Activity</h2>
                <table class="activity-table">
                    <thead>
                        <tr>
                            <th>Food Name</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="activity-body">
                        <!-- Rows will be inserted here by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="admin.js"></script>
</body>
</html>