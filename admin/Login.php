<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../src/Models/User.php';
require_once __DIR__ . '/../src/Core/Session.php';

$session = new Session();
$error = '';

if ($session->isLoggedIn()) {
    header('Location: countries/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        try {
            $db = (new Database())->getConnection();
            $userModel = new User($db);
            
            $user = $userModel->authenticate($username, $password);
            
            if ($user) {
                $session->set('user_id', $user['id']);
                $session->set('username', $user['username']);
                session_regenerate_id(true);
                header('Location: countries/index.php');
                exit;
            } else {
                $error = 'Invalid username or password';
            }
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $error = 'An error occurred. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Explore World</title>
    <link rel="stylesheet" href="/travel-app/assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        <p class="login-subtitle">Manage Countries Database</p>
        
        <?php if ($error): ?>
            <div class="alert alert-error" role="alert">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="login-form" autocomplete="off">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       required 
                       autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required 
                       autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary btn-full">Login</button>
        </form>
        
        <a href="/travel-app/index.php" class="back-link">← Back to Website</a>
    </div>
</body>
</html>