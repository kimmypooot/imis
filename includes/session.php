<?php
// includes/session.php

// ── Must be set BEFORE session_start() ──────────────────────────────────────
// These ini_set calls are idempotent, so safe to call even if a caller (e.g.
// login.php) has already set them. The session_status() guard below ensures
// session_start() is never called twice, preventing the headers-already-sent
// warning that was occurring before.
ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_secure', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? '1' : '0');
ini_set('session.cache_limiter', 'nocache');
ini_set('session.cookie_samesite', 'Strict');

// ── Single session_start() ────────────────────────────────────────────────────
// FIX: Guard against double session_start().
// Previously login.php called session_start() then included this file, which
// called session_start() again — triggering a PHP warning and potentially
// resetting session state.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once __DIR__ . '/connect.php';

// ── Session ID rotation every 10 minutes ──────────────────────────────────────
if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
} elseif (time() - $_SESSION['created'] > 600) {
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}

// ── Determine whether this is a public (unauthenticated) page ─────────────────
$current_page = basename($_SERVER['PHP_SELF']);
$request_uri  = $_SERVER['REQUEST_URI'];

$public_pages = ['login', 'index'];

$is_public_page =
    in_array($current_page, $public_pages, true) ||
    str_contains($request_uri, '/login')           ||
    str_contains($request_uri, '/index');

// ── Authentication gate ───────────────────────────────────────────────────────
// Only enforce on non-public pages.
if (!$is_public_page) {
    $valid_roles = ['user', 'admin', 'superadmin'];
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $valid_roles, true)) {

        $host     = $_SERVER['HTTP_HOST'];
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        header('Location: ' . $protocol . '://' . $host . '/login');
        exit();
    }
}

// ── Cache prevention headers ──────────────────────────────────────────────────
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

/**
 * Load user system permissions from the database into $_SESSION.
 *
 * @param int $userId
 * @return array|false  The raw permissions row, or false on failure / not found.
 */
function loadUserSystemPermissions(int $userId): array|false
{
    global $conn;

    try {
        $stmt = $conn->prepare('SELECT * FROM system_access WHERE user = :userId LIMIT 1');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $permissions = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($permissions) {
            $systems = [
                'eris',
                'lms',
                'otrs',
                'ors',
                'psed',
                'cdl',
                'ictsrts',
                'rooms',
                'procurement',
                'leave_cscro8',
                'pms',
                'rfcs',
                'dvs',
                'cts',
            ];
            foreach ($systems as $system) {
                if (array_key_exists($system, $permissions)) {
                    $_SESSION[$system] = $permissions[$system];
                }
            }
            return $permissions;
        }

        return false;
    } catch (PDOException $e) {
        error_log('[session.php] loadUserSystemPermissions error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Check whether the current user has the required permission level for a system.
 * Redirects to the dashboard (or system index) if access is insufficient.
 *
 * @param string $system_name
 * @param string $required_level  'User' | 'Admin' | 'SuperAdmin'
 */
function checkSystemAccess(string $system_name, string $required_level = 'User'): true
{
    $valid_systems = [
        'eris',
        'lms',
        'otrs',
        'ors',
        'psed',
        'cdl',
        'ictsrts',
        'rooms',
        'procurement',
        'leave_cscro8',
        'pms',
        'rfcs',
        'dvs',
        'cts',
    ];

    if (!in_array($system_name, $valid_systems, true)) {
        header('Location: /dashboard');
        exit();
    }

    if (empty($_SESSION['username'])) {
        header('Location: /login');
        exit();
    }

    if (!isset($_SESSION[$system_name])) {
        $userId = (int) ($_SESSION['id'] ?? 0);
        if ($userId > 0) {
            loadUserSystemPermissions($userId);
        }
    }

    $user_permission = $_SESSION[$system_name] ?? 'None';

    $hierarchy = ['None' => 0, 'User' => 1, 'Admin' => 2, 'SuperAdmin' => 3];
    $userLevel     = $hierarchy[$user_permission]  ?? 0;
    $requiredLevel = $hierarchy[$required_level]   ?? 0;

    if ($userLevel === 0) {
        header('Location: /dashboard');
        exit();
    }

    if ($userLevel < $requiredLevel) {
        header("Location: /{$system_name}/index");
        exit();
    }

    return true;
}

/**
 * Get the permission level string for a system for the current user.
 *
 * @param string $system_name
 * @return string  'None' | 'User' | 'Admin' | 'SuperAdmin'
 */
function getUserSystemPermission(string $system_name): string
{
    $valid_systems = [
        'eris',
        'lms',
        'otrs',
        'ors',
        'psed',
        'cdl',
        'ictsrts',
        'rooms',
        'procurement',
        'leave_cscro8',
        'pms',
        'rfcs',
        'dvs',
        'cts',
    ];

    if (!in_array($system_name, $valid_systems, true)) {
        return 'None';
    }

    if (!isset($_SESSION[$system_name])) {
        $userId = (int) ($_SESSION['id'] ?? 0);
        if ($userId > 0) {
            loadUserSystemPermissions($userId);
        }
    }

    return $_SESSION[$system_name] ?? 'None';
}

/**
 * Returns true if the current user meets the minimum permission level.
 *
 * @param string $system_name
 * @param string $required_level
 */
function hasSystemPermission(string $system_name, string $required_level = 'User'): bool
{
    $hierarchy = ['None' => 0, 'User' => 1, 'Admin' => 2, 'SuperAdmin' => 3];
    $userLevel     = $hierarchy[getUserSystemPermission($system_name)] ?? 0;
    $requiredLevel = $hierarchy[$required_level] ?? 0;
    return $userLevel >= $requiredLevel;
}

/**
 * Returns an associative array of [ system => permission ] for all systems
 * where the user has at least 'User' level access.
 *
 * @return array<string, string>
 */
function getUserAccessibleSystems(): array
{
    $systems    = ['eris', 'lms', 'otrs', 'ors', 'psed', 'cdl', 'ictsrts', 'rooms', 'procurement', 'leave_cscro8', 'pms'];
    $accessible = [];
    foreach ($systems as $system) {
        $permission = getUserSystemPermission($system);
        if ($permission !== 'None') {
            $accessible[$system] = $permission;
        }
    }
    return $accessible;
}

/**
 * Returns true (and clears the flag) if a login-success message should be shown.
 */
function handleLoginSuccess(): bool
{
    if (isset($_SESSION['login_success']) && $_SESSION['login_success'] === true) {
        unset($_SESSION['login_success']);
        return true;
    }
    return false;
}

/**
 * Detect the current system from the URL path.
 */
function getCurrentSystem(): ?string
{
    $path    = $_SERVER['REQUEST_URI'];
    $systems = ['eris', 'lms', 'otrs', 'ors', 'psed', 'cdl', 'ictsrts', 'rooms', 'procurement'];
    foreach ($systems as $system) {
        if (str_contains($path, "/{$system}/")) {
            return $system;
        }
    }
    return null;
}

/**
 * Returns true if the current URL path contains /admin/.
 */
function isAdminPage(): bool
{
    return str_contains($_SERVER['REQUEST_URI'], '/admin/');
}

/**
 * Unified access control — call at the top of any system page.
 * Auto-detects the system and required level from the URL if not provided.
 */
function enforceSystemAccess(?string $system_name = null, ?string $required_level = null): true
{
    $system_name   ??= getCurrentSystem();
    $required_level ??= isAdminPage() ? 'Admin' : 'User';

    if ($system_name === null) {
        return true; // Main dashboard — no system restriction
    }

    return checkSystemAccess($system_name, $required_level);
}

/**
 * Force a fresh permission load from the database for the given user.
 */
function refreshUserPermissions(int $userId): array|false
{
    $systems = ['eris', 'lms', 'otrs', 'ors', 'psed', 'cdl', 'ictsrts', 'rooms', 'procurement', 'leave_cscro8', 'pms', 'rfcs', 'dvs', 'cts'];
    foreach ($systems as $system) {
        unset($_SESSION[$system]);
    }
    unset($_SESSION['permissions_loaded']);
    return loadUserSystemPermissions($userId);
}

/**
 * Redirect a user to the correct system page based on their DB permissions.
 * Used by the dashboard when a user clicks on a system tile.
 * (Simple systems only — CTS/RFCS/ICTSRTS use api/system-redirect.php.)
 */
function redirectToSystemWithPermissionCheck(string $system, int $userId): never
{
    $permissions = loadUserSystemPermissions($userId);

    if (!$permissions) {
        header('Location: /dashboard');
        exit();
    }

    $dbColumn = strtolower($system);
    $role     = $permissions[$dbColumn] ?? 'None';

    if ($role === 'None') {
        header('Location: /dashboard');
        exit();
    }

    $redirectMap = [
        'ERIS'        => ['Admin' => '/eris/esd/index',          'User' => '/eris/index'],
        'LEAVE_CSCRO8' => ['Admin' => '/leave/admin',             'User' => '/leave/index'],
        'LMS'         => ['Admin' => '/lms/hrd/index',           'User' => '/lms/index'],
        'OTRS'        => ['Admin' => '/otrs/hrd/index',          'User' => '/otrs/index'],
        'ORS'         => ['Admin' => '/ors/admin/index',         'User' => '/ors/index'],
        'PMS'         => ['Admin' => '/ipcrf/admin/index',       'User' => '/ipcrf/index'],
        'PSED'        => ['Admin' => '/psed/index',              'User' => '/psed/index'],
        'CDL'         => ['Admin' => '/cdl/index_clients',       'User' => '/cdl/index_clients'],
        'ROOMS'       => ['Admin' => '/rooms/index',             'User' => '/rooms/index'],
        'PROCUREMENT' => ['Admin' => '/procurement/index',       'User' => '/procurement/index'],
        'ICTSRTS'     => ['Admin' => '/ict-srts/admin/index',    'User' => '/ict-srts/user/index'],
    ];

    $paths = $redirectMap[strtoupper($system)] ?? null;
    if (!$paths) {
        header('Location: /dashboard');
        exit();
    }

    $dest = ($role === 'Admin' || $role === 'SuperAdmin')
        ? ($paths['Admin'] ?? $paths['User'])
        : $paths['User'];

    header('Location: ' . $dest);
    exit();
}

// ── Auto-load permissions on first session access ──────────────────────────────
if (isset($_SESSION['id']) && empty($_SESSION['permissions_loaded'])) {
    loadUserSystemPermissions((int) $_SESSION['id']);
    $_SESSION['permissions_loaded'] = true;
}
