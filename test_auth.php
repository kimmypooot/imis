<?php
// test_auth.php - Place in your root directory
session_start();
require_once('includes/connect.php');

// TEST CONFIGURATION
$test_username = 'etcasinillo'; // CHANGE THIS
$test_password = '12345'; // CHANGE THIS

echo "<h2>Authentication Test</h2>";
echo "<hr>";

// Step 1: Check database connection
echo "<h3>1. Database Connection</h3>";
if ($conn) {
    echo "✅ Database connected<br>";
} else {
    echo "❌ Database connection failed<br>";
    exit;
}

// Step 2: Fetch user
echo "<h3>2. User Query</h3>";
$stmt = $conn->prepare("
    SELECT u.*, 
    (SELECT COUNT(*) FROM itg_tbl WHERE id = u.id) as is_itg_member
    FROM users_cscro8 u 
    WHERE u.username = ?
    LIMIT 1
");
$stmt->execute([$test_username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "✅ User found<br>";
    echo "Username: " . htmlspecialchars($user['username']) . "<br>";
    echo "Role: " . htmlspecialchars($user['role'] ?? 'not set') . "<br>";
    echo "Status: " . htmlspecialchars($user['status'] ?? 'not set') . "<br>";
    echo "Password hash: " . substr($user['password'], 0, 30) . "...<br>";
} else {
    echo "❌ User not found<br>";
    exit;
}

// Step 3: Check required fields
echo "<h3>3. Required Fields</h3>";
$required = ['id', 'username', 'password', 'fname', 'lname'];
$missing = [];
foreach ($required as $field) {
    if (!isset($user[$field]) || empty($user[$field])) {
        $missing[] = $field;
    }
}

if (empty($missing)) {
    echo "✅ All required fields present<br>";
} else {
    echo "❌ Missing fields: " . implode(', ', $missing) . "<br>";
}

// Step 4: Check status
echo "<h3>4. Account Status</h3>";
if (isset($user['status']) && strtolower(trim($user['status'])) === 'inactive') {
    echo "❌ Account is INACTIVE<br>";
} else {
    echo "✅ Account is active<br>";
}

// Step 5: Password verification
echo "<h3>5. Password Verification</h3>";
echo "Password length: " . strlen($test_password) . "<br>";
echo "Hash algorithm: " . (strpos($user['password'], '$2y$') === 0 ? 'bcrypt' : 'unknown') . "<br>";

$verified = password_verify($test_password, $user['password']);
if ($verified) {
    echo "✅ Password verification: <strong style='color:green'>SUCCESS</strong><br>";
} else {
    echo "❌ Password verification: <strong style='color:red'>FAILED</strong><br>";
    
    // Additional debug
    echo "<br><strong>Debug Info:</strong><br>";
    echo "Stored hash: " . $user['password'] . "<br>";
    
    // Test with a new hash
    $new_hash = password_hash($test_password, PASSWORD_DEFAULT);
    echo "New hash test: " . (password_verify($test_password, $new_hash) ? 'Works' : 'Broken') . "<br>";
}

// Step 6: Role check
echo "<h3>6. Role Authorization</h3>";
$user_role = strtolower(trim($user['role'] ?? ''));
echo "User role (normalized): '$user_role'<br>";

echo "<br><strong>Access Levels:</strong><br>";
echo "- Regular login: " . ($user_role ? "✅ Allowed" : "❌ No role") . "<br>";
echo "- Superadmin login: " . ($user_role === 'superadmin' ? "✅ Allowed" : "❌ Not superadmin") . "<br>";

echo "<hr>";
echo "<h3>Summary</h3>";
if ($verified && !empty($user_role)) {
    echo "✅ <strong style='color:green'>Authentication should work!</strong><br>";
} else {
    echo "❌ <strong style='color:red'>Authentication will fail</strong><br>";
    echo "Fix the issues above.<br>";
}
?>