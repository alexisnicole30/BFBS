<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="images/BFL.png">
    <link rel="stylesheet" href="./CSS/LandingPageStyle.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/8158c9d3a5.js" crossorigin="anonymous"></script>
    <script src="JS/LandingPageScript.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <title>Blissful Bouquet</title>

</head>
<body>
    <!--HEADER-->
    <div>
    <nav class="Top-Head">
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fas fa-bars"></i>
        </label>

        <img src="images/BFL.png" alt="Logo">

        <ul>
            <li><a href="#aboutUs" id="navi"  class="nav-link">ABOUT</a></li>
            <li><p>|</p></li>
            <li><a href="./html/Registration.php" id="navi">REGISTER</a></li>
            <li><p>|</p></li>
            <li><a href="./html/Login.php" id="navi">LOGIN</a></li>
            <a href="html/Login.php" class="icons"><i class="fa-regular fa-heart"></i></a>
            <a href="html/Login.php" class="icons"><i class="fa-solid fa-cart-plus"></i></a>
            <a href="html/Login.php" class="icons"><i class="fa-solid fa-circle-user"></i></a>
        </ul>
    </nav>
    v </div>
    <!--END OF HEADER-->

    <div class="section1">
        
        <h1 id="start">Brighten Your Day <br/> & And Your Loved Ones!</h1>
        <p id="par">Flowers are great way to express your<br>
        individuality and show how much you care.</p>
        <a href="html/Login.php" class="Shop-btn">SHOP NOW</a>
    </div>

    <div class="bestSeller">
        
        <div class="content">
            <img src="images/des2.png" alt="">
            <h1 id="bestSeller">OUR BESTSELLERS</h1>
            <p>Experience pure bliss with our Best Seller, <br> 
                the Blissful Bouquet—a stunning arrangement <br> 
                of vibrant blooms crafted to perfection, sure to <br>
                enchant any recipient.</p>
        </div>
    </div>

    <section class="product">
    <button class="pre-btn"><i class="fa-solid fa-angle-left"></i></button>
    <button class="nxt-btn"><i class="fa-solid fa-angle-right"></i></button>    
    <div class="product-container">
    <?php
    // Database connection
    $servername = "127.0.0.1:3306";
    $username = "u753706103_blissfulbqt";
    $password = "dF0tj?A=7]|";
    $dbname = "u753706103_blissful_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch products
    $sql = "SELECT p.prod_id, p.prod_image, p.prod_name, p.prod_origprice, p.prod_discountprice, p.rating, SUM(o.quantity) AS total_quantity_sold
            FROM product p
            JOIN orders o ON p.prod_id = o.prod_id
            GROUP BY p.prod_id
            ORDER BY total_quantity_sold DESC
            LIMIT 10";
    $result = $conn->query($sql);

    // Check if there are products in the database
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo '<div class="product-card">';
            echo '<div class="product-image">';
            echo '<img src="../admin/products/Product/' . $row['prod_image'] . '" class="product-thumb" alt="Product Image">';
            echo '<button class="card-btn">Add to Wishlist</button>';
            echo '</div>';
            echo '<div class="product-info">';
            echo '<h2 class="product-name">' . $row['prod_name'] . '</h2>';
            echo '<span class="price">₱' . $row['prod_origprice'] . '</span><span class="actual-price">₱' . $row['prod_discountprice'] . '</span>';

            // Check if the rating is greater than 0
            if ($row['total_quantity_sold'] > 0) {
                echo '<a href="" class="rate-product">';
                echo '<i class="fa-solid fa-star" style="color: #FCB001; margin-right:2px;"></i>';
                echo '<span class="totRate">' . $row['total_quantity_sold'] . '</span>';
                echo '</a>';
            } else {
                // Rating is 0, display empty star
                echo '<a href="" class="rate-product">';
                echo '<i class="fa-regular fa-star"></i>';
                echo '<span class="totRate">' . $row['total_quantity_sold'] . '</span>';
                echo '</a>';
            }

            
            echo '<button class="buy-btn" onclick="window.location.href=\'./html/login.php\'">BUY NOW</button>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "0 results";
    }
    $conn->close();
?>

    </div>
</section>


    <section class="category">
       <div class="category-title">
            <h1>CATEGORY</h1>
       </div>
        <div class="category-container">
            <div class="category-card">
                <div class="category-image">
                    <img src="images/bd.jpg" class="category-thumb" alt="img">
                </div>
                <div class="category-info">
                    <h1 class="bd">BIRTHDAY</h1>
                    <p id="bd-desc">Blissful Bouquet: Blooming birthdays with joy
                                       and elegance, one flower at a time.</p>
                </div>
            </div>
            <div class="category-card">
                <div class="category-image">
                    <img src="images/anniversary.jpg" class="category-thumb" alt="img">
                </div>
                <div class="category-info">
                    <h1 class="bd">ANNIVERSARY</h1>
                    <p id="bd-desc">Cherished Moments: Celebrating Timeless Love 
                                    and Togetherness with Every Petal.</p>
                </div>
            </div>
            <div class="category-card">
                <div class="category-image">
                    <img src="images/occassion.jpg" class="category-thumb" alt="img">
                </div>
                <div class="category-info">
                    <h1 class="bd">OCCASION</h1>
                    <p id="bd-desc">Blooms & Beyond: Creating Magical Moments for Every Occasion.</p>
                </div>
            </div>
        </div>
        <div class="more-cat">
            <a href="html/Login.php">More Categories?</a>
       </div>
    </section>
    
    <section class="aboutUs" id="aboutUs">
        <div class="title-container">
            <h1 class="aboutUs-title">About Us</h1>
            <div class="shape"></div>
        </div>
        <hr class="line"/>
        <div class="aboutUs-container">
            <div class="aboutUs-Info">
                <h1 class="abb">About Blissful Bouquet</h1>
                <p>
                    &emsp; At <b>Blissful Bouquet</b>, we believe in the magic of flowers.
                    Our mission is to spread joy, love, and beauty through
                    thoughtfully crafted bouquets. 
                    <span class="read-more-text">
                        Whether you’re celebrating a special occasion,
                        expressing gratitude, or simply brightening some
                        one’s day our exquisite floral creations are here
                        to make every moment memorable. 
                    </span> 
                    <span class="read-more-btn">Read More...</span>
                </p>
                
            </div>
            <div class="aboutUs-Info">
                <h1 class="abb1">Our Offerings</h1>
            <p>
                <b> Classic Bouquet:</b> A harmonious blend of 5 stems of peach roses,
                5 stems of yellow roses, and 1 stem of hydrangea, adorned with delicate
                baby’s breath and lush green fillers. Wrapped beautifully, this bouquet
                exudes elegance and charm.
            
                <span class="read-more-text">
                    <br><br>
                    <b> Deluxe Bouquet:</b> Elevate your gifting experience with our deluxe option.
                    It features 10 stems of peach roses, 10 stems of yellow roses, and
                    2 stems of hydrangea, all carefully arranged in a stunning presentation.
                    <br><br>
                    <b>Premium Bouquet:</b> For grand gestures, choose our premium bouquet.
                       It boasts 15 stems of      peach roses, 15 stems of yellow roses,
                       and 3 stems of      hydrangea, creating a breathtaking ensemble.
                </span>
                <span class="read-more-btn">Read More...</span>
            </p>
            
            </div>
            <div class="aboutUs-Info">
                <h1 class="abb1">Customization</h1>
                <p>
                    &emsp;At the heart of Blissful Bouquet. We understand that every occasion and every recipient is unique.
                    That’s why we offer personalized options to make your floral gift even more special:
            
                    <span class="read-more-text">
                        <br><br>
                        <b>1. Choose Your Blooms:</b> Select specific flowers to include in your bouquet.
                        Whether it’s the recipient’s favorite flower or a meaningful bloom, we’ll create a
                        custom arrangement just for you.
                        <br><br>
                        <b>2. Color Palette:</b> Let us know your preferred color scheme.
                        Whether you want vibrant and bold hues or soft pastels,
                        we’ll curate a bouquet that matches your vision.
                        <br><br>
                        <b>3. Add-ons:</b> Enhance your bouquet with thoughtful add-ons. Consider including: <br>
                        <b>&emsp;&emsp; Greenery:</b> Eucalyptus, ferns, or other foliage to complement the flowers. <br>
                        <b>&emsp;&emsp; Ribbons and Wraps:</b>Choose from different ribbons and wrapping styles to suit the
                        occasion. <br>
                        <b>&emsp;&emsp; Message Card:</b>Personalize your gift with a heartfelt message.
                        <br><br>
                        <b> 4. Size and Shape:</b> Customize the size and shape of your bouquet.
                        Whether you prefer a compact posy or a cascading arrangement, we’ll tailor it to your preferences.
                        <br><br>
                        <b>5. Special Requests:</b> Have a specific theme or design in mind? Share your ideas with us, and we’ll
                        bring them to life.
                    </span>
                    <span class="read-more-btn">Read More...</span>
                </p>
            
            </div>
            <div class="aboutUs-Info">
                <h1 class="abb1">Fresh Flowers Delivery</h1>

                <p>
                    &emsp; We take pride in delivering fresh flowers across the selected areas in Tagum City.
                    With our hassle-free service, you can trust us to make your loved ones smile. Whether it’s a
                    birthday, anniversary, or any special occasion, we’re committed to spreading happiness.
                    <span class="read-more-text">
                        <br><br>
                        Remember, Blissful Bouquet is all about creating moments of joy,
                        and customization allows us to celebrate those moments uniquely. 🌸✨
                        <br><br>
                        <b>Feel free to explore our website, Blissful Bouquet, and let your imagination bloom! 🌷🎨</b>
                    </span>
                    <span class="read-more-btn">Read More...</span>
                </p>

            </div>
            <div class="aboutUs-Info">
                <div class="mdb-container">
                    <a href="" class="more-details-btn">More Details</a>
                </div>
                
            </div>
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
                    <a href="#">Login</a>
                    <a href="#">Register</a>
                </div>
            </div>
            <div class="information-content">
                <h2 class="information-title">Stay Connected</h2>
                <hr class="title-line" />
                <div class="sub-info">
                    <a href="" ><i class="fa-brands fa-square-facebook"></i><span>Facebook</span></a>
                    <a href="" ><i class="fa-brands fa-youtube"></i><span>YouTube</span></a>
                    <a href="" ><i class="fa-brands fa-square-x-twitter"></i><span>Twitter</span></a>
                    <a href="" ><i class="fa-solid fa-envelope"></i><span>Email</span></a>
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
    
    <!--Back To Top-->
    <a href="#" class="back-to-top" id="backToTopBtn"><i class="fa-solid fa-arrow-up"></i><span>Back to Top</span></a>

    <!--Script-->
    <script defer src="LandingPageScript.js"></script>
</body>
</html>