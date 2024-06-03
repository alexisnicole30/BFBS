<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user is logged in
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        // Check if all required POST data is received
        if (isset($_POST['cartId']) && isset($_POST['quantity']) && isset($_POST['flowerId'])) {
            $cartId = $_POST['cartId'];
            $quantity = $_POST['quantity'];
            $flowerId = $_POST['flowerId'];

            // Establish database connection
            $servername = "127.0.0.1:3306";
    $username = "u753706103_blissfulbqt";
    $password = "dF0tj?A=7]|";
    $dbname = "u753706103_blissful_db";

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check database connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Update cart with selected flower variation
            $sql_update_cart_with_flower = "UPDATE cart_tbl SET flower_id = ? WHERE cart = ? AND cust_Num = ?";
            $stmt_update_cart_with_flower = $conn->prepare($sql_update_cart_with_flower);
            $stmt_update_cart_with_flower->bind_param("iii", $flowerId, $cartId, $_SESSION['cust_Num']);
            $stmt_update_cart_with_flower->execute();
            $stmt_update_cart_with_flower->close();

            // Close database connection
            $conn->close();

            // Send response
            echo "Success";
        } else {
            echo "Missing data";
        }
    } else {
        echo "User not logged in";
    }
} else {
    echo "Invalid request method";
}
?>
