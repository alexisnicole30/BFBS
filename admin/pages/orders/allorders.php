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

// Fetch orders from the database
$sql = "
    SELECT 
        o.order_id, 
        o.prod_id,
        c.cust_num,
        o.variations,
        o.date_purchase AS order_date, 
        o.totalPrice AS total, 
        o.status AS fulfillment_status,
        o.quantity AS total_items
    FROM 
        orders o
    JOIN 
        customers c ON o.cust_num = c.cust_num
    ORDER BY 
        o.date_purchase DESC";

$result = $conn->query($sql);

$orders = [];
if ($result->num_rows > 0) {
  // Fetch all orders
  while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
  }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Blissful Bouquet</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../../vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../../vendors/base/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../css/style.css">
  <!-- endinject -->
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
          <a class="nav-link" href="../../pages/orders/allorders.php">
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
                <h4 class="font-weight-bold mb-0">All Orders</h4>
              </div>
              <div>
              </div>
            </div>
          </div>
        </div>

        <div class="main-panel">
          <div class="content-wrapper">
            <!-- Table to display orders -->
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>Order ID</th>
                          <th>Product ID</th>
                          <th>Customer No.</th>
                          <th>Variations</th>
                          <th>Date</th>
                          <th>Total</th>
                          <th>Fulfillment Status</th>
                          <th>Items</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (count($orders) > 0) : ?>
                          <?php foreach ($orders as $order) : ?>
                            <tr class="clickable-row" data-order-id="<?php echo $order['order_id']; ?>">
                              <td style="text-align: left;"><?php echo $order['order_id']; ?></td>
                              <td style="text-align: left;"><?php echo $order['prod_id']; ?></td>
                              <td style="text-align: left;"><?php echo $order['cust_num']; ?></td>
                              <td style="text-align: left;"><?php echo $order['variations']; ?></td>
                              <td style="text-align: left;"><?php echo $order['order_date']; ?></td>
                              <td style="text-align: left;"><?php echo $order['total']; ?></td>
                              <td style="text-align: left;">
                                <!-- Order status dropdown -->
                                <select class="form-control fulfillment-status" data-order-id="<?php echo $order['order_id']; ?>">
                                  <option value="Pending" <?php if ($order['fulfillment_status'] == "Pending") echo "selected"; ?>>Pending</option>
                                  <option value="Delivered" <?php if ($order['fulfillment_status'] == "Delivered") echo "selected"; ?>>Delivered</option>
                                  <option value="Shipped" <?php if ($order['fulfillment_status'] == "Shipped") echo "selected"; ?>>Shipped</option>
                                  <option value="Cancel" <?php if ($order['fulfillment_status'] == "Cancel") echo "selected"; ?>>Cancel</option>
                                </select>
                              </td>
                              <td style="text-align: left;"><?php echo $order['total_items']; ?></td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else : ?>
                          <tr>
                            <td colspan="6" style="text-align: center;">No orders found.</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>


            <!-- Order Details Modal -->
            <div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body" id="orderDetailsBody">
                    <!-- Order details will be dynamically loaded here -->
                  </div>
                  <div class="modal-footer">
                    <!-- Order status dropdown in modal footer -->
                    <select class="form-control fulfillment-status-modal" id="fulfillment-status-modal">
                      <option value="Pending">Pending</option>
                      <option value="Delivered">Delivered</option>
                      <option value="Shipped">Shipped</option>
                      <option value="Cancel">Cancel</option>
                    </select>
                    <button type="button" class="btn btn-primary" onclick="updateOrderStatusModal()">Update Status</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    // Update fulfillment status when dropdown value changes
    $('.fulfillment-status').change(function() {
        var orderId = $(this).data('order-id');
        var status = $(this).val();

        // AJAX request to update fulfillment status
        $.ajax({
            url: 'update_fulfillment_status.php',
            method: 'POST',
            data: {
                order_id: orderId,
                status: status
            },
            success: function(response) {
                console.log(response);
                // Optionally update UI or show success message
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
</script>


    <script>
      function updateOrderStatus(orderId) {
        var newStatus = document.getElementById('order' + orderId + '_status').value;
        // Here you can perform an AJAX request to update the status in the database
        // For demonstration purposes, we'll just log the status to the console
        console.log('Updating status of order ' + orderId + ' to: ' + newStatus);
      }
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