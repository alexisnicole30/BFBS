<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://kit.fontawesome.com/8158c9d3a5.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" type="x-icon" href="../images/BFL.png">
    <script src="https://kit.fontawesome.com/8158c9d3a5.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../CSS/registration.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Signup</title>
</head>
<body>

<?php
session_start(); // Start or resume a session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "blissFul_DB";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve username and password from form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sanitize inputs to prevent SQL injection (recommended to use prepared statements instead)
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check if username and password match
    $sql = "SELECT * FROM Customers WHERE cust_username='$username' AND cust_password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login successful
        $_SESSION['username'] = $username; // Store username in session variable
        $_SESSION['loggedin'] = true; // Set user as logged in

        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Login successful!',
                    showConfirmButton: false,
                    timer: 1500
                });
              setTimeout(function(){
                  window.location.href = '../CustomerProfile.php';
              }, 1500);
              </script>";
    } else {
        // Login failed
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid username or password',
                    text: 'Please try again',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true 
                }).then(() => {
                    window.location.href = 'Login.php'; 
                });;
              </script>";
    }


    $conn->close(); // Close database connection
}

if (isset($_SESSION['alert'])) {
    $type = $_SESSION['alert']['type'];
    $title = $type === 'success' ? 'Success' : 'Oops...';
    $message = $_SESSION['alert']['message'];
    echo "<script>
            let timerInterval;
            Swal.fire({
                icon: '$type',
                title: '$title',
                html: '$message<br>Will direct you to login in <b></b> milliseconds.',
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getHtmlContainer().querySelector('b');
                    timerInterval = setInterval(() => {
                        timer.textContent = Swal.getTimerLeft();
                    }, 100);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                    window.location.href = 'login.php'; // Redirect to login.php
                }
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log('I was closed by the timer');
                    window.location.href = 'login.php'; // Redirect to login.php
                }
            });
          </script>";
    unset($_SESSION['alert']); // Clear the alert after displaying
}

?>


    <nav class="top-head">
        <a href="../index.php" class="back-home">
            <div class="back-icon-circle">
                <i class="fa-solid fa-house"></i> <!-- Use the home icon class -->
            </div>
        </a>
        <img src="../images/BFL.png" alt="Logo">
    </nav>

<!--
    <div id="overlay-container" class="overlay-container">
        <div class="overlay-content">
            <i class="fas fa-check checkmark-icon"></i>
            <p>You have successfully registered!</p>
        </div>
    </div>
    -->

    <div class="container sign-up-mode"> <!-- Added "sign-up-mode" class -->
        <div class="signin-signup">
        <form action="" class="sign-in-form" id="login" method="post">
                <h2 class="title">Sign in</h2>
                <div class="input-field">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Username">
                </div>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password">
                </div>
                <div id="errorMessage" class="error-message"></div>

                <input type="submit" value="Login" class="btn" id="loginBtn">

                <p class="social-text">Or Sign in with social platform</p>
                <div class="social-media">
                    <a href="#" class="social-icon" data-tooltip="Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="" class="social-icon"  data-tooltip="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="" class="social-icon"  data-tooltip="Google">
                        <i class="fab fa-google"></i>
                    </a>
                    <a href="" class="social-icon"  data-tooltip="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="../adminLogin.php" class="social-icon"  data-tooltip="Authorized Person">
                        <i class="fa-solid fa-user-shield"></i>
                    </a>
                </div>
                <p class="account-text">Don't have an account? <a href="#" id="sign-up-btn2">Sign up</a></p>
            </form>

            <form action="../register.php" class="sign-up-form" id="register" method="post" enctype="multipart/form-data">
               
                <div class="container1">
                <h2 class="title">Sign up</h2>
                    <div class="row">
                        <div class="input-field1">
                            <i class="fas fa-user"></i>
                            <input id="firstName" name="firstName" type="text" placeholder="First Name" required>
                        </div>
                        <div class="input-field1">
                            <i class="fas fa-user"></i>
                            <input id="lastName" name="lastName" type="text" placeholder="Last Name" required>
                        </div>
                    </div>
                    <div class="input-field1">
                        <i class="fa-solid fa-id-badge"></i>
                        <input id="username" name="username" type="text" placeholder="Username" required>
                    </div>
                    <div id="usernameWarning" class="warning"></div>
                    <div class="input-field1">
                        <i class="fas fa-envelope"></i>
                        <input id="email" name="email" type="text" placeholder="Email" required>
                    </div>
                    <div id="emailWarning" class="warning"></div>
                    <div class="input-field1">
                        <i class="fas fa-lock"></i>
                        <input id="password" name="password" type="password" placeholder="Password" required>
                    </div>
                    <div id="passwordWarning" class="warning"></div>
                    <div class="input-field1">
                        <i class="fas fa-phone"></i>
                        <input id="phoneNumber" name="phoneNumber" type="text" placeholder="Phone Number" required>
                    </div>
                    <div class="row">
                        <div class="input-field1">
                            <i class="fas fa-venus-mars"></i>
                            <select id="gender" name="gender" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="input-field1">
                            <i class="fas fa-calendar-alt"></i>
                            <input id="birthdate" name="birthdate" type="date" required>
                        </div>
                    </div>
                    <div class="input-field1">
                        <i class="fas fa-file-image"></i>
                        <input id="profilePic" name="profilePic" type="file" accept="image/*" required>
                    </div>

                    <input type="submit" value="Sign up" class="btn" onclick="return validateForm()">

                </div>
                <!-- End of New Fields -->
                <!--
                <p class="social-text">Or Sign in with social platform</p>
                <div class="social-media">
                    <a href="#" class="social-icon" data-tooltip="Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="" class="social-icon" data-tooltip="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="" class="social-icon" data-tooltip="Google">
                        <i class="fab fa-google"></i>
                    </a>
                    <a href="" class="social-icon" data-tooltip="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="../adminLogin.html" class="social-icon" data-tooltip="Authorized Person">
                        <i class="fa-solid fa-user-shield"></i>
                    </a>
                </div>
                <p class="account-text">Already have an account? <a href="#" id="sign-in-btn2">Sign in</a></p>
-->
            </form>
            
        </div>
        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>Want to spread joy today with our Blissful Bouquet?</h3>
                    <p>Discover the charm of our Blissful Bouquet and bring happiness home. Shop now!</p>
                    <button class="btn" id="sign-in-btn">Sign in</button>
                </div>
                <img src="../images/su.svg" alt="" class="image">
            </div>
            <div class="panel right-panel">
                <div class="content">
                    <h3>Ready to join our community?</h3>
                    <p> Sign up on our website for exclusive access to our Blissful Bouquets. Register now!</p>
                    <button class="btn" id="sign-up-btn">Sign up</button>
                </div>
                <img src="../images/su1.svg" alt="" class="image">
            </div>
        </div>
    </div>
    <script src="../JS/registration.js"></script>
</body>
</html>
