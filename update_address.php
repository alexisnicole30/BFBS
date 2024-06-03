<?php
session_start(); // Resume session

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

// Retrieve form data
$fullName = $_POST['unique-form-fullname'];
$phoneNumber = $_POST['unique-form-PhoneNumber'];
$streetName = $_POST['unique-streetName'];
$purok = $_POST['unique-purok'];
$barangay = $_POST['unique-barangay'];
$city = $_POST['unique-city'];
$province = $_POST['unique-province'];

// Prepare and execute SQL query to update user address information
$sql = "UPDATE cust_address_tbl SET cust_fullName=?, cust_phoneNumber=?, cust_Street=?, cust_Purok=?, cust_Barangay=?, cust_City=?, cust_Province=? WHERE cust_Num = (SELECT cust_Num FROM Customers WHERE cust_username = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $fullName, $phoneNumber, $streetName, $purok, $barangay, $city, $province, $username);

if ($stmt->execute()) {
    $_SESSION['success_message'] = 'Address information updated successfully.';
    // Data update successful, redirect to a success page
    header("Location: CustomerProfile.php");
    exit;
} else {
    // Handle data update failure
    $_SESSION['alert'] = array('type' => 'error', 'message' => 'An error occurred while updating your address information.');
    header("Location: CustomerProfile.php"); // Redirect back to the address form
    exit;
}

$stmt->close();
$conn->close();
?>
