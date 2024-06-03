
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
        $sql_address = "SELECT cust_fullname, cust_phonenumber, cust_street, cust_purok, cust_barangay, cust_city, cust_province FROM cust_address_tbl WHERE cust_num = (SELECT cust_num FROM customers WHERE cust_username = ?)";
        $stmt_address = $conn->prepare($sql_address);
        $stmt_address->bind_param("s", $username);
        $stmt_address->execute();
        $result_address = $stmt_address->get_result();

        // Check if a row is returned for address information
        if ($result_address->num_rows > 0) {
            // Fetch user address information from the result set
            $row_address = $result_address->fetch_assoc();
            $fullName = $row_address['cust_fullName'];
            $addressPhoneNumber = $row_address['cust_phonenumber'];
            $streetName = $row_address['cust_street'];
            $purok = $row_address['cust_purok'];
            $barangay = $row_address['cust_barangay'];
            $city = $row_address['cust_city'];
            $province = $row_address['cust_povince'];
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





<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/8158c9d3a5.js" crossorigin="anonymous"></script>
    <title>what's new</title>
    <link rel="stylesheet" href="what_s_new.css">
    <link rel="stylesheet" href="CustomerProfile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-r2bCxINmdn6oZk/x1CEf5+rJbLq0htodAjSoO6JQ91nWllpt4Y3Ivvlqy5zvfNHd" crossorigin="anonymous">
<!--    chat-->
    <style>
        .chat-overlay-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #9b30ff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            z-index: 9999;

        }

        .chat-overlay-container {
            display: none;
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 300px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            z-index: 9998;
        }

        .chat-overlay-header {
            background-color: #9b30ff;
            color: #fff;
            padding: 10px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

        .chat-overlay-close-btn {
            float: right;
            background-color: transparent;
            border: none;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
        }

        .chat-overlay-messages-container {
            overflow-y: auto;
            max-height: 250px;
        }

        .chat-overlay-message {
            margin: 5px;
            padding: 10px;
            border-radius: 5px;
            clear: both;
        }

        .user-message {
            background-color: #9b30ff;
            color: #fff;
            float: right;
        }

        .admin-message {
            background-color: #f0f0f0;
            color: #333;
            float: left;
        }

        .chat-overlay-input {
            display: flex;
            align-items: center;
            padding: 10px;
        }

        .chat-overlay-input-box {
            margin-top: 200px;
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            width: 195px;
        }

        .chat-overlay-send-btn {
            margin-top: 200px;
            width: auto;
            height: 32px;
            margin-left: 10px;
            margin-right: 20px;
            background-color: #9b30ff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            padding: 0 20px;
        }
    </style>
    <style>

        #chat-container {
            width: 500px;
            height: 400px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: none;
            flex-direction: column;
            z-index: 100;
            position: fixed;
            left: 65%;
            bottom: 15%;


        }
        #chat-header {
            background-color: violet;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 1.2em;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        #close-button {
            background: none;
            border: none;
            color: white;
            font-size: 1.2em;
            cursor: pointer;
        }
        #chat-box {
            height: 400px;
            border: 1px solid #ccc;
            overflow-y: scroll;
            padding: 10px;
            margin: 0;
            flex-grow: 1;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            line-height: 1.5em;
        }
        .user {
            background-color: #e6f7ff;
            border-left: 5px solid violet;
            margin-left: auto;
            max-width: 80%;
        }
        .admin {
            background-color: #eaffea;
            border-left: 5px solid darkviolet;
            margin-right: auto;
            max-width: 80%;
        }
        #chat-input {
            display: flex;
            border-top: 1px solid #ccc;
        }
        #message {
            flex: 1;
            padding: 20px;
            border: none;
            border-top: 1px solid #ccc;
        }
        #send-button {
            background-color: darkviolet;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        #send-button:hover {
            background-color: violet;
        }
        #chat-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: darkviolet;
            height: 65px;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 50%;
            font-size: 1.5em;
            cursor: pointer;
        }
        #chat-button:hover {
            background-color: violet;
        }
    </style>
<!--    end chat-->
</head>
<body>



<header class ="header">
    <div class="top_inner">
        <div class="navigation">

            <div style="width: 50%">
                <img src="./images/BFL.png" alt="logo">
            </div>

            <div class="search">
                <a href="#" class="navigation_links2">
                    <div>
                        <form action="#" class="search-container">
                            <input type="text" placeholder="" name="search" onclick="searchOpenOverlay()">
                            <button type="submit">Search</button>
                        </form>
                    </div>
                </a>


            </div>

            <div class="right_icons">

                <a href="./wishList/favoritePage.php" class="navigation_links">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="icon" fill="currentColor">
                            <path d="M225.8 468.2l-2.5-2.3L48.1 303.2C17.4 274.7 0 234.7 0 192.8v-3.3c0-70.4 50-130.8 119.2-144C158.6 37.9 198.9 47 231 69.6c9 6.4 17.4 13.8 25 22.3c4.2-4.8 8.7-9.2 13.5-13.3c3.7-3.2 7.5-6.2 11.5-9c0 0 0 0 0 0C313.1 47 353.4 37.9 392.8 45.4C462 58.6 512 119.1 512 189.5v3.3c0 41.9-17.4 81.9-48.1 110.4L288.7 465.9l-2.5 2.3c-8.2 7.6-19 11.9-30.2 11.9s-22-4.2-30.2-11.9zM239.1 145c-.4-.3-.7-.7-1-1.1l-17.8-20c0 0-.1-.1-.1-.1c0 0 0 0 0 0c-23.1-25.9-58-37.7-92-31.2C81.6 101.5 48 142.1 48 189.5v3.3c0 28.5 11.9 55.8 32.8 75.2L256 430.7 431.2 268c20.9-19.4 32.8-46.7 32.8-75.2v-3.3c0-47.3-33.6-88-80.1-96.9c-34-6.5-69 5.4-92 31.2c0 0 0 0-.1 .1s0 0-.1 .1l-17.8 20c-.3 .4-.7 .7-1 1.1c-4.5 4.5-10.6 7-16.9 7s-12.4-2.5-16.9-7z"/>
                        </svg>
                    </div>
                </a>
                <a href="./cartPage/cartPage.php" class="navigation_links">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="icon" fill="currentColor">
                            <path d="M160 112c0-35.3 28.7-64 64-64s64 28.7 64 64v48H160V112zm-48 48H48c-26.5 0-48 21.5-48 48V416c0 53 43 96 96 96H352c53 0 96-43 96-96V208c0-26.5-21.5-48-48-48H336V112C336 50.1 285.9 0 224 0S112 50.1 112 112v48zm24 48a24 24 0 1 1 0 48 24 24 0 1 1 0-48zm152 24a24 24 0 1 1 48 0 24 24 0 1 1 -48 0z"/>
                        </svg>
                    </div>
                </a>
                <div id="greeting">Hi, <span><?php echo $firstName; ?></span>!</div>
                <div class="dropdown">
                    <a href="#" class="navigation_links" onclick="toggleDropdown()">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="icon" fill="currentColor">
                            <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/>
                        </svg>
                    </a>
                    <div class="dropdown-content" id="dropdownContent">
                    <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="profile-pic" id="profile-picture" class="profile-picture">
                    <a for="profile-pic" id="profile-username"><span id="storedUsername"><?php echo $firstName . " " . $lastName ?></span></a>
                        <a href="CustomerProfile.php">My Account</a>
                        <a href="CustomerProfile.php#myPurchases">My Purchases</a>
                        <a href="logout.php" id="logout">Logout</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="navigation2">
        <a href="what_s_new.php" class="navigation_links">What's New</a>
        <a href="sales.php" class="navigation_links">Sales</a>
        <a href="C..php" class="navigation_links">Occasion</a>
       
    </div>

</header>


<section class="section1">
    <?php
    // Database connection
    $servername = "127.0.0.1:3306";
    $username = "u753706103_blissfulbqt";
    $password = "dF0tj?A=7]|";
    $dbname = "u753706103_blissful_db";

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch products with a discount
    $sql = "SELECT * FROM product LIMIT 3";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<div class='F_container_item3'>";
            echo "<img class='F_item_image' src='./".$row['prod_image'] . "' alt='Flower'>";
            echo "<div class='F_box_info'>";
            echo "<h3 class='F_item_title2'>" . $row['prod_name'] . "</h3>";
            echo "<div class='price-container'>";
            echo "<span class='F_item_text'>$ " . $row['prod_origprice'] . "</span>";
            echo "<span class='price'>$ " . $row['prod_discountprice'] . "</span>"; // Display the product discount
            echo "</div>";
            echo "<form class='star '>";
            echo "<input class='radio-input' type='radio' id='star12' name='star-input' value='5' />";
            echo "<label class='radio-label' for='star12' title='11 stars'>5 stars</label>";
            echo "</form>";
            echo "<p class='F_item_text2'>4.8 (117)</p>"; // Adjust this as needed
            echo "<div class='FlowerBtn'></div>";
            echo "</div></div>";
        }
    } else {
        echo "No products with a discount found";
    }

    $conn->close();
    ?>
</section>

<section class="section2" id="section2">
    <div>
        <div>
            <div class="top_title_img">
                <h1>50% off</h1>
            </div>
        </div>
    </div>
</section>
<section class="section3">

    <?php
    // Database connection
    $conn = new mysqli("localhost", "root", "", "blissful_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch products with a discount
    $sql = "SELECT * FROM product LIMIT 3";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<div class='F_container_item3'>";
            echo "<img class='F_item_image' src='./" . $row['prod_image'] . "' alt='Flower'>";
            echo "<div class='F_box_info'>";
            echo "<h3 class='F_item_title2'>" . $row['prod_name'] . "</h3>";
            echo "<div class='price-container'>";
            echo "<span class='F_item_text'>$ " . $row['prod_origprice'] . "</span>";
            echo "<span class='price'>$ " . $row['prod_discountprice'] . "</span>"; // Display the product discount
            echo "</div>";
            echo "<form class='star '>";
            echo "<input class='radio-input' type='radio' id='star12' name='star-input' value='5' />";
            echo "<label class='radio-label' for='star12' title='11 stars'>5 stars</label>";
            echo "</form>";
            echo "<p class='F_item_text2'>4.8 (117)</p>"; // Adjust this as needed
            echo "<div class='FlowerBtn'></div>";
            echo "</div></div>";
        }
    } else {
        echo "No products with a discount found";
    }

    $conn->close();
    ?>

</section>

<section class="section4" id="section4">
    <div>
        <div>
            <h1>
                Popular Items (Recommendation)
            </h1>

        </div>
    </div>
</section>

<section class="section5">
    <?php
    // Database connection
    $conn = new mysqli("localhost", "root", "", "blissful_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch 3 random products
    $sql = "SELECT * FROM product ORDER BY RAND() LIMIT 3";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<div class='F_container_item3'>";
            echo "<img class='F_item_image' src='./" . $row['prod_image'] . "' alt='Flower'>";
            echo "<div class='F_box_info'>";
            echo "<h3 class='F_item_title2'>" . $row['prod_name'] . "</h3>";
            echo "<div class='price-container'>";
            echo "<span class='F_item_text'>$ " . $row['prod_origprice'] . "</span>";
            echo "<span class='price'>$ " . $row['prod_discountprice'] . "</span>"; // Display the product discount
            echo "</div>";
            echo "<form class='star '>";
            echo "<input class='radio-input' type='radio' id='star12' name='star-input' value='5' />";
            echo "<label class='radio-label' for='star12' title='11 stars'>5 stars</label>";
            echo "</form>";
            echo "<p class='F_item_text2'>4.8 (117)</p>"; // Adjust this as needed
            echo "<div class='FlowerBtn'></div>";
            echo "</div></div>";
        }
    } else {
        echo "No products found";
    }

    $conn->close();
    ?>
</section>

<div style="display: flex; flex-direction: row; gap: 60px; align-items: center; justify-content: center; padding-top: 50px; height: 10%; width: 100%">
    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>


<section class="section6">
    <div class="border">
        <div style="display: flex;flex-direction: row ;width:100%;gap:1250px">

            <h1 style="float:left">Birthday
            </h1>
            <h4 style="float:right; color: rebeccapurple;">show more
            </h4>

        </div>
        <hr>
    </div>
</section>



<section class="section8">
    <?php
    // Database connection
    $conn = new mysqli("localhost", "root", "", "blissful_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch 4 birthday products
    $sql = "SELECT * FROM product WHERE category_name = 'Birthday' LIMIT 4";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<div class='F_container_item3'>";
            echo "<img class='F_item_image' src='./" . $row['prod_image'] . "' alt='Flower'>";
            echo "<div class='F_box_info'>";
            echo "<h3 class='F_item_title2'>" . $row['prod_name'] . "</h3>";
            echo "<div class='price-container'>";
            echo "<span class='F_item_text'>$ " . $row['prod_origprice'] . "</span>";
            echo "<span class='price'>$ " . $row['prod_discountprice'] . "</span>"; // Display the product discount
            echo "</div>";
            echo "<form class='star '>";
            echo "<input class='radio-input' type='radio' id='star12' name='star-input' value='5' />";
            echo "<label class='radio-label' for='star12' title='11 stars'>5 stars</label>";
            echo "</form>";
            echo "<p class='F_item_text2'>4.8 (117)</p>"; // Adjust this as needed
            echo "<div class='FlowerBtn'></div>";
            echo "</div></div>";
        }
    } else {
        echo "No birthday products found";
    }

    $conn->close();
    ?>
</section>

<section class="section7">
    <div class="border">
        <div style="display: flex;flex-direction: row ;width:100%; gap:1200px">

            <h1 style="float:left">Anniversary
            </h1>
            <h4 style="float:right; color: rebeccapurple;">show more
            </h4>
        </div>
        <hr>
    </div>
</section>



<section class="section9">
    <?php
    // Database connection
    $conn = new mysqli("localhost", "root", "", "blissful_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch 4 anniversary products
    $sql = "SELECT * FROM product WHERE category_name = 'Anniversary' LIMIT 4";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<div class='F_container_item3'>";
            echo "<img class='F_item_image' src='./" . $row['prod_image'] . "' alt='Flower'>";
            echo "<div class='F_box_info'>";
            echo "<h3 class='F_item_title2'>" . $row['prod_name'] . "</h3>";
            echo "<div class='price-container'>";
            echo "<span class='F_item_text'>$ " . $row['prod_origprice'] . "</span>";
            echo "<span class='price'>$ " . $row['prod_discountprice'] . "</span>"; // Display the product discount
            echo "</div>";
            echo "<form class='star '>";
            echo "<input class='radio-input' type='radio' id='star12' name='star-input' value='5' />";
            echo "<label class='radio-label' for='star12' title='11 stars'>5 stars</label>";
            echo "</form>";
            echo "<p class='F_item_text2'>4.8 (117)</p>"; // Adjust this as needed
            echo "<div class='FlowerBtn'></div>";
            echo "</div></div>";
        }
    } else {
        echo "No anniversary products found";
    }

    $conn->close();
    ?>
</section>


<section class="section6">
    <div class="border">
        <div style="display: flex;flex-direction: row ;width:100%;gap:1210px">

            <h1 style="float:left">Ceremonies
            </h1>
            <h4 style="float:right; color: rebeccapurple;">show more
            </h4>
        </div>
        <hr>
    </div>
</section>

<section class="section10">
    <?php
    // Database connection
    $conn = new mysqli("localhost", "root", "", "blissful_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch 4 anniversary products
    $sql = "SELECT * FROM product WHERE category_name = 'Ceremonies' LIMIT 4";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<div class='F_container_item3'>";
            echo "<img class='F_item_image' src='./" . $row['prod_image'] . "' alt='Flower'>";
            echo "<div class='F_box_info'>";
            echo "<h3 class='F_item_title2'>" . $row['prod_name'] . "</h3>";
            echo "<div class='price-container'>";
            echo "<span class='F_item_text'>$ " . $row['prod_origprice'] . "</span>";
            echo "<span class='price'>$ " . $row['prod_discountprice'] . "</span>"; // Display the product discount
            echo "</div>";
            echo "<form class='star '>";
            echo "<input class='radio-input' type='radio' id='star12' name='star-input' value='5' />";
            echo "<label class='radio-label' for='star12' title='11 stars'>5 stars</label>";
            echo "</form>";
            echo "<p class='F_item_text2'>4.8 (117)</p>"; // Adjust this as needed
            echo "<div class='FlowerBtn'></div>";
            echo "</div></div>";
        }
    } else {
        echo "No anniversary products found";
    }

    $conn->close();
    ?>
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
                <a href="../html/Login.html">Login</a>
                <a href="../html/Registration.html">Register</a>
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


<!-----overlays------->



<div class="search-overlay" id="search-overlay" style="display: none;">
    <div class="search-overlay-content">
      <p style="font-weight: bold;">POPULAR SUGGESTION</p>
      <hr>
      <p>List of popular suggestions:</p>
      <ul>
        <li><a href="#">L1</a></li>
        <li><a href="#">L2</a></li>
        <li><a href="#">L3</a></li>
      </ul>
      <p>CATEGORIES</p>
      <hr>
      <p><a href="#">Birthday</a> | <a href="#">Anniversary</a> | <a href="#">Ceremonies</a></p>
      <p>PRODUCTS</p>

      <p>See all recommended products here in containers.</p>
      <div class="container">
        <!-- Product 1 -->
        <div>
          <img src="product1.jpg" alt="Product 1">
          <p>Product 1 Name</p>
          <p>Price: $XX.XX</p>
          <p>Rating: X.X</p>
        </div>
        <!-- Product 2 -->
        <div>
          <img src="product2.jpg" alt="Product 2">
          <p>Product 2 Name</p>
          <p>Price: $XX.XX</p>
          <p>Rating: X.X</p>
        </div>
      </div>

      <hr>
      <button id="search-close-overlay" onclick="searchCloseOverlay()" style="bottom: 10px; ">Close</button>
    </div>
  </div>

  <!-- Filter -->


   <div class="filter-overlay" style="display: none;">
          <div class="filter-menu">
              <div class="filter-section">
                  <div class="filter-title">Filter:</div>
                   <button class="close-btn" onclick="closeFilterOverlay()">X</button>

                  <div class="filter-title">By Category</div>
                  <div class="divider-line"></div>
                  <button class="filter-btn" onclick="toggleSelection(this)">Birthday</button>
                  <button class="filter-btn" onclick="toggleSelection(this)">Valentines</button>
                  <br>
                  <button class="filter-btn" onclick="toggleSelection(this)">Mothers/Fathers Day</button>
                  <button class="filter-btn" onclick="toggleSelection(this)">Ceremonies</button>
              </div>
              <div class="filter-section">
                  <div class="filter-title">Rating</div>
                  <div class="divider-line"></div>
                  <button class="filter-btn" onclick="toggleSelection(this)">5 Stars</button>
                  <button class="filter-btn" onclick="toggleSelection(this)">4 Stars & Up</button>
                  <br>
                  <button class="filter-btn" onclick="toggleSelection(this)">3 Stars & Up</button>
                  <button class="filter-btn" onclick="toggleSelection(this)">2 Stars & Up</button>
                  <br>
                  <button class="filter-btn" onclick="toggleSelection(this)">1 Star & Up</button>
              </div>
              <div class="filter-section">
                  <div class="filter-title">Price Range</div>
                  <div class="divider-line"></div>
                  <div style="margin-left: 40px;">
                      <input id="minPrice" type="text" class="price-input" placeholder="MIN" style="width: 100px; height: 30px;">
                      <div style="display: inline-block; width: 20px; height: 5px; background-color: white; margin: 0 10px;"></div>
                      <input id="maxPrice" type="text" class="price-input" placeholder="MAX" style="width: 100px; height: 30px;">
                  </div>
                  <br>
                  <button class="filter-btn" onclick="toggleSelection(this)">0 - 300</button>
                  <button class="filter-btn" onclick="toggleSelection(this)">300 - 600</button>
                  <button class="filter-btn" onclick="toggleSelection(this)">600 - 1000</button>
              </div>
              <div class="filter-controls">
                  <button class="reset-btn" onclick="resetForm()">RESET</button>
                  <button class="apply-btn">APPLY</button>
              </div>
          </div>
      </div>





  <div class="noverlay" id="notification-overlay">
    <div class="noverlay-content">
      <h2>Notifications</h2>
      <hr>
      <ul class="notification-list" id="notification-list">
        <!-- Notification items will be added dynamically here -->
      </ul>
      <div class="noverlay-buttons">
        <button id="read-all">Read All</button>
        <button id="delete-all">Delete All</button>
        <button id="close-notification-overlay">Close</button>
      </div>
    </div>
  </div>

<div id="chat-container">
    <div id="chat-header">
        <span>User Chat</span>
        <button id="close-button" onclick="toggleChat()">×</button>
    </div>
    <div id="chat-box">
        <!-- Messages will be loaded here -->
    </div>
    <div id="chat-input">
        <input type="text" id="message" placeholder="Type your message here">
        <button id="send-button" onclick="sendMessage()">Send</button>
    </div>
</div>
<button id="chat-button" onclick="toggleChat()">
    💬
</button>


  <!-----overlays------->



<script>

    let lastScrollTop = 0;

    window.addEventListener("scroll", function() {
        let navigation3 = document.querySelector(".navigation3");
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop){

            navigation3.style.display = "none";
        } else {

            navigation3.style.display = "block";
        }
        lastScrollTop = scrollTop;
    }, false);
</script>


<script>
    let userId = 1; // Assuming the user is logged in and has an ID of 1

    function loadMessages() {
        fetch(`getMessages.php?user_id=${userId}`)
            .then(response => response.json())
            .then(data => {
                const chatBox = document.getElementById('chat-box');
                chatBox.innerHTML = '';
                data.forEach(message => {
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('message');
                    messageElement.classList.add(message.sender);
                    messageElement.innerText = `${message.user_name}: ${message.message}`;
                    chatBox.appendChild(messageElement);
                });
                chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom
            });
    }

    function sendMessage() {
        const message = document.getElementById('message').value;
        fetch('sendMessage.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                message: message,
                sender: 'user', // Assuming 'user' role here
                user_id: userId
            })
        }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('message').value = '';
                    loadMessages();
                } else {
                    alert('Error sending message');
                }
            });
    }

    function toggleChat() {
        const chatContainer = document.getElementById('chat-container');
        chatContainer.style.display = chatContainer.style.display === 'none' ? 'flex' : 'none';
    }

    loadMessages();
    setInterval(loadMessages, 5000);


</script>


<script src="what_s_new_Script.js"></script>
<script defer src="CustomerProfile.js"></script>


</body>
</html>