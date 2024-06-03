<?php
// Database connection details
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

// Fetch flower details and total purchases
$sql1 = "SELECT p.prod_id, p.prod_name, p.prod_image, COUNT(o.order_id) as total_purchases
         FROM product p
         LEFT JOIN orders o ON o.prod_id = p.prod_id
         GROUP BY p.prod_id
         ORDER BY total_purchases DESC
         LIMIT 3";

$result1 = $conn->query($sql1);

$products = [];
if ($result1->num_rows > 0) {
  while ($row = $result1->fetch_assoc()) {
    $products[] = $row;
  }
}

// Fetch categories and their counts from the database
$sql2 = "SELECT c.category_name, COUNT(*) as count FROM product p
         JOIN category c ON c.category_id = c.category_id
         GROUP BY c.category_name";

$result2 = $conn->query($sql2);

$categories = [];
$counts = [];

if ($result2->num_rows > 0) {
  while ($row = $result2->fetch_assoc()) {
    $categories[] = $row['category_name'];
    $counts[] = $row['count'];
  }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Blissful Bouquet</title>
  <link rel="stylesheet" href="../admin/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="shortcut icon" href="images/BFL.png" />
</head>

<body>
  <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
      <a class="navbar-brand brand-logo me-5" href="index.html"><img src="images/BFL.png" class="me-2" alt="logo" /></a>
      <a class="navbar-brand brand-logo-mini" href="index.html"><img src="images/BFL.png" alt="logo" /></a>
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
                <img src="images/faces/face4.jpg" alt="image" class="profile-pic">
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
                <img src="images/faces/face2.jpg" alt="image" class="profile-pic">
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
                <img src="images/faces/face3.jpg" alt="image" class="profile-pic">
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
            <img src="images/faces/aaaface.png" alt="profile" />
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
          <a class="nav-link" href="../admin/home.php">
            <i class="ti-home menu-icon"></i>
            <span class="menu-title">Home</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../admin/pages/orders/allorders.php">
            <i class="ti-notepad menu-icon"></i>
            <span class="menu-title">Orders</span>
          </a>
        </li>

        <li class="nav-item">
              <a class="nav-link" href="../admin/pages/products/collections.php">
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
          <a class="nav-link" href="../admin/pages/analytics/reports.html">
            <i class="ti-bar-chart-alt menu-icon"></i>
            <span class="menu-title">Reports</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../admin/dis/discounts.html">
            <i class="ti-gift menu-icon"></i>
            <span class="menu-title">Discounts</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../admin/pages/onlinestore/themes.html">
            <i class="ti-archive menu-icon"></i>
            <span class="menu-title">Online Store</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../admin/settings/settings.html">
            <i class="ti-settings menu-icon"></i>
            <span class="menu-title">Settings</span>
          </a>
        </li>
      </ul>
    </nav>

    <!-- partial -->
    <div class="main-panel">
      <div class="content-wrapper">

        <!-- best sellers -->
        <?php
if (!empty($products)) {
  echo '<div class="row">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card position-relative">
              <div class="card-body">
                <p class="card-title">Best Sellers</p>
                <table class="table table-hover">
                  <tbody>';

  $rowCount = 0;
  foreach ($products as $row) {
    if ($rowCount % 4 == 0) {
      echo '<tr>';
    }

    echo '<td>
            <a href="pages/product-details/' . $row["prod_id"] . '.html">
              <img src="images/product/' . $row["prod_image"] . '" alt="image"/>
              <h6 class="menu-title">' . $row["prod_name"] . '</h6>
              <p class="text-small">' . $row["total_purchases"] . ' Purchased Orders</p>
            </a>
          </td>';

    if ($rowCount % 3 == 2) {
      echo '</tr>';
    }

    $rowCount++;
  }

  if ($rowCount % 3 != 0) {
    echo '</tr>';
  }

  echo '    </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>';
} else {
  echo "No best sellers found.";
}
?>


        <!-- categories -->
        <div class="row">

          <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <p class="card-title">Popular Categories</p>
                <canvas id="popularCategoriesChart"></canvas>
              </div>
            </div>
          </div>


          <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body pb-0">
                <p class="card-title">Customer Favourites</p>
                <table class="table table-hover">
                  <tbody>
                    <tr>
                      <td>
                        <div class="flower-info">
                          <img src="images/flowers/1.png" alt="image" class="large-image" />
                          <h6 class="menu-title">Vintage Bloom Swansea</h6>
                          <div>
                            <button class="heart-button">&hearts;</button>
                            <span class="likes">120</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="flower-info">
                          <img src="images/flowers/2.png" alt="image" class="large-image" />
                          <h6 class="menu-title">Elegant Rose Bouquet</h6>
                          <div>
                            <button class="heart-button">&hearts;</button>
                            <span class="likes">98</span>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="flower-info">
                          <img src="images/flowers/3.png" alt="image" class="large-image" />
                          <h6 class="menu-title">Classic Tulip Arrangement</h6>
                          <div>
                            <button class="heart-button">&hearts;</button>
                            <span class="likes">150</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="flower-info">
                          <img src="images/flowers/6.png" alt="image" class="large-image" />
                          <h6 class="menu-title">Sunshine Flowers</h6>
                          <div>
                            <button class="heart-button">&hearts;</button>
                            <span class="likes">200</span>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>


        <div class="row">
          <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <p class="card-title">Add Items</p>
                <button type="button" class="btn btn-secondary btn-rounded btn-fw btn-no-link">
                  <a href="pages/add-products/flowers.php">Flowers&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+</a>
                </button>
              </div>
            </div>
          </div>

          <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title mb-0">View Store</h5>
                  <button class="btn btn-icon btn-sm btn-outline-primary">
                    <i class="mdi mdi-eye"></i>
                  </button>
                </div>
                <table class="mt-3">
                  <tbody>
                    <tr>
                      <td>
                        <img src="images/flowers/11.png" alt="image" style="width: 100%;" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>


          <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
              <div class="card-body pb-0">
                <p class="card-title">Feedbacks</p>
                <div class="feedback-buttons">
                  <button type="button" class="btn btn-secondary btn-rounded btn-fw feedback-button">Beautiful bouquets and fresh flowers!
                    <button class="btn btn-icon btn-sm btn-outline-primary ml-2">
                      <i class="mdi mdi-dots-horizontal"></i>
                    </button>
                  </button>
                  <button type="button" class="btn btn-secondary btn-rounded btn-fw feedback-button">Fantastic service! Will definitely order again.
                    <button class="btn btn-icon btn-sm btn-outline-primary ml-2">
                      <i class="mdi mdi-dots-horizontal"></i>
                    </button>
                  </button>
                  <button type="button" class="btn btn-secondary btn-rounded btn-fw feedback-button">Highly recommend! The flowers were stunning.
                    <button class="btn btn-icon btn-sm btn-outline-primary ml-2">
                      <i class="mdi mdi-dots-horizontal"></i>
                    </button>
                  </button>
                  <button type="button" class="btn btn-secondary btn-rounded btn-fw feedback-button">Excellent quality and prompt delivery.
                    <button class="btn btn-icon btn-sm btn-outline-primary ml-2">
                      <i class="mdi mdi-dots-horizontal"></i>
                    </button>
                  </button>
                </div>
              </div>
              <div class="card-footer">
                <div class="text-center">
                  <i class="mdi mdi-star text-warning"></i>
                  <i class="mdi mdi-star text-warning"></i>
                  <i class="mdi mdi-star text-warning"></i>
                  <i class="mdi mdi-star text-warning"></i>
                  <i class="mdi mdi-star-outline text-warning"></i>
                </div>
              </div>
            </div>
          </div>

        </div>


      </div>
      <!-- content-wrapper ends -->
      <!-- partial:partials/_footer.html -->
      <!-- partial -->
    </div>
    <!-- main-panel ends -->
  </div>
  <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <canvas id="popularCategoriesChart" width="400" height="400"></canvas>
  <script>
    // Data for the chart
    const data = {
      labels: <?php echo json_encode($categories); ?>,
      datasets: [{
        label: 'Popular Categories',
        data: <?php echo json_encode($counts); ?>,
        backgroundColor: [
          'rgba(54, 162, 235, 0.6)',
          'rgba(255, 206, 86, 0.6)',
          'rgba(75, 192, 192, 0.6)',
          // Add more colors if there are more categories
        ],
        borderColor: [
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          // Add more colors if there are more categories
        ],
        borderWidth: 1
      }]
    };

    // Configuration for the chart
    const config = {
      type: 'pie',
      data: data,
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          tooltip: {
            callbacks: {
              label: function(tooltipItem) {
                return data.labels[tooltipItem.dataIndex] + ': ' + data.datasets[0].data[tooltipItem.dataIndex];
              }
            }
          }
        }
      }
    };

    // Render the chart
    window.onload = function() {
      const ctx = document.getElementById('popularCategoriesChart').getContext('2d');
      new Chart(ctx, config);
    };
  </script>

  <!-- plugins:js -->
  <script src="vendors/base/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="vendors/chart.js/Chart.min.js"></script>
  <script src="js/jquery.cookie.js" type="text/javascript"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <script src="js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="js/dashboard.js"></script>
  <!-- End custom js for this page-->
</body>

</html>