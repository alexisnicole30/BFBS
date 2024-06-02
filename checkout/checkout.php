<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Out</title>
    <script>
        function validatePayment() {
            var totalAmount = parseFloat(<?php echo json_encode($totalAmount); ?>);
            var paymentAmount = parseFloat(document.getElementById('paymentAmount').value);

            if (isNaN(paymentAmount) || paymentAmount < totalAmount) {
                alert("Payment amount must be greater than or equal to the total amount.");
                return false;
            }
            return true;
        }
    </script>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f7f7f7;
    }
    .container {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }
    .product {
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
    }
    .product img {
        width: 50px; /* Adjust the width as needed */
        height: 50px; /* Adjust the height as needed */
        float: left;
        margin-right: 10px;
    }
    .product-info {
        padding: 10px;
        overflow: hidden;
    }
    .product-info p {
        margin: 5px 0;
    }
    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    input[type="number"] {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }
    button {
        background-color: #4CAF50;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
    }
    button:hover {
        background-color: #45a049;
    }
</style>

</head>
<body>
<div class="container">
        <h2>Check Out</h2>
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

    // Retrieve selected cart items from the previous page
    $selectedItems = $_POST['selectedItems'];

    // Prepare placeholders for the IN clause
    $placeholders = implode(',', array_fill(0, count($selectedItems), '?'));

    // SQL query to fetch selected cart details
    $sql_fetch_cart = "
    SELECT 
        ct.cart, 
        ct.quantity, 
        ct.flower_id,
        p.prod_id, 
        p.prod_name, 
        p.prod_origPrice, 
        p.prod_discountPrice, 
        p.prod_image,
        f.flower_name,
        f.flower_price
    FROM 
        cart_tbl ct
    JOIN 
        product p ON ct.prod_id = p.prod_id
    LEFT JOIN 
        flowers_tbl f ON ct.flower_id = f.flower_id
    WHERE 
        ct.cust_Num = ? AND
        ct.cart IN ($placeholders)";

    // Create parameter type string dynamically
    $param_types = str_repeat('s', count($selectedItems) + 1); // One additional parameter for cust_Num

    // Bind parameters dynamically
    $stmt_fetch_cart = $conn->prepare($sql_fetch_cart);
    $stmt_fetch_cart->bind_param($param_types, ...array_merge([$cust_Num], $selectedItems));

    // Execute the statement
    $stmt_fetch_cart->execute();
    $result = $stmt_fetch_cart->get_result();

    // Check if there are any selected items
    if ($result->num_rows > 0) {
        echo '<form method="POST" action="saveOrders.php" onsubmit="return validatePayment()">';
        $totalAmount = 0; // Initialize total amount
        // Loop through each selected item and display its information
        while ($row = $result->fetch_assoc()) {

            
            // Display product details
            echo '<div class="cart-prod-container">';
            echo '<div class="cart-product-cardContainer">';
            echo '<div class="check-box-container-product">';
            echo '<img src="../' . htmlspecialchars($row['prod_image']) . '" alt="product" class="cart-product-image">';
            echo '<div class="cartProduct-info">';
            echo '<h3 id="cartProd-name" class="cartProd-name">' . htmlspecialchars($row['prod_name']) . '</h3>';
            echo '<p>Quantity: ' . htmlspecialchars($row['quantity']) . '</p>';
            echo '<p>Original Price: ₱' . number_format($row['prod_origPrice'], 2) . '</p>';
            echo '<p>Discounted Price: ₱' . number_format($row['prod_discountPrice'], 2) . '</p>';
            // Display selected flower variation if available and not 0
            if ($row['flower_id'] != 0) {
                echo '<p>Selected Flower: ' . htmlspecialchars($row['flower_name']) . '</p>';
                echo '<p>Flower Price: ₱' . number_format($row['flower_price'], 2) . '</p>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            // Calculate total amount for each item and add to the overall total
            $constantQty = 1;
            $itemTotal = $row['prod_discountPrice'] * $row['quantity'];
            if ($row['flower_id'] != 0 && $row['flower_price'] !== null) {
                $itemTotal += $row['flower_price'] * $constantQty;
            }
            $totalAmount += $itemTotal;

            // Add hidden input fields to store selected data
            echo '<input type="hidden" name="selectedItems[]" value="' . $row['cart'] . '">';
            echo '<input type="hidden" name="quantity[]" value="' . $row['quantity'] . '">';
            echo '<input type="hidden" name="prod_id[]" value="' . $row['prod_id'] . '">';
            echo '<input type="hidden" name="prod_name[]" value="' . $row['prod_name'] . '">';
            echo '<input type="hidden" name="prod_origPrice[]" value="' . $row['prod_origPrice'] . '">';
            echo '<input type="hidden" name="prod_discountPrice[]" value="' . $row['prod_discountPrice'] . '">';
            if ($row['flower_id'] != 0) {
            echo '<input type="hidden" name="flower_id[]" value="' . $row['flower_id'] . '">';
            echo '<input type="hidden" name="flower_price[]" value="' . $row['flower_price'] . '">';
            echo '<input type="hidden" name="flower_name[]" value="' . $row['flower_name'] . '">';
            }
            }
                // Display total amount
    echo '<input type="hidden" name="totalAmount" value="' . $totalAmount . '">';
    echo '<p>Total Amount: ₱' . number_format($totalAmount, 2) . '</p>';

    // Payment method selection
    echo '<label for="paymentAmount">Enter Payment Amount:</label>';
    echo '<input type="number" id="paymentAmount" name="paymentAmount" min="' . $totalAmount . '" step="0.01" required>';
    
    // Add a submit button
    echo '<button type="submit" name="checkout">Check Out</button>';
    echo '</form>';
} else {
    echo '<p>No items selected.</p>';
}

$stmt_fetch_cart->close();
} else {
    echo '<p>Customer not found.</p>';
    }
    
    $stmt_fetch_cust_num->close();
    $conn->close();
    ?>
    
    </div>
    </body>
    </html>
