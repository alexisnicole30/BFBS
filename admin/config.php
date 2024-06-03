<!DOCTYPE html>
<html>

<head>
  <title>Database Setup</title>
</head>

<body>
  <?php
  // Enable error reporting
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  // Database connection details
  $servername = "127.0.0.1:3306";
  $username = "u753706103_blissfulbqt";
  $password = "dF0tj?A=7]|";
  $dbname = "u753706103_blissful_db";

  // Create connection
  $conn = new mysqli($servername, $username, $password);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Create database
  $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
  if ($conn->query($sql) === TRUE) {
    echo "Database '$dbname' created successfully.<br>";
  } else {
    echo "Error creating database '$dbname': " . $conn->error . "<br>";
  }

  // Select database
  $conn->select_db($dbname);

  // SQL script for table creation and data insertion
  $sqlScript = "
    -- Drop existing tables if they exist
    DROP TABLE IF EXISTS cart_tbl, category, customers, cust_address_tbl, flowers_tbl, orders, product, reviews, status, wishlist_tbl;

    --
    -- Table structure for table `cart_tbl`
    --
    CREATE TABLE cart_tbl (
      cart INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      cust_Num INT DEFAULT NULL,
      prod_id INT DEFAULT NULL,
      flower_id INT DEFAULT NULL,
      quantity INT DEFAULT NULL,
      KEY cust_Num (cust_Num),
      KEY prod_id (prod_id),
      KEY flower_id (flower_id)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

    --
    -- Dumping data for table `cart_tbl`
    --
    INSERT INTO cart_tbl (cart, cust_Num, prod_id, flower_id, quantity) VALUES
    (18, 20240010, 13, 0, 1),
    (17, 20240010, 10, 0, 1),
    (16, 20240008, 10, 1, 5),
    (15, 20240008, 12, 0, 3),
    (11, 20240010, 11, 1, 40),
    (10, 20240010, 14, 0, 5),
    (19, 20240010, 7, 0, 1),
    (20, 20240010, 9, 0, 1);

    --
    -- Table structure for table `category`
    --
    CREATE TABLE category (
      category_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      category_name VARCHAR(100) UNIQUE
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;  

    --
    -- Dumping data for table `category`
    --
    INSERT INTO category (category_id, category_name) VALUES
    (1, 'Birthday'),
    (2, 'Anniversary'),
    (3, 'Ceremonies');

    --
    -- Table structure for table `customers`
    --
    CREATE TABLE customers (
      cust_Num INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      cust_Fname VARCHAR(255),
      cust_Lname VARCHAR(255),
      cust_username VARCHAR(255),
      cust_email VARCHAR(255),
      cust_password VARCHAR(255),
      cust_PhoneNumber VARCHAR(50),
      cust_Gender VARCHAR(20),
      cust_Bdate DATE,
      cust_profPic VARCHAR(255)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

    --
    -- Dumping data for table `customers`
    --
    INSERT INTO customers (cust_Num, cust_Fname, cust_Lname, cust_username, cust_email, cust_password, cust_PhoneNumber, cust_Gender, cust_Bdate, cust_profPic) VALUES
    (20240011, 'Kaiser', 'Zamora', 'Kaiser22', 'kaiser@gmail.com', '@Kaiser0201', '09388952457', 'Male', '2003-12-02', 'uploads/66593637897b6_Cute-Ball-Help-icon.png'),
    (20240009, 'Sheryll', 'Javier', 'Sheryll03', 'sheryll@gmail.com', '@Sheryll0201', '09388952457', 'Female', '2003-12-25', 'uploads/online.png'),
    (20240008, 'Ayeng', 'Labiste', 'ayeng100', 'aldohinog00158@usep.edu.ph', '@Ayeng0201', '09388952457', 'Male', '2001-12-02', 'uploads/6647728e15504_ayeng.png'),
    (20240010, 'Miralie', 'Lyka', 'lyka08', 'lyka08@gmail.com', '@Miralie0201', '09388952457', 'Female', '2002-06-08', 'uploads/marina-summers-drag-race-uk-insert-9-1707479427.jpg');

    --
    -- Table structure for table `cust_address_tbl`
    --
    CREATE TABLE cust_address_tbl (
      Address_ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      cust_Num INT NOT NULL,
      cust_fullName VARCHAR(255) NOT NULL,
      cust_phoneNumber VARCHAR(60) NOT NULL,
      cust_Street VARCHAR(255) NOT NULL,
      cust_Purok VARCHAR(60) NOT NULL,
      cust_Barangay VARCHAR(255) NOT NULL,
      cust_City VARCHAR(255) NOT NULL,
      cust_Province VARCHAR(60) NOT NULL,
      KEY cust_Num (cust_Num)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

    --
    -- Dumping data for table `cust_address_tbl`
    --
    INSERT INTO cust_address_tbl (Address_ID, cust_Num, cust_fullName, cust_phoneNumber, cust_Street, cust_Purok, cust_Barangay, cust_City, cust_Province) VALUES
    (13, 20240010, 'Miralie Lyka Borjal', '09388952457', 'Gloria Orang street', 'Purok-6', 'Apokon', 'Tagum City', 'Davao del Norte'),
    (12, 20240011, 'Kaiser Zamora', '09388952457', 'Gloria Orang street', 'Purok-2', 'Apokon', 'Tagum City', 'Davao del Norte'),
    (10, 20240008, 'Ariel Dohinog', '09388952457', 'Gloria Orang street', 'Purok-3', 'Apokon', 'Tagum City', 'Davao del Norte'),
    (11, 20240009, 'Sheryll Javier', '09388952457', 'Gloria Orang street', 'Purok-2', 'Apokon', 'Tagum City', 'Davao del Norte');

    --
    -- Table structure for table `flowers_tbl`
    --
    CREATE TABLE flowers_tbl (
      flower_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      flower_name VARCHAR(100) UNIQUE,
      flower_price DECIMAL(10,2) NOT NULL,
      flower_image VARCHAR(255)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

    --
    -- Dumping data for table `flowers_tbl`
    --
    INSERT INTO flowers_tbl (flower_id, flower_name, flower_price, flower_image) VALUES
    (1, 'Tulips', 20.00, 'Flower/occassion.jpg');

    --
    -- Table structure for table `orders`
    --
    CREATE TABLE orders (
      order_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      prod_id INT NOT NULL,
      cust_Num INT NOT NULL,
      variations VARCHAR(255) NOT NULL,
      quantity INT NOT NULL,
      totalPrice DECIMAL(10,2) NOT NULL,
      cash DECIMAL(10,2) NOT NULL,
      date_purchase TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
      status VARCHAR(50) DEFAULT 'Pending',
      KEY prod_id (prod_id),
      KEY cust_Num (cust_Num),
      KEY fk_status (status)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

    --
    -- Dumping data for table `orders`
    --
    INSERT INTO orders (order_id, prod_id, cust_Num, variations, quantity, totalPrice, cash, date_purchase, status) VALUES
    (45, 9, 20240010, 'Tulips', 1, 470.00, 600.00, '2024-06-02 08:43:34', 'Pending'),
    (44, 7, 20240010, 'Tulips', 1, 470.00, 600.00, '2024-06-02 08:42:10', 'Pending'),
    (43, 14, 20240010, 'Tulips', 1, 400.00, 600.00, '2024-06-02 08:40:50', 'Pending'),
    (42, 12, 20240008, 'Tulips', 3, 375.00, 2000.00, '2024-06-01 14:59:45', 'Completed');

    --
    -- Table structure for table `product`
    --
    CREATE TABLE product (
      prod_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      category_name INT NOT NULL,
      prod_name VARCHAR(255) DEFAULT NULL,
      prod_origPrice INT DEFAULT NULL,
      prod_discountPrice INT DEFAULT NULL,
      prod_qoh INT DEFAULT NULL,
      prod_image VARCHAR(255) DEFAULT NULL,
      rating INT DEFAULT 0,
      FOREIGN KEY (category_name) REFERENCES category (category_name)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
  

    --
    -- Dumping data for table `product`
    --
    INSERT INTO product (prod_id, category_name, prod_name, prod_origPrice, prod_discountPrice, prod_qoh, prod_image) VALUES
    (1, 'Birthdays', 'Product A', 100.00, 5, 10, 'Product/product_a.jpg'),
    (2, 'Anniversaries', 'Product B', 100.00, 6, 15, 'Product/product_a.jpg'),
    (3, 'Ceremonies', 'Product C', 100.00, 6, 15, 'Product/product_a.jpg');

    --
    -- Table structure for table `reviews`
    --
    CREATE TABLE reviews (
      review_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      prod_id INT NOT NULL,
      cust_Num INT NOT NULL,
      rating INT NOT NULL,
      comment TEXT,
      review_date TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
      KEY prod_id (prod_id),
      KEY cust_Num (cust_Num)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

    --
    -- Dumping data for table `reviews`
    --
    INSERT INTO reviews (review_id, prod_id, cust_Num, rating, comment, review_date) VALUES
    (1, 1, 20240011, 5, 'Great product!', '2024-06-01 10:00:00');

    --
    -- Table structure for table `status`
    --
    CREATE TABLE status (
      status_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      status VARCHAR(50) UNIQUE
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

    --
    -- Dumping data for table `status`
    --
    INSERT INTO status (status_id, status) VALUES
    (1, 'Pending'),
    (2, 'Completed'),
    (3, 'Canceled');

    --
    -- Table structure for table `wishlist_tbl`
    --
    CREATE TABLE wishlist_tbl (
      wish_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      cust_Num INT NOT NULL,
      prod_id INT DEFAULT NULL,
      flower_id INT DEFAULT NULL,
      KEY cust_Num (cust_Num),
      KEY prod_id (prod_id),
      KEY flower_id (flower_id)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

    --
    -- Dumping data for table `wishlist_tbl`
    --
    INSERT INTO wishlist_tbl (wish_id, cust_Num, prod_id, flower_id) VALUES
    (1, 20240011, 1, NULL),
    (2, 20240010, NULL, 1);

    ";

  // Execute SQL script
  $sqlStatements = explode(';', $sqlScript);

  foreach ($sqlStatements as $statement) {
    if (trim($statement) != '') {
      if ($conn->query($statement) === TRUE) {
        echo "Query executed successfully: $statement<br>";
      } else {
        echo "Error executing query: $statement<br>Error: " . $conn->error . "<br>";
      }
    }
  }

  // Close connection
  $conn->close();
  ?>
</body>

</html>