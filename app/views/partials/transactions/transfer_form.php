<!-- app/views/partials/transactions/transfer_form.php -->
<form id="transfer-form" class="transaction-form" method="POST" action="<?= BASE_URL ?>app/controllers/AddTransactionController.php?action=store">
    <h2>Transfer</h2>

    <input type="hidden" name="transaction_type" value="transfer">

    <label for="from_account_id">From Account:</label>
    <select id="from_account_id" name="from_account_id" required>
        <option value="">-- Select Account --</option>
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

    <label for="to_account_id">To Account:</label>
    <select id="to_account_id" name="to_account_id" required>
        <option value="">-- Select Account --</option>
        <?php if (!empty($accounts)) : ?>
            <?php foreach ($accounts as $account) : ?>
                <option value="<?= htmlspecialchars($account['id']); ?>" data-account="<?= htmlspecialchars($account['id']); ?>">
                <?= htmlspecialchars($account['account_name']); ?>
                </option>
            <?php endforeach; ?>
        <?php else : ?>
            <option value="">No account found</option>
        <?php endif; ?>
    </select>

    <label for="amount">Amount:</label>
    <input type="number" id="amount" name="amount" placeholder="Enter amount" step="0.01" required>

    <label for="datetime">Date & Time:</label>
    <input type="datetime-local" id="datetime" name="datetime" value="<?= date('Y-m-d\TH:i'); ?>" required>

    <label for="description">Description:</label>
    <input type="text" id="description" name="description" placeholder="Enter description">

    <input type="submit" value="Transfer">
</form>

<script src="<?= BASE_URL ?>public/assets/js/transfer_form.js"></script>
