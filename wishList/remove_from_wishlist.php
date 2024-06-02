<?php
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

// Check if product ID is provided via POST request
if (isset($_POST['prod_id'])) {
    // Retrieve product ID from POST data
    $prod_id = $_POST['prod_id'];

    // Prepare and execute SQL query to remove the product from the wishlist
    $sql = "DELETE FROM wishlist_tbl WHERE prod_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $prod_id);

    if ($stmt->execute()) {
        // Product removed successfully
        echo "Product removed from wishlist.";
    } else {
        // Error occurred while removing product
        echo "Error: Unable to remove product from wishlist.";
    }

    $stmt->close();
} else {
    // Product ID not provided
    echo "Error: Product ID not provided.";
}

// Close database connection
$conn->close();
?>
