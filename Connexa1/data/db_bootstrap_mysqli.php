<?php
// Include mysqli connection
require_once __DIR__ . '/config.php';

// Basic CORS for local dev; adjust origin as needed
if (!headers_sent()) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
}
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') { exit; }

// Ensure tables exist
$mysqli->query("CREATE TABLE IF NOT EXISTS clients (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$mysqli->query("CREATE TABLE IF NOT EXISTS projects (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  client_id INT UNSIGNED NOT NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT NULL,
  budget DECIMAL(12,2) NOT NULL DEFAULT 0,
  due_date DATE NOT NULL,
  status ENUM('Not Started','In Progress','Done') NOT NULL DEFAULT 'Not Started',
  hours_spent INT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_projects_client FOREIGN KEY (client_id)
    REFERENCES clients(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

function getOrCreateClientId(mysqli $db, string $clientName): int {
    $id = 0;
    if ($stmt = $db->prepare('SELECT id FROM clients WHERE name = ?')) {
        $stmt->bind_param('s', $clientName);
        $stmt->execute();
        $stmt->bind_result($id);
        if ($stmt->fetch()) { $stmt->close(); return (int)$id; }
        $stmt->close();
    }
    $stmt = $db->prepare('INSERT INTO clients(name) VALUES (?)');
    $stmt->bind_param('s', $clientName);
    $stmt->execute();
    $newId = $db->insert_id;
    $stmt->close();
    return (int)$newId;
}

function loadProject(mysqli $db, int $id): array {
    $stmt = $db->prepare('SELECT p.*, c.name AS client_name FROM projects p JOIN clients c ON p.client_id = c.id WHERE p.id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();
    return $row ?: [];
}

