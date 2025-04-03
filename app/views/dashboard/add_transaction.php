<!-- app/views/dashboard/add_transaction.php -->
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

<main class="add-transaction-container">
    <?php include VIEW_PATH . '/partials/sidebar.php'; ?>

    <section class="content-area">
        <div class="transaction-tabs">
            <button class="tab-link active" data-form="income-form">➕ Income</button>
            <button class="tab-link" data-form="expense-form">➖ Expense</button>
            <button class="tab-link" data-form="transfer-form">🔄 Transfer</button>
        </div>

        <!-- Transaction Forms -->
        <div class="transaction-forms">
            <?php include VIEW_PATH . '/partials/transactions/income_form.php'; ?>
            <?php include VIEW_PATH . '/partials/transactions/expense_form.php'; ?>
            <?php include VIEW_PATH . '/partials/transactions/transfer_form.php'; ?>
        </div>

    </section>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tabs = document.querySelectorAll(".tab-link");
        const forms = document.querySelectorAll(".transaction-form");

        tabs.forEach(tab => {
            tab.addEventListener("click", function () {
                // Remove active class from all tabs & forms
                tabs.forEach(t => t.classList.remove("active"));
                forms.forEach(f => f.classList.remove("active-form"));

                // Add active class to selected tab & form
                this.classList.add("active");
                document.getElementById(this.dataset.form).classList.add("active-form");
            });
        });
    });
</script>

<?php include VIEW_PATH . '/partials/footer.php'; ?>

