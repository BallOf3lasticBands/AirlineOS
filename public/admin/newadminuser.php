<?php
require_once __DIR__ . '/../../includes/config.php';

$email = 'admin@airlineos.nl';
$password = 'Welkom01';
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $db_connect->prepare('INSERT INTO users (email, password_hash, role) VALUES (?, ?, ?)');
if ($stmt === false) {
    http_response_code(500);
    die('Database prepare failed: ' . htmlspecialchars($db_connect->error));
}

$role = 'admin';
$stmt->bind_param('sss', $email, $passwordHash, $role);

if (! $stmt->execute()) {
    if ($db_connect->errno === 1062) {
        http_response_code(409);
        die('User already exists: ' . htmlspecialchars($email));
    }

    http_response_code(500);
    die('Database execute failed: ' . htmlspecialchars($stmt->error));
}

echo 'Admin user created for ' . htmlspecialchars($email) . '.';
$stmt->close();
?>