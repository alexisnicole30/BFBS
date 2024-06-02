<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../html/login.php");
    exit;
}

// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blissFul_DB";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$username = $_SESSION['username'];
$prod_id = $_POST['prod_id'];
$quantity = $_POST['quantity'];
$flower_id = $_POST['flower_id'];
$cash = $_POST['cash'];

// Fetch product details to update quantity and get available stock
$product_query = "SELECT prod_qoh, prod_discountPrice FROM product WHERE prod_id = ?";
$stmt = $conn->prepare($product_query);
$stmt->bind_param("i", $prod_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

// Compute total amount
$discountedPrice = $product['prod_discountPrice'];
$flower_price = 0;

if ($flower_id != 0) {
    $flower_query = "SELECT flower_price FROM flowers_tbl WHERE flower_id = ?";
    $stmt = $conn->prepare($flower_query);
    $stmt->bind_param("i", $flower_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $flower = $result->fetch_assoc();

    if ($flower) {
        $flower_price = $flower['flower_price'];
    }
}

$totalAmount = ($discountedPrice * $quantity) + $flower_price;

// Check if the inputted quantity exceeds the available stock
if ($quantity > $product['prod_qoh']) {
    echo "<script>alert('Quantity exceeds available stock.'); window.location.href = 'favoritePage.php';</script>";
    exit;
}

// Check if the inputted cash is less than the total amount
if ($cash < $totalAmount) {
    echo "<script>alert('Insufficient cash.'); window.history.back();</script>";
    exit;
}

// Retrieve the customer number (cust_Num) for the logged-in user
$query_cust = "SELECT cust_Num FROM customers WHERE cust_username = ?";
$stmt_cust = $conn->prepare($query_cust);
$stmt_cust->bind_param("s", $username);
$stmt_cust->execute();
$result_cust = $stmt_cust->get_result();
$row_cust = $result_cust->fetch_assoc();
$cust_Num = $row_cust['cust_Num'];

// Update product quantity
$new_quantity = $product['prod_qoh'] - $quantity;
$update_query = "UPDATE product SET prod_qoh = ? WHERE prod_id = ?";
$stmt = $conn->prepare($update_query);
$stmt->bind_param("ii", $new_quantity, $prod_id);
$stmt->execute();

// Check if the selected flower exists and get its details
if ($flower_id != 0) {
    $flower_query = "SELECT flower_name FROM flowers_tbl WHERE flower_id = ?";
    $stmt = $conn->prepare($flower_query);
    $stmt->bind_param("i", $flower_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $flower = $result->fetch_assoc();

    if ($flower) {
        $flower_name = $flower['flower_name'];
    } else {
        die("Selected flower not found.");
    }
} else {
    // If no flower is selected, set a default value
    $flower_name = "None";
}

// Insert order details into orders table
$insert_query = "INSERT INTO orders (prod_id, cust_Num, variations, quantity, totalPrice, cash) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insert_query);
$stmt->bind_param("iissdd", $prod_id, $cust_Num, $flower_name, $quantity, $totalAmount, $cash);
$stmt->execute();

// Check if the insertion was successful
if ($stmt->affected_rows > 0) {
    // Successfully inserted, show alert and redirect
    echo "<script>alert('Purchase successful!'); window.location.href = 'favoritePage.php';</script>";
    exit;
} else {
    // Insertion failed
    echo "<script>alert('Purchase failed. Please try again later.'); window.location.href = 'favoritePage.php';</script>";
}

$conn->close();
?>
