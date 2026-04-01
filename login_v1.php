<?php
// Enhanced security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Start session with secure settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);
session_start();

// Include session management functions
include_once('includes/session.php');

// Check if user is already logged in
if (isset($_SESSION['username']) && !empty($_SESSION['username']) && 
    isset($_SESSION['role']) && in_array($_SESSION['role'], ['user', 'admin', 'superadmin'])) {
    // User is already logged in, redirect to dashboard
    header('Location: /index_dashboard');
    exit();
}

include_once('includes/connect.php');

// Check for logout message in session
$logout_message = null;
if (isset($_SESSION['logout_message'])) {
    $logout_message = $_SESSION['logout_message'];
    unset($_SESSION['logout_message']); // Clear the message after reading it
}

// Also check URL parameter as backup
if (!$logout_message && isset($_GET['logout'])) {
    $logout_message = $_GET['logout'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, height=device-height, viewport-fit=cover">

	<!-- Favicons -->
	<link href="assets/img/favicon.png" rel="icon">

	<title>CSC RO VIII - Integrated Management Information System</title>
	<meta content="" name="description">
	<meta content="" name="keywords">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<!-- Google Fonts -->
  	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="css/preloader.css" rel="stylesheet">
	<!-- Bootstrap Icons -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<style>
	body {
		font-family: 'Poppins', sans-serif;
		overflow: auto;
	}
	.container {
      max-height: 100vh; /* Viewport height */
      overflow-y: auto;  /* Allow scrolling */
    }
	.custom-border {
		border: 2px solid #e0e0e0;
		border-radius: 1rem;
		transition: border-color 0.3s ease;
		border-color: #0077b6;
	}
	.custom-border:hover {
		border-color: #ffffff;
	}
    #toggleContainer {
        opacity: 0;
        max-height: 0;
        overflow: hidden;
        transition: opacity 0.4s ease, max-height 0.4s ease;
    }
    #toggleContainer.show {
        opacity: 1;
        max-height: 60px; /* Adjust depending on content */
    }
    .form-control-dark:focus ~ label,
    .form-control-dark:not(:placeholder-shown) ~ label {
        color: #000000 !important;
    }
    .form-floating > .form-control-dark:focus ~ label {
        opacity: 1;
        transform: scale(.85) translateY(-0.8rem) translateX(0.15rem);
    }
    .form-floating > .form-control-dark ~ label {
        color: #000000;
    }
    .form-floating > .form-control-dark::placeholder {
        color: transparent;
    }
    .captcha-dark::placeholder {
        color: #eee !important; /* Light placeholder text */
        opacity: 1 !important;
    }
    .transition-all {
        transition: color 0.3s ease;
    }
    /* Custom styles for idle logout popup */
    .swal2-popup-idle {
        border-left: 4px solid #17a2b8 !important;
    }
    
    .swal2-popup-idle .swal2-icon.swal2-info {
        border-color: #17a2b8 !important;
        color: #17a2b8 !important;
    }
</style>
</head>
<body class="my-login-page overflow-hidden">
<div id="preloader"></div>
  <img class="wave d-none d-md-block" src="login_img/wave.png">

  <div class="container-fluid">
    <div class="row min-vh-100">
      
<!-- Left Image Section (Hidden on mobile) -->
<div class="col-md-6 d-none d-md-flex align-items-center justify-content-center">
  <img src="login_img/bg.png" class="img-fluid" alt="Background" style="max-height: 450px; object-fit: contain;">
</div>


      <!-- Login Form Section -->
      <div class="col-12 col-md-6 d-flex align-items-center justify-content-center px-3">
        <div class="card bg-light p-4 shadow-lg w-100" style="max-width: 450px;">
          <form class="w-100">
            <input type="hidden" id="loginMode" name="login_mode" value="admin">
            <div class="text-center">
              <img src="login_img/csclogo.png" class="img-fluid mb-2" style="height: 80px;">
              <img src="login_img/CSC-IMIS.png" id="toggleTriggerImg" class="img-fluid mb-3" style="height: 80px; cursor: pointer;">
                <h5 id="welcomeText" class="text-primary fw-bold text-uppercase transition-all">
                  Welcome to CSC RO VIII - Integrated Management Information System
                </h5>
              <span id="loginModeBadge" class="badge bg-primary mb-3 text-white" style="font-size: 12px;">
                LOGIN AS CSC RO VIII EMPLOYEE
              </span>
            </div>

            <!-- Username -->
            <div class="input-group mb-3">
              <span class="input-group-text bg-white border-end-0">
                <i class="bi bi-person text-secondary"></i>
              </span>
              <div class="form-floating flex-grow-1">
                <input type="text" class="form-control border-start-0" id="username" placeholder="Username" required autofocus>
                <label for="username">Username</label>
              </div>
            </div>

            <!-- Password -->
            <div class="input-group mb-2">
              <span class="input-group-text bg-white border-end-0">
                <i class="bi bi-lock text-secondary"></i>
              </span>
              <div class="form-floating flex-grow-1 position-relative">
                <input type="password" class="form-control border-start-0 pe-5" id="password" placeholder="Password" required autocomplete="current-password">
                <label for="password">Password</label>
                <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 text-muted" id="togglePassword" style="cursor: pointer;"></i>
              </div>
            </div>

            <!-- CAPTCHA -->
            <div class="mb-2">
              <div class="text-center">
                <span class="badge bg-primary mb-3 text-white" style="font-size: 12px;">ENTER CAPTCHA TO PROCEED</span>
              </div>
              <div class="d-flex">
                <!-- Input field (CAPTCHA entry) -->
                <div class="flex-grow-1 me-2">
                  <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                      <i class="bi bi-shield-lock text-secondary"></i>
                    </span>
                    <input type="number" class="form-control border-start-0" id="captcha" placeholder="CAPTCHA" required maxlength="4">
                  </div>
                </div>
                <!-- Generated CAPTCHA display -->
                <div class="d-flex align-items-center justify-content-between bg-primary border rounded px-2 fw-bold" style="width: 40%;" id="generatedCaptcha">
                  <span id="captchaValue" class="flex-grow-1 bg-primary text-white text-center">1234</span>
                  <i class="bi bi-arrow-clockwise ms-2 text-light" style="cursor: pointer;" id="refreshCaptcha" title="Refresh CAPTCHA"></i>
                </div>
              </div>
            
              <!-- Forgot Password Link -->
              <div class="text-end mt-1">
                <a href="forgot_pw" class="text-decoration-none text-muted small">Forgot Password?</a>
              </div>
            </div>


            <hr>
            <button type="submit" class="btn btn-primary w-100 fw-semibold text-uppercase mt-0" id="loginBtn">
              <span id="btnText"><i class="bi bi-arrow-right-circle"></i> Login</span>
              <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
            </button>

            <div class="d-flex justify-content-end mb-2" id="toggleContainer">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="toggleModeBtn">
                <label class="form-check-label" for="toggleModeBtn" style="font-size: 0.9rem;">
                  Switch to Superadmin
                </label>
              </div>
            </div>

          </form>
        </div>
      </div>

    </div>
  </div>
    <script type="text/javascript" src="js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="js/preloader.js"></script>
    <script src="LumaFramework/LumaFramework.js"></script>
    <script type="text/javascript" src="auth.js"></script>
	<script>
  document.addEventListener("DOMContentLoaded", function () {
    // -------------------------
    // Toggle Password Visibility
    // -------------------------
    const togglePasswordBtn = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');

    if (togglePasswordBtn && passwordField) {
      togglePasswordBtn.addEventListener('click', function () {
        const isPassword = passwordField.type === 'password';
        passwordField.type = isPassword ? 'text' : 'password';
        this.classList.toggle('bi-eye-slash');
        this.classList.toggle('bi-eye');
      });
    }

    // -----------------------------
    // Toggle Login Mode (jQuery-based)
    // -----------------------------
    $('#toggleModeBtn').on('change', function () {
      const isSuperadmin = $(this).is(':checked');

      const $badge = $('#loginModeBadge');
      const $captchaBadge = $('.text-center > .badge.bg-primary, .text-center > .badge.bg-secondary');
      const $modeInput = $('#loginMode');
      const $toggleLabel = $('label[for="toggleModeBtn"]');
      const $card = $('.card');
      const $inputs = $('.form-control');
      const $inputGroups = $('.input-group-text');
      const $labels = $('.form-floating label');
      const $heading = $('#welcomeText');
      const $captchaInput = $('#captcha');
      const $toggleImg = $('#toggleTriggerImg');
      const $forgotLink = $('.text-end a');
      const $generatedCaptcha = $('#generatedCaptcha');
      const $refreshCaptcha = $('#refreshCaptcha');

      // Update classes and values
      $forgotLink.toggleClass('text-light text-muted', isSuperadmin);
      $badge.text(isSuperadmin ? 'LOGIN AS CSC RO VIII SUPERADMIN' : 'LOGIN AS CSC RO VIII EMPLOYEE')
            .toggleClass('bg-secondary bg-primary', isSuperadmin);
      $captchaBadge.toggleClass('bg-secondary bg-primary', isSuperadmin);
      $modeInput.val(isSuperadmin ? 'superadmin' : 'admin');
      $toggleLabel.text(isSuperadmin
        ? 'Switch Back to Login as CSC RO VIII Employee'
        : 'Login as Super Administrator');
      $card.toggleClass('bg-dark text-white', isSuperadmin);
      $inputGroups.toggleClass('bg-white bg-dark text-white border-white', isSuperadmin);
      $inputs.toggleClass('form-control-dark bg-dark text-white border-white', isSuperadmin);
      $labels.toggleClass('text-white', isSuperadmin);
      $captchaInput.toggleClass('captcha-dark', isSuperadmin);
      $heading.toggleClass('text-white text-primary', isSuperadmin);
      $generatedCaptcha.toggleClass('bg-dark text-white bg-light text-dark', isSuperadmin);
      $refreshCaptcha.toggleClass('text-dark text-white text-light', isSuperadmin);

      // Swap logo
      $toggleImg.attr('src', isSuperadmin
        ? 'login_img/CSC-IMIS-dark.png'
        : 'login_img/CSC-IMIS.png');
    });

    // ------------------------
    // Toggle Info Container
    // ------------------------
    const toggleImg = document.getElementById("toggleTriggerImg");
    const toggleContainer = document.getElementById("toggleContainer");

    if (toggleImg && toggleContainer) {
      toggleImg.addEventListener("click", function () {
        toggleContainer.classList.toggle("show");
      });
    }

    // -----------------------------
    // Show Logout Alerts via SweetAlert2
    // -----------------------------
    const urlParams = new URLSearchParams(window.location.search);
    const logoutReason = urlParams.get('logout');
    const usernameInput = document.getElementById('username');

    const clearLogoutParam = () => {
      const url = new URL(window.location);
      url.searchParams.delete('logout');
      window.history.replaceState({}, document.title, url.toString());
    };

    const focusUsername = () => {
      usernameInput?.focus();
    };

    if (logoutReason === 'idle') {
      Swal.fire({
        title: 'Session Expired',
        html: 'You have been automatically logged out due to inactivity.<br><br>Please log in again to continue.',
        icon: 'info',
        confirmButtonText: '<i class="bi bi-box-arrow-in-right"></i> OK',
        allowOutsideClick: false,
        customClass: {
          popup: 'swal2-popup-idle'
        }
      }).then(() => {
        clearLogoutParam();
        focusUsername();
      });

    } else if (logoutReason === 'manual') {
      Swal.fire({
        title: 'Logged Out Successfully',
        text: 'You have been successfully logged out.',
        icon: 'success',
        confirmButtonText: '<i class="bi bi-box-arrow-in-right"></i> OK',
        timer: 2000,
        timerProgressBar: true
      }).then(() => {
        clearLogoutParam();
        focusUsername();
      });

    } else {
      focusUsername();
    }
  });
</script>
</body>
</html>

