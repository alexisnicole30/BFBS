<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cartId = $_POST['cartId'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "blissFul_DB";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Remove item from cart
    $sql = "DELETE FROM cart_tbl WHERE cart = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cartId);
    $stmt->execute();
    $stmt->close();

    $conn->close();
}
?>
