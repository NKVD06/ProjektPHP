<?php
require_once __DIR__ . '/config/Database.php';

try {
    $db = (new Database())->getConnection();
    
    $username = 'admin';
    $password = password_hash('password', PASSWORD_BCRYPT, ['cost' => 12]);
    
    $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password) ON DUPLICATE KEY UPDATE password = :password2");
    $stmt->execute([
        'username' => $username,
        'password' => $password,
        'password2' => $password
    ]);
    
    echo "<h2>Setup Complete</h2>";
    echo "<p>Admin user created/updated successfully!</p>";
    echo "<p><strong>Username:</strong> admin</p>";
    echo "<p><strong>Password:</strong> password</p>";
    echo "<p><a href='admin/login.php'>Go to Login Page</a></p>";
    echo "<p style='color: red;'><strong>IMPORTANT:</strong> Delete this file (setup.php) immediately after use for security!</p>";
    
} catch (Exception $e) {
    echo "<h2>Setup Failed</h2>";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}