<!-- app/views/dashboard.php -->
<?php include VIEW_PATH . '/partials/header.php'; ?>

<main class="dashboard-container">
    <?php include VIEW_PATH . '/partials/sidebar.php'; ?>

    <section class="dashboard-content">
        <!-- Balance Section -->
        <div class="balance-section">
            <div class="stat-box">
                <h3>Total Balance</h3>
                <p>৳5,000</p>
            </div>
            <div class="stat-box">
                <h3>Cash</h3>
                <p>৳2,500</p>
            </div>
            <div class="stat-box">
                <h3>Bank</h3>
                <p>৳1,500</p>
            </div>
            <div class="stat-box">
                <h3>bKash</h3>
                <p>৳1,000</p>
            </div>
        </div>

        <!-- Cash Flow Section -->
        <section class="cash-flow">
            <div class="cash-flow-column cash-flow-title">
                <h3>Cash Flow</h3>
                <label for="period">Select Period:</label>
                <select id="period">
                    <option value="week">This Week</option>
                    <option value="month" selected>This Month</option>
                    <option value="year">This Year</option>
                </select>
            </div>

            <div class="cash-flow-column cash-flow-income">
                <h4>Income</h4>
                <p class="cash-amount">৳7,000</p>
            </div>

            <div class="cash-flow-column cash-flow-expense">
                <h4>Expenses</h4>
                <p class="cash-amount">৳3,500</p>
            </div>
        </section>

        <!-- Expense Pie Chart -->
        <canvas id="expenseChart"></canvas>

        <!-- Recent Transactions -->
        <section class="recent-transactions">
            <h3>Recent Transactions</h3>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2024-03-15</td>
                        <td>Salary</td>
                        <td class="income">+ ৳3,000</td>
                    </tr>
                    <tr>
                        <td>2024-03-16</td>
                        <td>Groceries</td>
                        <td class="expense">- ৳150</td>
                    </tr>
                    <tr>
                        <td>2024-03-17</td>
                        <td>Utility Bills</td>
                        <td class="expense">- ৳200</td>
                    </tr>
                </tbody>
            </table>

            <a href="<?= BASE_URL ?>app/controllers/TransactionsController.php" class="see-more">See More Transactions</a>
        </section>

    </section>
</main>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var ctx = document.getElementById('expenseChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Food', 'Groceries', 'Education', 'Bills', 'Others'],
        datasets: [{
            label: 'Expense Categories',
            data: [500, 600, 750, 400, 650],
            backgroundColor: ['#e74c3c', '#f39c12', '#3498db', '#2ecc71', '#9b59b6']
        }]
    }
});
</script>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
