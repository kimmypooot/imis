<?php 
$password = $_POST['password'];
$storedHash = $_POST['hash'];// Example: fetch from DB

echo json_encode(["data" => password_verify($password, $storedHash)]);
?>