<?php
header("Content-Type: application/json");
require_once 'connect.php';

$input = json_decode(file_get_contents("php://input"), true);
date_default_timezone_set('Asia/Manila');
$key = (intval(date('H')) * 60 + intval(date('i'))) % 256;
$data = xorDecrypt($input['encPayload'], $key);

// Validate input
if (isset($data['query'])) {
    $stmt = $conn->prepare($data['query']);
    $stmt->execute($data['params'] ?? []); // Handles empty or undefined 'params'
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["data" => $result]);
} else {
    echo json_encode(["data" => null, "error" => "Missing query."]);
}

function xorDecrypt($data, $key)
{
    $data = base64_decode($data);
    $output = '';
    for ($i = 0; $i < strlen($data); $i++) {
        $output .= chr(ord($data[$i]) ^ $key);
    }
    return json_decode($output, true);
}
?>