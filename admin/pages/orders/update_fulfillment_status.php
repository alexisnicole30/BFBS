<?php
// Database connection details
$servername = "127.0.0.1:3306";
    $username = "u753706103_blissfulbqt";
    $password = "dF0tj?A=7]|";
    $dbname = "u753706103_blissful_db";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get order ID and new status from POST request
$order_id = $_POST['order_id'];
$status = $_POST['status'];

// Update fulfillment status in the database
$sql = "UPDATE orders SET status='$status' WHERE order_id=$order_id";

if ($conn->query($sql) === TRUE) {
    echo "Fulfillment status updated successfully";
} else {
    echo "Error updating fulfillment status: " . $conn->error;
}

$conn->close();
?>
