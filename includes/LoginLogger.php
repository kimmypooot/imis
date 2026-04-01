<?php
// includes/LoginLogger.php
date_default_timezone_set('Asia/Manila');

class LoginLogger {
    private $conn;
    
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
        
        // Validate database connection
        if (!$this->conn) {
            throw new Exception('Database connection is null');
        }
        
        // Test database connection
        try {
            $this->conn->query('SELECT 1');
            // SET MYSQL TIMEZONE TO MATCH PHP
            $this->conn->exec("SET time_zone = '+08:00'");
        } catch (PDOException $e) {
            throw new Exception('Database connection test failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Get client IP address
     */
    private function getClientIP() {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 
                   'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = explode(',', $ip)[0];
                }
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, 
                    FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
    
    /**
     * Log user login
     */
    public function logLogin($userId, $username) {
        try {
            // FIXED: Validate inputs
            if (empty($userId) || empty($username)) {
                error_log("LoginLogger: Invalid input - userId: '$userId', username: '$username'");
                return false;
            }
            
            // FIXED: Start session if not started
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            // First, mark any existing active sessions for this user as logged out
            $this->markPreviousSessionsAsLoggedOut($userId);
            
            // FIXED: Check if table exists
            $checkTable = "SHOW TABLES LIKE 'imis_history_login_logs'";
            $stmt = $this->conn->prepare($checkTable);
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                error_log("LoginLogger: Table 'imis_history_login_logs' does not exist");
                return false;
            }
            
            $sql = "INSERT INTO imis_history_login_logs 
                    (user_id, username, ip_address, login_time, user_agent, status, session_id) 
                    VALUES (:user_id, :username, :ip_address, NOW(), :user_agent, 'active', :session_id)";
            
            $stmt = $this->conn->prepare($sql);
            
            $params = [
                ':user_id' => $userId,
                ':username' => $username,
                ':ip_address' => $this->getClientIP(),
                ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                ':session_id' => session_id()
            ];
            
            // FIXED: Log the parameters for debugging
            error_log("LoginLogger: Executing login log with params: " . json_encode($params));
            
            $result = $stmt->execute($params);
            
            if ($result) {
                // Store the log ID in session for later reference
                $_SESSION['login_log_id'] = $this->conn->lastInsertId();
                error_log("LoginLogger: Login logged successfully. Log ID: " . $_SESSION['login_log_id']);
                return true;
            } else {
                error_log("LoginLogger: Execute failed. Error info: " . json_encode($stmt->errorInfo()));
                return false;
            }
            
        } catch (PDOException $e) {
            error_log("LoginLogger: Login log error: " . $e->getMessage());
            error_log("LoginLogger: SQL: " . ($sql ?? 'N/A'));
            error_log("LoginLogger: Params: " . json_encode($params ?? []));
            return false;
        } catch (Exception $e) {
            error_log("LoginLogger: General error in logLogin: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Log user logout
     */
    public function logLogout($userId = null, $username = null, $reason = 'manual') {
        try {
            // FIXED: Start session if not started
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            $logId = $_SESSION['login_log_id'] ?? null;
            error_log("LoginLogger: Logout attempt - logId: $logId, userId: $userId, username: $username, reason: $reason");
            
            if ($logId) {
                // Update the specific log entry
                $sql = "UPDATE imis_history_login_logs 
                        SET logout_time = NOW(), 
                            status = :status,
                            updated_at = CURRENT_TIMESTAMP
                        WHERE id = :log_id";
                
                $status = ($reason === 'timeout' || $reason === 'idle') ? 'timeout' : 'logged_out';
                
                $stmt = $this->conn->prepare($sql);
                $result = $stmt->execute([
                    ':status' => $status,
                    ':log_id' => $logId
                ]);
                
                if ($result) {
                    error_log("LoginLogger: Logout logged successfully using log ID: $logId");
                } else {
                    error_log("LoginLogger: Failed to update logout using log ID. Error: " . json_encode($stmt->errorInfo()));
                }
                
            } else if ($userId && $username) {
                // Fallback: update by user_id and session_id
                error_log("LoginLogger: Using fallback logout method");
                
                $sql = "UPDATE imis_history_login_logs 
                        SET logout_time = NOW(), 
                            status = :status,
                            updated_at = CURRENT_TIMESTAMP
                        WHERE user_id = :user_id 
                        AND username = :username 
                        AND status = 'active' 
                        AND session_id = :session_id";
                
                $status = ($reason === 'timeout' || $reason === 'idle') ? 'timeout' : 'logged_out';
                
                $stmt = $this->conn->prepare($sql);
                $result = $stmt->execute([
                    ':status' => $status,
                    ':user_id' => $userId,
                    ':username' => $username,
                    ':session_id' => session_id()
                ]);
                
                if ($result) {
                    error_log("LoginLogger: Logout logged successfully using fallback method");
                } else {
                    error_log("LoginLogger: Failed to update logout using fallback. Error: " . json_encode($stmt->errorInfo()));
                }
            } else {
                error_log("LoginLogger: Cannot log logout - no log ID and no user info provided");
                return false;
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("LoginLogger: Logout log error: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("LoginLogger: General error in logLogout: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Mark previous sessions as logged out (for concurrent login handling)
     */
    private function markPreviousSessionsAsLoggedOut($userId) {
        try {
            $sql = "UPDATE imis_history_login_logs 
                    SET logout_time = NOW(), 
                        status = 'logged_out',
                        updated_at = CURRENT_TIMESTAMP
                    WHERE user_id = :user_id 
                    AND status = 'active' 
                    AND session_id != :current_session_id";
            
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':user_id' => $userId,
                ':current_session_id' => session_id()
            ]);
            
            if ($result) {
                $affectedRows = $stmt->rowCount();
                if ($affectedRows > 0) {
                    error_log("LoginLogger: Marked $affectedRows previous sessions as logged out for user $userId");
                }
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("LoginLogger: Error marking previous sessions: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get active sessions for a user
     */
    public function getActiveSessions($userId) {
        try {
            $sql = "SELECT * FROM imis_history_login_logs 
                    WHERE user_id = :user_id 
                    AND status = 'active' 
                    ORDER BY login_time DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("LoginLogger: Error getting active sessions: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Clean up old sessions (utility function for maintenance)
     */
    public function cleanupOldSessions($daysOld = 30) {
        try {
            $sql = "UPDATE imis_history_login_logs 
                    SET status = 'timeout',
                        logout_time = COALESCE(logout_time, login_time),
                        updated_at = CURRENT_TIMESTAMP
                    WHERE status = 'active' 
                    AND login_time < DATE_SUB(NOW(), INTERVAL :days DAY)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':days' => $daysOld]);
            
            $affectedRows = $stmt->rowCount();
            error_log("LoginLogger: Cleaned up $affectedRows old sessions");
            
            return $affectedRows;
        } catch (PDOException $e) {
            error_log("LoginLogger: Error cleaning up old sessions: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * FIXED: Add method to verify table structure
     */
    public function verifyTableStructure() {
        try {
            $sql = "DESCRIBE imis_history_login_logs";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("LoginLogger: Table structure: " . json_encode($columns));
            return $columns;
        } catch (PDOException $e) {
            error_log("LoginLogger: Error checking table structure: " . $e->getMessage());
            return false;
        }
    }
}