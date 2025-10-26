<?php
session_start();
include_once("API/conn.php");

if(isset($_SESSION["user_id"])){
    header("Location: dashboard.php");
}

$error = false;
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    if(isset($_POST["email"]) && isset($_POST["password"]) && !empty($_POST["email"]) || !empty($_POST["password"])){
      $email = mysqli_real_escape_string($conn, $_POST["email"]);
      $password = md5(mysqli_real_escape_string($conn, $_POST["password"]));

      if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $message = "Please enter a valid email address and try again.";
      } else {
        $sql = "SELECT * FROM user_login WHERE email_address='$email' AND password='$password' LIMIT 1";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $_SESSION["user_id"] = $row["user_id"];

          header("Location: dashboard.php");
        } else {
          $error = true;
          $message = "Email address or password is incorrect. Please try again.";
        }
    }
  }
}


  if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
      if(isset($_POST["first_name"]) && isset($_POST["last_name"]) && !empty($_POST["first_name"]) || 
      !empty($_POST["last_name"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm_password"]) 
      && !empty($_POST["email"]) || !empty($_POST["password"]) || !empty($_POST["confirm_password"])){
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $password = md5(mysqli_real_escape_string($conn, $_POST["password"]));
        $confirm_password = md5(mysqli_real_escape_string($conn, $_POST["confirm_password"]));
        $first_name = mysqli_real_escape_string($conn, $_POST["first_name"]);
        $last_name = mysqli_real_escape_string($conn, $_POST["last_name"]);

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $error = true;
          $message = "Please enter a valid email address and try again.";
        } else if (strlen($password_raw) < 8) {
          $error = true;
          $message = "Password must be at least 8 characters long.";
        } else {
          $checkSql = "SELECT * FROM user_login WHERE email_address='$email' LIMIT 1";
          $result = $conn->query($checkSql);
          
          if ($result->num_rows > 0) {
            $error = true;
            $message = "Email address already exists. Please try again with another one.";
          } else if($password == $confirm_password) {

            $user_id = random_int(100000000, 999999999); 

            $userInfo = "INSERT INTO users (user_id, f_name, l_name) 
                        VALUES ('$user_id', '$first_name', '$last_name')";
            $userLogin = "INSERT INTO user_login (user_id, email_address, password) 
                          VALUES ('$user_id', '$email', '$password')";

            $userInfoInsertion = $conn->query($userInfo);
            $userLoginInsertion = $conn->query($userLogin);

            if ($userInfoInsertion && $userLoginInsertion) {
              $_SESSION["user_id"] = $user_id;

              header("Location: index.php");
            } else {
              $error = true;
              $message = "Something went wrong. We were unable to register you to our database. Please try again.";
            }
          } else {
            $error = true;
            $message = "Password does not match. Please try again.";
          }
        }
      }
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bloom Box OrderHub - Authentication</title>
  <link rel="icon" type="image/png" href="assets/img/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Luckiest+Guy&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      color: #212529; 
    }
    .hero { 
      position: relative; 
      background: url('assets/img/bloomboxbg.jpg') center center/cover no-repeat; 
      min-height: 100vh; 
      display: flex; 
      align-items: center; 
      justify-content: center; 
      flex-direction: column; 
      padding: 0 20px; 
      overflow: hidden; 
    } 
    .hero::before { 
      content: "";
      position: absolute; 
      inset: 0; 
      background: rgba(0, 0, 0, 0.55); 
      backdrop-filter: blur(6px); 
      -webkit-backdrop-filter: blur(6px); 
      z-index: 1; 
    } 
    .hero * { 
      position: relative; 
      z-index: 2; 
    }
    .auth-card {
      background: rgba(255, 255, 255, 0.9);
      border-radius: 1rem;
      width: 550px;
      max-width: 90%;
      box-shadow: 0 8px 24px rgba(0,0,0,0.3);
      backdrop-filter: blur(10px);
      overflow: hidden;
    }
    .auth-header {
      background: #c8102e;
      color: #fff;
      padding: 1.2rem;
      text-align: center;
    }
    .auth-header img {
      height: 80px;
      display: block;
      margin: 0 auto 0.5rem auto;
    }
    .auth-header h2 {
      font-size: 1.2rem;
      margin: 0;
      font-weight: bold;
    }
    .form-section {
      background: #f8f9fa;
      padding: 1rem;
      border-radius: 0.75rem;
      margin-bottom: 1rem;
      border: 1px solid #e0e0e0;
    }
    .form-section h6 {
      font-size: 0.9rem;
      margin-bottom: 0.75rem;
      color: #c8102e;
      font-weight: 600;
    }
    .form-control {
      font-size: 0.9rem;
      border-radius: 0.5rem;
      border: 1px solid #ccc;
    }
    .form-control:focus {
      border-color: #c8102e;
      box-shadow: 0 0 0 0.15rem rgba(200,16,46,0.25);
    }
    .nav-pills .nav-link {
      color: #212529;
      border-radius: 50px;
      background: #e9ecef;
    }
    .nav-pills .nav-link.active {
      background: #c8102e;
      color: #fff;
    }
    .btn-primary {
      background-color: #c8102e;
      border: none;
      border-radius: 0.5rem;
    }
    .btn-primary:hover {
      background-color: #a50d24;
    }
    .input-group-text {
      background: #fff;
      border-left: none;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <div class="hero">
    <div class="auth-card">
      <div class="auth-header">
        <img src="assets/img/logo.png" alt="Bloom Box Logo">
        <h2 style="font-family: 'Luckiest Guy', cursive; letter-spacing: 1px; font-size: 1.6rem; text-transform: uppercase; text-shadow: 0 2px 8px rgba(0,0,0,0.5);">Welcome to Bloom Box OrderHub</h2>
      </div>

      <div class="p-4">
        <ul class="nav nav-pills nav-justified mb-3 gap-2" id="authTabs" role="tablist">
          <li class="nav-item">
            <button class="nav-link active fw-bold" id="login-tab" data-bs-toggle="pill" data-bs-target="#login" type="button" role="tab">
              <i class="bi bi-box-arrow-in-right me-1"></i> Login
            </button>
          </li>
          <li class="nav-item">
            <button class="nav-link fw-bold" id="register-tab" data-bs-toggle="pill" data-bs-target="#register" type="button" role="tab">
              <i class="bi bi-person-plus me-1"></i> Register
            </button>
          </li>
        </ul>

        <div class="tab-content" id="authTabsContent">
          <div class="tab-pane fade show active" id="login" role="tabpanel">
            <form method="POST">
              <div class="form-section">
                <h6><i class="bi bi-lock me-2"></i> Login Information</h6>
                <div class="mb-2">
                  <label for="loginEmail" class="form-label">Email</label>
                  <input type="email" name="email" id="loginEmail" class="form-control" placeholder="Enter email address">
                </div>
                <div class="mb-2">
                  <label for="loginPassword" class="form-label">Password</label>
                  <div class="input-group">
                    <input type="password" name="password" id="loginPassword" class="form-control" placeholder="Password">
                    <span class="input-group-text" onclick="togglePassword('loginPassword', this)">
                      <i class="bi bi-eye"></i>
                    </span>
                  </div>
                </div>
                <small><a href="#" style="text-decoration:none;">Forgot your password?</a></small>
              </div>
              <button type="submit" name="login" class="btn btn-primary w-100 fw-bold">
                <i class="bi bi-box-arrow-in-right me-1"></i> Login
              </button>
            </form>
          </div>

          <div class="tab-pane fade" id="register" role="tabpanel">
            <form method="POST">
              <div class="form-section">
                <h6><i class="bi bi-person me-2"></i> Personal Information</h6>
                <div class="row">
                  <div class="col-6 mb-2">
                    <input type="text" name="first_name" class="form-control" placeholder="First name" required>
                  </div>
                  <div class="col-6 mb-2">
                    <input type="text" name="last_name" class="form-control" placeholder="Last name" required>
                  </div>
                </div>
                <h6><i class="bi bi-envelope me-2"></i> Account Info</h6>
                <div class="mb-2">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" placeholder="Enter email address" required>
                </div>
                <div class="mb-2">
                  <label class="form-label">Password</label>
                  <div class="input-group">
                    <input type="password" name="password" id="registerPassword" class="form-control" placeholder="Password" required>
                    <span class="input-group-text" onclick="togglePassword('registerPassword', this)">
                      <i class="bi bi-eye"></i>
                    </span>
                  </div>
                </div>
                <div class="mb-2">
                  <label class="form-label">Confirm Password</label>
                  <div class="input-group">
                    <input type="password" name="confirm_password" id="confirmPassword" class="form-control" placeholder="Confirm Password" required>
                    <span class="input-group-text" onclick="togglePassword('confirmPassword', this)">
                      <i class="bi bi-eye"></i>
                    </span>
                  </div>
                </div>
              </div>
              <button type="submit" name="register" class="btn btn-primary w-100 fw-bold">
                <i class="bi bi-check-circle me-1"></i> Register
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    function togglePassword(id, el) {
      const input = document.getElementById(id);
      const icon = el.querySelector("i");
      if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
      } else {
        input.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
      }
    }
  </script>

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($message) && $message != "") {
      if($error){
        echo "
            <script>
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '". $message ."',
                timer: 2000,
                showConfirmButton: false
              });
            </script>
        ";
      } else {
        echo "
            <script>
              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '". $message ."',
                timer: 2000,
                showConfirmButton: false
              });
            </script>
        ";
      }
    }
  } ?>
</body>
</html>
