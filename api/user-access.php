<?php

/**
 * api/user-access.php — User system permissions endpoint
 * ─────────────────────────────────────────────────────────────────────────────
 * Returns the system_access row for the currently authenticated user.
 * Replaces the Luma.FetchData({ query: 'SELECT * FROM system_access …' }) call
 * that was previously running raw SQL from the browser.
 *
 * Requires: active authenticated session (session.php handles the redirect if not).
 * Method:   GET
 * Returns:  { success: true,  access: { … } }
 *         | { success: true,  access: null }   — user has no access row
 *         | { error: '…' }                     — 401 / 400 / 500
 * ─────────────────────────────────────────────────────────────────────────────
 */

declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

// ── Auth gate — session.php redirects unauthenticated requests to /login ──────
require_once __DIR__ . '/../includes/session.php';

// ── Authenticated user must be present ───────────────────────────────────────
if (empty($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized.']);
    exit();
}

// ── AJAX-only guard ───────────────────────────────────────────────────────────
$xrw = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
if (strtolower($xrw) !== 'xmlhttprequest') {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request.']);
    exit();
}

// ── Database ──────────────────────────────────────────────────────────────────
require_once __DIR__ . '/../includes/connect.php';

try {
    $stmt = $conn->prepare('SELECT * FROM system_access WHERE user = :userId LIMIT 1');
    $stmt->bindParam(':userId', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
    $access = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'access'  => $access ?: null,   // null = no row found, not an error
    ]);
} catch (PDOException $e) {
    error_log('[api/user-access.php] DB error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'A database error occurred.']);
}
exit();
