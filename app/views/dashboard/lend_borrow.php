<!-- app/views/dashboard/lend_borrow.php -->
<?php include VIEW_PATH . '/partials/header.php'; ?>

<main class="lend-borrow-container">
    <?php include VIEW_PATH . '/partials/sidebar.php'; ?>

    <section class="content-area">
        <h1>Lend/Borrow Record</h1>

        <!-- Display messages -->
        <?php if (isset($_SESSION['alert-success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['alert-success']; unset($_SESSION['alert-success']); ?>
            </div>
        <?php elseif (isset($_SESSION['alert-error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['alert-error']; unset($_SESSION['alert-error']); ?>
            </div>
        <?php endif; ?>

        <!-- Open Items -->
        <h2>Open Lend/Borrow</h2>
        <table class="lend-borrow-table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($openItems)): ?>
                    <?php foreach ($openItems as $item): ?>
                        <tr class="<?= $item['type'] === 'lend' ? 'lend-row' : 'borrow-row' ?>">
                            <td>
                                <span class="type-badge <?= $item['type'] === 'lend' ? 'lend-badge' : 'borrow-badge' ?>">
                                    <?= ucfirst($item['type']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>৳<?= number_format($item['amount'], 2) ?></td>
                            <td><?= htmlspecialchars($item['description'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['created_at']) ?></td>
                            <td>
                                <form method="POST" action="<?= BASE_URL ?>app/controllers/LendBorrowController.php?action=close">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="submit" title="Mark as closed" class="check-btn">✔</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No open lend/borrow items.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Closed Items -->
        <details class="closed-section">
            <summary>Closed Items</summary>
            <table class="lend-borrow-table closed">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Closed On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($closedItems)): ?>
                        <?php foreach ($closedItems as $item): ?>
                            <tr class="<?= $item['type'] === 'lend' ? 'lend-row' : 'borrow-row' ?>">
                                <td>
                                    <span class="type-badge <?= $item['type'] === 'lend' ? 'lend-badge' : 'borrow-badge' ?>">
                                        <?= ucfirst($item['type']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td>৳<?= number_format($item['amount'], 2) ?></td>
                                <td><?= htmlspecialchars($item['description'] ?? '') ?></td>
                                <td><?= htmlspecialchars($item['closed_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No closed items yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </details>

        <!-- Add New Entry -->
        <h2>Add New Entry</h2>
        <form method="POST" action="<?= BASE_URL ?>app/controllers/LendBorrowController.php?action=store" class="add-lend-borrow-form">
            <select name="type" required>
                <option value="">Select Type</option>
                <option value="lend">Lend</option>
                <option value="borrow">Borrow</option>
            </select>

            <input type="text" name="name" placeholder="Name" required>
            <input type="number" step="0.01" name="amount" placeholder="Amount" required>
            <input type="text" name="description" placeholder="Description (optional)">
            
            <input type="submit" value="Add Entry">
        </form>
    </section>
</main>

<?php include VIEW_PATH . '/partials/footer.php'; ?>