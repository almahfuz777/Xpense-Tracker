<!-- app/views/dashboard/accounts.php -->
<?php include VIEW_PATH . '/partials/header.php'; ?>

<main class="accounts-container">
    <?php include VIEW_PATH . '/partials/sidebar.php'; ?>

    <section class="content-area">
        <h1>Manage Accounts</h1>

        <!-- Display Success Message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Account List -->
        <table class="accounts-table">
            <thead>
                <tr>
                    <th>Account Name</th>
                    <th>Balance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($accounts)): ?>
                    <?php foreach ($accounts as $account): ?>
                        <tr>
                            <td><?= htmlspecialchars($account['account_name']); ?></td>
                            <td>৳<?= number_format($account['balance'], 2) ?></td>
                            <td>
                                <?php if (strtolower($account['account_name']) !== 'cash'): ?>
                                    <form action="<?= BASE_URL ?>app/controllers/AccountsController.php?action=delete" method="POST">
                                        <input type="hidden" name="account_id" value="<?= $account['id'] ?>">
                                        <button type="submit" class="delete-btn">🗑 Delete</button>
                                    </form>
                                <?php else: ?>
                                    <span>—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No accounts found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Add New Account Form -->
        <h2>Add New Account</h2>
        <form action="<?= BASE_URL ?>app/controllers/AccountsController.php?action=store" method="POST" class="add-account-form">
            <input type="text" id="account_name" name="account_name" placeholder="Account Name" required>

            <input type="number" id="initial_balance" name="initial_balance" placeholder="Initial Balance" step="0.01" required>

            <input type="submit" value="Add Account">
        </form>
    </section>
</main>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
