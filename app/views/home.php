<!-- app/views/home.php  -->
<?php include VIEW_PATH . '/partials/header.php'; ?>

<main class="homepage-container">
    <section class="welcome-banner">
        <h2>Welcome to XpenseTracker</h2>
        <p>Take control of your finances. Track income, expenses, and budgets in one place.</p>

        <?php if (!$isLoggedIn): ?>
            <a href="app/controllers/SignupController.php" class="cta-button btn">Get Started</a>
        <?php else: ?>
            <a href="app/controllers/DashboardController.php" class="cta-button btn">Go to Dashboard</a>
        <?php endif; ?>
    </section>

    <section class="features-section">
        <h2>Why Use Xpense Tracker?</h2>

        <div class="features-container">
            <div class="feature-box">
                <h4>Track Expenses</h4>
                <p>Monitor your spending and stay within budget.</p>
            </div>
            <div class="feature-box">
                <h4>Plan Budgets</h4>
                <p>Set financial goals and manage savings effectively.</p>
            </div>
            <div class="feature-box">
                <h4>View Insights</h4>
                <p>Analyze your income vs. expenses with reports.</p>
            </div>
        </div>
    </section>
</main>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
