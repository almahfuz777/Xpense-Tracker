<!-- app/views/partials/transactions/expense_form.php -->
<form id="expense-form" class="transaction-form" method="POST" action="<?= BASE_URL ?>app/controllers/AddTransactionController.php?action=store">
    <h2>Add Expense</h2>

    <input type="hidden" name="transaction_type" value="expense">

    <label for="category_id">Category:</label>
    <select id="category_id" name="category_id" required>
        <?php if (!empty($expenseCategories)) : ?>
            <?php foreach ($expenseCategories as $category) : ?>
                <option value="<?= htmlspecialchars($category['id']); ?>">
                    <?= htmlspecialchars($category['category_name']); ?>
                </option>
            <?php endforeach; ?>
        <?php else : ?>
            <option value="">No categories found</option>
        <?php endif; ?>
    </select>

    <label for="description">Description:</label>
    <input type="text" id="description" name="description" placeholder="Enter description">

    <label for="amount">Amount:</label>
    <input type="number" id="amount" name="amount" placeholder="Enter amount" step="0.01" required>

    <label for="account_id">Paid From:</label>
    <select id="account_id" name="account_id" required>
        <?php if (!empty($accounts)) : ?>
            <?php foreach ($accounts as $account) : ?>
                <option value="<?= htmlspecialchars($account['id']); ?>">
                    <?= htmlspecialchars($account['account_name']); ?>
                </option>
            <?php endforeach; ?>
        <?php else : ?>
            <option value="">No account found</option>
        <?php endif; ?>
    </select>

    <label for="datetime">Date & Time:</label>
    <input type="datetime-local" id="datetime" name="datetime" value="<?= date('Y-m-d\TH:i'); ?>" required>

    <input type="submit" value="Add Expense">
</form>
