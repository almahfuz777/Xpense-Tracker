<?php include VIEW_PATH . '/partials/header.php'; ?>

<main class="analytics-container">
    <?php include VIEW_PATH . '/partials/sidebar.php'; ?>

    <section class="content-area">
        <h1>Analytics Dashboard</h1>

        <!-- Section: Income vs Expense -->
        <div class="chart-card">
            <h2>Income vs Expense (This Month)</h2>
            <canvas id="incomeExpenseChart"></canvas>
        </div>

        <!-- Section: Cash Flow Trend -->
        <div class="chart-card">
            <h2>Cash Flow (Last 30 Days)</h2>
            <canvas id="cashFlowChart"></canvas>
        </div>

        <!-- Section: Category-wise Expense -->
        <div class="chart-card">
            <h2>Category-wise Expenses</h2>
            <canvas id="categoryExpenseChart"></canvas>
        </div>
    </section>
</main>

<script>
    window.analyticsData = {
        income: <?= json_encode($income) ?>,
        expense: <?= json_encode($expense) ?>,
        categoryExpenses: <?= json_encode($categoryExpenses) ?>,
        cashFlow: <?= json_encode($cashFlow) ?>
    };
</script>

<script src="<?= BASE_URL ?>public/assets/js/jsdeliver-chart.umd.min.js"></script>
<script src="<?= BASE_URL ?>public/assets/js/analytics.js"></script>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
