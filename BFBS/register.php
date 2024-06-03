<?php
session_start();

// Database configuration
$servername = "localhost";
$username = "u753706103_blissfulbqt";
$password = "dF0tj?A=7]|";
$dbname = "u753706103_blissful_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $firstName = htmlspecialchars($_POST['firstName']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $userName = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']); // Plain-text password
    $phoneNumber = htmlspecialchars((string)$_POST['phoneNumber']);
    $gender = ucfirst(htmlspecialchars($_POST['gender'])); // Capitalize first letter
    $birthdate = htmlspecialchars($_POST['birthdate']);

    // Check if username is already taken
    $checkUsernameSql = "SELECT cust_username FROM customers WHERE cust_username = ?";
    $stmt = $conn->prepare($checkUsernameSql);
    $stmt->bind_param("s", $userName);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Username already taken!'
        ];
        $stmt->close();
        $conn->close();
        header("Location: ./html/Registration.php");
        exit();
    }
    $stmt->close();

    // Handle file upload
    $profilePic = $_FILES['profilePic'];
    $targetDir = "uploads/";
    $fileName = basename($profilePic["name"]);
    $targetFile = $targetDir . uniqid() . '_' . $fileName; // Unique filename
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a valid image
    $check = getimagesize($profilePic["tmp_name"]);
    if ($check === false) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'File is not an image!'
        ];
        $uploadOk = 0;
        header("Location: ./html/Registration.php");
        exit();
    }

    // Check file size (1MB limit)
    if ($profilePic["size"] > 1000000) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Sorry, your file is too large!'
        ];
        $uploadOk = 0;
        header("Location: ./html/Registration.php");
        exit();
    }

    // Allow certain file formats (JPG, JPEG, PNG)
    $allowedExtensions = ["jpg", "jpeg", "png"];
    if (!in_array($imageFileType, $allowedExtensions)) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Sorry, only JPG, JPEG, PNG files are allowed!'
        ];
        $uploadOk = 0;
        header("Location: ./html/Registration.php");
        exit();
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Sorry, your file was not uploaded!'
        ];
        header("Location: ./html/Registration.php");
        exit();
    } else {
        // Ensure target directory exists
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true); // Create directory recursively
        }

        // Attempt to upload file
        if (move_uploaded_file($profilePic["tmp_name"], $targetFile)) {
            // Insert data into database using prepared statement
            $sql = "INSERT INTO Customers (cust_Fname, cust_Lname, cust_username, cust_email, cust_password, cust_PhoneNumber, cust_Gender, cust_Bdate, cust_profPic) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssss", $firstName, $lastName, $userName, $email, $password, $phoneNumber, $gender, $birthdate, $targetFile);

            if ($stmt->execute()) {
                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => 'Registered successfully!'
                ];
                $stmt->close();
                $conn->close();
                header("Location: ./html/Registration.php");
                exit();
            } else {
                $_SESSION['alert'] = [
                    'type' => 'error',
                    'message' => 'Error: ' . $stmt->error
                ];
                $stmt->close();
                $conn->close();
                header("Location: ./html/Registration.php");
                exit();
            }
        } else {
            $_SESSION['alert'] = [
                'type' => 'error',
                'message' => 'Sorry, there was an error uploading your file.'
            ];
            header("Location: ./html/Registration.php");
            exit();
        }
    }
}
?>
