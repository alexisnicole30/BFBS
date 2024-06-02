<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Retrieve username from session
$username = $_SESSION['username'];

// Establish database connection
$servername = "localhost";
$usernameDB = "root";
$passwordDB = "";
$dbname = "blissFul_DB";

$conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute SQL query to fetch customer number based on username
$sql_cust_num = "SELECT cust_Num FROM customers WHERE cust_username = ?";
$stmt_cust_num = $conn->prepare($sql_cust_num);
$stmt_cust_num->bind_param("s", $username);
$stmt_cust_num->execute();
$result_cust_num = $stmt_cust_num->get_result();

// Check if a row is returned
if ($result_cust_num->num_rows > 0) {
    $row_cust_num = $result_cust_num->fetch_assoc();
    $cust_Num = $row_cust_num['cust_Num'];

    // Check if prod_id is sent via POST
    if (isset($_POST['prod_id'])) {
        $prod_id = $_POST['prod_id'];

        // Check if the product is already in the wishlist
        $sql_check_wishlist = "SELECT * FROM wishlist_tbl WHERE prod_id = ? AND cust_Num = ?";
        $stmt_check_wishlist = $conn->prepare($sql_check_wishlist);
        $stmt_check_wishlist->bind_param("ii", $prod_id, $cust_Num);
        $stmt_check_wishlist->execute();
        $result_check_wishlist = $stmt_check_wishlist->get_result();

        if ($result_check_wishlist->num_rows > 0) {
            echo json_encode(['error' => 'Product already in wishlist']);
        } else {
            // Insert into wishlist_tbl
            $stmt_insert = $conn->prepare("INSERT INTO wishlist_tbl (prod_id, cust_Num) VALUES (?, ?)");
            $stmt_insert->bind_param("ii", $prod_id, $cust_Num);

            if ($stmt_insert->execute()) {
                echo json_encode(['success' => 'Product added to wishlist']);
            } else {
                echo json_encode(['error' => 'Database insertion failed']);
            }

            $stmt_insert->close();
        }

        $stmt_check_wishlist->close();
    } else {
        echo json_encode(['error' => 'Product ID not provided']);
    }
} else {
    echo json_encode(['error' => 'User not found']);
}

$stmt_cust_num->close();
$conn->close();
?>
