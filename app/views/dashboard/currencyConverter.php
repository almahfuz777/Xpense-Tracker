<!-- app/views/dashboard/currencyConverter.php -->
<?php include VIEW_PATH . '/partials/header.php'; ?>

<main class="currency-converter-container">
    <?php include VIEW_PATH . '/partials/sidebar.php'; ?>

    <section class="content-area">
        <h1>Currency Converter</h1>

        <form class="currency-form" onsubmit="event.preventDefault(); convertCurrency();">
            <label for="base-currency">From:</label>
            <select id="base-currency" required>
                <option value="BDT" selected>BDT (Bangladeshi Taka)</option>
                <option value="USD">USD (US Dollar)</option>
                <option value="EUR">EUR (Euro)</option>
                <option value="GBP">GBP (British Pound)</option>
                <option value="INR">INR (Indian Rupee)</option>
            </select>

            <label for="target-currency">To:</label>
            <select id="target-currency" required>
                <option value="USD" selected>USD (US Dollar)</option>
                <option value="BDT">BDT (Bangladeshi Taka)</option>
                <option value="EUR">EUR (Euro)</option>
                <option value="GBP">GBP (British Pound)</option>
                <option value="INR">INR (Indian Rupee)</option>
            </select>

            <label for="amount">Amount:</label>
            <input type="number" id="amount" placeholder="Enter amount" required min="0.01" step="any">

            <button type="button" onclick="convertCurrency()">Convert</button>
        </form>

        <div class="currency-result">
            <div id="conversion-loading" style="display: none;">🔄 Converting...</div>
            <p id="conversion-result"></p>
        </div>
    </section>
</main>

<script> const BASE_URL = "<?= BASE_URL ?>"; </script>
<script src="<?= BASE_URL ?>public/assets/js/currency_converter.js"></script>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
