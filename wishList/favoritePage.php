<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/8158c9d3a5.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" type="x-icon" href="../images/BFL.png">
    <link rel="stylesheet" href="./favoritePage.css">
    <link rel="stylesheet" href="../CustomerProfile.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>My Favorite</title>
</head>
<body>

<?php
    session_start();

       // Check if user is not logged in, redirect to login page
       if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: ../html/login.php");
        exit;
        
        }

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

    // Retrieve username from session
    $username = $_SESSION['username'];

     // Prepare and execute SQL query to fetch user profile information based on username
     $sql_profile = "SELECT cust_Fname, cust_Lname, cust_Email, cust_PhoneNumber, cust_Gender, cust_Bdate, cust_ProfPic FROM Customers WHERE cust_username = ?";
     $stmt_profile = $conn->prepare($sql_profile);
     $stmt_profile->bind_param("s", $username);
     $stmt_profile->execute();
     $result_profile = $stmt_profile->get_result();

     // Check if a row is returned for profile information
    if ($result_profile->num_rows > 0) {
        // Fetch user profile information from the result set
        $row_profile = $result_profile->fetch_assoc();
        $firstName = $row_profile['cust_Fname'];
        $lastName = $row_profile['cust_Lname'];
        $email = $row_profile['cust_Email'];
        $phoneNumber = $row_profile['cust_PhoneNumber'];
        $gender = $row_profile['cust_Gender'];
        $birthdate = $row_profile['cust_Bdate'];
        $profilePic = $row_profile['cust_ProfPic'];

        $stmt_profile->close();

        // Prepare and execute SQL query to fetch user address information based on username
        $sql_address = "SELECT cust_fullName, cust_phoneNumber, cust_Street, cust_Purok, cust_Barangay, cust_City, cust_Province FROM cust_address_tbl WHERE cust_Num = (SELECT cust_Num FROM Customers WHERE cust_username = ?)";
        $stmt_address = $conn->prepare($sql_address);
        $stmt_address->bind_param("s", $username);
        $stmt_address->execute();
        $result_address = $stmt_address->get_result();

        // Check if a row is returned for address information
        if ($result_address->num_rows > 0) {
            // Fetch user address information from the result set
            $row_address = $result_address->fetch_assoc();
            $fullName = $row_address['cust_fullName'];
            $addressPhoneNumber = $row_address['cust_phoneNumber'];
            $streetName = $row_address['cust_Street'];
            $purok = $row_address['cust_Purok'];
            $barangay = $row_address['cust_Barangay'];
            $city = $row_address['cust_City'];
            $province = $row_address['cust_Province'];
        }

        $stmt_address->close();
    } else {
        $stmt_profile->close();
        $conn->close();
        // Redirect to login page if no matching user is found (should not happen if user is logged in)
        header("Location: login.php");
        exit;
    }

    $conn->close();

?>


    <header class = "header">
        <div class="top_inner">
            <div class="navigation">
                <div style="width: 50%">
                    <img src="../images/BFL.png" alt="logo">
                </div>
    
                <div class="search">
                    <a href="#" class="navigation_links2">
                        <div>
                            <form action="#" class="search-container">
                            <input type="text" placeholder="" name="search" onclick="searchOpenOverlay()" onfocus="openNewPage()">
                                <button type="submit">Search</button>
                            </form>
                        </div>
                    </a>
    
                    <a href="#" class="navigation_links">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="icon " fill="currentColor" id="open-filter-btn">

                                <path d="M3.9 54.9C10.5 40.9 24.5 32 40 32H472c15.5 0 29.5 8.9 36.1 22.9s4.6 30.5-5.2 42.5L320 320.9V448c0 12.1-6.8 23.2-17.7 28.6s-23.8 4.3-33.5-3l-64-48c-8.1-6-12.8-15.5-12.8-25.6V320.9L9 97.3C-.7 85.4-2.8 68.8 3.9 54.9z"/>
                            </svg>
                        </div>
                    </a>
                </div>
    
                <div class="right_icons">
                    <a href="#" class="navigation_links">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="icon" fill="currentColor" id="notification-icon">

                                <path d="M224 0c-17.7 0-32 14.3-32 32V51.2C119 66 64 130.6 64 208v18.8c0 47-17.3 92.4-48.5 127.6l-7.4 8.3c-8.4 9.4-10.4 22.9-5.3 34.4S19.4 416 32 416H416c12.6 0 24-7.4 29.2-18.9s3.1-25-5.3-34.4l-7.4-8.3C401.3 319.2 384 273.9 384 226.8V208c0-77.4-55-142-128-156.8V32c0-17.7-14.3-32-32-32zm45.3 493.3c12-12 18.7-28.3 18.7-45.3H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7z"/>
                            </svg>
                        </div>
                    </a>
                    <a href="favoritePage.php" class="navigation_links">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="icon" fill="currentColor">
                                <path d="M225.8 468.2l-2.5-2.3L48.1 303.2C17.4 274.7 0 234.7 0 192.8v-3.3c0-70.4 50-130.8 119.2-144C158.6 37.9 198.9 47 231 69.6c9 6.4 17.4 13.8 25 22.3c4.2-4.8 8.7-9.2 13.5-13.3c3.7-3.2 7.5-6.2 11.5-9c0 0 0 0 0 0C313.1 47 353.4 37.9 392.8 45.4C462 58.6 512 119.1 512 189.5v3.3c0 41.9-17.4 81.9-48.1 110.4L288.7 465.9l-2.5 2.3c-8.2 7.6-19 11.9-30.2 11.9s-22-4.2-30.2-11.9zM239.1 145c-.4-.3-.7-.7-1-1.1l-17.8-20c0 0-.1-.1-.1-.1c0 0 0 0 0 0c-23.1-25.9-58-37.7-92-31.2C81.6 101.5 48 142.1 48 189.5v3.3c0 28.5 11.9 55.8 32.8 75.2L256 430.7 431.2 268c20.9-19.4 32.8-46.7 32.8-75.2v-3.3c0-47.3-33.6-88-80.1-96.9c-34-6.5-69 5.4-92 31.2c0 0 0 0-.1 .1s0 0-.1 .1l-17.8 20c-.3 .4-.7 .7-1 1.1c-4.5 4.5-10.6 7-16.9 7s-12.4-2.5-16.9-7z"/>
                            </svg>
                        </div>
                    </a>
                    <a href="../cartPage/cartPage.php" class="navigation_links">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="icon" fill="currentColor">
                                <path d="M160 112c0-35.3 28.7-64 64-64s64 28.7 64 64v48H160V112zm-48 48H48c-26.5 0-48 21.5-48 48V416c0 53 43 96 96 96H352c53 0 96-43 96-96V208c0-26.5-21.5-48-48-48H336V112C336 50.1 285.9 0 224 0S112 50.1 112 112v48zm24 48a24 24 0 1 1 0 48 24 24 0 1 1 0-48zm152 24a24 24 0 1 1 48 0 24 24 0 1 1 -48 0z"/>
                            </svg>
                        </div>
                    </a>
                    <div id="greeting">Hi, <span id="storedFirstName"><?php echo $firstName; ?></span>!</div>
                    <div class="dropdown">
                        <a href="#" class="navigation_links" onclick="toggleDropdown()">
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="icon" fill="currentColor">
                            <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/>
                          </svg>
                        </a>
                        <div class="dropdown-content" id="dropdownContent">
                            <img src="../<?php echo htmlspecialchars($profilePic); ?>" alt="profile-pic" id="profile-picture" class="profile-picture">
                            <a for="profile-pic" id="profile-username"><span id="storedUsername"><?php echo $firstName . " " . $lastName ?></span></a>
                            <a href="../CustomerProfile.php#rightSide-container">My Account</a>
                            <a href="../CustomerProfile.php#myPurchases">My Purchases</a>
                            <a href="#" id="logout">Logout</a>
                        </div>
                      </div>
                      
                </div>
            </div>
        </div>
        <div class="navigation2">
            <a href="../what_s_new.php" class="navigation_links">What's New</a>
            <a href="../sales.php" class="navigation_links">Sales</a>
            <a href="../C..php" class="navigation_links">Occasion</a>
            </div>   
         </header>

    <section class="favorite-container">
       <div class="fav-header">
        <h1 id="myWishlist-title" class="myWishlist-title">My Wishlist</h1>
        <hr class="line-separator">
       </div>
       <div class="fav-prod-container">

       <?php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../html/login.php");
    exit;
}

// Establish database connection
$servername = "127.0.0.1:3306";
    $username = "u753706103_blissfulbqt";
    $password = "dF0tj?A=7]|";
    $dbname = "u753706103_blissful_db";

$conn = new mysqli($servername, $user_name, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve username from session
$username = $_SESSION['username'];

// Query to fetch wishlist items for the logged-in user
$query = "SELECT p.prod_id, p.prod_name, p.prod_origPrice, p.prod_discountPrice, p.prod_image 
          FROM wishlist_tbl w
          JOIN Product p ON w.prod_id = p.prod_id
          JOIN customers c ON w.cust_Num = c.cust_Num
          WHERE c.cust_username = '$username'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="fav-product-cardContainer">
            <div class="fav-prod-info">
                <div class="fav-prod-details">
                    <img src="../<?php echo $row['prod_image']; ?>" alt="product" class="fav-product-image">
                    <h1 id="fav-product-name" class="fav-product-name"><?php echo $row['prod_name']; ?></h1>
                    <div class="fav-prod-prices">
                        <p id="favProd-original-price" class="favProd-original-price">₱<?php echo $row['prod_origPrice']; ?></p>
                        <p id="favProd-discounted-price" class="favProd-discounted-price">₱<?php echo $row['prod_discountPrice']; ?></p>
                    </div>
                </div>
                <hr class="line-separator">
                <div class="fav-buttons">
                    <button class="fav-remove-btn" data-prod-id="<?php echo $row['prod_id']; ?>">Remove</button>
                    <button class="fav-BuyNow-btn" data-prod-id="<?php echo $row['prod_id']; ?>">Buy Now</button>
                    <button class="fav-AddCart-btn" data-prod-id="<?php echo $row['prod_id']; ?>">
                        <i class="fa-solid fa-cart-plus"></i> Add To Cart
                    </button>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    ?>
    <div class="fav-initial-status" style="display: flex;">
        <i class="fa-solid fa-file"></i>
        <h1>Uh-oh! Your favorites list is feeling a bit lonely.</h1>
    </div>
    <?php
}

$conn->close();
?>



       </div>
    </section>



    <section class="other-information">
        <div class="information-container">
            <div class="information-content">
                <h2 class="information-title">Information</h2>
                <hr class="title-line" />
                <div class="sub-info">
                    <a href="#aboutUs">About Us</a>
                    <a href="#">Policies</a>
                    <a href="#">Delivery</a>
                    <a href="#">Reviews</a>
                    <a href="#">Payment</a>
                    <a href="#">Product Care</a>
                    <a href="#">Contact Us</a>
                </div>
            </div>
            <div class="information-content">
                <h2 class="information-title">My Account</h2>
                <hr class="title-line" />
                <div class="sub-info">
                    <a href="./html/Login.html">Login</a>
                    <a href="./html/Registration.html">Register</a>
                </div>
            </div>
            <div class="information-content">
                <h2 class="information-title">Stay Connected</h2> 
                <hr class="title-line" />
                <div class="sub-info">
                    <div class="socMed-icon">
                        <i class="fa-brands fa-square-facebook"></i>
                        <a href="" target="_blank">Facebook</a>
                    </div>
                    <div class="socMed-icon">
                        <i class="fa-brands fa-square-youtube"></i>
                        <a href="" target="_blank">YouTube</a>
                    </div>
                    <div class="socMed-icon">
                        <i class="fa-brands fa-x-twitter"></i>
                        <a href="" target="_blank">Twitter</a>
                    </div>
                    <div class="socMed-icon">
                        <i class="fa-solid fa-envelope"></i>
                        <a href="" target="_blank">Email</a>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </section>

    <section class="footer">
        <div class="footer-container">
            <a href=""><i class="fa-brands fa-cc-mastercard"></i></a>
            <a href=""><i class="fa-brands fa-cc-paypal"></i></a>
        </div>
        <div class="copyright">
            <p>Copyright © 2024 Blissful Bouquet and More. All Rights Reserved.</p>
        </div>
    </section>

  
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
    // Add event listener to all remove buttons
    var removeButtons = document.querySelectorAll(".fav-remove-btn");
    removeButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            // Prompt confirmation dialog
            var confirmRemove = confirm("Are you sure you want to remove this product from your wishlist?");
            if (confirmRemove) {
                // Get the product ID associated with the remove button
                var prodId = this.getAttribute("data-prod-id");
                // Send an AJAX request to remove the product from the wishlist
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Remove the product container from the DOM upon successful removal
                        var productContainer = button.closest(".fav-product-cardContainer");
                        productContainer.parentNode.removeChild(productContainer);
                    }
                };
                xhr.open("POST", "remove_from_wishlist.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send("prod_id=" + prodId);
            }
        });
    });

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
    xhr.open('POST', 'add_to_cart.php', true);
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
            url: "buy_now.php",
            data: {
                prod_id: prodId
            },
            success: function(response) {
                window.location.href = "buy_now.php?prod_id=" + prodId;
            }
        });
    });
});

document.getElementById('logout').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default link behavior
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, logout!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../html/Login.php';
        }
    });
});

function openNewPage() {
            window.location.href = '../search.php';
        }

</script>

  
    <script defer src="./CustomerProfile.js"></script>
</body>
</html>