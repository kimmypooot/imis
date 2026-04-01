<?php

/**
 * api/system-redirect.php — System-specific session setup & redirect resolver
 * ─────────────────────────────────────────────────────────────────────────────
 * Some systems (CTS, RFCS, ICTSRTS) need a DB lookup before the user can be
 * redirected — e.g. to find a driver ID or confirm ITG membership.
 *
 * Previously this was done entirely in the browser via:
 *   Luma.FetchData(rawSQL) → client holds DB row
 *   Luma.SetSession(clientPayload) → server trusted client-supplied data
 *
 * Now the flow is:
 *   1. Client posts { system: 'CTS' } to this endpoint
 *   2. Server looks up the required data using the authenticated user's session ID
 *   3. Server sets any additional session keys it needs
 *   4. Server returns { success: true, redirect: '/path' }
 *   5. Client navigates — it never sees raw DB rows or sets session data itself
 *
 * Method:  POST
 * Body:    system=<SYSTEM_ID>
 * Returns: { success: true,  redirect: '/...' }
 *        | { success: false, message: '...' }
 * ─────────────────────────────────────────────────────────────────────────────
 */

declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit();
}

header('Content-Type: application/json; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

// ── Auth gate ─────────────────────────────────────────────────────────────────
require_once __DIR__ . '/../includes/session.php';

if (empty($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit();
}

// ── AJAX-only guard ───────────────────────────────────────────────────────────
$xrw = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
if (strtolower($xrw) !== 'xmlhttprequest') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit();
}

// ── Database ──────────────────────────────────────────────────────────────────
require_once __DIR__ . '/../includes/connect.php';

$system = strtoupper(trim($_POST['system'] ?? ''));
$userId = (int) $_SESSION['id'];

// ── Helper ────────────────────────────────────────────────────────────────────
function jsonExit(bool $success, ?string $redirect = null, ?string $message = null): never
{
    echo json_encode(array_filter([
        'success'  => $success,
        'redirect' => $redirect,
        'message'  => $message,
    ], static fn($v) => $v !== null));
    exit();
}

// ── System dispatch ───────────────────────────────────────────────────────────
try {
    switch ($system) {

        // ── CTS: Legal Services Division ──────────────────────────────────────
        case 'CTS': {
                $stmt = $conn->prepare(
                    'SELECT * FROM cts_manage_users WHERE user = :user LIMIT 1'
                );
                $stmt->execute([':user' => $userId]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$row) {
                    jsonExit(false, null, 'Access is limited to Legal Services Division personnel only.');
                }

                // Server sets the session — client never touches these values.
                $_SESSION['cts_user_id'] = (int) $row['id'];
                $_SESSION['ao_number']   = $row['ao_number'] ?? '';

                $role = $_SESSION['cts'] ?? 'User';
                $redirectMap = [
                    'Superadmin' => '/cts/superadmin/index_dashboard',
                    'Admin'      => '/cts/admin/index_dashboard',
                    'User'       => '/cts/users/index_my_dashboard',
                ];
                jsonExit(true, $redirectMap[$role] ?? '/cts/users/index_my_dashboard');
            }

            // ── RFCS: Fuel Consumption Report System ──────────────────────────────
        case 'RFCS': {
                $role = $_SESSION['rfcs'] ?? 'None';

                if ($role === 'Admin') {
                    jsonExit(true, '/fts/admin/index_dashboard');
                }

                if ($role === 'User') {
                    $stmt = $conn->prepare(
                        "SELECT * FROM trip_drivers
                     WHERE  user   = :user
                     AND    status != 'Inactive'
                     LIMIT  1"
                    );
                    $stmt->execute([':user' => $userId]);
                    $driver = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$driver) {
                        jsonExit(false, null, 'No active driver account found for your user.');
                    }

                    // Server sets driver session — not the client.
                    $_SESSION['driver_id'] = (int) $driver['id'];
                    jsonExit(true, '/fts/user/index_dashboard');
                }

                jsonExit(false, null, 'You do not have access to this system.');
            }

            // ── ICTSRTS: ICT Service Request Ticketing System ─────────────────────
        case 'ICTSRTS': {
                $stmt = $conn->prepare(
                    'SELECT id FROM itg_tbl WHERE id = :userId LIMIT 1'
                );
                $stmt->execute([':userId' => $userId]);
                $isITG = (bool) $stmt->fetch();

                // Persist ITG membership in session (authoritative DB value, not client claim).
                $_SESSION['is_itg_member'] = $isITG;

                jsonExit(true, $isITG ? '/ict-srts/admin/index' : '/ict-srts/user/index');
            }

            // ── Unknown system ────────────────────────────────────────────────────
        default:
            http_response_code(400);
            jsonExit(false, null, 'Unknown system: ' . htmlspecialchars($system, ENT_QUOTES, 'UTF-8'));
    }
} catch (PDOException $e) {
    error_log('[api/system-redirect.php] DB error for system ' . $system . ': ' . $e->getMessage());
    http_response_code(500);
    jsonExit(false, null, 'A system error occurred. Please try again.');
}
