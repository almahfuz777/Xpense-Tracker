<!-- app/views/partials/header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= BASE_URL; ?>">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Xpense Tracker'; ?></title>

    <!-- Global Styles -->
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/style.css">
    
    <!-- Page-Specific CSS -->
    <?php if (isset($page) && file_exists(PUBLIC_PATH . "/assets/css/{$page}.css")): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/<?= $page ?>.css">
    <?php endif; ?>

    <!-- Sidebar CSS if user is logged in -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/sidebar.css">
    <?php endif; ?>
</head>

<body>
<header>
    <nav>
        <div class="logo">
            <a href="public/">Xpense Tracker</a>
        </div>
        <ul class="nav-links">  
            <?php if (!$isLoggedIn) : ?>
                <li><a href="app/controllers/LoginController.php">Login</a></li>
                <li><a href="app/controllers/SignupController.php">Sign Up</a></li>
            <?php else: ?>
                <li><a href="#">Profile</a></li>
                <li><a href="app/controllers/LogoutController.php">Logout</a></li>        
            <?php endif; ?>
        </ul>
    </nav>
</header>
