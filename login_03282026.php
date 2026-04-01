<?php
// ============================================================
// Enhanced Security Headers
// ============================================================
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdn.jsdelivr.net https://code.jquery.com https://cdn.jsdelivr.net/npm/sweetalert2@11; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net; img-src 'self' data:;");

// ============================================================
// Secure Session Configuration
// ============================================================
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');
session_start();

// Include session management functions
include_once('includes/session.php');

// ============================================================
// Redirect if already logged in
// ============================================================
if (
  isset($_SESSION['username']) && !empty($_SESSION['username']) &&
  isset($_SESSION['role']) && in_array($_SESSION['role'], ['user', 'admin', 'superadmin'])
) {
  header('Location: /index_dashboard');
  exit();
}

include_once('includes/connect.php');

// ============================================================
// Server-side CAPTCHA Generation
// ============================================================
$_SESSION['captcha'] = rand(1000, 9999);
$captcha_hash = hash('sha256', $_SESSION['captcha'] . session_id());

// ============================================================
// Logout Message Handling
// ============================================================
$logout_message = null;
if (isset($_SESSION['logout_message'])) {
  $logout_message = htmlspecialchars($_SESSION['logout_message'], ENT_QUOTES, 'UTF-8');
  unset($_SESSION['logout_message']);
}
if (!$logout_message && isset($_GET['logout'])) {
  $allowed_logout_msgs = ['idle', 'manual'];
  $raw = $_GET['logout'];
  if (in_array($raw, $allowed_logout_msgs)) {
    $logout_message = $raw;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, height=device-height, viewport-fit=cover">
  <meta name="description" content="CSC RO VIII Integrated Management Information System – Secure Employee Login">
  <meta name="robots" content="noindex, nofollow">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">

  <!-- Preconnect for fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <!-- Preload critical images -->
  <link rel="preload" as="image" href="login_img/csclogo.png">
  <link rel="preload" as="image" href="login_img/CSC-IMIS.png">

  <!-- Google Fonts – Poppins only (removed unused Open Sans & Roboto) -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
    rel="stylesheet" media="print" onload="this.media='all'">
  <noscript>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  </noscript>

  <!-- Bootstrap CSS with SRI -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous">

  <!-- Bootstrap Icons with SRI -->
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd"
    crossorigin="anonymous">

  <!-- Preloader CSS -->
  <link href="assets/css/preloader.css" rel="stylesheet">
  <link href="assets/css/new_imis.css" rel="stylesheet">

  <title>CSC RO VIII – IMIS Login</title>
</head>

<body>

  <!-- Skip Navigation (Accessibility) -->
  <a href="#loginForm" class="skip-link">Skip to login form</a>

  <!-- Background Effects -->
  <div class="bg-orbs" aria-hidden="true">
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>
  </div>
  <div class="bg-grid" aria-hidden="true"></div>

  <!-- Preloader -->
  <div id="preloader" role="status" aria-label="Loading…"></div>

  <!-- ============================================================
       PAGE LAYOUT
  ============================================================ -->
  <div class="login-wrapper">

    <!-- Hero Panel (Desktop only) -->
    <div class="hero-panel" aria-hidden="true">
      <img src="login_img/bg.png" class="hero-illustration" alt="">
      <p class="hero-tagline">
        <strong>CSC RO VIII – Integrated Management Information System</strong>
        A centralized enterprise platform that streamlines and integrates core divisional operations, providing authorized personnel with unified access to human resource management, examination services, client assistance, financial operations, and technical support.
      </p>
      <div class="hero-dots">
        <div class="hero-dot active"></div>
        <div class="hero-dot"></div>
        <div class="hero-dot"></div>
      </div>
    </div>

    <!-- Login Card -->
    <main id="loginMain" aria-label="Login">
      <div class="login-card" id="loginCard">

        <!-- Hidden login mode -->
        <input type="hidden" id="loginMode" name="login_mode" value="admin">

        <!-- ── Logos ── -->
        <div class="logo-row">
          <img src="login_img/csclogo.png"
            class="logo-img"
            alt="Civil Service Commission logo"
            width="72" height="72">
          <div class="logo-divider" aria-hidden="true"></div>
          <img src="login_img/CSC-IMIS.png"
            id="toggleTriggerImg"
            class="logo-img logo-wordmark"
            alt="CSC IMIS wordmark – click to toggle superadmin login"
            role="button"
            tabindex="0"
            aria-expanded="false"
            aria-controls="toggleContainer"
            title="Click to access superadmin login"
            width="80" height="72">
        </div>

        <!-- ── Heading ── -->
        <h1 class="card-heading" id="cardHeading">
          Welcome to CSC RO VIII<br>Integrated Management Information System
        </h1>

        <!-- ── Role Badge ── -->
        <div class="text-center">
          <span class="role-badge" id="roleBadge" aria-live="polite">
            <i class="bi bi-person-badge" aria-hidden="true"></i>
            <span id="roleBadgeText">Login as CSC RO VIII Employee</span>
          </span>
        </div>

        <!-- ── Lockout Notice ── -->
        <div class="lockout-notice" id="lockoutNotice" role="alert" aria-live="assertive">
          <i class="bi bi-shield-exclamation" aria-hidden="true"></i>
          <span>
            Too many failed attempts. Please wait
            <strong id="lockoutTimer">30</strong> seconds before trying again.
          </span>
        </div>

        <!-- ============================================================
             LOGIN FORM
        ============================================================ -->
        <form
          id="loginForm"
          method="POST"
          action="/auth/login"
          novalidate
          autocomplete="on"
          aria-labelledby="cardHeading">

          <!-- ── Username ── -->
          <div class="field-wrap" id="usernameWrap">
            <label class="field-label" for="username">
              Username
            </label>
            <div class="input-shell" id="usernameShell">
              <span class="input-icon" aria-hidden="true">
                <i class="bi bi-person"></i>
              </span>
              <input
                type="text"
                class="input-field"
                id="username"
                name="username"
                placeholder="Enter your username"
                autocomplete="username"
                spellcheck="false"
                autocorrect="off"
                autocapitalize="none"
                required
                autofocus
                aria-required="true"
                aria-describedby="usernameErr"
                aria-invalid="false"
                maxlength="100">
            </div>
            <div class="field-error-msg" id="usernameErr" role="alert" aria-live="polite">
              <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
              <span class="err-text"></span>
            </div>
          </div>

          <!-- ── Password ── -->
          <div class="field-wrap" id="passwordWrap">
            <label class="field-label" for="password">Password</label>
            <div class="input-shell" id="passwordShell">
              <span class="input-icon" aria-hidden="true">
                <i class="bi bi-lock"></i>
              </span>
              <input
                type="password"
                class="input-field"
                id="password"
                name="password"
                placeholder="Enter your password"
                autocomplete="current-password"
                required
                aria-required="true"
                aria-describedby="passwordErr pwVisibleWarn"
                aria-invalid="false"
                maxlength="255">
              <!-- Toggle visibility button -->
              <button
                type="button"
                class="input-action"
                id="togglePassword"
                aria-label="Show password"
                aria-pressed="false"
                title="Toggle password visibility">
                <i class="bi bi-eye-slash" aria-hidden="true"></i>
              </button>
            </div>
            <!-- Visibility warning -->
            <div class="pw-visible-warn" id="pwVisibleWarn" role="status" aria-live="polite">
              <i class="bi bi-eye-fill" aria-hidden="true"></i>
              Password is currently visible
            </div>
            <div class="field-error-msg" id="passwordErr" role="alert" aria-live="polite">
              <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
              <span class="err-text"></span>
            </div>
          </div>

          <!-- ── CAPTCHA ── -->
          <div class="captcha-block" id="captchaBlock">
            <p class="captcha-label">
              <i class="bi bi-shield-lock" aria-hidden="true"></i>
              Verify you're human
            </p>
            <div class="captcha-row">
              <div class="input-shell" id="captchaShell">
                <span class="input-icon" aria-hidden="true">
                  <i class="bi bi-hash"></i>
                </span>
                <input
                  type="number"
                  class="input-field"
                  id="captcha"
                  name="captcha"
                  placeholder="Enter 4-digit code"
                  required
                  aria-required="true"
                  aria-label="CAPTCHA verification code"
                  aria-describedby="captchaErr"
                  aria-invalid="false"
                  min="1000"
                  max="9999"
                  inputmode="numeric">
              </div>
              <!-- CAPTCHA Display -->
              <div class="captcha-display" aria-label="CAPTCHA code display" id="captchaDisplay">
                <span class="captcha-value" id="captchaValue" aria-live="polite" aria-label="CAPTCHA code">
                  <?= htmlspecialchars((string)$_SESSION['captcha'], ENT_QUOTES, 'UTF-8') ?>
                </span>
                <button
                  type="button"
                  class="captcha-refresh-btn"
                  id="refreshCaptcha"
                  aria-label="Refresh CAPTCHA code"
                  title="Get a new code">
                  <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                </button>
              </div>
            </div>
            <div class="field-error-msg" id="captchaErr" role="alert" aria-live="polite">
              <i class="bi bi-exclamation-circle" aria-hidden="true"></i>
              <span class="err-text"></span>
            </div>
          </div>

          <!-- ── Submit Button ── -->
          <button type="submit" class="login-btn" id="loginBtn" aria-describedby="loginStatus">
            <span class="btn-inner">
              <i class="bi bi-arrow-right-circle btn-text" aria-hidden="true"></i>
              <span class="btn-text">Login</span>
              <span class="btn-spinner" role="status" aria-hidden="true"></span>
            </span>
          </button>
          <div id="loginStatus" class="sr-only" aria-live="polite" aria-atomic="true"></div>

        </form><!-- /form -->

        <!-- ── Superadmin Toggle Panel ── -->
        <div id="toggleContainer" role="region" aria-label="Login mode switch">
          <div class="role-switch-panel">
            <span class="role-switch-label" id="toggleLabel">
              Switch to Superadmin Login
            </span>
            <label class="toggle-switch" aria-label="Toggle superadmin login mode">
              <input type="checkbox" id="toggleModeBtn" aria-describedby="toggleLabel">
              <span class="toggle-track"></span>
            </label>
          </div>
        </div>

        <!-- ── Card Footer ── -->
        <p class="card-footer-note">
          CSC RO VIII &copy; <?= date('Y') ?> &nbsp;|&nbsp; IMIS v2.0 &nbsp;|&nbsp;
          <a href="mailto:ro8@csc.gov.ph">ro8@csc.gov.ph</a>
        </p>

      </div><!-- /login-card -->
    </main>

  </div><!-- /login-wrapper -->

  <!-- ============================================================
       SCRIPTS – Bootstrap JS deferred, jQuery, SweetAlert2
  ============================================================ -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"
    defer></script>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="assets/js/preloader.js"></script>
  <script src="LumaFramework/LumaFramework.js"></script>
  <script src="auth.js"></script>

  <script>
    /* ============================================================
       MAIN SCRIPT — All login page interactions
    ============================================================ */
    (function() {
      'use strict';

      // ── Element References ──────────────────────────────────
      const card = document.getElementById('loginCard');
      const loginForm = document.getElementById('loginForm');
      const loginBtn = document.getElementById('loginBtn');
      const loginStatus = document.getElementById('loginStatus');
      const loginModeInput = document.getElementById('loginMode');

      const usernameInput = document.getElementById('username');
      const passwordInput = document.getElementById('password');
      const captchaInput = document.getElementById('captcha');

      const usernameShell = document.getElementById('usernameShell');
      const passwordShell = document.getElementById('passwordShell');
      const captchaShell = document.getElementById('captchaShell');

      const togglePassBtn = document.getElementById('togglePassword');
      const pwVisibleWarn = document.getElementById('pwVisibleWarn');

      const captchaValue = document.getElementById('captchaValue');
      const refreshCaptcha = document.getElementById('refreshCaptcha');
      const captchaDisplay = document.getElementById('captchaDisplay');

      const toggleImg = document.getElementById('toggleTriggerImg');
      const toggleContainer = document.getElementById('toggleContainer');
      const toggleModeBtn = document.getElementById('toggleModeBtn');
      const roleBadgeText = document.getElementById('roleBadgeText');
      const roleBadge = document.getElementById('roleBadge');
      const toggleLabel = document.getElementById('toggleLabel');

      const lockoutNotice = document.getElementById('lockoutNotice');
      const lockoutTimer = document.getElementById('lockoutTimer');


      // ── 1. PASSWORD VISIBILITY TOGGLE ───────────────────────
      let lockoutInterval = null;

      togglePassBtn.addEventListener('click', function() {
        const isHidden = passwordInput.type === 'password';
        passwordInput.type = isHidden ? 'text' : 'password';

        const icon = this.querySelector('i');
        icon.classList.toggle('bi-eye-slash', !isHidden);
        icon.classList.toggle('bi-eye', isHidden);

        this.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        this.setAttribute('aria-pressed', isHidden ? 'true' : 'false');

        pwVisibleWarn.classList.toggle('show', isHidden);
      });


      // ── 2. CAPTCHA – Client-side display only ───────────────
      // NOTE: Real validation is done server-side via PHP session.
      // This JS only requests a fresh CAPTCHA value from the server
      // and updates the display — it does NOT expose the real value.

      refreshCaptcha.addEventListener('click', function() {
        this.style.transform = 'rotate(360deg)';
        setTimeout(() => {
          this.style.transform = '';
        }, 300);

        // Request new CAPTCHA from server
        fetch('/auth/captcha/refresh', {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
          })
          .then(r => r.json())
          .then(data => {
            if (data.display) {
              captchaValue.textContent = data.display;
              captchaValue.setAttribute('aria-label', 'New CAPTCHA code');
            }
          })
          .catch(() => {
            // Fallback: reload page to regenerate
            window.location.reload();
          });
      });

      // ── 3. INLINE FIELD VALIDATION ──────────────────────────
      function showFieldError(shell, errorId, message) {
        shell.classList.add('is-error');
        const errEl = document.getElementById(errorId);
        const input = shell.querySelector('.input-field');
        errEl.querySelector('.err-text').textContent = message;
        errEl.classList.add('show');
        if (input) {
          input.setAttribute('aria-invalid', 'true');
        }
      }

      function clearFieldError(shell, errorId) {
        shell.classList.remove('is-error');
        const errEl = document.getElementById(errorId);
        const input = shell.querySelector('.input-field');
        errEl.querySelector('.err-text').textContent = '';
        errEl.classList.remove('show');
        if (input) {
          input.setAttribute('aria-invalid', 'false');
        }
      }

      // Progressive validation on blur
      usernameInput.addEventListener('blur', function() {
        if (!this.value.trim()) {
          showFieldError(usernameShell, 'usernameErr', 'Username is required.');
        } else {
          clearFieldError(usernameShell, 'usernameErr');
        }
      });

      passwordInput.addEventListener('blur', function() {
        if (!this.value) {
          showFieldError(passwordShell, 'passwordErr', 'Password is required.');
        } else {
          clearFieldError(passwordShell, 'passwordErr');
        }
      });

      captchaInput.addEventListener('blur', function() {
        const val = this.value.trim();
        if (!val) {
          showFieldError(captchaShell, 'captchaErr', 'Please enter the CAPTCHA code.');
        } else if (val.length !== 4 || isNaN(val)) {
          showFieldError(captchaShell, 'captchaErr', 'CAPTCHA must be a 4-digit number.');
        } else {
          clearFieldError(captchaShell, 'captchaErr');
        }
      });

      // Clear errors on input
      [usernameInput, passwordInput, captchaInput].forEach(input => {
        input.addEventListener('input', function() {
          const shellId = this.id + 'Shell';
          const errId = this.id + 'Err';
          const shell = document.getElementById(shellId);
          if (shell) clearFieldError(shell, errId);
        });
      });

      // ── 5. ROLE TOGGLE (Employee ↔ Superadmin) ──────────────
      function applyDarkMode(isDark) {
        card.classList.toggle('is-dark', isDark);
        roleBadgeText.textContent = isDark ? 'Login as CSC RO VIII Superadmin' : 'Login as CSC RO VIII Employee';
        toggleLabel.textContent = isDark ? 'Switch Back to Employee Login' : 'Switch to Superadmin Login';
        loginModeInput.value = isDark ? 'superadmin' : 'admin';

        // Swap logo wordmark image for dark variant
        toggleImg.src = isDark ? 'login_img/CSC-IMIS-dark.png' : 'login_img/CSC-IMIS.png';
        toggleImg.setAttribute('aria-expanded', String(isDark));
      }

      toggleModeBtn.addEventListener('change', function() {
        applyDarkMode(this.checked);
      });

      // Clicking the IMIS logo toggles the superadmin panel visibility
      function toggleSuperadminPanel() {
        const isOpen = toggleContainer.classList.toggle('open');
        toggleImg.setAttribute('aria-expanded', String(isOpen));
        if (!isOpen && toggleModeBtn.checked) {
          // Reset to employee mode when panel closes
          toggleModeBtn.checked = false;
          applyDarkMode(false);
        }
      }

      toggleImg.addEventListener('click', toggleSuperadminPanel);
      toggleImg.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          toggleSuperadminPanel();
        }
      });


      // ── 6. LOCKOUT HELPER (called from auth.js as needed) ───
      window.showLockout = function(seconds) {
        let remaining = seconds;
        lockoutNotice.classList.add('show');
        lockoutTimer.textContent = remaining;
        loginBtn.disabled = true;

        clearInterval(lockoutInterval);
        lockoutInterval = setInterval(function() {
          remaining -= 1;
          lockoutTimer.textContent = remaining;
          if (remaining <= 0) {
            clearInterval(lockoutInterval);
            lockoutNotice.classList.remove('show');
            loginBtn.disabled = false;
          }
        }, 1000);
      };


      // ── 7. LOGOUT ALERTS via SweetAlert2 ────────────────────
      const urlParams = new URLSearchParams(window.location.search);
      const logoutReason = urlParams.get('logout');

      const clearLogoutParam = () => {
        const url = new URL(window.location);
        url.searchParams.delete('logout');
        window.history.replaceState({}, document.title, url.toString());
      };

      const focusUsername = () => usernameInput?.focus();

      if (logoutReason === 'idle') {
        Swal.fire({
          title: 'Session Expired',
          html: 'You have been automatically logged out due to inactivity.<br><br>Please log in again to continue.',
          icon: 'info',
          confirmButtonText: '<i class="bi bi-box-arrow-in-right"></i>&nbsp; OK',
          confirmButtonColor: '#1a56a0',
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
          confirmButtonText: '<i class="bi bi-box-arrow-in-right"></i>&nbsp; OK',
          confirmButtonColor: '#1a56a0',
          timer: 3000,
          timerProgressBar: true
        }).then(() => {
          clearLogoutParam();
          focusUsername();
        });

      } else {
        focusUsername();
      }

    })(); // END IIFE
  </script>

</body>

</html>