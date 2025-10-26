<?php
session_start();
include_once("API/conn.php");

if (!isset($_SESSION["user_id"])) {
  header("Location: auth.php");
  exit();
}

$user_id = $_SESSION["user_id"];
$sql = "SELECT f_name FROM users WHERE user_id='$user_id' LIMIT 1";
$result = $conn->query($sql);
$user = ($result && $result->num_rows > 0) ? $result->fetch_assoc()["f_name"] : "User";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bloom Box OrderHub - My Orders</title>
  <link rel="icon" type="image/png" href="assets/img/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
    body {font-family: 'Poppins', sans-serif; background: #f8f9fa; color: #212529; }
    .navbar { background: #c8102e; padding: 0.8rem 1rem; }
    .navbar-brand img { height: 40px; filter: brightness(0) invert(1); }
    .navbar-nav .nav-link { color: #fff !important; margin-left: 1rem; transition: 0.3s; }
    .navbar-nav .nav-link:hover { color: #ffd700 !important; }
    .navbar-nav .nav-link.active { font-weight: bold; color: #ffd700 !important; }
    .hero-section { background: #c8102e; color: #fff; text-align: center; padding: 3rem 1rem; margin-bottom: 2rem; }
    .hero-section img { height: 90px; margin-bottom: 1rem; filter: brightness(0) invert(1); }
    .order-card {
      background: #fff;
      border: 1px solid #eee;
      border-radius: 12px;
      padding: 1.2rem;
      margin-bottom: 1.2rem;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
      transition: 0.3s;
    }
    .order-card:hover { transform: scale(1.01); }
    .order-status {
      font-weight: bold;
      border-radius: 50px;
      padding: 5px 15px;
      font-size: 0.85rem;
    }
    .status-Pending { background-color: #fff3cd; color: #856404; }
    .status-Completed { background-color: #d4edda; color: #155724; }
    .status-Cancelled { background-color: #f8d7da; color: #721c24; }
    .item-list {
      margin-top: 1rem;
      max-height: 180px;
      overflow-y: auto;
    }
    .item-list::-webkit-scrollbar { width: 6px; }
    .item-list::-webkit-scrollbar-thumb { background: #c8102e; border-radius: 10px; }
    .item {
      display: flex;
      align-items: center;
      background: #f9f9f9;
      border-radius: 10px;
      padding: 10px;
      margin-bottom: 10px;
    }
    .item img {
      width: 55px;
      height: 55px;
      border-radius: 8px;
      object-fit: cover;
      margin-right: 10px;
    }
    .item-details { flex-grow: 1; }
    .item-details h6 { margin: 0; font-size: 0.95rem; font-weight: 600; }
    .item-details p { margin: 0; font-size: 0.85rem; color: #555; }
    .highlight-note {
      background: #fff3cd;
      padding: 8px 10px;
      border-radius: 8px;
      font-weight: 500;
    }
    #backToTop {
      position: fixed; bottom: 30px; right: 30px; display: none;
      background-color: #c8102e; color: #fff; border: none;
      border-radius: 50%; width: 50px; height: 50px;
      font-size: 1.5rem; cursor: pointer;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3); z-index: 1000;
    }
    #backToTop:hover { background-color: #a50d24; }
    .highlight-note {
        background: #fff8e1;
        border-left: 4px solid #ffc107;
        padding: 10px 12px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.95rem;
        color: #333;
    }
    .modal-content {
        border: none !important;
    }
    .modal-header i {
        font-size: 1.2rem;
    }
    footer {
      background: #c8102e;
      color: #fff;
      padding: 25px 0;
      text-align: center;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="assets/img/logo.png" alt="Bloom Box Logo" class="me-2" style="height: 40px;">
        <span class="fw-bold text-white">Bloom Box OrderHub</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-center">
            <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-house"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link active" href="orders.php"><i class="bi bi-basket2-fill"></i> My Orders</a></li>
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle position-relative" href="#" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-cart3"></i> Cart
                <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">0</span>
            </a>
            <ul id="cart-dropdown" class="dropdown-menu dropdown-menu-end p-2" style="min-width: 300px;">
                <li class="text-center text-muted">Your cart is empty.</li>
            </ul>
            </li>
            <li class="nav-item"><a class="nav-link fw-bold" onclick="logoutConirmation();"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
        </ul>
        </div>
    </div>
    </nav>
    
    <div class="hero-section">
        <img src="assets/img/logo.png" alt="Bloom Box Logo">
        <h2 style="font-family: 'Luckiest Guy', cursive; letter-spacing: 4px; font-size: 2.6rem; text-shadow: 0 8px 8px rgba(0,0,0,0.5);">My Orders</h2>
        <p>Track your orders and manage your deliveries 🍱</p>
    </div>

    <div class="container mb-5">
        <div class="order-container">
            <?php
            $orders = $conn->query("SELECT * FROM orders WHERE user_id='$user_id' ORDER BY id DESC");
            if ($orders && $orders->num_rows > 0) {
                while ($row = $orders->fetch_assoc()) {
                    $status = ucfirst($row["status"] ?? "Pending");
                    $items = json_decode($row["items_ordered"], true);
                    $payment = "NULL";
                    if($row["payment_method"] == "cod"){
                        $payment = "Cash On Delivery / COD";
                    };
                    echo '
                    <div class="order-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Order #'.$row["order_id"].'</h5>
                    <span class="order-status status-'.$status.'">'.$status.'</span>
                    </div>
                    <p class="mb-1"><b>Date:</b> '.($row["date_ordered"] ?? "N/A").'</p>
                    <p class="mb-1"><b>Payment:</b> '.$payment.'</p>
                    <hr>
                    <div class="item-list">';
                    if (!empty($items)) {
                        foreach ($items as $item) {
                            echo '
                            <div class="item">
                            <img src="'.htmlspecialchars($item["image_url"]).'" alt="">
                            <div class="item-details">
                            <h6>'.htmlspecialchars($item["item_name"]).'</h6>
                            <p>Qty: '.$item["quantity"].' × ₱'.$item["price"].'</p>
                            </div>
                            <div><b>₱'.number_format($item["quantity"] * $item["price"], 2).'</b></div>
                            </div>';
                        }
                    } else {
                        echo '<p class="text-muted">No items found.</p>';
                    }
                    echo '
                    </div>
                    <div class="d-flex justify-content-end mt-3">';
                    if ($status == "Pending") {
                        echo '<button class="btn btn-outline-danger btn-sm me-2 cancel-btn" data-id="'.$row["order_id"].'"><i class="bi bi-x-circle"></i> Cancel</button>';
                    }
                    echo '<button class="btn btn-outline-primary btn-sm view-status-btn"
                    data-id="'.$row["order_id"].'"
                    data-fname="'.$row["fname"].'"
                    data-lname="'.$row["lname"].'"
                    data-phone="'.$row["phonenumber"].'"
                    data-barangay="'.$row["barangay"].'"  
                    data-address="'.$row["full_address"].'"
                    data-notes="'.htmlspecialchars($row["notes"]).'"
                    data-status="'.$status.'"><i class="bi bi-truck"></i> View Status</button>';
                    echo '</div>
                    </div>';
                }
            } else {
                echo '<p class="text-center text-muted mt-5">You have no orders yet.</p>';
            } ?>
        </div>
    </div>
    
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title fw-bold"><i class="bi bi-truck me-2"></i>Order Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light px-4 py-3">
                    <div class="p-3 bg-white rounded-3 shadow-sm mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 text-danger"><b>Order ID:</b> <span id="modalOrderId"></span></h6>
                            <span id="modalStatus" class="badge rounded-pill bg-warning text-dark fw-bold px-3 py-2"></span>
                        </div>
                        <hr class="my-2">
                        <div class="row g-2">
                            <div class="col-md-6"><i class="bi bi-person-fill text-danger me-2"></i><b>Name:</b> <span id="modalName"></span></div>
                            <div class="col-md-6"><i class="bi bi-telephone-fill text-danger me-2"></i><b>Phone:</b> <span id="modalPhone"></span></div>
                            <div class="col-md-6"><i class="bi bi-geo-alt-fill text-danger me-2"></i><b>Barangay:</b> <span id="modalBarangay"></span></div>
                            <div class="col-md-6"><i class="bi bi-house-fill text-danger me-2"></i><b>Address:</b> <span id="modalAddress"></span></div>
                        </div>
                    </div>
                    <div class="p-3 bg-white rounded-3 shadow-sm">
                        <h6 class="text-danger mb-2"><i class="bi bi-card-text me-2"></i>Note</h6>
                        <div id="modalNote" class="highlight-note shadow-sm"></div>
                    </div>
                </div>
                <div class="modal-footer bg-danger text-white d-flex justify-content-between rounded-bottom-4">
                    <small><i class="bi bi-info-circle me-2"></i>Delivery progress feature coming soon...</small>
                    <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <button id="backToTop"><i class="bi bi-arrow-up"></i></button>
    <footer>
        <div class="container">
            <p class="mb-0">&copy; 2025 Bloom Box OrderHub. All rights reserved.</p>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/orders.js"></script>
</body>
</html>
