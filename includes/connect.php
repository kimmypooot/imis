<?php
// includes/connect.php

$servername = 'localhost';
$username = 'u390694310_cscro8';
$password = 'civilService@ro08';
$dbname = 'u390694310_cscro8';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Better performance + security
} catch (PDOException $e) {
    error_log("DB Connection Error: " . $e->getMessage());
    die("Database connection failed.");
}
