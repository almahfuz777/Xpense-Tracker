<!-- app/views/partials/header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= BASE_URL; ?>">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Xpense Tracker'; ?></title>
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="app/controllers/DashboardController.php">Dashboard</a></li>
            <li><a href="app/controllers/LoginController.php">Login</a></li>
            <li><a href="app/controllers/SignupController.php">Sign Up</a></li>
            <li><a href="app/controllers/DashboardController.php">Profile</a></li>
            <li><a href="app/controllers/LogoutController.php">Logout</a></li>        
        </ul>
    </nav>
</header>

