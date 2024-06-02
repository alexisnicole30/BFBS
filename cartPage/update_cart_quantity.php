<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cartId = $_POST['cartId'];
    $change = $_POST['change'];

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

    // Update quantity
    $sql = "UPDATE cart_tbl SET quantity = quantity + ? WHERE cart = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $change, $cartId);
    $stmt->execute();
    $stmt->close();

    $conn->close();
}
?>
