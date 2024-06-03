<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/8158c9d3a5.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" type="x-icon" href="./images/BFL.png">
    <title>My Profile</title>
    <link rel="stylesheet" href="CustomerProfile.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>

    <?php
    session_start(); // Resume session

    // Check if user is not logged in, redirect to login page
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: ../html/Login.php");
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

    // Check if the profile info form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveBtn'])) {
        // Retrieve form data
        $firstName = $_POST['firstname'];
        $lastName = $_POST['lastname'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];
        $gender = $_POST['gender'];
        $birthdate = $_POST['date-of-birth'];

        // Prepare and execute SQL query to update user profile information
        $sql = "UPDATE customers SET cust_fname=?, cust_lname=?, cust_email=?, cust_phonenumber=?, cust_gender=?, cust_bdate=? WHERE cust_username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $firstName, $lastName, $email, $phoneNumber, $gender, $birthdate, $username);

        if ($stmt->execute()) {
            // Data update successful, trigger SweetAlert notification
            echo '<script>
            Swal.fire({
                icon: "success",
                title: "Update Successful",
                text: "Your profile information has been updated.",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true 
            });
            </script>';
        } else {
            // Handle data update failure
            echo '<script>
            Swal.fire({
                icon: "error",
                title: "Update Failed",
                text: "An error occurred while updating your profile information.",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true 
            });
            </script>';
        }

        $stmt->close();
    }

    // Check if the profile picture form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['prof-btn'])) {
        $profilePic = $_FILES['prof-btn'];
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($profilePic['name']);

        // Check file size (max 1MB)
        if ($profilePic['size'] > 1048576) {
            echo '<script>
            Swal.fire({
                icon: "error",
                title: "File too large",
                text: "The uploaded file exceeds the maximum size of 1MB.",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true 
            });
            </script>';
        } else {
            // Check file extension
            $fileType = pathinfo($uploadFile, PATHINFO_EXTENSION);
            if ($fileType != "jpeg" && $fileType != "jpg" && $fileType != "png") {
                echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Invalid file type",
                    text: "Only JPEG and PNG files are allowed.",
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true 
                });
                </script>';
            } else {
                // Move uploaded file to destination directory
                if (move_uploaded_file($profilePic['tmp_name'], $uploadFile)) {
                    $profilePicPath = $uploadFile;
                    // Update profile picture path in database
                    $sql = "UPDATE Customers SET cust_ProfPic=? WHERE cust_username=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $profilePicPath, $username);

                    if ($stmt->execute()) {
                        echo '<script>
                        Swal.fire({
                            icon: "success",
                            title: "Upload Successful",
                            text: "Your profile picture has been updated.",
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true 
                        });
                        </script>';
                    } else {
                        echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Update Failed",
                            text: "An error occurred while updating your profile picture.",
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true 
                        });
                        </script>';
                    }
                    $stmt->close();
                } else {
                    echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Upload Failed",
                        text: "An error occurred while uploading your profile picture.",
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true 
                    });
                    </script>';
                }
            }
        }
    }

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


    // Check for session alert message and display it
    if (isset($_SESSION['alert']) && is_array($_SESSION['alert'])) {
        $type = $_SESSION['alert']['type'] === 'success' ? 'Success' : 'Error';
        $message = $_SESSION['alert']['message'];
        echo "<script>
                Swal.fire({
                    icon: '{$type}',
                    title: '{$type}',
                    text: '{$message}'
                });
            </script>";
        unset($_SESSION['alert']); // Clear the alert after displaying
    }

    // Check for success message
    if (isset($_SESSION['success_message'])) {
        echo '<script>
        Swal.fire({
            icon: "success",
            title: "Success",
            text: "' . $_SESSION['success_message'] . '",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true 
        });
        </script>';
        // Remove the success message from session
        unset($_SESSION['success_message']);
    }
    ?>




    <header class="header">
        <div class="top_inner">
            <div class="navigation">
                <div style="width: 50%">
                    <img src="../BFBS/images/BFL.png" alt="logo">
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
                                <path d="M3.9 54.9C10.5 40.9 24.5 32 40 32H472c15.5 0 29.5 8.9 36.1 22.9s4.6 30.5-5.2 42.5L320 320.9V448c0 12.1-6.8 23.2-17.7 28.6s-23.8 4.3-33.5-3l-64-48c-8.1-6-12.8-15.5-12.8-25.6V320.9L9 97.3C-.7 85.4-2.8 68.8 3.9 54.9z" />
                            </svg>
                        </div>
                    </a>
                </div>

                <div class="right_icons">
                    <a href="#" class="navigation_links">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="icon" fill="currentColor" id="notification-icon">
                                <path d="M224 0c-17.7 0-32 14.3-32 32V51.2C119 66 64 130.6 64 208v18.8c0 47-17.3 92.4-48.5 127.6l-7.4 8.3c-8.4 9.4-10.4 22.9-5.3 34.4S19.4 416 32 416H416c12.6 0 24-7.4 29.2-18.9s3.1-25-5.3-34.4l-7.4-8.3C401.3 319.2 384 273.9 384 226.8V208c0-77.4-55-142-128-156.8V32c0-17.7-14.3-32-32-32zm45.3 493.3c12-12 18.7-28.3 18.7-45.3H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7z" />
                            </svg>
                        </div>
                    </a>
                    <a href="./wishList/favoritePage.php" class="navigation_links">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="icon" fill="currentColor">
                                <path d="M225.8 468.2l-2.5-2.3L48.1 303.2C17.4 274.7 0 234.7 0 192.8v-3.3c0-70.4 50-130.8 119.2-144C158.6 37.9 198.9 47 231 69.6c9 6.4 17.4 13.8 25 22.3c4.2-4.8 8.7-9.2 13.5-13.3c3.7-3.2 7.5-6.2 11.5-9c0 0 0 0 0 0C313.1 47 353.4 37.9 392.8 45.4C462 58.6 512 119.1 512 189.5v3.3c0 41.9-17.4 81.9-48.1 110.4L288.7 465.9l-2.5 2.3c-8.2 7.6-19 11.9-30.2 11.9s-22-4.2-30.2-11.9zM239.1 145c-.4-.3-.7-.7-1-1.1l-17.8-20c0 0-.1-.1-.1-.1c0 0 0 0 0 0c-23.1-25.9-58-37.7-92-31.2C81.6 101.5 48 142.1 48 189.5v3.3c0 28.5 11.9 55.8 32.8 75.2L256 430.7 431.2 268c20.9-19.4 32.8-46.7 32.8-75.2v-3.3c0-47.3-33.6-88-80.1-96.9c-34-6.5-69 5.4-92 31.2c0 0 0 0-.1 .1s0 0-.1 .1l-17.8 20c-.3 .4-.7 .7-1 1.1c-4.5 4.5-10.6 7-16.9 7s-12.4-2.5-16.9-7z" />
                            </svg>
                        </div>
                    </a>
                    <a href="./cartPage/cartPage.php" class="navigation_links">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="icon" fill="currentColor">
                                <path d="M160 112c0-35.3 28.7-64 64-64s64 28.7 64 64v48H160V112zm-48 48H48c-26.5 0-48 21.5-48 48V416c0 53 43 96 96 96H352c53 0 96-43 96-96V208c0-26.5-21.5-48-48-48H336V112C336 50.1 285.9 0 224 0S112 50.1 112 112v48zm24 48a24 24 0 1 1 0 48 24 24 0 1 1 0-48zm152 24a24 24 0 1 1 48 0 24 24 0 1 1 -48 0z" />
                            </svg>
                        </div>
                    </a>
                    <div id="greeting">Hi, <span><?php echo $firstName; ?></span>!</div>
                    <div class="dropdown">
                        <a href="#" class="navigation_links" onclick="toggleDropdown()">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="icon" fill="currentColor">
                                <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z" />
                            </svg>
                        </a>
                        <div class="dropdown-content" id="dropdownContent">
                            <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="profile-pic" id="profile-picture" class="profile-picture">
                            <a for="profile-pic" id="profile-username"><span id="storedUsername"><?php echo $firstName . " " . $lastName ?></span></a>
                            <a href="#" onclick="toggleDisplay('profile', this)">My Account</a>
                            <a href="#" onclick="toggleDisplay('myPurchases', this)">My Purchases</a>
                            <a href="" id="logout">Logout</a>
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

    <section class="updateProfile">
        <div class="leftSide-container">
            <div class="profile-icon">
                <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="profile-pic" id="profile-picture1" class="profile-picture1">
                <a for="profile-pic1" id="profile-username1"><?php echo $username ?></a>
            </div>
            <div class="profile-info">
                <i class="fa-solid fa-user" id="MyProfile-icon"></i>
                <label for="MyProfile-icon" id="MyAccount">My Account</label>
                <div class="subMenu-MyAccount">
                    <ul>
                        <li><a href="#" class="profile active" onclick="toggleDisplay('profile', this)">Profile</a></li>
                        <li><a href="#" onclick="toggleDisplay('addresses', this)">Addresses</a></li>
                        <li><a href="#" onclick="toggleDisplay('change-password', this)">Change Password</a></li>
                    </ul>
                </div>
                <i class="fas fa-file-invoice-dollar" id="Purchases-icon"></i>
                <label for="Purchases-icon" id="MyPurchases"><a href="#" id="purchase-label" onclick="toggleDisplay('purchase-label', this)">My Purchases</a></label>
            </div>

        </div>
        <div class="rightSide-container" id="rightSide-container">
            <div class="profileEdit-top">
                <h2>My Profile</h2>
                <p>Manage and protect your account</p>
                <hr />
            </div>
            <div class="profileEdit-left-right">
                <div class="profileEdit-left">
                    <div class="label-input-row">
                        <div class="label">
                            <p>Username</p>
                            <p>Name</p>
                            <p>Email</p>
                            <p>Phone Number</p>
                            <p>Gender</p>
                            <p>Birth of Date</p>
                        </div>
                        <div class="input">
                            <form method="post" action="">
                                <p><?php echo htmlspecialchars($username); ?></p>
                                <div class="name-container">
                                    <input type="text" name="firstname" placeholder="First Name" id="First-name" class="First-name" value="<?php echo htmlspecialchars($firstName); ?>">
                                    <input type="text" name="lastname" placeholder="Last Name" id="Last-name" class="Last-name" value="<?php echo htmlspecialchars($lastName); ?>">
                                </div>
                                <div class="email-container">
                                    <input type="email" name="email" placeholder="Email" id="Customer-email" class="Customer-email" value="<?php echo htmlspecialchars($email); ?>">
                                </div>
                                <div class="number-container">
                                    <input type="text" name="phoneNumber" placeholder="Phone Number" id="Customer-Pnumber" class="Customer-Pnumber" value="<?php echo htmlspecialchars($phoneNumber); ?>">
                                </div>
                                <div class="number-container">
                                    <input type="text" name="gender" placeholder="Gender" id="Customer-Gender" class="Cust-Gender" readonly style="border: none; font-size: 18px;" value="<?php echo htmlspecialchars($gender); ?>">
                                </div>
                                <div class="date-of-birth-container">
                                    <input type="date" name="date-of-birth" id="date-of-birth" class="date-of-birth" value="<?php echo htmlspecialchars($birthdate); ?>">
                                </div>
                                <div class="save-btn-container">
                                    <button id="save-btn" name="saveBtn" class="save-btn">SAVE</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="profileEdit-right">
                    <div class="profile-upload">
                        <form method="post" action="" enctype="multipart/form-data">
                            <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="upload-profile" id="change-profile" class="change-profile">

                            <label for="prof-btn" class="custom-file-upload">
                                Select Image
                            </label>
                            <input type="file" name="prof-btn" id="prof-btn" class="prof-btn" accept="image/png, image/jpeg">
                            <div class="uploadBtnCon">
                                <button type="submit" class="upload-btn">UPLOAD</button>
                            </div>

                        </form>
                        <div class="file-info">
                            <p>File size: Maximum of 1 MB</p>
                            <p>File Extension: .JPEG, .PNG</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Addresses form-->
        <div class="addresses-container">
            <div class="myAddresses-top">
                <h2>My Address</h2>
                <!--
                <div class="addAddresses-container">
                    <div class="add-btn">
                        <i class="fa-solid fa-plus" id="fa-plus"></i>
                        <button id="add-address" class="add-address" onclick="openOverlay()">ADD NEW ADDRESS</button>
                    </div>
                </div>
-->
            </div>
            <hr />
            <div class="unique-address-form">
                <form action="address_process.php" method="post">
                    <div class="form-row">
                        <div class="input-group">
                            <label for="unique-form-fullname">Full Name</label>
                            <input type="text" id="unique-form-fullname" name="unique-form-fullname" value="<?php echo isset($fullName) ? $fullName : ''; ?>" required>
                        </div>
                        <div class="input-group">
                            <label for="unique-form-PhoneNumber">Phone Number</label>
                            <input type="text" id="unique-form-PhoneNumber" name="unique-form-PhoneNumber" value="<?php echo isset($addressPhoneNumber) ? $addressPhoneNumber : ''; ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group">
                            <label for="unique-streetName">Street Name</label>
                            <input type="text" id="unique-streetName" name="unique-streetName" value="<?php echo isset($streetName) ? $streetName : ''; ?>" required>
                        </div>
                        <div class="input-group">
                            <label for="unique-purok">Purok</label>
                            <select id="unique-purok" name="unique-purok" required>
                                <option value="" disabled selected>Select Purok</option>
                                <option value="Purok-1" <?php echo (isset($purok) && $purok == 'Purok-1') ? 'selected' : ''; ?>>Purok-1</option>
                                <option value="Purok-2" <?php echo (isset($purok) && $purok == 'Purok-2') ? 'selected' : ''; ?>>Purok-2</option>
                                <option value="Purok-3" <?php echo (isset($purok) && $purok == 'Purok-3') ? 'selected' : ''; ?>>Purok-3</option>
                                <option value="Purok-4" <?php echo (isset($purok) && $purok == 'Purok-4') ? 'selected' : ''; ?>>Purok-4</option>
                                <option value="Purok-5" <?php echo (isset($purok) && $purok == 'Purok-5') ? 'selected' : ''; ?>>Purok-5</option>
                                <option value="Purok-6" <?php echo (isset($purok) && $purok == 'Purok-6') ? 'selected' : ''; ?>>Purok-6</option>
                                <option value="Purok-7" <?php echo (isset($purok) && $purok == 'Purok-7') ? 'selected' : ''; ?>>Purok-7</option>
                                <option value="Purok-8" <?php echo (isset($purok) && $purok == 'Purok-8') ? 'selected' : ''; ?>>Purok-8</option>
                                <option value="Purok-9" <?php echo (isset($purok) && $purok == 'Purok-9') ? 'selected' : ''; ?>>Purok-9</option>
                                <option value="Purok-10" <?php echo (isset($purok) && $purok == 'Purok-10') ? 'selected' : ''; ?>>Purok-10</option>
                            </select>

                        </div>
                        <div class="input-group">
                            <label for="unique-barangay">Barangay</label>
                            <select id="unique-barangay" name="unique-barangay" required>
                                <option value="" disabled selected>Select Barangay</option>
                                <option value="Apokon" <?php echo (isset($barangay) && $barangay == 'Apokon') ? 'selected' : ''; ?>>Apokon</option>
                                <option value="Bincungan" <?php echo (isset($barangay) && $barangay == 'Bincungan') ? 'selected' : ''; ?>>Bincungan</option>
                                <option value="Busaon" <?php echo (isset($barangay) && $barangay == 'Busaon') ? 'selected' : ''; ?>>Busaon</option>
                                <option value="Canocotan" <?php echo (isset($barangay) && $barangay == 'Canocotan') ? 'selected' : ''; ?>>Canocotan</option>
                                <option value="Cuambogan" <?php echo (isset($barangay) && $barangay == 'Cuambogan') ? 'selected' : ''; ?>>Cuambogan</option>
                                <option value="La Filipina" <?php echo (isset($barangay) && $barangay == 'La Filipina') ? 'selected' : ''; ?>>La Filipina</option>
                                <option value="Liboganon" <?php echo (isset($barangay) && $barangay == 'Liboganon') ? 'selected' : ''; ?>>Liboganon</option>
                                <option value="Madaum" <?php echo (isset($barangay) && $barangay == 'Madaum') ? 'selected' : ''; ?>>Madaum</option>
                                <option value="Magdum" <?php echo (isset($barangay) && $barangay == 'Magdum') ? 'selected' : ''; ?>>Magdum</option>
                                <option value="Magugpo East" <?php echo (isset($barangay) && $barangay == 'Magugpo East') ? 'selected' : ''; ?>>Magugpo East</option>
                                <option value="Magugpo North" <?php echo (isset($barangay) && $barangay == 'Magugpo North') ? 'selected' : ''; ?>>Magugpo North</option>
                                <option value="Magugpo Poblacion" <?php echo (isset($barangay) && $barangay == 'Magugpo Poblacion') ? 'selected' : ''; ?>>Magugpo Poblacion</option>
                                <option value="Magugpo South" <?php echo (isset($barangay) && $barangay == 'Magugpo South') ? 'selected' : ''; ?>>Magugpo South</option>
                                <option value="Magugpo West" <?php echo (isset($barangay) && $barangay == 'Magugpo West') ? 'selected' : ''; ?>>Magugpo West</option>
                                <option value="Mankilam" <?php echo (isset($barangay) && $barangay == 'Mankilam') ? 'selected' : ''; ?>>Mankilam</option>
                                <option value="New Balamban" <?php echo (isset($barangay) && $barangay == 'New Balamban') ? 'selected' : ''; ?>>New Balamban</option>
                                <option value="Nueva Fuerza" <?php echo (isset($barangay) && $barangay == 'Nueva Fuerza') ? 'selected' : ''; ?>>Nueva Fuerza</option>
                                <option value="Pagsabangan" <?php echo (isset($barangay) && $barangay == 'Pagsabangan') ? 'selected' : ''; ?>>Pagsabangan</option>
                                <option value="Pandapan" <?php echo (isset($barangay) && $barangay == 'Pandapan') ? 'selected' : ''; ?>>Pandapan</option>
                                <option value="San Agustin" <?php echo (isset($barangay) && $barangay == 'San Agustin') ? 'selected' : ''; ?>>San Agustin</option>
                                <option value="San Isidro" <?php echo (isset($barangay) && $barangay == 'San Isidro') ? 'selected' : ''; ?>>San Isidro</option>
                                <option value="San Miguel" <?php echo (isset($barangay) && $barangay == 'San Miguel') ? 'selected' : ''; ?>>San Miguel</option>
                                <option value="Visayan Village" <?php echo (isset($barangay) && $barangay == 'Visayan Village') ? 'selected' : ''; ?>>Visayan Village</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group">
                            <label for="unique-city">City</label>
                            <select id="unique-city" name="unique-city" required>
                                <option value="" disabled selected>Select City</option>
                                <option value="Tagum City" <?php echo (isset($city) && $city == 'Tagum City') ? 'selected' : ''; ?>>Tagum City</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label for="unique-province">Province</label>
                            <select id="unique-province" name="unique-province" required>
                                <option value="" disabled selected>Select Province</option>
                                <option value="Davao del Norte" <?php echo (isset($province) && $province == 'Davao del Norte') ? 'selected' : ''; ?>>Davao del Norte</option>
                            </select>
                        </div>
                        <div class="button-group">
                            <button type="submit">Submit</button>
                            <button type="submit" class="edit-button" id="editBtn">Edit</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        </div>

        <div class="change-password-container">
            <div class="new-password-top">
                <h2>Change Password</h2>
                <p>For your account's security, do not share your password with anyone else</p>
                <hr>
            </div>
            <div class="newPassword-form-container">
                <form action="update_password.php" method="post">
                    <div id="password-strength"></div>
                    <label for="new-password-input">New Password</label>
                    <input type="password" name="new_password" id="new-password-input" class="new-password-input"><br>

                    <label for="confirm-password-input">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm-password-input" class="confirm-password-input"><br>

                    <button type="submit" name="newPassBtn" class="newPassword-save-btn">Confirm</button>
                </form>
            </div>


        </div>

        <div class="myPurchases-container" id="myPurchases">
            <button class="orderBtn active" data-status="current" onclick="showOrderContainer('current')">Current Order</button>
            <button class="orderBtn" data-status="completed" onclick="showOrderContainer('completed')">Completed</button>
            <button class="orderBtn" data-status="canceled" onclick="showOrderContainer('canceled')">Canceled</button>

            <div id="currentContainer" class="order-container">
                <!-- Current Order items will be dynamically populated here -->
                <?php

                // Check if user is logged in, otherwise redirect to login page
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

                // Retrieve customer number from session
                $username = $_SESSION['username'];
                $sql_cust_num = "SELECT cust_Num FROM customers WHERE cust_username = ?";
                $stmt_cust_num = $conn->prepare($sql_cust_num);
                $stmt_cust_num->bind_param("s", $username);
                $stmt_cust_num->execute();
                $result_cust_num = $stmt_cust_num->get_result();

                if ($result_cust_num->num_rows > 0) {
                    $row_cust_num = $result_cust_num->fetch_assoc();
                    $cust_Num = $row_cust_num['cust_Num'];

                    // Retrieve orders with status Pending or Shipped
                    $sql_orders = "
                            SELECT o.*, p.prod_name, p.prod_origPrice, p.prod_discountPrice, p.prod_image 
                            FROM orders o
                            JOIN product p ON o.prod_id = p.prod_id
                            WHERE o.cust_Num = ? AND (o.status = 'Pending' OR o.status = 'Shipped')";
                    $stmt_orders = $conn->prepare($sql_orders);
                    $stmt_orders->bind_param("i", $cust_Num);
                    $stmt_orders->execute();
                    $result_orders = $stmt_orders->get_result();

                    if ($result_orders->num_rows > 0) {
                        // Output orders
                        while ($row = $result_orders->fetch_assoc()) {
                            echo '<div class="order-item">';
                            // Output order details dynamically
                            echo '<img src="./' . htmlspecialchars($row['prod_image']) . '" alt="Product Image">';
                            echo '<div class="order-item-details">';
                            echo '<div>';
                            echo '<p><strong>Product ID:</strong> ' . htmlspecialchars($row['prod_id']) . '</p>';
                            echo '<p><strong>Product Name:</strong> ' . htmlspecialchars($row['prod_name']) . '</p>';
                            echo '<p><strong>Variation:</strong> ' . htmlspecialchars($row['variations']) . '</p>';
                            echo '<p><strong>Quantity:</strong> ' . htmlspecialchars($row['quantity']) . '</p>';
                            echo '</div>';
                            echo '<div class="price-details">';
                            echo '<p><span class="original-price">₱' . number_format($row['prod_origPrice'], 2) . '</span></p>';
                            echo '<p class="discountedPrice">₱' . number_format($row['prod_discountPrice'], 2) . '</p>';
                            echo '<hr class="line-separator">';
                            echo '<p class="subtotal"><strong>Subtotal:</strong> ₱' . number_format($row['totalPrice'], 2) . '</p>';
                            echo '</div>';
                            echo '</div>';
                            echo '<hr class="line-separator">';
                            echo '<div class="buttons-CurrOrd">';
                            echo '<button class="OrderStatus" data-status="' . htmlspecialchars($row['status']) . '">ORDER STATUS</button>';
                            echo '<button class="cancelOrder" data-order-id="' . htmlspecialchars($row['order_id']) . '">CANCEL</button>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo "You don't have orders at the moment!";
                    }
                } else {
                    echo 'Customer not found.';
                }

                // Close statements and connection
                $stmt_cust_num->close();
                $stmt_orders->close();
                $conn->close();
                ?>

            </div>

            <div id="completedContainer" class="order-container" style="display: none;">
                <?php
                // Check if user is logged in, otherwise return error
                if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
                    echo "User not logged in";
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

                // Retrieve customer number from session
                $username = $_SESSION['username'];
                $sql_cust_num = "SELECT cust_Num FROM customers WHERE cust_username = ?";
                $stmt_cust_num = $conn->prepare($sql_cust_num);
                $stmt_cust_num->bind_param("s", $username);
                $stmt_cust_num->execute();
                $result_cust_num = $stmt_cust_num->get_result();

                if ($result_cust_num->num_rows > 0) {
                    $row_cust_num = $result_cust_num->fetch_assoc();
                    $cust_Num = $row_cust_num['cust_Num'];

                    // Retrieve orders with status Completed
                    $sql_completed_orders = "
                        SELECT o.*, p.prod_name, p.prod_origPrice, p.prod_discountPrice, p.prod_image 
                        FROM orders o
                        JOIN product p ON o.prod_id = p.prod_id
                        WHERE o.cust_Num = ? AND o.status = 'Delivered'";
                    $stmt_completed_orders = $conn->prepare($sql_completed_orders);
                    $stmt_completed_orders->bind_param("i", $cust_Num);
                    $stmt_completed_orders->execute();
                    $result_completed_orders = $stmt_completed_orders->get_result();

                    if ($result_completed_orders->num_rows > 0) {
                        // Output completed orders
                        while ($row = $result_completed_orders->fetch_assoc()) {
                            $prod_id = $row['prod_id'];

                            // Check if the product has already been reviewed
                            $sql_check_review = "SELECT * FROM reviews WHERE prod_id = ? AND cust_Num = ?";
                            $stmt_check_review = $conn->prepare($sql_check_review);
                            $stmt_check_review->bind_param("ii", $prod_id, $cust_Num);
                            $stmt_check_review->execute();
                            $result_check_review = $stmt_check_review->get_result();
                            $alreadyReviewed = $result_check_review->num_rows > 0;

                            echo '<div class="order-item">';
                            // Output order details dynamically
                            echo '<img src="./' . htmlspecialchars($row['prod_image']) . '" alt="Product Image">';
                            echo '<div class="order-item-details">';
                            echo '<div>';
                            echo '<p><strong>Product ID:</strong> ' . htmlspecialchars($row['prod_id']) . '</p>';
                            echo '<p><strong>Product Name:</strong> ' . htmlspecialchars($row['prod_name']) . '</p>';
                            echo '<p><strong>Variation:</strong> ' . htmlspecialchars($row['variations']) . '</p>';
                            echo '<p><strong>Quantity:</strong> ' . htmlspecialchars($row['quantity']) . '</p>';
                            echo '</div>';
                            echo '<div class="price-details">';
                            echo '<p><span class="original-price">₱' . number_format($row['prod_origPrice'], 2) . '</span></p>';
                            echo '<p class="discountedPrice">₱' . number_format($row['prod_discountPrice'], 2) . '</p>';
                            echo '<hr class="line-separator">';
                            echo '<p class="subtotal"><strong>Subtotal:</strong> ₱' . number_format($row['totalPrice'], 2) . '</p>';
                            echo '</div>';
                            echo '</div>';
                            echo '<hr class="line-separator">';
                            echo '<div class="buttons-CurrOrd">';
                            echo '<button class="viewFeedback" data-status="' . htmlspecialchars($row['prod_id']) . '">VIEW FEEDBACK</button>';
                            if ($alreadyReviewed) {
                                echo '<button class="ReviewOrder" data-status="' . htmlspecialchars($row['prod_id']) . '" disabled>REVIEWED</button>';
                            } else {
                                echo '<button class="ReviewOrder" data-status="' . htmlspecialchars($row['prod_id']) . '">REVIEW ORDER</button>';
                            }
                            echo '</div>';
                            echo '</div>';

                            $stmt_check_review->close();
                        }
                    } else {
                        echo "You don't have completed orders yet.";
                    }
                } else {
                    echo 'Customer not found.';
                }

                // Close statements and connection
                $stmt_cust_num->close();
                $stmt_completed_orders->close();
                $conn->close();
                ?>


            </div>

            <div id="canceledContainer" class="order-container" style="display: none;">
                <?php

                // Check if user is logged in, otherwise return error
                if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
                    echo "User not logged in";
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

                // Retrieve customer number from session
                $username = $_SESSION['username'];
                $sql_cust_num = "SELECT cust_Num FROM customers WHERE cust_username = ?";
                $stmt_cust_num = $conn->prepare($sql_cust_num);
                $stmt_cust_num->bind_param("s", $username);
                $stmt_cust_num->execute();
                $result_cust_num = $stmt_cust_num->get_result();

                if ($result_cust_num->num_rows > 0) {
                    $row_cust_num = $result_cust_num->fetch_assoc();
                    $cust_Num = $row_cust_num['cust_Num'];

                    // Retrieve orders with status Cancel
                    $sql_canceled_orders = "
        SELECT o.*, p.prod_name, p.prod_origPrice, p.prod_discountPrice, p.prod_image 
        FROM orders o
        JOIN product p ON o.prod_id = p.prod_id
        WHERE o.cust_Num = ? AND o.status = 'Cancel'";
                    $stmt_canceled_orders = $conn->prepare($sql_canceled_orders);
                    $stmt_canceled_orders->bind_param("i", $cust_Num);
                    $stmt_canceled_orders->execute();
                    $result_canceled_orders = $stmt_canceled_orders->get_result();

                    if ($result_canceled_orders->num_rows > 0) {
                        // Output canceled orders
                        while ($row = $result_canceled_orders->fetch_assoc()) {
                            echo '<div class="order-item">';
                            // Output order details dynamically
                            echo '<img src="./' . htmlspecialchars($row['prod_image']) . '" alt="Product Image">';
                            echo '<div class="order-item-details">';
                            echo '<div>';
                            echo '<p><strong>Product ID:</strong> ' . htmlspecialchars($row['prod_id']) . '</p>';
                            echo '<p><strong>Product Name:</strong> ' . htmlspecialchars($row['prod_name']) . '</p>';
                            echo '<p><strong>Variation:</strong> ' . htmlspecialchars($row['variations']) . '</p>';
                            echo '<p><strong>Quantity:</strong> ' . htmlspecialchars($row['quantity']) . '</p>';
                            echo '</div>';
                            echo '<div class="price-details">';
                            echo '<p><span class="original-price">₱' . number_format($row['prod_origPrice'], 2) . '</span></p>';
                            echo '<p class="discountedPrice">₱' . number_format($row['prod_discountPrice'], 2) . '</p>';
                            echo '<hr class="line-separator">';
                            echo '<p class="subtotal"><strong>Subtotal:</strong> ₱' . number_format($row['totalPrice'], 2) . '</p>';
                            echo '</div>';
                            echo '</div>';
                            echo '<hr class="line-separator">';
                            echo '<div class="buttons-CurrOrd">';
                            echo '<button class="OrderStatus" data-status="' . htmlspecialchars($row['status']) . '">ORDER STATUS</button>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo "You don't have canceled orders yet.";
                    }
                } else {
                    echo 'Customer not found.';
                }

                // Close statements and connection
                $stmt_cust_num->close();
                $stmt_canceled_orders->close();
                $conn->close();
                ?>



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


    <!--Review Sample Rating-->
    <div id="myNewModal" class="myNewModal">
        <div class="new-modal-content">
            <span class="close-icon" id="close-icon">&times;</span>
            <h2 class="summary-review-heading">SUMMARY REVIEW</h2>
            <div class="product-info-review">
                <h2 id="product-name-review">Product Name</h2>
                <p id="quantity">x1</p>
                <p id="order-total-review">Order Total:</p>
                <p>Rate: <span class="rate-star"><i class="fas fa-star"></i></span></p>
                <p id="order-number-review">Order ID: <span id="order-number">20240001</span></p>
                <p class="date-purchased-review">Date Purchased: <span id="date-purchased-review">04/08/2024</span></p>
            </div>
            <div class="feedback-comment">
                <p>Feedback/Comment:</p>
                <p id="review">This product is amazing! It exceeded all my expectations and I would definitely recommend it to others.</p>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="success-message">Profile successfully updated!</div>
            <!-- Additional content or styling can be added here -->
        </div>
    </div>
    <div id="errorModal" class="modal">
        <!-- Error Modal content -->
        <div class="modal-content">
            <span class="close close-error">&times;</span>
            <div class="error-message">Please fill out all fields.</div>
        </div>
    </div>
    <!---New Password Modal message-->
    <div id="myModal1" class="NewPassword-modal">
        <div class="modal-content1">
            <span class="close1">&times;</span>
            <div id="modal-message"></div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const orderStatusButtons = document.querySelectorAll('.OrderStatus');
            const cancelOrderButtons = document.querySelectorAll('.cancelOrder');

            orderStatusButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const status = this.getAttribute('data-status');
                    Swal.fire({
                        title: 'Order Status',
                        text: 'The status of this order is: ' + status,
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                });
            });

            cancelOrderButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-order-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you really want to cancel this order?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, cancel it!',
                        cancelButtonText: 'No, keep it'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Make AJAX request to cancel order
                            fetch('cancel_order.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded'
                                    },
                                    body: new URLSearchParams({
                                        order_id: orderId
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire('Canceled!', data.message, 'success').then(() => {
                                            location.reload(); // Reload the page to reflect changes
                                        });
                                    } else {
                                        Swal.fire('Error!', data.message, 'error');
                                    }
                                })
                                .catch(error => {
                                    Swal.fire('Error!', 'An error occurred while canceling the order.', 'error');
                                });
                        }
                    });
                });
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.ReviewOrder').forEach(function(button) {
                button.addEventListener('click', function() {
                    const prodId = button.getAttribute('data-status');
                    Swal.fire({
                        title: 'Review Product',
                        html: `
                            <input type="number" id="rev_star" class="swal2-input" placeholder="Rating (1-5)" min="1" max="5">
                            <textarea id="rev_description" class="swal2-textarea" placeholder="Write your review here"></textarea>
                        `,
                        confirmButtonText: 'Submit',
                        focusConfirm: false,
                        preConfirm: () => {
                            const revStar = Swal.getPopup().querySelector('#rev_star').value;
                            const revDescription = Swal.getPopup().querySelector('#rev_description').value;
                            if (!revStar || revStar < 1 || revStar > 5 || !revDescription) {
                                Swal.showValidationMessage(`Please enter a valid rating and review.`);
                                return false;
                            }
                            return {
                                revStar: revStar,
                                revDescription: revDescription
                            };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const reviewData = {
                                prod_id: prodId,
                                rev_star: result.value.revStar,
                                rev_description: result.value.revDescription
                            };

                            $.ajax({
                                url: 'submit_review.php',
                                type: 'POST',
                                data: reviewData,
                                success: function(response) {
                                    if (response.status === 'success') {
                                        Swal.fire('Submitted!', response.message, 'success');
                                        // Disable the review button after successful submission
                                        button.innerText = 'REVIEWED';
                                        button.disabled = true;
                                    } else {
                                        Swal.fire('Error!', response.message, 'error');
                                    }
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire('Error!', 'There was an error submitting your review.', 'error');
                                }
                            });
                        }
                    });
                });
            });
        });

        // Assuming you have jQuery included in your project
        // Handle click event for view feedback button
        $(".viewFeedback").click(function() {
            var prodId = $(this).attr("data-status");
            $.ajax({
                url: "fetch_feedback.php",
                type: "POST",
                data: {
                    prodId: prodId
                },
                success: function(response) {
                    // Parse the JSON response
                    var feedbackData = JSON.parse(response);
                    // Check if feedback exists
                    if (feedbackData.length > 0) {
                        // Construct HTML for displaying feedback
                        var feedbackHtml = "<ul>";
                        feedbackData.forEach(function(feedback) {
                            // Construct star icon based on the number of stars
                            var starIcon = "";
                            for (var i = 0; i < feedback.rev_star; i++) {
                                starIcon += "<i class='fas fa-star' style='color: #FFBF00;'></i>"; // Font Awesome star icon
                            }
                            // Construct feedback HTML
                            feedbackHtml += "<li>";
                            feedbackHtml += "<div>Star: " + starIcon + "</div>";
                            feedbackHtml += "<div>Description: " + feedback.rev_description + "</div>";
                            feedbackHtml += "<div>Date: " + feedback.rev_date + "</div>";
                            feedbackHtml += "</li>";
                        });
                        feedbackHtml += "</ul>";
                        // Show SweetAlert with feedback details
                        Swal.fire({
                            title: "Feedback",
                            html: feedbackHtml,
                            confirmButtonText: "Close"
                        });
                    } else {
                        // Show SweetAlert if no feedback is available
                        Swal.fire({
                            icon: "info",
                            title: "No Feedback",
                            text: "No feedback available for this product."
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching feedback:", error);
                }
            });
        });


        function openNewPage() {
            window.location.href = 'search.php';
        }
    </script>

    <script defer src="CustomerProfile.js"></script>

</body>

</html>