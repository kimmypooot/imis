/**
 * auth.js — CSC RO VIII IMIS Login Authentication
 * ─────────────────────────────────────────────────────────────────────────────
 * Handles all client-side login logic for the enhanced login page.
 *
 * Dependencies (loaded before this file in login.php):
 *   - jQuery 3.7.1
 *   - SweetAlert2 v11
 *   - LumaFramework (Luma.FetchData, Luma.Verify, Luma.SetSession, Luma.Alert)
 *
 * DOM IDs this script targets (all defined in login.php):
 *   #username         – username text input
 *   #password         – password input
 *   #captcha          – captcha number input
 *   #captchaValue     – captcha display <span>
 *   #refreshCaptcha   – captcha refresh <button>
 *   #loginBtn         – submit <button>
 *   #toggleModeBtn    – superadmin toggle <input type="checkbox">
 *   #loginMode        – hidden input carrying 'admin' | 'superadmin'
 *   #usernameShell    – wrapper div for username (receives .is-error)
 *   #passwordShell    – wrapper div for password (receives .is-error)
 *   #captchaShell     – wrapper div for captcha  (receives .is-error)
 *   #usernameErr      – username inline error container
 *   #passwordErr      – password inline error container
 *   #captchaErr       – captcha inline error container
 *   #pwVisibleWarn    – password-visible warning strip
 *   #lockoutNotice    – lockout countdown banner
 *   #lockoutTimer     – <strong> inside lockout banner
 *   #loginStatus      – sr-only live region for screen readers
 *   #toggleTriggerImg – IMIS logo (superadmin panel toggle trigger)
 *   #toggleContainer  – collapsible superadmin panel
 *   #roleBadgeText    – text inside the role badge
 *   #toggleLabel      – label next to the toggle switch
 * ─────────────────────────────────────────────────────────────────────────────
 */

$(document).ready(function () {

  // ============================================================
  // 1. STATE
  // ============================================================

  /** Tracks the current CAPTCHA value received from the server. */
  let captchaNow = '';

  /** Prevents double-submission while a login request is in flight. */
  let isLoggingIn = false;

  /** setInterval handle for the lockout countdown timer. */
  let lockoutInterval = null;

  /** Max failed attempts before triggering the lockout UI. */
  const MAX_FAILED_ATTEMPTS = 5;

  /** Lockout duration in seconds shown to the user. */
  const LOCKOUT_SECONDS = 30;

  /** Session-storage key used to persist failed attempt count across refreshes. */
  const ATTEMPT_KEY = 'imis_failed_attempts';

  /** Session-storage key for lockout expiry timestamp. */
  const LOCKOUT_UNTIL_KEY = 'imis_lockout_until';


  // ============================================================
  // 2. DOM REFERENCES  (cached once, used everywhere)
  // ============================================================

  const $loginBtn       = $('#loginBtn');
  const $loginStatus    = $('#loginStatus');
  const $usernameInput  = $('#username');
  const $passwordInput  = $('#password');
  const $captchaInput   = $('#captcha');
  const $captchaValue   = $('#captchaValue');
  const $refreshBtn     = $('#refreshCaptcha');
  const $togglePassBtn  = $('#togglePassword');
  const $pwVisibleWarn  = $('#pwVisibleWarn');
  const $lockoutNotice  = $('#lockoutNotice');
  const $lockoutTimer   = $('#lockoutTimer');
  const $toggleModeBtn  = $('#toggleModeBtn');


  // ============================================================
  // 3. SERVER TIME SYNC
  // ─────────────────────────────────────────────────────────────
  // Borrowed and preserved from the original auth.js.
  // Syncs client time against server time once per hour and
  // caches the offset in sessionStorage to avoid repeat requests.
  // ============================================================

  function syncServerTime() {
    return new Promise((resolve) => {
      const cachedOffset = sessionStorage.getItem('serverTimeOffset');
      const cacheTime    = sessionStorage.getItem('serverTimeCached');

      // Re-use cached offset if it's less than 1 hour old
      if (cachedOffset && cacheTime && (Date.now() - parseInt(cacheTime)) < 3_600_000) {
        resolve(parseInt(cachedOffset));
        return;
      }

      $.ajax({
        url: 'inc/get_server_time.php',
        type: 'GET',
        dataType: 'json',
        timeout: 3000,
        cache: false,
        success(response) {
          if (response?.server_time) {
            const offset = new Date(response.server_time * 1000).getTime() - Date.now();
            sessionStorage.setItem('serverTimeOffset', offset);
            sessionStorage.setItem('serverTimeCached', Date.now());
            resolve(offset);
          } else {
            resolve(0);
          }
        },
        error() { resolve(0); }
      });
    });
  }


  // ============================================================
  // 4. CAPTCHA  (server-driven, with local real-time feedback)
  // ─────────────────────────────────────────────────────────────
  // The CAPTCHA value is generated server-side (PHP session) and
  // rendered into #captchaValue on page load.
  //
  // "Refresh" calls /auth/captcha/refresh (POST) to get a fresh
  // server-generated code.  We fall back to a full page reload
  // if the endpoint is unavailable.
  //
  // Client-side feedback (border-success / border-danger) is
  // purely cosmetic — real validation always happens server-side.
  // ============================================================

  /** Read the initial CAPTCHA value that PHP rendered into the DOM. */
  function initCaptcha() {
    captchaNow = $captchaValue.text().trim();
  }

  /**
   * Request a fresh CAPTCHA from the server and update the display.
   * Rate-limited to 10 refreshes/minute server-side (refresh.php).
   */
  function refreshCaptcha() {
    // Spin the icon while waiting
    $refreshBtn.find('i').css('transition', 'transform 0.4s').css('transform', 'rotate(360deg)');
    setTimeout(() => $refreshBtn.find('i').css('transform', ''), 400);

    // Reset the input field and clear any feedback
    $captchaInput.val('');
    clearFieldError($('#captchaShell'), 'captchaErr');

    fetch('/auth/captcha/refresh', {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type':     'application/json'
      },
      credentials: 'same-origin'
    })
    .then(r => {
      if (!r.ok) throw new Error(`HTTP ${r.status}`);
      return r.json();
    })
    .then(data => {
      if (data?.display) {
        captchaNow = data.display;
        $captchaValue.text(captchaNow);
        $captchaValue.attr('aria-label', 'New CAPTCHA code — ' + captchaNow);
      }
    })
    .catch(() => {
      // Network or rate-limit failure — reload to get a new server-side CAPTCHA
      window.location.reload();
    });
  }

  /** Visual feedback as the user types into the CAPTCHA field. */
  function handleCaptchaInput() {
    const val = $captchaInput.val().trim();
    $captchaInput.removeClass('border-success border-danger');

    if (val.length === 4) {
      $captchaInput.addClass(val === captchaNow ? 'border-success' : 'border-danger');
    }

    // Clear error once the field has content
    if (val.length > 0) clearFieldError($('#captchaShell'), 'captchaErr');
  }


  // ============================================================
  // 5. INLINE FIELD VALIDATION HELPERS
  // ============================================================

  /**
   * Mark a field shell as invalid and show its error message.
   * @param {jQuery} $shell   – the .input-shell wrapper element
   * @param {string} errorId  – id of the .field-error-msg container
   * @param {string} message  – human-readable error text
   */
  function showFieldError($shell, errorId, message) {
    $shell.addClass('is-error');
    const $err = $('#' + errorId);
    $err.find('.err-text').text(message);
    $err.addClass('show');
    $shell.find('.input-field').attr('aria-invalid', 'true');
  }

  /**
   * Clear a field's error state.
   * @param {jQuery} $shell  – the .input-shell wrapper element
   * @param {string} errorId – id of the .field-error-msg container
   */
  function clearFieldError($shell, errorId) {
    $shell.removeClass('is-error');
    const $err = $('#' + errorId);
    $err.find('.err-text').text('');
    $err.removeClass('show');
    $shell.find('.input-field').attr('aria-invalid', 'false');
  }

  /** Run all field validations and return true only if everything passes. */
  function validateForm(username, password, captcha) {
    let valid = true;

    if (!username) {
      showFieldError($('#usernameShell'), 'usernameErr', 'Username is required.');
      valid = false;
    }

    if (!password) {
      showFieldError($('#passwordShell'), 'passwordErr', 'Password is required.');
      valid = false;
    }

    if (!captcha) {
      showFieldError($('#captchaShell'), 'captchaErr', 'Please enter the CAPTCHA code.');
      valid = false;
    } else if (captcha.length !== 4 || isNaN(captcha)) {
      showFieldError($('#captchaShell'), 'captchaErr', 'CAPTCHA must be exactly 4 digits.');
      valid = false;
    } else if (captcha !== captchaNow) {
      showFieldError($('#captchaShell'), 'captchaErr', 'Incorrect CAPTCHA. Please try again.');
      valid = false;
    }

    if (!valid) {
      // Focus the first invalid field to help keyboard/screen-reader users
      $('#loginForm [aria-invalid="true"]').first().focus();
    }

    return valid;
  }


  // ============================================================
  // 6. LOGIN BUTTON STATE HELPERS
  // ============================================================

  /** Switch the login button to its loading / in-progress state. */
  function setButtonLoading() {
    $loginBtn.addClass('loading').prop('disabled', true);
    $loginStatus.text('Logging in, please wait…');
  }

  /** Restore the login button to its default, interactive state. */
  function resetButton() {
    isLoggingIn = false;
    $loginBtn.removeClass('loading').prop('disabled', false);
    $loginStatus.text('');
  }


  // ============================================================
  // 7. FAILED ATTEMPT TRACKING + LOCKOUT
  // ─────────────────────────────────────────────────────────────
  // Tracks consecutive failures in sessionStorage.
  // After MAX_FAILED_ATTEMPTS the UI shows a LOCKOUT_SECONDS
  // countdown during which the login button stays disabled.
  // ============================================================

  function getFailedAttempts() {
    return parseInt(sessionStorage.getItem(ATTEMPT_KEY) || '0', 10);
  }

  function incrementFailedAttempts() {
    const next = getFailedAttempts() + 1;
    sessionStorage.setItem(ATTEMPT_KEY, next);
    return next;
  }

  function clearFailedAttempts() {
    sessionStorage.removeItem(ATTEMPT_KEY);
    sessionStorage.removeItem(LOCKOUT_UNTIL_KEY);
  }

  /** Returns true if the user is currently locked out. */
  function isLockedOut() {
    const until = parseInt(sessionStorage.getItem(LOCKOUT_UNTIL_KEY) || '0', 10);
    return Date.now() < until;
  }

  /**
   * Start the visual lockout countdown.
   * Also exposed on window so login.php inline script can call it if needed.
   * @param {number} seconds – how long to lock the form
   */
  function startLockout(seconds) {
    const until = Date.now() + (seconds * 1000);
    sessionStorage.setItem(LOCKOUT_UNTIL_KEY, until);

    let remaining = seconds;
    $lockoutTimer.text(remaining);
    $lockoutNotice.addClass('show');
    $loginBtn.prop('disabled', true);

    clearInterval(lockoutInterval);
    lockoutInterval = setInterval(() => {
      remaining -= 1;
      $lockoutTimer.text(remaining);
      if (remaining <= 0) {
        clearInterval(lockoutInterval);
        $lockoutNotice.removeClass('show');
        $loginBtn.prop('disabled', false);
      }
    }, 1000);
  }

  // Expose so login.php inline script can call window.showLockout(n)
  window.showLockout = startLockout;

  /** Resume a lockout that was already running (e.g. after CAPTCHA refresh). */
  function resumeLockoutIfActive() {
    if (!isLockedOut()) return;
    const remaining = Math.ceil((parseInt(sessionStorage.getItem(LOCKOUT_UNTIL_KEY)) - Date.now()) / 1000);
    if (remaining > 0) startLockout(remaining);
  }


  // ============================================================
  // 8. CORE LOGIN FLOW
  // ============================================================

  /**
   * Main login handler — called after all client-side validation passes.
   * Mirrors the original auth.js performLogin() flow exactly, adapted
   * to work with the new DOM's button/spinner structure.
   *
   * @param {string} username
   * @param {string} password
   * @param {string} loginMode  – 'admin' | 'superadmin'
   */
  function performLogin(username, password, loginMode) {
    const query = {
      query: `SELECT u.*,
                (SELECT COUNT(*) FROM itg_tbl WHERE id = u.id) as is_itg_member
              FROM users_cscro8 u
              WHERE u.username = :username LIMIT 1`,
      params: { username }
    };

    Luma.FetchData(query)
      .then(data => {
        // ── User not found ──────────────────────────────────
        if (!Array.isArray(data) || data.length === 0) {
          throw new Error('User not found');
        }

        const user = data[0];

        // ── Data integrity check ────────────────────────────
        if (!user.id || !user.username || !user.fname || !user.lname) {
          throw new Error('Incomplete user data');
        }

        // ── Account status check (BEFORE password hash work) ─
        if (user.status && user.status.toLowerCase() === 'inactive') {
          throw new Error('ACCOUNT_INACTIVE');
        }

        // ── Password verification ───────────────────────────
        return Luma.Verify(password, user.password).then(verified => {
          if (!verified) throw new Error('Incorrect password');

          // ── Build session payload ───────────────────────
          const name         = `${user.fname} ${user.minitial || ''} ${user.lname}`.trim();
          const isITGMember  = parseInt(user.is_itg_member) > 0;

          const sessionData = {
            name,
            id:           user.id,
            username:     user.username,
            fo_rsu:       user.fo_rsu       || '',
            type:         user.type         || '',
            itg:          user.itg          || '',
            role:         user.role         || '',
            email:        user.email        || '',
            position:     user.position     || '',
            profile:      user.profile      || '',
            login_user:   user.id,
            user_group:   user.fo_rsu       || '',
            lname:        user.lname,
            fname:        user.fname,
            minitial:     user.minitial     || '',
            fullname:     name,
            is_itg_member: isITGMember
          };

          // ── Create session ──────────────────────────────
          return Luma.SetSession(sessionData, true).then(success => {
            if (!success) throw new Error('Session creation failed');

            // Login succeeded — clear any lockout state
            clearFailedAttempts();

            // Log asynchronously — don't block the UX on it
            logUserLogin(user.id, user.username).catch(err => {
              console.warn('[auth.js] Login logging failed (non-critical):', err);
            });

            proceedWithLogin(loginMode, user.role, name);
          });
        });
      })
      .catch(error => {
        console.error('[auth.js] Login error:', error.message);
        handleLoginError(error);
      });
  }


  // ============================================================
  // 9. ERROR HANDLER
  // ============================================================

  /**
   * Centralised error handler for all login failures.
   * Manages attempt counting, lockout triggering, and user messaging.
   * @param {Error} error
   */
  function handleLoginError(error) {
    const msg = error.message;

    // ── Account deactivated (no attempt count, no CAPTCHA refresh) ──
    if (msg === 'ACCOUNT_INACTIVE') {
      Swal.fire({
        icon:              'error',
        title:             'Account Deactivated',
        html:              'Your account has been <strong>deactivated</strong>.<br>Please contact the system administrator for assistance.',
        confirmButtonText: 'OK',
        confirmButtonColor: '#1a56a0'
      });
      resetButton();
      return;
    }

    // ── For all other errors, increment the attempt counter ──
    const attempts = incrementFailedAttempts();

    let userMessage = 'Login failed. Please try again.';

    if (msg === 'User not found') {
      // Deliberately vague — don't leak whether the username exists
      userMessage = 'Invalid username or password.';
    } else if (msg === 'Incomplete user data') {
      userMessage = 'A system error occurred. Please contact support.';
    } else if (msg === 'Incorrect password') {
      userMessage = 'Invalid username or password.';
    } else if (msg === 'Session creation failed') {
      userMessage = 'Session could not be created. Please try again.';
    }

    // ── Show alert ──
    Luma.Alert('Error', userMessage, 'error');

    // ── Refresh CAPTCHA on credential failure ──
    if (['User not found', 'Incorrect password'].includes(msg)) {
      refreshCaptcha();
    }

    // ── Trigger lockout if threshold reached ──
    if (attempts >= MAX_FAILED_ATTEMPTS) {
      startLockout(LOCKOUT_SECONDS);
    }

    resetButton();
  }


  // ============================================================
  // 10. POST-LOGIN SUCCESS FLOW
  // ============================================================

  /**
   * Show the success modal and redirect.
   * Mirrors proceedWithLogin() from the original auth.js exactly.
   *
   * @param {string} loginMode – 'admin' | 'superadmin'
   * @param {string} role      – user's role from the DB
   * @param {string} name      – user's formatted full name
   */
  function proceedWithLogin(loginMode, role, name) {
    let title       = 'Welcome!';
    let html        = `Welcome to the <strong>CSC RO VIII – IMIS</strong>,<br>
                       <span class="text-primary fw-bold">${escapeHtml(name)}</span>.<br>
                       Please click below to proceed.`;
    let redirectUrl = 'index_dashboard';

    if (loginMode === 'superadmin') {
      // Role check — must actually be superadmin in the DB
      if (role !== 'superadmin') {
        Swal.fire({
          icon:              'error',
          title:             'Unauthorized',
          html:              'You are not authorized to access this feature.<br>Please contact your system administrator.',
          confirmButtonText: 'OK',
          confirmButtonColor: '#1a56a0'
        });
        resetButton();
        return;
      }

      title       = 'Login Successful';
      html        = `Welcome, <span class="text-primary fw-bold">${escapeHtml(name)}</span>.
                     You have successfully logged in as a <strong>Super Administrator</strong>.<br>
                     Please click below to proceed.`;
      redirectUrl = 'admin/index_users_management';
    }

    Swal.fire({
      title,
      html,
      icon:              'success',
      confirmButtonText: '<i class="bi bi-box-arrow-in-right"></i>&nbsp; Proceed',
      confirmButtonColor: loginMode === 'superadmin' ? '#6d28d9' : '#1a56a0',
      allowOutsideClick: false
    }).then(result => {
      if (result.isConfirmed) {
        window.location.href = redirectUrl;
      }
    });

    resetButton();
  }


  // ============================================================
  // 11. LOGIN LOGGING
  // ============================================================

  /**
   * Fire-and-forget POST to log a successful login.
   * Mirrors logUserLogin() from the original auth.js.
   *
   * @param {string|number} userId
   * @param {string}        username
   * @returns {Promise}
   */
  function logUserLogin(userId, username) {
    return new Promise((resolve, reject) => {
      if (!userId || !username) {
        reject('Invalid user data for logging');
        return;
      }

      $.ajax({
        url:      'inc/log_login.php',
        type:     'POST',
        dataType: 'json',
        timeout:  5000,
        data: {
          action:   'login',
          user_id:  userId,
          username: username
        },
        success(response) {
          response?.success ? resolve(response) : reject(response?.message || 'Logging failed');
        },
        error(xhr) {
          reject(`Logging AJAX error: HTTP ${xhr.status}`);
        }
      });
    });
  }


  // ============================================================
  // 12. UTILITY
  // ============================================================

  /**
   * Escape a string for safe insertion into HTML (prevents XSS in
   * the SweetAlert2 html: option which accepts raw HTML).
   * @param {string} str
   * @returns {string}
   */
  function escapeHtml(str) {
    return String(str)
      .replace(/&/g,  '&amp;')
      .replace(/</g,  '&lt;')
      .replace(/>/g,  '&gt;')
      .replace(/"/g,  '&quot;')
      .replace(/'/g,  '&#39;');
  }


  // ============================================================
  // 13. EVENT BINDINGS
  // ============================================================

  // ── Progressive field validation (on blur) ──────────────────
  $usernameInput.on('blur', function () {
    const val = $(this).val().trim();
    val
      ? clearFieldError($('#usernameShell'), 'usernameErr')
      : showFieldError($('#usernameShell'), 'usernameErr', 'Username is required.');
  });

  $passwordInput.on('blur', function () {
    const val = $(this).val();
    val
      ? clearFieldError($('#passwordShell'), 'passwordErr')
      : showFieldError($('#passwordShell'), 'passwordErr', 'Password is required.');
  });

  $captchaInput.on('blur', function () {
    const val = $(this).val().trim();
    if (!val) {
      showFieldError($('#captchaShell'), 'captchaErr', 'Please enter the CAPTCHA code.');
    } else if (val.length !== 4 || isNaN(val)) {
      showFieldError($('#captchaShell'), 'captchaErr', 'CAPTCHA must be exactly 4 digits.');
    } else {
      clearFieldError($('#captchaShell'), 'captchaErr');
    }
  });

  // ── Clear errors as user starts typing ──────────────────────
  $usernameInput.on('input', () => clearFieldError($('#usernameShell'), 'usernameErr'));
  $passwordInput.on('input', () => clearFieldError($('#passwordShell'), 'passwordErr'));
  $captchaInput.on('input',  handleCaptchaInput);

  // ── CAPTCHA refresh button ───────────────────────────────────
  $refreshBtn.on('click', refreshCaptcha);

  // ── Form submit ──────────────────────────────────────────────
  $('#loginForm').on('submit', function (e) {
    e.preventDefault();

    // Guard: already processing a login
    if (isLoggingIn) return;

    // Guard: still locked out
    if (isLockedOut()) {
      resumeLockoutIfActive();
      return;
    }

    const username  = $usernameInput.val().trim();
    const password  = $passwordInput.val();           // do NOT trim passwords
    const captcha   = $captchaInput.val().trim();
    const loginMode = $toggleModeBtn.prop('checked') ? 'superadmin' : 'admin';

    // Client-side validation gate
    if (!validateForm(username, password, captcha)) return;

    // All clear — proceed
    isLoggingIn = true;
    setButtonLoading();
    performLogin(username, password, loginMode);
  });


  // ============================================================
  // 14. INITIALISE
  // ============================================================

  // Sync server time then initialise CAPTCHA from the PHP-rendered value
 initCaptcha(); // run immediately
 syncServerTime(); // run independently, no dependency needed

  // Resume any lockout that was active before a page refresh
  resumeLockoutIfActive();

});