<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "blissful_db";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    echo '<script>alert("Connection failed: ' . $conn->connect_error . '"); window.location.href = "flowers.php";</script>';
    die();
  }

  $flower_name = $conn->real_escape_string($_POST['flower_name']);
  $flower_price = $conn->real_escape_string($_POST['flower_price']);

  $target_dir = "Flower/";
  if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
  }

  if (isset($_FILES["flower_img"]) && $_FILES["flower_img"]["error"] == UPLOAD_ERR_OK) {
    $target_file = $target_dir . basename($_FILES["flower_img"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["flower_img"]["tmp_name"]);
    if ($check !== false) {
      $uploadOk = 1;
    } else {
      echo '<script>alert("File is not an image."); window.location.href = "flowers.php";</script>';
      die();
    }

    if (file_exists($target_file)) {
      echo '<script>alert("Sorry, file already exists."); window.location.href = "flowers.php";</script>';
      die();
    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM flowers_tbl WHERE flower_name = ?");
    $stmt->bind_param("s", $flower_name);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
      echo '<script>alert("Sorry, a product with this name already exists."); window.location.href = "flowers.php";</script>';
      die();
    }

    if ($_FILES["flower_img"]["size"] > 900000) {
      echo '<script>alert("Sorry, your file is too large."); window.location.href = "flowers.php";</script>';
      die();
    }

    $allowed_file_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_file_types)) {
      echo '<script>alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed."); window.location.href = "flowers.php";</script>';
      die();
    }

    if ($uploadOk == 0) {
      echo '<script>alert("Sorry, your file was not uploaded."); window.location.href = "flowers.php";</script>';
      die();
    } else {
      if (move_uploaded_file($_FILES["flower_img"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO flowers_tbl (flower_name, flower_price, flower_img) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $flower_name, $flower_price, $target_file);

        if ($stmt->execute()) {
          echo '<script>alert("New record created successfully."); window.location.href = "flowers.php";</script>';
        } else {
          echo '<script>alert("Error: ' . $stmt->error . '"); window.location.href = "flowers.php";</script>';
        }
        $stmt->close();
      } else {
        echo '<script>alert("Sorry, there was an error uploading your file."); window.location.href = "flowers.php";</script>';
      }
    }
  } else {
    echo '<script>alert("No file uploaded or there was an error uploading the file. Error code: ' . $_FILES["flower_img"]["error"] . '"); window.location.href = "flowers.php";</script>';
  }

  $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Blissful Bouquet</title>
  <link rel="stylesheet" href="../../vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../../vendors/base/vendor.bundle.base.css">
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="shortcut icon" href="../../images/BFL.png" />
</head>

<body>
  <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
      <a class="navbar-brand brand-logo me-5" href="../../index.html"><img src="../../images/BFL.png" class="me-2" alt="logo" /></a>
      <a class="navbar-brand brand-logo-mini" href="../../index.html"><img src="../../images/BFL.png" alt="logo" /></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <span class="ti-view-list"></span>
      </button>
      <ul class="navbar-nav mr-lg-2">
        <li class="nav-item nav-search d-none d-lg-block">
          <div class="input-group">
            <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
              <span class="input-group-text" id="search">
                <i class="ti-search"></i>
              </span>
            </div>
            <input type="text" class="form-control" id="navbar-search-input" placeholder="" aria-label="search" aria-describedby="search">
          </div>
        </li>
      </ul>
      <ul class="navbar-nav navbar-nav-right">
        <li class="nav-item dropdown me-1">
          <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" id="messageDropdown" href="#" data-bs-toggle="dropdown">
            <i class="ti-email mx-0"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="messageDropdown">
            <p class="mb-0 font-weight-normal float-left dropdown-header">Messages</p>
            <a class="dropdown-item">
              <div class="item-thumbnail">
                <img src="../../images/faces/face4.jpg" alt="image" class="profile-pic">
              </div>
              <div class="item-content flex-grow">
                <h6 class="ellipsis font-weight-normal">David Grey
                </h6>
                <p class="font-weight-light small-text text-muted mb-0">
                  The meeting is cancelled
                </p>
              </div>
            </a>
            <a class="dropdown-item">
              <div class="item-thumbnail">
                <img src="../../images/faces/face2.jpg" alt="image" class="profile-pic">
              </div>
              <div class="item-content flex-grow">
                <h6 class="ellipsis font-weight-normal">Tim Cook
                </h6>
                <p class="font-weight-light small-text text-muted mb-0">
                  New product launch
                </p>
              </div>
            </a>
            <a class="dropdown-item">
              <div class="item-thumbnail">
                <img src="../../images/faces/face3.jpg" alt="image" class="profile-pic">
              </div>
              <div class="item-content flex-grow">
                <h6 class="ellipsis font-weight-normal"> Johnson
                </h6>
                <p class="font-weight-light small-text text-muted mb-0">
                  Upcoming board meeting
                </p>
              </div>
            </a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
            <i class="ti-bell mx-0"></i>
            <span class="count"></span>
          </a>
          <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="notificationDropdown">
            <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
            <a class="dropdown-item">
              <div class="item-thumbnail">
                <div class="item-icon bg-success">
                  <i class="ti-info-alt mx-0"></i>
                </div>
              </div>
              <div class="item-content">
                <h6 class="font-weight-normal">Application Error</h6>
                <p class="font-weight-light small-text mb-0 text-muted">
                  Just now
                </p>
              </div>
            </a>
            <a class="dropdown-item">
              <div class="item-thumbnail">
                <div class="item-icon bg-warning">
                  <i class="ti-settings mx-0"></i>
                </div>
              </div>
              <div class="item-content">
                <h6 class="font-weight-normal">Settings</h6>
                <p class="font-weight-light small-text mb-0 text-muted">
                  Private message
                </p>
              </div>
            </a>
            <a class="dropdown-item">
              <div class="item-thumbnail">
                <div class="item-icon bg-info">
                  <i class="ti-user mx-0"></i>
                </div>
              </div>
              <div class="item-content">
                <h6 class="font-weight-normal">New user registration</h6>
                <p class="font-weight-light small-text mb-0 text-muted">
                  2 days ago
                </p>
              </div>
            </a>
          </div>
        </li>
        <li class="nav-item nav-profile dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
            <img src="../../images/faces/aaaface.png" alt="profile" />
          </a>
          <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
            <a class="dropdown-item">
              <i class="ti-settings text-primary"></i>
              Settings
            </a>
            <a class="dropdown-item">
              <i class="ti-power-off text-primary"></i>
              Logout
            </a>
          </div>
        </li>
      </ul>
      <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
        <span class="ti-view-list"></span>
      </button>
    </div>
  </nav>
  <!-- partial -->
  <div class="container-fluid page-body-wrapper">
    <!-- partial:partials/_sidebar.html -->

    <nav class="sidebar sidebar-offcanvas" id="sidebar">
      <ul class="nav">
        <li class="nav-item">
          <a class="nav-link" href="../../home.php">
            <i class="ti-home menu-icon"></i>
            <span class="menu-title">Home</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../orders/allorders.php">
            <i class="ti-notepad menu-icon"></i>
            <span class="menu-title">Orders</span>
          </a>
        </li>

        <li class="nav-item">
              <a class="nav-link" href="../../pages/products/collections.php">
                <i class="ti-tag menu-icon"></i>
                <span class="menu-title">Products</span>
              </a>
            </li>

        <li class="nav-item">
          <a class="nav-link" href="../../pages/customers/segments.html">
            <i class="ti-user menu-icon"></i>
            <span class="menu-title">Customers</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../../pages/analytics/reports.html">
            <i class="ti-bar-chart-alt menu-icon"></i>
            <span class="menu-title">Reports</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../../dis/discounts.html">
            <i class="ti-gift menu-icon"></i>
            <span class="menu-title">Discounts</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../../pages/onlinestore/themes.html">
            <i class="ti-archive menu-icon"></i>
            <span class="menu-title">Online Store</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../../settings/settings.html">
            <i class="ti-settings menu-icon"></i>
            <span class="menu-title">Settings</span>
          </a>
        </li>
      </ul>
    </nav>

    <!--TABLE-->
    <div class="main-panel">
      <div class="content-wrapper">

        <div class="row">
          <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h4 class="font-weight-bold mb-0">Add Items</h4>
              </div>
              <div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card position-relative">
              <div class="card-body">
                <p class="card-title"></p>
                <h4>Add Flowers</h4>
                <form id="add-flower-form" action="" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="flowerName">Flower Name</label>
                    <input type="text" class="form-control" id="flower_name" name="flower_name" required>
                  </div>
                  <div class="form-group">
                    <label for="flowerPrice">Flower Price</label>
                    <input type="number" class="form-control" id="flower_price" name="flower_price" step="0.01" required>
                  </div>
                  <div class="form-group">
                    <div id="container">
                      <label for="flower_img">Upload Picture</label>
                      <input type="file" name="flower_img" id="image-input" accept="image/*">
                      <div id="preview-container" style="display: none;">
                        <img id="image-preview" src="" alt="Image Preview">
                        <button id="remove-button">X</button>
                      </div>
                    </div>
                  </div>

                  <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('image-input').addEventListener('change', function() {
      const fileInput = document.getElementById('image-input');
      const previewContainer = document.getElementById('preview-container');
      const imagePreview = document.getElementById('image-preview');

      const file = fileInput.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          imagePreview.src = e.target.result;
          previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);
      } else {
        previewContainer.style.display = 'none';
      }
    });

    document.getElementById('remove-button').addEventListener('click', function() {
      const fileInput = document.getElementById('image-input');
      const previewContainer = document.getElementById('preview-container');
      const imagePreview = document.getElementById('image-preview');

      fileInput.value = '';
      imagePreview.src = '';
      previewContainer.style.display = 'none';
    });
  </script>

  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="../../vendors/base/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/hoverable-collapse.js"></script>
  <script src="../../js/template.js"></script>
  <script src="../../js/todolist.js"></script>
  <!-- endinject -->
</body>

</html>