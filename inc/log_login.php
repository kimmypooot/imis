<?php
// inc/log_login.php
// Optimized login logger
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../includes/connect.php');

// Set timezone for consistent logging
date_default_timezone_set('Asia/Manila');

header('Content-Type: application/json');

try {
    // Quick validation
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid method');
    }

    $action = $_POST['action'] ?? '';
    $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    
    if (!$userId || !$username || !$action) {
        throw new Exception('Missing parameters');
    }

    if (!$conn) {
        throw new Exception('Database unavailable');
    }

    $currentTime = date('Y-m-d H:i:s'); // Asia/Manila timezone

    switch ($action) {
        case 'login':
            // Direct database insert - skip class overhead
            $stmt = $conn->prepare("INSERT INTO imis_history_login_logs (user_id, username, login_time, ip_address, user_agent, session_id) VALUES (?, ?, ?, ?, ?, ?)");
            
            $result = $stmt->execute([
                $userId,
                $username,
                $currentTime,
                $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255), // Limit length
                session_id()
            ]);
            
            if ($result) {
                $_SESSION['login_log_id'] = $conn->lastInsertId();
                echo json_encode([
                    'success' => true,
                    'message' => 'Login logged',
                    'log_id' => $_SESSION['login_log_id']
                ]);
            } else {
                throw new Exception('Insert failed');
            }
            break;

        case 'logout':
            $reason = $_POST['reason'] ?? 'manual';
            
            // Update existing log entry
            $stmt = $conn->prepare("UPDATE imis_history_login_logs SET logout_time = ?, logout_reason = ? WHERE user_id = ? AND session_id = ? AND logout_time IS NULL ORDER BY login_time DESC LIMIT 1");
            
            $result = $stmt->execute([
                $currentTime,
                $reason,
                $userId,
                session_id()
            ]);
            
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Logout logged' : 'No active session found'
            ]);
            break;

        default:
            throw new Exception('Invalid action');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    // Close connection to free resources
    $conn = null;
}

// Immediate exit
exit();
?>