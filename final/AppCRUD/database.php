<?php
declare(strict_types=1);

/**
 * Database connection for the AppCRUD demo.
 *
 * SQL setup:
 *
 * CREATE DATABASE IF NOT EXISTS app_crud CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
 * USE app_crud;
 * CREATE TABLE IF NOT EXISTS students (
 *     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *     last_name VARCHAR(80) NOT NULL,
 *     first_name VARCHAR(80) NOT NULL,
 *     address VARCHAR(160) NOT NULL,
 *     email VARCHAR(120) NOT NULL,
 *     birth_date DATE NOT NULL,
 *     gender ENUM('m','f') NOT NULL,
 *     bac_average DECIMAL(4,2) NOT NULL DEFAULT 0,
 *     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
 * );
 */

$dbHost = 'localhost';
$dbName = 'app_crud';
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
