<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./adminLogin.css">
    <link rel="shortcut icon" type="x-icon" href="../images/BFL.png">
    <script src="https://kit.fontawesome.com/8158c9d3a5.js" crossorigin="anonymous"></script>
    <title>Document</title>
</head>

<body>
    <nav class="top-head">
        <a href="./html/Login.php" class="back-home">
            <div class="back-icon-circle">
                <i class="fas fa-arrow-left"></i>
            </div>
        </a>
        <img src="./images/BFL.png" alt="Logo">
    </nav>

    <div class="admin-container">
        <div class="adminGreeting">
            <h1>Welcome back, Admin!</h1>
        </div>
        <div class="adminLogin-container">
            <div class="adminLogin-cardContainer">
                <div class="login-label">
                    <h3>Login</h3>
                </div>
                <div class="adminForm-login">
                    <form action="" method="post">
                        <div class="Admin-input-field">
                            <i class="fas fa-user"></i>
                            <input type="text" placeholder="Username" required>
                        </div>
                        <div class="Admin-input-field">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Password" required>
                        </div>
                        <div id="errorMessage"></div>
                        <button type="submit" class="Adminbtn">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <script defer src="./adminLogin.js"></script>
</body>

</html>