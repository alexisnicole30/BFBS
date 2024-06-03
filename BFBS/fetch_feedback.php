<?php
session_start(); // Start session if not started already

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

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // If not logged in, return error message
    echo json_encode(array("error" => "User not logged in"));
    exit;
}

// Retrieve username from session
$username = $_SESSION['username'];

// Retrieve customer number based on username
$sql_get_cust_num = "SELECT cust_Num FROM customers WHERE cust_username = ?";
$stmt_get_cust_num = $conn->prepare($sql_get_cust_num);
$stmt_get_cust_num->bind_param("s", $username);
$stmt_get_cust_num->execute();
$result_cust_num = $stmt_get_cust_num->get_result();

// Check if customer number is found
if ($result_cust_num->num_rows > 0) {
    $row_cust_num = $result_cust_num->fetch_assoc();
    $custNum = $row_cust_num['cust_Num'];

    // Check if product ID is set in the POST data
    if(isset($_POST['prodId'])) {
        // Retrieve product ID from POST data
        $prodId = $_POST['prodId'];

        // SQL query to fetch feedback data for the specified product and customer
        $sql_fetch_feedback = "
            SELECT rev_star, rev_description, rev_date
            FROM reviews
            WHERE prod_id = ? AND cust_Num = ?";
        $stmt_fetch_feedback = $conn->prepare($sql_fetch_feedback);
        $stmt_fetch_feedback->bind_param("ii", $prodId, $custNum);
        $stmt_fetch_feedback->execute();
        $result_feedback = $stmt_fetch_feedback->get_result();

        // Initialize an array to store feedback data
        $feedbackData = array();

        // Check if feedback data exists
        if ($result_feedback->num_rows > 0) {
            // Fetch feedback data and add it to the feedbackData array
            while ($row_feedback = $result_feedback->fetch_assoc()) {
                $feedbackData[] = $row_feedback;
            }
        }

        // Close statement and connection
        $stmt_fetch_feedback->close();
        $conn->close();

        // Encode feedbackData array as JSON and echo it
        echo json_encode($feedbackData);
    } else {
        // If product ID is not set, return an error message
        echo json_encode(array("error" => "Product ID not provided"));
    }
} else {
    // If customer number is not found, return an error message
    echo json_encode(array("error" => "Customer not found"));
}

// Close statement and connection
$stmt_get_cust_num->close();
?>
