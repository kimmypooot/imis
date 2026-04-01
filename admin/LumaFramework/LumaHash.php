<?php
require 'connect.php'; // Ensure this points to your DB connection

$value = $_POST['value'];

// Return JSON response
echo json_encode(["data" => password_hash($value, PASSWORD_DEFAULT)]);
?>
