    <?php
    session_start();
    include_once("API/conn.php");

    if(!isset($_SESSION["user_id"])) {
    header("Location: auth.php");
    exit();
    }

    $user_id = $_SESSION["user_id"];

    $sql = "SELECT f_name, l_name FROM users WHERE user_id='$user_id' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user = $row["f_name"];
    } else {
        $user = "User";
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bloom Box OrderHub - Dashboard</title>
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
        .hero-section { background: #c8102e; color: #fff; text-align: center; padding: 3rem 1rem; margin-bottom: 2rem; }
        .hero-section img { height: 90px; margin-bottom: 1rem; filter: brightness(0) invert(1); }
        .hero-section h2 { font-weight: bold; margin-bottom: 0.5rem; }
        .hero-section p { font-size: 1rem; opacity: 0.9; }
        .product-card { background: #fff; border: 1px solid #eee; border-radius: 1rem; transition: transform 0.2s, box-shadow 0.2s; text-align: center; padding: 1rem; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
        .product-card img { height: 180px; width: 100%; object-fit: contain; margin-bottom: 1rem; }
        .product-title { font-weight: 600; font-size: 1rem; margin-bottom: 0.5rem; color: #212529; }
        .product-price { font-size: 1.1rem; font-weight: bold; color: #c8102e; margin-bottom: 1rem; }
        .btn-primary { background-color: #c8102e; border: none; border-radius: 50px; padding: 0.6rem 1.2rem; }
        .btn-primary:hover { background-color: #a50d24; }
        .btn-primary:focus { background-color: #a50d24; }
        .quantity-selector {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 50px;
        padding: 5px 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        transition: 0.2s;
        }
        .quantity-selector button {
        border-radius: 50%;
        width: 30px;
        height: 30px;
        font-weight: bold;
        padding: 0;
        }
        .quantity-selector .qty-value {
        font-size: 1rem;
        width: 30px;
        text-align: center;
        color: #212529;
        }
        #cart-dropdown { max-height: 300px; overflow-y: auto; }
        #backToTop {
        position: fixed;
        bottom: 30px;
        right: 30px;
        display: none;
        background-color: #c8102e;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        z-index: 1000;
        }
        #backToTop:hover { background-color: #a50d24; }
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
            <li class="nav-item"><a class="nav-link active" href="dashboard.php"><i class="bi bi-house"></i> Dashboard</a></li>
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
    </div>
    </nav>

    <div class="hero-section">
    <img src="assets/img/logo.png" alt="Bloom Box Logo">
    <h2 style="font-family: 'Luckiest Guy', cursive; letter-spacing: 4px; font-size: 2.6rem; text-shadow: 0 8px 8px rgba(0,0,0,0.5);">Welcome back, <?php echo htmlspecialchars($user); ?>!</h2>
    <p>Delicious meals, snacks, and drinks, all in one place 🌸🍴</p>
    </div>

    <div class="container mb-5">
    <?php include_once("API/extensions/table_items.php"); ?>
    </div>

    <button id="backToTop"><i class="bi bi-arrow-up"></i></button>
    <footer>
        <div class="container">
            <p class="mb-0">&copy; 2025 Bloom Box OrderHub. All rights reserved.</p>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/dashboard.js"></script>
    <script src="assets/js/cart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
