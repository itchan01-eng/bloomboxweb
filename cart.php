<?php
session_start();
include_once("API/conn.php");

if (!isset($_SESSION["user_id"])) {
  header("Location: auth.php");
  exit();
}
$user_id = $_SESSION["user_id"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bloom Box OrderHub - Cart</title>
  <link rel="icon" type="image/png" href="assets/img/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    body {font-family: 'Poppins', sans-serif; background: #f8f9fa; color: #212529; }
    .navbar { background: #c8102e; padding: 0.8rem 1rem; }
    .navbar-brand img { height: 40px; filter: brightness(0) invert(1); }
    .navbar-nav .nav-link { color: #fff !important; margin-left: 1rem; transition: 0.3s; }
    .navbar-nav .nav-link:hover { color: #ffd700 !important; }
    .navbar-nav .nav-link.active { font-weight: bold; color: #ffd700 !important; }
    h3 { color: #c8102e; font-weight: bold; }
    .table { background: #fff; border-radius: 0.75rem; overflow: hidden; }
    .table thead { background: #c8102e; color: #fff; text-align: center; }
    .table tbody tr td { vertical-align: middle; text-align: center; }
    .table img { border-radius: 8px; margin-right: 8px; }
    .checkout-box { background: #fff; padding: 1.5rem; border-radius: 1rem; border: 1px solid #eee; }
    .checkout-box h5 { color: #c8102e; }
    .btn-danger { background: #dc3545; border: none; }
    .btn-danger:hover { background: #b02a37; }
    .btn-primary { background: #c8102e; border: none; }
    .btn-primary:hover { background: #a50d24; }
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] {
      text-align: center;
      font-weight: bold;
      border: 1px solid #ccc;
      border-radius: 6px;
      width: 65px;
      padding: 4px;
    }
    footer {
  background: #c8102e;
  color: #fff;
  padding: 25px 0;
  text-align: center;
  position: fixed;     /* Stays fixed at the bottom */
  bottom: 0;           /* Anchors it to the bottom */
  left: 0;             /* Ensures it spans from the left */
  width: 100%;         /* Full width */
  z-index: 10;         /* Keeps it above other elements */
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
            <li class="nav-item"><a class="nav-link" href="orders.php"><i class="bi bi-basket2-fill"></i> My Orders</a></li>
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle position-relative active" href="#" role="button" data-bs-toggle="dropdown">
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
    
    <div class="container my-5">
      <h3 class="mb-4"><i class="bi bi-cart3 me-2"></i> Your Cart</h3>
      <div class="row">
        <div class="col-lg-8 mb-4">
          <div class="table-responsive shadow-sm">
            <table class="table table-hover align-middle">
              <thead>
              <tr>
                <th style="width:40%">Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody id="cart-body">
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">Loading cart...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="checkout-box shadow-sm">
          <h5 class="fw-bold mb-3">Order Summary</h5>
          <p class="d-flex justify-content-between mb-2">
            <span>Subtotal</span> <span id="subtotal">₱0.00</span>
          </p>
          <p class="d-flex justify-content-between mb-2">
            <span>Delivery Fee</span> <span id="delivery">₱0.00</span>
          </p>
          <hr>
          <h5 class="d-flex justify-content-between fw-bold mb-3">
            <span>Total</span> <span id="total">₱0.00</span>
          </h5>
          <a href="checkout.php" class="btn btn-primary w-100 mt-2">
            <i class="bi bi-credit-card me-1"></i> Proceed to Checkout
          </a>
        </div>
      </div>
    </div>
  </div>

  <footer>
        <div class="container">
            <p class="mb-0">&copy; 2025 Bloom Box OrderHub. All rights reserved.</p>
        </div>
    </footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/cart.js"></script>
<script src="assets/js/mycart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
