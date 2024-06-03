<?php
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../html/login.php");
    exit;
}

// Database credentials
$servername = "127.0.0.1:3306";
    $username = "u753706103_blissfulbqt";
    $password = "dF0tj?A=7]|";
    $dbname = "u753706103_blissful_db";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve username from session
$username = $_SESSION['username'];

// Prepare and execute SQL query to fetch user profile information based on username
$sql_profile = "SELECT cust_fname, cust_lname, cust_email, cust_phonenumber, cust_gender, cust_bdate, cust_profpic FROM customers WHERE cust_username = ?";
$stmt_profile = $conn->prepare($sql_profile);
$stmt_profile->bind_param("s", $username);
$stmt_profile->execute();
$result_profile = $stmt_profile->get_result();

// Check if a row is returned for profile information
if ($result_profile->num_rows > 0) {
    // Fetch user profile information from the result set
    $row_profile = $result_profile->fetch_assoc();
    $firstName = $row_profile['cust_fname'];
    $lastName = $row_profile['cust_lname'];
    $email = $row_profile['cust_email'];
    $phoneNumber = $row_profile['cust_phonenumber'];
    $gender = $row_profile['cust_gender'];
    $birthdate = $row_profile['cust_bdate'];
    $profilePic = $row_profile['cust_profpic'];

    $stmt_profile->close();

    // Prepare and execute SQL query to fetch user address information based on username
    $sql_address = "SELECT cust_fullName, cust_phonenumber, cust_street, cust_purok, cust_barangay, cust_city, cust_province FROM cust_address_tbl WHERE cust_num = (SELECT cust_num FROM customers WHERE cust_username = ?)";
    $stmt_address = $conn->prepare($sql_address);
    $stmt_address->bind_param("s", $username);
    $stmt_address->execute();
    $result_address = $stmt_address->get_result();

    // Check if a row is returned for address information
    if ($result_address->num_rows > 0) {
        // Fetch user address information from the result set
        $row_address = $result_address->fetch_assoc();
        $fullName = $row_address['cust_fullname'];
        $addressPhoneNumber = $row_address['cust_phonenumber'];
        $streetName = $row_address['cust_street'];
        $purok = $row_address['cust_purok'];
        $barangay = $row_address['cust_barangay'];
        $city = $row_address['cust_city'];
        $province = $row_address['cust_province'];
    }

    $stmt_address->close();
} else {
    $stmt_profile->close();
    $conn->close();
    // Redirect to login page if no matching user is found (should not happen if user is logged in)
    header("Location: login.php");
    exit;
}

// Fetch products from the database
$sql = "SELECT prod_id, prod_name, prod_image, prod_origprice, prod_discountprice, prod_qoh FROM product WHERE prod_qoh > 0 AND prod_discountprice > 0";
$result = $conn->query($sql);

$products = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close();

// Convert the PHP array to JSON format
$products_json = json_encode($products);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Page</title>
    <script src="https://kit.fontawesome.com/8158c9d3a5.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="shortcut icon" type="x-icon" href="../images/BFL.png">
    <link rel="stylesheet" href="styles.css">
    <style>
        .navigation {
            left: 20px;
            width: 20px;
            height: 20px;
        }
        .navigation img{
            width: auto;
            height: 100px;
            margin-left: 10px;
            margin-top: 10px;
        }

        .search-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 500px;

        }

        .search-container input,
        .search-container button {
            height: 49px;
            font-size: 18px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-container input {
            padding: 0 15px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
            outline: none;
            width: 100%;
        }

        .search-container input:focus {
            border-color: #6f42c1;
            box-shadow: 0 0 5px rgba(111, 66, 193, 0.5);
        }

        .search-container button {
            border: 1px solid #6f42c1;
            border-left: none;
            border-radius: 0 5px 5px 0;
            background-color: #6f42c1;
            color: white;
            cursor: pointer;
            height: 61px;
            width: 200px;
        }

        .search-container button:hover {
            background-color: #5936a8;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        header {
            display: block;
            unicode-bidi: isolate;
            background-color: violet;
            height: 150px;
            width: 100%;
            position: fixed;
            z-index: 9999;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .main_container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .product-list-container {
            width: 80%;
            height: 80vh;
            overflow-y: auto;
            margin: 0 auto;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
        }

        #product-list {
            margin-top: 150px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(4, auto);
            grid-gap: 20px;
            padding: 20px;
        }

        .product-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.3s;
        }

        .product-container:hover {
            transform: translateY(-10px);
        }

        .product-image {
            text-align: center;
            margin-bottom: 20px;
        }

        .product-image img {
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .product-details {
            text-align: center;
        }

        .product-details h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .product-details .price {
            text-decoration: line-through;
            color: #888;
            margin: 5px 0;
        }

        .product-details .discounted-price {
            color: #e74c3c;
            font-size: 1.2rem;
            margin: 5px 0;
        }

        .quantity {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }

        .quantity button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .quantity button:hover {
            background-color: #c0392b;
        }

        .quantity .quantity-number {
            margin: 0 10px;
            font-size: 1.2rem;
        }

        .button-container {
            text-align: center;
        }

        .fav-BuyNow-btn,
        .fav-AddCart-btn,
        .favorite-button,
        .wishlist-button {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin-top: 10px;
            margin-right: 5px;
        }

        .fav-BuyNow-btn:hover,
        .fav-AddCart-btn:hover,
        .favorite-button:hover,
        .wishlist-button:hover {
            background-color: #27ae60;
        }

        .buy-now-button {
            background-color: #3498db;
        }

        .buy-now-button:hover {
            background-color: #2980b9;
        }

        .favorite-button {
            background-color: #e74c3c;
        }

        .favorite-button:hover {
            background-color: #c0392b;
        }

        .wishlist-button {
            background-color: #f1c40f;
        }

        .wishlist-button:hover {
            background-color: #f39c12;
        }

        .favorite-button i {
            margin-right: 5px;
        }

        .search {
            margin-bottom: 60px;
            margin-left: 50%;
        }

        .search-container input {
            padding: 5px;
            font-size: 16px;
        }

        .search-container button {
            padding: 5px;
            font-size: 16px;
        }

        .back-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #2980b9;
        }

        .filter-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .filter-container select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        .filterBtn{
            padding: 10px;
            border: none;
            width: 100px;
            height: 40px;
            border-radius: 5px;
            color: #ccc;
            font-size: 18px;
            font-weight: bold;
            background: #5936a8;
            text-decoration: none;
        }
        
/*new*/
#overlay5 {
  position: fixed;
  display: none; /* Hidden by default */
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0,0,0,0.5); /* Black background with opacity */
  z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
}
.overlay-content5 {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0px 0px 10px 0px #000;
  width: 300px;
  text-align: center;
}
.overlay-content5 label {
  display: block;
  margin-bottom: 10px;
  font-weight: bold;
}
.overlay-content5 input, .overlay-content5 select {
  width: 100%;
  padding: 8px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}
.overlay-content5 button {
  width: 45%;
  padding: 10px;
  border: none;
  border-radius: 5px;
  margin: 5px;
  cursor: pointer;
  font-weight: bold;
}
.overlay-content5 button#overlay-save-btn5 {
  background-color: #28a745;
  color: white;
}
.overlay-content5 button[onclick*="style.display='none'"] {
  background-color: #dc3545;
  color: white;
}
.overlay-content5 button:hover {
  opacity: 0.9;
}
    </style>
</head>
<body>
    <header class="header">
        <div class="top_inner">
            <div class="navigation">
                <img src="./images/BFL.png" alt="logo">
            </div>
            <div class="search">
                <a href="#" class="navigation_links2">
                    <div>
                        <form action="#" class="search-container">
                            <input type="text" placeholder="Search" name="search">
                            <button type="submit">Search</button>
                        </form>
                        <div class="filter-container">
        <select id="discount-filter">
            <option value="all">All Discounts</option>
            <option value="50">50% or more</option>
            <option value="30">30% or more</option>
            <option value="20">20% or more</option>
            <option value="10">10% or more</option>
        </select>
        <button class="filterBtn" onclick="applyFilter()">Filter</button>
    </div>
                    </div>
                </a>
            </div>
        </div>
    </header>
    <!-- Back button added here -->
    <div class="back-button-container">
        <button class="back-button" onclick="window.history.back();">Back</button>
    </div>

    <main class="main_container">
        <div id="product-list"></div>
    </main>

    <div id="overlay5">
        <div class="overlay-content5">
            <label for="quantity-input">Enter Quantity:</label>
            <input type="number" id="quantity-input" min="1" value="1"><br><br>
            <label for="flower-select">Select Flower:</label>
            <select id="flower-select">
                <option value="">None</option>
                <?php
                // Establish database connection
                $servername = "127.0.0.1:3306";
                $username = "u753706103_blissfulbqt";
                $password = "dF0tj?A=7]|";
                $dbname = "u753706103_blissful_db";
                
                $conn = new mysqli($servername, $user_name, $password, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Query to fetch flower names and prices from flowers_tbl
                $query_flowers = "SELECT flower_id, flower_name, flower_price FROM flowers_tbl";
                $result_flowers = mysqli_query($conn, $query_flowers);
                if (mysqli_num_rows($result_flowers) > 0) {
                    while ($row_flower = mysqli_fetch_assoc($result_flowers)) {
                        // Format flower name and price
                        $flower_info = $row_flower['flower_name'] . " - ₱ " . number_format($row_flower['flower_price'], 2);
                        echo '<option value="' . $row_flower['flower_id'] . '">' . $flower_info . '</option>';
                    }
                } else {
                    echo '<option value="">No flowers available</option>';
                }
                ?>
            </select><br><br>
            <button id="overlay-save-btn5">Save</button>
            <button onclick="document.getElementById('overlay5').style.display='none'">Cancel</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const products = <?php echo $products_json; ?>;
            const productList = document.getElementById('product-list');

            // Render all products initially
            renderProducts(products);

            // Add event listener for search form submission
            const searchForm = document.querySelector('.search-container');
            searchForm.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent form submission

                // Extract search query
                const searchInput = searchForm.querySelector('input');
                const searchQuery = searchInput.value.toLowerCase().trim();

                // Filter products based on search query
                const filteredProducts = products.filter(product =>
                    product.prod_name.toLowerCase().includes(searchQuery) ||
                    product.prod_origPrice.toString().includes(searchQuery) ||
                    product.prod_discountPrice.toString().includes(searchQuery)
                );

                // Render filtered products
                renderProducts(filteredProducts);
            });
        });

        function applyFilter() {
            const filterValue = document.getElementById('discount-filter').value;
            const products = <?php echo $products_json; ?>;
            
            let filteredProducts;

            if (filterValue === 'all') {
                filteredProducts = products;
            } else {
                const discountThreshold = parseFloat(filterValue);
                filteredProducts = products.filter(product => {
                    const discountPercent = ((product.prod_origPrice - product.prod_discountPrice) / product.prod_origPrice) * 100;
                    return discountPercent >= discountThreshold;
                });
            }

            renderProducts(filteredProducts);
        }

        // Function to render products on the page
        function renderProducts(products) {
            const productList = document.getElementById('product-list');
            productList.innerHTML = ''; // Clear existing products

            if (products.length === 0) {
                productList.innerHTML = '<p>No products found</p>';
                return;
            }

            products.forEach(product => {
                const productContainer = document.createElement('div');
                productContainer.classList.add('product-container');
                productContainer.dataset.productId = product.prod_id;
                productContainer.dataset.maxQuantity = product.prod_qoh;

                productContainer.innerHTML = `
                    <div class="product-image">
                        <img src="${product.prod_image}" alt="${product.prod_name}">
                    </div>
                    <div class="product-details">
                        <h2>${product.prod_name}</h2>
                        <p class="price">$${product.prod_origprice}</p>
                        <p class="discounted-price">$${product.prod_discountprice}</p>
                        
                        <div class="button-container">
                            <button class="fav-AddCart-btn" data-prod-id="${product.prod_id}">Add to Cart</button>
                            <button class="fav-BuyNow-btn" data-prod-id="${product.prod_id}">Buy Now</button>
                            <button class="favorite-button" data-prod-id="${product.prod_id}"><i class="fas fa-heart"></i> Favorite</button>
                        </div>
                    </div>
                ` ;

                productList.appendChild(productContainer);
            });
        }

           // AJAX REQUEST

           document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.fav-AddCart-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const prodId = this.getAttribute('data-prod-id');
                    showOverlay(prodId);
                });
            });

            document.getElementById('overlay-save-btn5').addEventListener('click', function() {
                const prodId = document.getElementById('overlay5').getAttribute('data-prod-id');
                const quantity = document.getElementById('quantity-input').value;
                addToCart(prodId, quantity);
            });

            function showOverlay(prodId) {
                const overlay = document.getElementById('overlay5');
                overlay.setAttribute('data-prod-id', prodId);
                overlay.style.display = 'block';
            }
        });

        function addToCart(prodId, quantity) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', './wishlist/add_to_cart.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    if (xhr.responseText === 'Already Added') {
                        alert('Product is already in the cart!');
                    } else if (xhr.responseText === 'Success') {
                        alert('Product added to cart successfully!');
                        document.getElementById('overlay5').style.display = 'none';
                    } else {
                        alert('Error adding product to cart.');
                    }
                } else {
                    alert('Error adding product to cart.');
                }
            };

            // Get the selected flower ID from the dropdown list
            const flowerId = document.getElementById('flower-select').value;

            // Send the product ID, quantity, and flower ID to add_to_cart.php
            xhr.send('prod_id=' + prodId + '&quantity=' + quantity + '&flower_id=' + flowerId);
        }

        //buuy now button

        $(document).ready(function() {
            $('.fav-BuyNow-btn').on('click', function() {
                var prodId = $(this).data('prod-id');

                $.ajax({
                    type: "POST",
                    url: "./wishlist/buy_now.php",
                    data: {
                        prod_id: prodId
                    },
                    success: function(response) {
                        window.location.href = "./wishlist/buy_now.php?prod_id=" + prodId;
                    }
                });
            });
        });

        //Favorite Button
        $(document).ready(function() {
    $(".favorite-button").click(function() {
        var prodId = $(this).data("prod-id");

        $.ajax({
            url: 'add_to_wishlist.php',
            type: 'POST',
            data: {
                prod_id: prodId
            },
            success: function(response) {
                var responseObj = JSON.parse(response);

                if (responseObj.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: responseObj.success,
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else if (responseObj.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: responseObj.error
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.'
                });
            }
        });
    });
});

       
    </script>




</body>
</html>
