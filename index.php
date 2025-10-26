<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bloom Box OrderHub</title>
  <link rel="icon" type="image/png" href="assets/img/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fff;
      color: #212529;
    }
    .navbar {
      background-color: #c8102e;
    }
    .navbar-brand img {
      height: 40px;
      filter: brightness(0) invert(1);
    }
    .navbar-nav .nav-link {
      color: #fff !important;
      font-weight: 500;
      margin-left: 1rem;
      transition: 0.3s;
    }
    .navbar-nav .nav-link:hover {
      color: #ffd700 !important;
    }
    .hero {
      position: relative;
      background: url('assets/img/bloomboxbg.jpg') center center/cover no-repeat;
      height: 90vh;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      flex-direction: column;
      padding: 0 20px;
      overflow: hidden;
    }
    .hero::before {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.55); /* Dark overlay */
      backdrop-filter: blur(6px);       /* Blur effect */
      -webkit-backdrop-filter: blur(6px);
      z-index: 1;
    }
    .hero * {
      position: relative;
      z-index: 2;
    }
    .hero h1, .hero p {
      text-shadow: 0 2px 6px rgba(0,0,0,0.5);
    }
    .hero::before {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.45);
      z-index: 0;
    }
    .hero h1, .hero p, .hero div {
      position: relative;
      z-index: 1;
    }
    .hero h1 {
      font-size: 3rem;
      font-weight: 700;
    }
    .hero p {
      font-size: 1.2rem;
      margin: 15px 0 25px;
    }
    .btn-primary {
      background: #c8102e;
      border: none;
      padding: 12px 30px;
      font-weight: 600;
      border-radius: 30px;
    }
    .btn-primary:hover {
      background: #a30d22;
    }
    .about {
      padding: 80px 0;
    }
    .about h2 {
      color: #c8102e;
      font-weight: bold;
      margin-bottom: 30px;
    }
    .features {
      background: #f8f9fa;
      padding: 80px 0;
    }
    .feature-box {
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      transition: 0.3s;
    }
    .feature-box:hover {
      transform: translateY(-5px);
    }
    .feature-box i {
      font-size: 2.5rem;
      color: #c8102e;
      margin-bottom: 15px;
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
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="assets/img/logo.png" alt="Bloom Box Logo" class="me-2">
      <span class="fw-bold">Bloom Box OrderHub</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a href="#about" class="nav-link">About</a></li>
        <li class="nav-item"><a href="#features" class="nav-link">Features</a></li>
        <li class="nav-item"><a href="auth.php" class="nav-link fw-bold"><i class="bi bi-box-arrow-in-right me-1"></i>Login</a></li>
      </ul>
    </div>
  </div>
</nav>

<section class="hero">
  <h1 style="font-family: 'Luckiest Guy', cursive; letter-spacing: 1px; font-size: 3.6rem; text-transform: uppercase; text-shadow: 0 2px 8px rgba(0,0,0,0.5);">Welcome to <span style="color:#ffd700;">Bloom Box OrderHub</span></h1>
  <p>Where freshness meets convenience — order your favorite meals, snacks, and drinks in one place!</p>
  <div>
    <a href="auth.php" class="btn btn-primary me-3"><i class="bi bi-cart me-1"></i> Order Now</a>
  </div>
</section>

<section class="about text-center" id="about">
  <div class="container">
    <h2>About Bloom Box</h2>
    <p class="lead w-75 mx-auto">
      Bloom Box OrderHub is a simple food ordering system designed to make your dining experience easier and faster.
      From sandwiches and pies to refreshing drinks, we bring your favorite food right to your fingertips.
    </p>
  </div>
</section>

<section class="features text-center" id="features">
  <div class="container">
    <h2 class="mb-5 fw-bold text-danger">Our Key Features</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="feature-box">
          <i class="bi bi-basket2"></i>
          <h5 class="fw-bold mt-2">Easy Ordering</h5>
          <p>Browse our menu and add your favorite meals to your cart with just a few clicks.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-box">
          <i class="bi bi-clock-history"></i>
          <h5 class="fw-bold mt-2">Fast Checkout</h5>
          <p>Quick and secure checkout ensures your order is processed right away.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-box">
          <i class="bi bi-truck"></i>
          <h5 class="fw-bold mt-2">Reliable Delivery</h5>
          <p>Enjoy timely delivery of your delicious food — fresh and ready to eat.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="text-center py-3">
  <div class="container">
    <a href="admin/auth.php" 
       style="font-size: 0.9rem; color: #999; text-decoration: none;">
       Admin Login
    </a>
  </div>
</section>


<footer>
  <div class="container">
    <p class="mb-0">&copy; 2025 Bloom Box OrderHub. All rights reserved.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
