<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore the World - Travel Guide</title>
    <link rel="stylesheet" href="/travel-app/assets/css/style.css">
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="/travel-app/index.php" class="logo">Explore World</a>
            <ul class="nav-links">
                <li><a href="/travel-app/index.php">Home</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="/travel-app/admin/countries/index.php">Manage Countries</a></li>
                    <li><a href="/travel-app/admin/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="/travel-app/admin/login.php">Admin Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <main></main>