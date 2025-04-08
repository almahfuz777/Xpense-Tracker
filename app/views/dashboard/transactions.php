<!-- app/views/transactions.php -->
<?php include VIEW_PATH . '/partials/header.php'; ?>

<!-- Display Messages -->
<?php if (isset($_SESSION['alert-success'])) : ?>
    <div class="alert alert-success">
        <?= $_SESSION['alert-success']; ?>
        <?php unset($_SESSION['alert-success']); ?>
    </div>
<?php elseif (isset($_SESSION['alert-error'])) : ?>
    <div class="alert alert-error">
        <?= $_SESSION['alert-error']; ?>
        <?php unset($_SESSION['alert-error']); ?>
    </div>
<?php endif; ?>

<main class="transactions-container">
    <?php include VIEW_PATH . '/partials/sidebar.php'; ?>
    
    <section class="content-area">
        <h1>All Transactions</h1>

        <!-- Filter and Search -->
        <div class="filters">
            <!-- Search input -->
            <input type="text" id="search" placeholder="Search transactions...">

            <!-- Filter buttons -->
            <a href="#" data-type="all" class="filter-btn active">All</a>
            <a href="#" data-type="income" class="filter-btn">Income</a>
            <a href="#" data-type="expense" class="filter-btn">Expense</a>
            <a href="#" data-type="transfer" class="filter-btn">Transfer</a>
        
            <!-- Category -->
            <select id="category">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['id']) ?>">
                        <?= htmlspecialchars($cat['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Date/Amount Range -->
            <div class="new">
                <input type="date" id="start-date" placeholder="Start date">
                <input type="date" id="end-date" placeholder="End date">
                
                <input type="number" id="min-amount" placeholder="Min amount" min="0" step="0.01">
                <input type="number" id="max-amount" placeholder="Max amount" min="0" step="0.01">
            </div>
        </div>

        <!-- Transactions Table -->
        <table class="transactions-table">
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
            <tbody id="transactions-body">
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="6">No transactions found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transactions as $transaction): ?>
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
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination Placeholder -->
        <div class="pagination">
            <button class="prev-page" onclick="prevPage()">« Previous</button>
            <span class="page-number">Page <span id="current-page">1</span></span>
            <button class="next-page" onclick="nextPage()">Next »</button>
        </div>
    </section>
</main>

<script> const BASE_URL = "<?= BASE_URL ?>"; </script>
<script src="<?= BASE_URL ?>public/assets/js/transactions.js"></script>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
