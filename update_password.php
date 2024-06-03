<?php
session_start();

// Check if user is logged in
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

// Function to check if password meets strength requirements
function isStrongPassword($password) {
    // You can implement your own password strength criteria here
    // For example, you might check for a minimum length, presence of uppercase letters, lowercase letters, numbers, and special characters
    if (strlen($password) >= 8 && preg_match("/[A-Z]/", $password) && preg_match("/[a-z]/", $password) && preg_match("/\d/", $password) && preg_match("/[!@#$%^&*()\-_=+{};:,<.>]/", $password)) {
        return true;
    }
    return false;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['newPassBtn'])) {
    // Validate form data
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if any field is empty
    if (empty($newPassword) || empty($confirmPassword)) {
        $_SESSION['alert'] = array('type' => 'error', 'message' => 'Please fill in all fields.');
        header("Location: CustomerProfile.php");
        exit;
    }

    // Check if passwords match
    if ($newPassword === $confirmPassword) {
        // Check if the new password meets strength requirements
        if (!isStrongPassword($newPassword)) {
            $_SESSION['alert'] = array('type' => 'warning', 'message' => 'Your password is not strong enough. Please use a combination of at least 8 characters including uppercase letters, lowercase letters, numbers, and special characters.');
            header("Location: CustomerProfile.php");
            exit;
        }

        // Retrieve username from session
        $username = $_SESSION['username'];

        // Prepare and execute SQL query to update password
        $sql = "UPDATE Customers SET cust_password=? WHERE cust_username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $newPassword, $username);

        if ($stmt->execute()) {
            // Password update successful
            $_SESSION['alert'] = array('type' => 'success', 'message' => 'Password updated successfully.');
        } else {
            // Password update failed
            $_SESSION['alert'] = array('type' => 'error', 'message' => 'Failed to update password.');
        }

        $stmt->close();
    } else {
        // Passwords don't match
        $_SESSION['alert'] = array('type' => 'error', 'message' => 'Passwords do not match.');
    }

    // Redirect back to the page with the form
    header("Location: CustomerProfile.php");
    exit;
}

$conn->close();
?>
