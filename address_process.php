<?php
session_start(); // Start or resume a session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user is logged in
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        // Redirect to login page if not logged in
        header("Location: login.php");
        exit();
    }
    
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

    // Retrieve cust_Num associated with the logged-in user
    $username = $_SESSION['username'];
    $sql_cust_num = "SELECT cust_Num FROM Customers WHERE cust_username='$username'";
    $result_cust_num = $conn->query($sql_cust_num);
    
    if ($result_cust_num->num_rows > 0) {
        $row = $result_cust_num->fetch_assoc();
        $cust_Num = $row['cust_Num'];
        
        // Check if the customer's details already exist in the database
        $sql_check_duplicate = "SELECT * FROM cust_address_tbl WHERE cust_Num='$cust_Num'";
        $result_check_duplicate = $conn->query($sql_check_duplicate);
        
        if ($result_check_duplicate->num_rows == 0) {
            // Retrieve address information from form submission
            $fullName = isset($_POST['unique-form-fullname']) ? htmlspecialchars($_POST['unique-form-fullname']) : '';
            $phoneNumber = isset($_POST['unique-form-PhoneNumber']) ? htmlspecialchars($_POST['unique-form-PhoneNumber']) : '';
            $streetName = isset($_POST['unique-streetName']) ? htmlspecialchars($_POST['unique-streetName']) : '';
            $purok = isset($_POST['unique-purok']) ? htmlspecialchars($_POST['unique-purok']) : '';
            $barangay = isset($_POST['unique-barangay']) ? htmlspecialchars($_POST['unique-barangay']) : '';
            $city = isset($_POST['unique-city']) ? htmlspecialchars($_POST['unique-city']) : '';
            $province = isset($_POST['unique-province']) ? htmlspecialchars($_POST['unique-province']) : '';

            // Check for empty fields
            if (empty($fullName) || empty($phoneNumber) || empty($streetName) || empty($purok) || empty($barangay) || empty($city) || empty($province)) {
                // Set session alert for error message
                $_SESSION['alert'] = [
                    'type' => 'error',
                    'message' => 'Please fill out all fields.'
                ];
            } else {
                // Prepare and execute SQL statement to insert data into the database
                $sql_insert = "INSERT INTO cust_address_tbl (cust_Num, cust_fullName, cust_phoneNumber, cust_Street, cust_Purok, cust_Barangay, cust_City, cust_Province) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql_insert);
                $stmt->bind_param("ssssssss", $cust_Num, $fullName, $phoneNumber, $streetName, $purok, $barangay, $city, $province);

                if ($stmt->execute()) {
                    // Set session alert for success message
                    $_SESSION['alert'] = [
                        'type' => 'success',
                        'message' => 'Data saved successfully!'
                    ];
                } else {
                    // Set session alert for error message
                    $_SESSION['alert'] = [
                        'type' => 'error',
                        'message' => 'Error: ' . $stmt->error
                    ];
                }

                $stmt->close(); // Close prepared statement
            }
        } else {
            // Customer's details already exist in the database
            $_SESSION['alert'] = [
                'type' => 'error',
                'message' => 'You have already submitted your details.'
            ];
        }
    } else {
        // Error retrieving cust_Num
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Error retrieving customer information.'
        ];
    }

    // Close database connection
    $conn->close();

    // Redirect back to the CustomerProfile.php page
    header("Location: CustomerProfile.php");
    exit();
} else {
    // If the form was not submitted, redirect to the CustomerProfile.php page
    header("Location: CustomerProfile.php");
    exit();
}
?>
