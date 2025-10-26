<?php
session_start();
include_once("API/conn.php");

if (!isset($_SESSION["user_id"])) {
  header("Location: auth.php");
  exit();
}

$user_id = $_SESSION["user_id"];
$checkSql = "SELECT * FROM cart WHERE user_id='$user_id' LIMIT 1";
$result = $conn->query($checkSql);
if ($result->num_rows > 0) {
    $sql = "SELECT f_name, l_name FROM users WHERE user_id='$user_id' LIMIT 1";
    $result = $conn->query($sql);
    $user = ($result && $result->num_rows > 0) ? $result->fetch_assoc() : ["f_name" => "", "l_name" => ""];
} else {
    include_once("pages/access_denied.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Checkout - Bloom Box OrderHub</title>
  <link rel="icon" type="image/png" href="assets/img/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
  body {font-family: 'Poppins', sans-serif; background: #f8f9fa; color: #212529; }
  .navbar { background: #c8102e; padding: 0.8rem 1rem; }
  .navbar-brand img { height: 40px; filter: brightness(0) invert(1); }
  .navbar-nav .nav-link { color: #fff !important; margin-left: 1rem; }
  .navbar-nav .nav-link.active { color: #ffd700 !important; }
  .form-box, .summary-box {
    background: #fff;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
   }
   .btn-primary { background: #c8102e; border: none; }
   .btn-primary:hover { background: #a50d24; }
   .summary-box img { width: 50px; height: 50px; border-radius: 6px; object-fit: cover; margin-right: 10px; }
   .form-select {
    position: relative;
    z-index: 10;
    background-color: #fff;
    }
    .form-select:focus {
    border-color: #c8102e;
    box-shadow: 0 0 0 0.2rem rgba(200, 16, 46, 0.25);
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
                    <li class="nav-item"><a class="nav-link" href="orders.php"><i class="bi bi-basket2-fill"></i> My Orders</a></li>
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
    </nav>
    
    <div class="container my-5">
        <div class="row g-4">
            <div class="col-lg-7">
                            <div class="form-box">
                                <h4 class="mb-3 text-danger fw-bold"><i class="bi bi-truck"></i> Delivery Information</h4>
                                <form id="checkoutForm">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="fname" value="<?php echo htmlspecialchars($user['f_name']); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="lname" value="<?php echo htmlspecialchars($user['l_name']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" id="phone" placeholder="09xxxxxxxxx" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Barangay</label>
                                        <select class="form-select" id="barangay" required>
                                            <option value="">-- Select Barangay --</option>
                                            <option value="Zone I">Zone I</option>
                                            <option value="Zone II">Zone II</option>
                                            <option value="Zone III">Zone III</option>
                                            <option value="Zone IV">Zone IV</option>
                                            <option value="Zone V">Zone V</option>
                                            <option value="Zone VI">Zone VI</option>
                                            <option value="Zone VII">Zone VII</option>
                                            <option value="Zone VIII">Zone VIII</option>
                                            <option value="Zone IX">Zone IX</option>
                                            <option value="Zone X">Zone X</option>
                                            <option value="Avanceña">Avanceña</option>
                                            <option value="Carpenter Hill">Carpenter Hill</option>
                                            <option value="Concepcion">Concepcion</option>
                                            <option value="General Paulino Santos">General Paulino Santos</option>
                                            <option value="Magsaysay">Magsaysay</option>
                                            <option value="Morales">Morales</option>
                                            <option value="Paraiso">Paraiso</option>
                                            <option value="Rotonda">Rotonda</option>
                                            <option value="San Isidro">San Isidro</option>
                                            <option value="San Jose">San Jose</option>
                                            <option value="San Miguel">San Miguel</option>
                                            <option value="Santa Cruz">Santa Cruz</option>
                                            <option value="Santo Niño">Santo Niño</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Full Address</label>
                                        <textarea class="form-control" id="address" rows="2" placeholder="House No., Street, Landmark" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Notes (optional)</label>
                                        <textarea class="form-control" id="notes" rows="2" placeholder="Add any instructions for the rider"></textarea>
                                    </div>
                                    <h5 class="mt-4"><i class="bi bi-cash-coin"></i> Payment Method</h5>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="cod" name="codpayment" checked>
                                        <label class="form-check-label" for="cod">Cash on Delivery (COD)</label>
                                    </div>
                                    <button type="button" id="placeOrder" class="btn btn-primary w-100 mt-4">
                                        <i class="bi bi-credit-card"></i> Proceed to Payment
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="summary-box">
                                <h5 class="fw-bold text-danger"><i class="bi bi-receipt"></i> Your Order Summary</h5>
                                <div id="order-list" class="mt-3"></div>
                                <hr>
                                <p class="d-flex justify-content-between mb-1">
                                    <span>Subtotal</span> <span id="subtotal">₱0.00</span>
                                </p>
                                <p class="d-flex justify-content-between mb-1">
                                    <span>Delivery Fee</span> <span id="delivery">₱49.00</span>
                                </p>
                                <hr>
                                <h5 class="d-flex justify-content-between">
                                    <span>Total</span> <span id="total">₱0.00</span>
                                </h5>
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
    <script src="assets/js/checkout.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
