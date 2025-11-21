<?php
// MySQLi connection config (use with phpMyAdmin-managed MySQL)
// Update these with your local DB credentials
$host = 'localhost';
$db   = 'connexa_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Establish mysqli connection
$mysqli = @new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'DB connection failed', 'details' => $mysqli->connect_error]);
    exit;
}
$mysqli->set_charset($charset);
?>
