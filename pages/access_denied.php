<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Access Denied</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: url('assets/img/bloomboxbg.jpg') center center/cover no-repeat;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
      position: relative;
      overflow: hidden;
    }

    body::before {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(6px);
      z-index: 0;
    }

    .block-container {
      z-index: 1;
      text-align: center;
      background: rgba(0, 0, 0, 0.5);
      padding: 40px 60px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .block-container h1 {
      font-size: 28px;
      margin-bottom: 15px;
      color: #fff;
    }

    .block-container p {
      font-size: 16px;
      color: #ddd;
      margin-bottom: 25px;
    }

    .block-container a {
      text-decoration: none;
      background: #c8102e;
      color: #fff;
      padding: 10px 20px;
      border-radius: 6px;
      transition: 0.3s;
    }

    .block-container a:hover {
      background: #a30f23;
    }
  </style>
</head>
<body>
  <div class="block-container">
    <h1>Access Denied 🚫</h1>
    <p>You cannot enter this page without adding items to your cart.</p>
    <a href="dashboard.php">Return to Dashboard</a>
  </div>
</body>
</html>
