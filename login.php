<?php
session_start(); // Start the session to store login state
include 'db_connect.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];

    // Prepare a statement to find the user by username
    $stmt = $conn->prepare("SELECT id, username, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user with that username was found
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // --- SUCCESS! ---
        // The username exists, so we grant access without checking a password.
        
        // Store user info in the session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect to the protected admin dashboard
        header("Location: admin.php");
        exit();
    }
}

// --- FAILURE ---
// If the script gets here, it means the username was not found or the form wasn't submitted.
// Redirect back to the login page with an error message.
header("Location: login.html?error=1");
exit();
?>