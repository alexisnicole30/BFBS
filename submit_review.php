<?php
session_start();

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

// Database connection
$servername = "127.0.0.1:3306";
    $username = "u753706103_blissfulbqt";
    $password = "dF0tj?A=7]|";
    $dbname = "u753706103_blissful_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Retrieve customer number from session username
$username = $_SESSION['username'];
$sql_cust_num = "SELECT cust_num FROM customers WHERE cust_username = ?";
$stmt_cust_num = $conn->prepare($sql_cust_num);

if (!$stmt_cust_num) {
    echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt_cust_num->bind_param("s", $username);
$stmt_cust_num->execute();
$result_cust_num = $stmt_cust_num->get_result();

if ($result_cust_num->num_rows > 0) {
    $row_cust_num = $result_cust_num->fetch_assoc();
    $cust_Num = $row_cust_num['cust_num'];
} else {
    echo json_encode(['status' => 'error', 'message' => 'Customer not found']);
    exit;
}

// Retrieve review data from POST request
$prod_id = $_POST['prod_id'];
$rev_star = $_POST['rev_star'];
$rev_description = $_POST['rev_description'];

// Check if a review already exists
$sql_check_review = "SELECT * FROM reviews WHERE prod_id = ? AND cust_num = ?";
$stmt_check_review = $conn->prepare($sql_check_review);
$stmt_check_review->bind_param("ii", $prod_id, $cust_Num);
$stmt_check_review->execute();
$result_check_review = $stmt_check_review->get_result();

if ($result_check_review->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'You have already given feedback for this product.']);
    exit;
}

// Insert review into database
$sql_insert_review = "INSERT INTO reviews (prod_id, cust_num, rev_star, rev_description) VALUES (?, ?, ?, ?)";
$stmt_insert_review = $conn->prepare($sql_insert_review);

if (!$stmt_insert_review) {
    echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt_insert_review->bind_param("iiis", $prod_id, $cust_Num, $rev_star, $rev_description);

if ($stmt_insert_review->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Review submitted successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error submitting review: ' . $stmt_insert_review->error]);
}

$stmt_insert_review->close();
$stmt_check_review->close();
$stmt_cust_num->close();
$conn->close();
?>
