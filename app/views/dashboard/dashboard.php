<!-- app/views/dashboard/dashboard.php -->
<?php include VIEW_PATH . '/partials/header.php'; ?>

<main class="dashboard-container">
    <?php include VIEW_PATH . '/partials/sidebar.php'; ?>

    <section class="dashboard-content">
        <!-- Balance Section -->
        <div class="balance-section">
            <div class="stat-box">
                <h3>Total Balance</h3>
                <p>৳<?= number_format($totalBalance, 2) ?></p>
            </div>

            <?php if (!empty($accounts)): ?>
                <?php foreach ($accounts as $account): ?>
                    <div class="stat-box">
                        <h3><?= htmlspecialchars($account['account_name']); ?></h3>
                        <p>৳<?= number_format($account['balance'], 2) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No accounts found</p>
            <?php endif; ?>
            <?php if ($noOfAccounts < 4): ?>
                <a href="<?= BASE_URL ?>app/controllers/AccountsController.php"" class="stat-box-link">
                    <div class="stat-box" id="add-account-box">
                        <h3>Add Account</h3>
                        <p>➕</p>
                    </div>
                </a>
            <?php endif; ?>

        </div>

        <!-- Cash Flow Section -->
        <section class="cash-flow">
            <div class="cash-flow-header">
                <h3>Cash Flow</h3>
                <div class="cash-flow-period">
                    <label for="period">Select Period:</label>
                    <select id="period" onchange="changePeriod()">
                        <option value="week" <?= ($selectedPeriod == 'week') ? 'selected' : '' ?>>This Week</option>
                        <option value="month" <?= ($selectedPeriod == 'month') ? 'selected' : '' ?>>This Month</option>
                        <option value="year" <?= ($selectedPeriod == 'year') ? 'selected' : '' ?>>This Year</option>
                    </select>
                </div>

            </div>

            <div class="cash-flow-content">
                <div class="cash-flow-column cash-flow-income">
                    <h4>Income</h4>
                    <p class="cash-amount">৳<?= number_format($income, 2) ?></p>
                </div>

                <div class="cash-flow-column cash-flow-expense">
                    <h4>Expenses</h4>
                    <p class="cash-amount">৳<?= number_format($expenses, 2) ?></p>
                </div>
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
                        <th>Date & Time</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Account</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentTransactions)): ?>
                        <?php foreach ($recentTransactions as $transaction): ?>
                            <tr class="<?= htmlspecialchars($transaction['transaction_type']); ?>">
                            <td><?= htmlspecialchars($transaction['transaction_time']); ?></td>
                            <td class="<?= htmlspecialchars($transaction['transaction_type']); ?>">
                                <?= ucfirst(htmlspecialchars($transaction['transaction_type'])); ?>
                            </td>
                            <td>
                                <?= $transaction['transaction_type'] !== 'transfer' ? htmlspecialchars($transaction['category_name'] ?? 'N/A') : 'N/A'; ?>
                            </td>
                            <td><?= htmlspecialchars($transaction['description']); ?></td>
                            <td>
                                <?php if ($transaction['transaction_type'] === 'transfer') : ?>
                                    <?= htmlspecialchars($transaction['from_account']); ?> → <?= htmlspecialchars($transaction['to_account']); ?>
                                <?php else : ?>
                                    <?= htmlspecialchars($transaction['account_name'] ?? 'N/A'); ?>
                                <?php endif; ?>
                            </td>
                            <td class="<?= htmlspecialchars($transaction['transaction_type']); ?>">
                                <?php if ($transaction['transaction_type'] === 'income') : ?>
                                    + 
                                <?php elseif ($transaction['transaction_type'] === 'expense') : ?>
                                    - 
                                <?php else : // Transfer ?>
                                    ± 
                                <?php endif; ?>
                                ৳<?= number_format($transaction['amount'], 2); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No recent transactions</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <a href="<?= BASE_URL ?>app/controllers/TransactionsController.php" class="see-more">See More Transactions</a>
        </section>

    </section>
</main>

<!-- Load Chart.js -->
<script src="<?= BASE_URL ?>public/assets/js/jsdeliver-chart.umd.min.js"></script>
<!-- Load Dashboard JavaScript -->
<script>const BASE_URL = "<?= BASE_URL ?>";</script>
<script src="<?= BASE_URL ?>public/assets/js/dashboard.js"></script>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
