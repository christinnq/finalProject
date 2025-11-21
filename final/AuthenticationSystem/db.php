<?php
declare(strict_types=1);

/**
 * Basic PDO connection for the authentication system.
 * Ensure the following table exists:
 *
 * CREATE TABLE users (
 *     id INT AUTO_INCREMENT PRIMARY KEY,
 *     name VARCHAR(120) NOT NULL,
 *     email VARCHAR(190) NOT NULL UNIQUE,
 *     password VARCHAR(255) NOT NULL,
 *     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
 * );
 */

$dbHost = 'localhost';
$dbName = 'auth_system';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    exit('Database connection failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES));
}
