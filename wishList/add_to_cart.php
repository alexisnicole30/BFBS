<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo 'User not logged in';
    exit;
}

$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "blissFul_DB";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$prod_id = $_POST['prod_id'];
$quantity = $_POST['quantity'];
$flower_id = $_POST['flower_id'];

// Retrieve the customer number (cust_Num) for the logged-in user
$query_cust = "SELECT cust_Num FROM customers WHERE cust_username = ?";
$stmt_cust = $conn->prepare($query_cust);
$stmt_cust->bind_param("s", $username);
$stmt_cust->execute();
$result_cust = $stmt_cust->get_result();
$row_cust = $result_cust->fetch_assoc();
$cust_Num = $row_cust['cust_Num'];

// Check if the product already exists in the cart for the logged-in user
$query_exist = "SELECT COUNT(*) AS num_rows FROM cart_tbl WHERE cust_Num = ? AND prod_id = ?";
$stmt_exist = $conn->prepare($query_exist);
$stmt_exist->bind_param("ii", $cust_Num, $prod_id);
$stmt_exist->execute();
$result_exist = $stmt_exist->get_result();
$row_exist = $result_exist->fetch_assoc();

if ($row_exist['num_rows'] > 0) {
    // Product already exists in the cart, prompt "Already Added"
    echo 'Already Added';
} else {
    // Product doesn't exist in the cart, insert it
    $query_insert = "INSERT INTO cart_tbl (cust_Num, prod_id, quantity, flower_id) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bind_param("iiii", $cust_Num, $prod_id, $quantity, $flower_id);

    if ($stmt_insert->execute()) {
        // Insertion successful, prompt "Success"
        echo 'Success';
    } else {
        // Insertion failed, prompt the error message
        echo 'Error adding product to cart: ' . $stmt_insert->error;
    }
}

$stmt_cust->close();
$stmt_exist->close();
$stmt_insert->close();
$conn->close();
?>
