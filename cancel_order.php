<?php
session_start();

// Check if user is logged in, otherwise return error
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Establish database connection
$servername = "127.0.0.1:3306";
    $username = "u753706103_blissfulbqt";
    $password = "dF0tj?A=7]|";
    $dbname = "u753706103_blissful_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get order ID from POST data
$order_id = $_POST['order_id'];

// Update the order status to 'Cancel'
$sql_update_status = "UPDATE orders SET status = 'Cancel' WHERE order_id = ?";
$stmt_update_status = $conn->prepare($sql_update_status);
$stmt_update_status->bind_param("i", $order_id);

if ($stmt_update_status->execute()) {
    echo json_encode(['success' => true, 'message' => 'Order canceled successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to cancel order']);
}

// Close the statement and connection
$stmt_update_status->close();
$conn->close();
?>
