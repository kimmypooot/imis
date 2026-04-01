<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php'; // Adjust path to your `vendor/autoload.php`

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Accept JSON POST request
header('Content-Type: application/json');
$input = json_decode(file_get_contents("php://input"), true);
date_default_timezone_set('Asia/Manila');
$key = (intval(date('H')) * 60 + intval(date('i'))) % 256;
$decrypted = xorDecrypt($input['config'], $key);

if (!isset($decrypted)) {
    echo json_encode(['success' => false, 'message' => 'Missing config.', 'Config' => $decrypted]);
    exit;
}

$config = $decrypted;

// Required fields
$requiredFields = ['Host', 'Username', 'Password', 'Port', 'FromEmail', 'FromName', 'ToEmail', 'ToName', 'Subject', 'Body'];

foreach ($requiredFields as $field) {
    if (empty($config[$field])) {
        echo json_encode(['success' => false, 'message' => "Missing field: $field"]);
        exit;
    }
}

// Send email using PHPMailer
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = $config['Host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['Username'];
    $mail->Password = $config['Password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $config['Port'];

    $mail->setFrom($config['FromEmail'], $config['FromName']);
    $mail->addAddress($config['ToEmail'], $config['ToName']);

    $mail->Subject = $config['Subject'];
    $mail->isHTML(true);
    $mail->Body = $config['Body'];

    // Optional: add plain text version
    if (!empty($config['AltBody'])) {
        $mail->AltBody = $config['AltBody'];
    }

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Email sent successfully.']);
} catch (Exception $e) {
    error_log("Email error: " . $mail->ErrorInfo);
    echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
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