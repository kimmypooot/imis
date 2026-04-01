<?php
header("Content-Type: application/json");
require_once 'connect.php';

// Decode the input JSON first
$input = json_decode(file_get_contents("php://input"), true);

// Check if 'queries' exists and decrypt only that part
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($input['queries'])) {
    date_default_timezone_set('Asia/Manila');
    $key = (intval(date('H')) * 60 + intval(date('i'))) % 256;
    $data = xorDecrypt($input['queries'], $key); // Only decrypt the 'queries' value

    try {
        $conn->beginTransaction();
        $id = null;

        foreach ($data as $q) {
            $stmt = $conn->prepare($q['query']);
            $stmt->execute($q['params']);
            $id = $conn->lastInsertId();
        }

        $conn->commit();
        echo json_encode([
            "success" => true,
            "message" => "Transaction completed successfully.",
            "id" => $id
        ]);
    } catch (PDOException $e) {
        $conn->rollBack();
        echo json_encode([
            "success" => false,
            "message" => "Transaction failed: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request."
    ]);
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