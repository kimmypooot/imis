<?php
require 'connect.php'; // Ensure this points to your DB connection

$encquery = $_POST['query'];
date_default_timezone_set('Asia/Manila');
$key = (intval(date('H')) * 60 + intval(date('i'))) % 256;

try {
    $query = xorDecrypt($encquery, $key);
    $stmt = $conn->query($query);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    echo json_encode(["data" => $data]);
}
catch (Exception $e) {
    echo json_encode(["message" => $e]);
}


function xorDecrypt($data, $key) {
    $data = base64_decode($data);
    $output = '';
    for ($i = 0; $i < strlen($data); $i++) {
        $output .= chr(ord($data[$i]) ^ $key);
    }
    return json_decode($output, true);
}
?>