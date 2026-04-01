<?php
// inc/logout.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('../includes/connect.php');
require_once('../includes/LoginLogger.php');

function is_subdomain()
{
    $host = $_SERVER['HTTP_HOST'];
    return (
        strpos($host, 'imis.') === 0 ||
        preg_match('/^imis\./', $host) ||
        $host === 'imis.cscro8.com' ||
        strpos($host, 'imis.cscro8.com') !== false
    );
}

function get_login_redirect_path()
{
    if (is_subdomain()) {
        return '/login';
    }

    $current_dir = dirname($_SERVER['SCRIPT_NAME']);
    $path_parts = explode('/', trim($current_dir, '/'));
    $imis_index = array_search('imis', $path_parts);

    if ($imis_index !== false) {
        $depth = count($path_parts) - $imis_index - 1;
        return str_repeat('../', $depth) . 'login';
    }

    return '../login';
}

// Validate and get logout reason
$logout_reason = $_GET['reason'] ?? 'manual';
$valid_reasons = ['manual', 'idle', 'timeout', 'forced'];
if (!in_array($logout_reason, $valid_reasons)) {
    $logout_reason = 'manual';
}

// Log the logout activity BEFORE destroying session
$userId = $_SESSION['id'] ?? null;
$username = $_SESSION['username'] ?? null;

if ($userId && $username) {
    try {
        $logger = new LoginLogger($conn);
        $logger->logLogout($userId, $username, $logout_reason);
        error_log("User logout logged: $username - Reason: $logout_reason");
    } catch (Exception $e) {
        error_log("Logout logging failed: " . $e->getMessage());
    }
}

// Clear all session data
$_SESSION = array();
unset($_SESSION['turnstile_verified']);

// Destroy session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Determine redirect URL with reason parameter
$redirect_url = get_login_redirect_path();
$redirect_url .= ($logout_reason === 'idle') ? '?logout=idle' : '?logout=manual';

// Redirect to login page
header('Location: ' . $redirect_url);
exit();
?>
<?php
// // Start session if not already started
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }

// // Function to detect if we're on a subdomain or main domain
// function is_subdomain() {
//     $host = $_SERVER['HTTP_HOST'];
//     return (
//         strpos($host, 'imis.') === 0 || 
//         preg_match('/^imis\./', $host) || 
//         $host === 'imis.cscro8.com' ||
//         strpos($host, 'imis.cscro8.com') !== false
//     );
// }

// // Function to get the correct redirect path
// function get_login_redirect_path() {
//     if (is_subdomain()) {
//         return '/login';
//     }

//     $current_dir = dirname($_SERVER['SCRIPT_NAME']);
//     $path_parts = explode('/', trim($current_dir, '/'));
//     $imis_index = array_search('imis', $path_parts);

//     if ($imis_index !== false) {
//         $depth = count($path_parts) - $imis_index - 1;
//         return str_repeat('../', $depth) . 'login';
//     }

//     return '../login'; // fallback
// }

// // Check logout reason
// $logout_reason = isset($_GET['reason']) ? $_GET['reason'] : 'manual';

// // Log the logout activity (optional)
// if (isset($_SESSION['username'])) {
//     error_log("User logout: " . $_SESSION['username'] . " - Reason: " . $logout_reason);
// }

// // Clear all session data
// $_SESSION = array();

// // Destroy session cookie
// if (ini_get("session.use_cookies")) {
//     $params = session_get_cookie_params();
//     setcookie(session_name(), '', time() - 42000,
//         $params["path"], $params["domain"],
//         $params["secure"], $params["httponly"]
//     );
// }

// // Destroy the session
// session_destroy();

// // Determine redirect URL with reason parameter
// $redirect_url = get_login_redirect_path();
// if ($logout_reason === 'idle') {
//     $redirect_url .= '?logout=idle';
// } else {
//     $redirect_url .= '?logout=manual';
// }

// // Redirect to login page
// header('Location: ' . $redirect_url);
// exit();
?>
<?php
// // Start or resume the session
// session_start();

// // Unset all session variables
// $_SESSION = array();

// // If you want to kill the session, also delete the session cookie.
// if (ini_get("session.use_cookies")) {
//     $params = session_get_cookie_params();
//     setcookie(
//         session_name(), 
//         '', 
//         time() - 42000,
//         $params["path"], 
//         $params["domain"], 
//         $params["secure"], 
//         $params["httponly"]
//     );
// }

// // Destroy the session
// session_destroy();

// // Determine the correct redirect URL based on subdomain usage
// $host = $_SERVER['HTTP_HOST'];
// $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';

// // Check if we're using a subdomain (contains imis. at the beginning)
// if (strpos($host, 'imis.') === 0) {
//     // Subdomain case: redirect to root/login
//     $redirect_url = $protocol . '://' . $host . '/login';
// } else {
//     // Main domain case: redirect to /imis/login
//     $redirect_url = $protocol . '://' . $host . '/imis/login';
// }

// // Redirect to the appropriate login page
// header("Location: " . $redirect_url);
// exit;
?>