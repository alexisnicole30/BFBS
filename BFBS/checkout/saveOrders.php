<?php
session_start();

// Check if user is logged in, otherwise redirect to login page
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

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve customer username from session
$username = $_SESSION['username'];

// SQL query to fetch customer number using username
$sql_fetch_cust_num = "SELECT cust_Num FROM customers WHERE cust_username = ?";
$stmt_fetch_cust_num = $conn->prepare($sql_fetch_cust_num);
$stmt_fetch_cust_num->bind_param("s", $username);
$stmt_fetch_cust_num->execute();
$result_cust_num = $stmt_fetch_cust_num->get_result();

if ($result_cust_num->num_rows > 0) {
    $row_cust_num = $result_cust_num->fetch_assoc();
    $cust_Num = $row_cust_num['cust_Num'];
} else {
    echo 'Customer not found.';
    exit;
}

$stmt_fetch_cust_num->close();

// Retrieve selected items and total amount from POST data
$selectedItems = $_POST['selectedItems'];
$quantity = $_POST['quantity'];
$prod_id = $_POST['prod_id'];
$prod_name = $_POST['prod_name'];
$prod_origPrice = $_POST['prod_origPrice'];
$prod_discountPrice = $_POST['prod_discountPrice'];
$flower_id = $_POST['flower_id'] ?? []; // Use an empty array if not set
$flower_price = $_POST['flower_price'] ?? []; // Use an empty array if not set
$flower_name = $_POST['flower_name'] ?? []; // Use an empty array if not set
$totalAmount = $_POST['totalAmount'];
$paymentAmount = $_POST['paymentAmount'];

if ($paymentAmount < $totalAmount) {
    echo 'Payment amount is less than the total amount.';
    exit;
}

// Begin transaction
$conn->begin_transaction();

try {
    for ($i = 0; $i < count($selectedItems); $i++) {
        // Fetch the current stock for the product
        $sql_fetch_qoh = "SELECT prod_qoh FROM product WHERE prod_id = ?";
        $stmt_fetch_qoh = $conn->prepare($sql_fetch_qoh);
        $stmt_fetch_qoh->bind_param("i", $prod_id[$i]);
        $stmt_fetch_qoh->execute();
        $result_qoh = $stmt_fetch_qoh->get_result();
        $row_qoh = $result_qoh->fetch_assoc();
        $current_qoh = $row_qoh['prod_qoh'];
        $stmt_fetch_qoh->close();

        // Check if the inputted quantity exceeds the available stock
        if ($quantity[$i] > $current_qoh) {
            echo '<script>alert("Quantity for ' . $prod_name[$i] . ' exceeds available stock."); window.location.href = "../cartPage/cartPage.php";</script>';
            exit;
        }

        $itemTotalPrice = $prod_discountPrice[$i] * $quantity[$i];
        if (!empty($flower_id[$i])) {
            $itemTotalPrice += $flower_price[$i];
        }

        // SQL query to insert order details into orders table
        $sql_insert_order = "
            INSERT INTO orders (prod_id, cust_Num, variations, quantity, totalPrice, cash) 
            VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert_order = $conn->prepare($sql_insert_order);
        $variations = empty($flower_id[$i]) ? 'none' : $flower_name[$i];
        $stmt_insert_order->bind_param(
            "iisiid",
            $prod_id[$i],
            $cust_Num,
            $variations,
            $quantity[$i],
            $itemTotalPrice,
            $paymentAmount
        );
        $stmt_insert_order->execute();
        $stmt_insert_order->close();

        // Update prod_qoh by subtracting purchased quantity
        $sql_update_qoh = "UPDATE product SET prod_qoh = prod_qoh - ? WHERE prod_id = ?";
        $stmt_update_qoh = $conn->prepare($sql_update_qoh);
        $stmt_update_qoh->bind_param("ii", $quantity[$i], $prod_id[$i]);
        $stmt_update_qoh->execute();
        $stmt_update_qoh->close();
    }

    // Commit transaction
    $conn->commit();
    echo '<script>alert("Order successfully placed."); window.location.href = "../cartPage/cartPage.php";</script>';

} catch (Exception $e) {
    // Rollback transaction if any error occurs
    $conn->rollback();
    echo '<script>alert("Failed to place order. Please try again."); window.location.href = "../cartPage/cartPage.php";</script>';
    exit;
}

// Close the connection
$conn->close();

?>
