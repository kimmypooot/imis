<?php
require_once 'connect.php';

$sql = "SELECT * FROM imis_history_login_logs ORDER BY login_time DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [];
$index = 1;

foreach ($results as $row) {
    // Normalize status display with badge
    $statusText = ucfirst(str_replace('_', ' ', $row['status']));
    $badgeClass = 'bg-secondary';

    switch ($row['status']) {
        case 'active':
            $badgeClass = 'bg-success';
            break;
        case 'timeout':
        case 'idle':
            $badgeClass = 'bg-warning';
            break;
        case 'logged_out':
            $badgeClass = 'bg-danger';
            break;
    }

    $data[] = [
        "#" => $index++,
        "username" => htmlspecialchars($row['username']),
        "ip_address" => $row['ip_address'],
        "login_time" => $row['login_time'],
        "logout_time" => $row['logout_time'] ?? '<i class="text-muted">Still logged in</i>',
        "status" => '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>',
        "user_agent" => $row['user_agent']
    ];
}

echo json_encode(["data" => $data]);
