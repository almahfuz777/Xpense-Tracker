<!-- app/views/partials/sidebar.php -->
<aside class="sidebar">
    <div class="profile-section">
        <div class="profile-pic">
            <img src="<?= BASE_URL ?>public/assets/images/uploads/<?= htmlspecialchars($_SESSION['profile_picture'] ?? 'default_profile.jpg'); ?>" alt="Profile Picture">
        </div>
        <p><?= htmlspecialchars($_SESSION['username'] ?? 'User'); ?></p>
    </div>

    <?php 
    // Get the current page name from the URL
    $currentPage = basename($_SERVER['REQUEST_URI']);
    ?>

    <ul>
        <li>
            <a href="<?= BASE_URL ?>app/controllers/DashboardController.php"
            class="<?= ($currentPage == 'DashboardController.php') ? 'active' : ''; ?>">
                🏠 Dashboard
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>app/controllers/AddTransactionController.php"
            class="<?= ($currentPage == 'AddTransactionController.php') ? 'active' : ''; ?>">
                ➕ Add Transaction
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>app/controllers/TransactionsController.php"
            class="<?= ($currentPage == 'TransactionsController.php') ? 'active' : ''; ?>">
                📂 Transactions
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>app/controllers/AccountsController.php"
            class="<?= ($currentPage == 'AccountsController.php') ? 'active' : ''; ?>">
                🏦 Accounts
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>app/controllers/BudgetController.php"
            class="<?= ($currentPage == 'BudgetController.php') ? 'active' : ''; ?>">
                💰 Budgeting
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>app/controllers/LendBorrowController.php"
            class="<?= ($currentPage == 'LendBorrowController.php') ? 'active' : ''; ?>">
                🔄 Lend/Borrow
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>app/controllers/AnalyticsController.php"
            class="<?= ($currentPage == 'AnalyticsController.php') ? 'active' : ''; ?>">
                📊 Analytics
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>app/controllers/CurrencyConverterController.php"
            class="<?= ($currentPage == 'CurrencyConverterController.php') ? 'active' : ''; ?>">
                💱 Currency Converter
            </a>
        
        </li>
        <li>
            <a href="<?= BASE_URL ?>app/controllers/SettingsController.php"
            class="<?= ($currentPage == 'SettingsController.php') ? 'active' : ''; ?>">
                ⚙️ Settings
            </a>
        </li>
    </ul>
</aside>
