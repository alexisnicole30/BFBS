<!DOCTYPE html>
<html>
<head>
    <title>Insert Flower Data</title>
</head>
<body>
    <h2>Insert Flower Data</h2>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "blissful_db"; // Replace with your actual database name

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $flower_name = $_POST['flower_name'];
        $flower_price = $_POST['flower_price'];
        
        // Handle file upload
        $target_dir = "Flower/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["flower_img"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["flower_img"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["flower_img"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["flower_img"]["tmp_name"], $target_file)) {
                $sql = "INSERT INTO Flowers_tbl (flower_name, flower_price, flower_img) VALUES ('$flower_name', '$flower_price', '$target_file')";

                if ($conn->query($sql) === TRUE) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        $conn->close();
    }
    ?>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="flower_name">Flower Name:</label><br>
        <input type="text" id="flower_name" name="flower_name" required><br><br>
        
        <label for="flower_price">Flower Price:</label><br>
        <input type="text" id="flower_price" name="flower_price" required><br><br>
        
        <label for="flower_img">Flower Image:</label><br>
        <input type="file" id="flower_img" name="flower_img" required><br><br>
        
        <input type="submit" value="Submit">
    </form>
</body>
</html>
