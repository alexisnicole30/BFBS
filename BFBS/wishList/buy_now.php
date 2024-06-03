<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../html/login.php");
    exit;
}

// Establish database connection
$servername = "localhost";
$user_name = "root";
$password = "";
$dbname = "blissFul_DB";

$conn = new mysqli($servername, $user_name, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve product ID from GET request
$prod_id = $_GET['prod_id'];

// Fetch product details from the database
$query = "SELECT prod_name, prod_origPrice, prod_discountPrice, prod_image FROM Product WHERE prod_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $prod_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

// Retrieve flowers for the dropdown
$flowers_query = "SELECT flower_id, flower_name, flower_price FROM flowers_tbl";
$flowers_result = $conn->query($flowers_query);
$flowers = [];
if ($flowers_result->num_rows > 0) {
    while ($row = $flowers_result->fetch_assoc()) {
        $flowers[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Buy Now</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .buy-now-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
            display: flex;
        }

        h1 {
            color: #333;
            text-align: center;
            width: 100%;
        }

        .order-form,
        .product-details {
            flex: 1;
            margin: 10px;
        }

        .product-details img {
            max-width: 75%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .product-details p,
        .total-amount p {
            margin: 5px 0;
        }

        .product-details {
            margin-left: 50px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 15px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <div class="buy-now-container">
        <div class="order-form">
            <h1>Confirm Your Purchase</h1>
            <form id="purchaseForm" action="process_purchase.php" method="post">
                <input type="hidden" name="prod_id" value="<?php echo $prod_id; ?>">
                <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" required>
                <div class="flower-selection">
                    <label for="flower">Add a Flower (Optional):</label>
                    <select id="flower" name="flower_id">
                        <option value="" disabled selected>Select Variation</option>
                        <?php foreach ($flowers as $flower) : ?>
                            <option value="<?php echo $flower['flower_id']; ?>" data-price="<?php echo $flower['flower_price']; ?>">
                                <?php echo htmlspecialchars($flower['flower_name']) . " - ₱" . htmlspecialchars($flower['flower_price']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>


                </div>
                <label for="cash">Cash:</label>
                <input type="number" id="cash" name="cash" min="0" required>
                <div class="total-amount">
                    <p>Total Amount: ₱<span id="totalAmount"><?php echo htmlspecialchars($product['prod_discountPrice']); ?></span></p>
                </div>
                <button type="submit">Confirm Purchase</button><br><br>
                <button id="backBtn"> Back</button>
            </form>
        </div>
        <div class="product-details">
            <img src="../<?php echo htmlspecialchars($product['prod_image']); ?>" alt="Product Image">
            <p>Product Name: <?php echo htmlspecialchars($product['prod_name']); ?></p>
            <p>Original Price: ₱<?php echo htmlspecialchars($product['prod_origPrice']); ?></p>
            <p>Discounted Price: ₱<?php echo htmlspecialchars($product['prod_discountPrice']); ?></p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const flowerSelect = document.getElementById('flower');
            const totalAmountElement = document.getElementById('totalAmount');
            const cashInput = document.getElementById('cash');
            const purchaseForm = document.getElementById('purchaseForm');
            const discountedPrice = <?php echo $product['prod_discountPrice']; ?>;

            // Initialize totalAmount
            updateTotalAmount();

            function updateTotalAmount() {
                const quantity = parseInt(quantityInput.value) || 1;
                const selectedFlower = flowerSelect.options[flowerSelect.selectedIndex];
                const flowerPrice = parseFloat(selectedFlower.getAttribute('data-price')) || 0;
                const totalAmount = (discountedPrice * quantity) + flowerPrice;
                totalAmountElement.textContent = totalAmount.toFixed(2);
            }

            function validateForm(event) {
                const totalAmount = parseFloat(totalAmountElement.textContent);
                const cash = parseFloat(cashInput.value);
                if (cash < totalAmount) {
                    alert('The cash provided is less than the total amount.');
                    event.preventDefault();
                }
            }

            quantityInput.addEventListener('input', updateTotalAmount);
            flowerSelect.addEventListener('change', updateTotalAmount);
            purchaseForm.addEventListener('submit', validateForm);
        });

        document.getElementById("backBtn").addEventListener("click", function() {
            history.back();
        });
    </script>
</body>

</html>

<?php $conn->close(); ?>