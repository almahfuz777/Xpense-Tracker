<!-- public/index.php - Homepage of XpenseTracker -->
<?php
session_start();                                        // Start the session before any output is sent
require_once __DIR__ . '/../app/config/config.php';     // Include the configuration file
$pageTitle = "Home | XpenseTracker";                    // Set the page title

include __DIR__ . '/../app/views/partials/header.php';
?>

<main>
    <h2>Welcome to XpenseTracker</h2>
    <p>Manage your income, expenses, budgets, and more.</p>
</main>

<?php include __DIR__ . '/../app/views/partials/footer.php'; ?>
