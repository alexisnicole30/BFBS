<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $servername = "127.0.0.1:3306";
    $username = "u753706103_blissfulbqt";
    $password = "dF0tj?A=7]|";
    $dbname = "u753706103_blissful_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind parameters
    $stmt = $conn->prepare("INSERT INTO Product (category_name, prod_name, prod_origPrice, prod_discountPrice, prod_qoh, prod_image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiiis", $category_name, $prod_name, $prod_origPrice, $prod_discountPrice, $prod_qoh, $prod_image);

    // Set parameters
    $category_name = $_POST['category_name'];
    $prod_name = $_POST['prod_name'];
    $prod_origPrice = $_POST['prod_origPrice'];
    $prod_discountPrice = $_POST['prod_discountPrice'];
    $prod_qoh = $_POST['prod_qoh'];
    $prod_image = $_FILES['prod_image']['name'];
    $uploadDir = 'sample/' . $category_name . '/'; // Dynamic folder creation based on category name

    // Create directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Move uploaded file to destination directory
    $uploadFile = $uploadDir . basename($_FILES['prod_image']['name']);
    move_uploaded_file($_FILES['prod_image']['tmp_name'], $uploadFile);

    // Concatenate the upload directory with the filename for the full file path
    $prod_image = $uploadFile;

    // Execute the statement
    if ($stmt->execute()) {
        echo "Product details inserted successfully.";
    } else {
        echo "Error: " . $conn->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Product Details</title>
</head>
<body>
    <h2>Insert Product Details</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
        <label for="category_name">Category Name:</label>
        <select id="category_name" name="category_name" required>
            <?php
            // Database connection
            $servername = "127.0.0.1:3306";
            $username = "u753706103_blissfulbqt";
            $password = "dF0tj?A=7]|";
            $dbname = "u753706103_blissful_db";

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check database connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch category names from the Category table
            $sql = "SELECT category_name FROM Category";
            $result = $conn->query($sql);

            // Check if categories were fetched successfully
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['category_name'] . "'>" . $row['category_name'] . "</option>";
                }
            } else {
                echo "0 results";
            }

            // Close database connection
            $conn->close();
            ?>
        </select><br><br>

        <label for="prod_name">Product Name:</label>
        <input type="text" id="prod_name" name="prod_name" required><br><br>

        <label for="prod_origPrice">Original Price:</label>
        <input type="number" id="prod_origPrice" name="prod_origPrice" required><br><br>

        <label for="prod_discountPrice">Discount Price:</label>
        <input type="number" id="prod_discountPrice" name="prod_discountPrice" required><br><br>

        <label for="prod_qoh">Quantity on Hand:</label>
        <input type="number" id="prod_qoh" name="prod_qoh" required><br><br>

        <label for="prod_image">Product Image:</label>
        <input type="file" id="prod_image" name="prod_image" required><br><br>

        <input type="submit" value="Submit">
        <a href="retrieveData.php">Click me here</a>
    </form>
</body>
</html>

