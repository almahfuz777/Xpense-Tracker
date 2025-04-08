// public/assets/js/transactions.js
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("search");
    const filterButtons = document.querySelectorAll(".filter-btn");
    const transactionsBody = document.getElementById("transactions-body");

    const categorySelect = document.getElementById("category");
    const startDateInput = document.getElementById("start-date");
    const endDateInput = document.getElementById("end-date");
    const minAmountInput = document.getElementById("min-amount");
    const maxAmountInput = document.getElementById("max-amount");

    let currentType = "all";

    // Gather all current filters into an object
    function getFilters() {
        return {
            type: currentType,
            search: searchInput.value.trim(),
            category: categorySelect.value,
            startDate: startDateInput.value,
            endDate: endDateInput.value,
            minAmount: minAmountInput.value,
            maxAmount: maxAmountInput.value
        };
    }

    // Render table rows
    function renderTransactions(data) {
        transactionsBody.innerHTML = "";

        if (!data.length) {
            transactionsBody.innerHTML = `<tr><td colspan="6">No transactions found.</td></tr>`;
            return;
        }

        data.forEach(tx => {
            const accountDisplay = tx.transaction_type === "transfer"
                ? `${tx.from_account} → ${tx.to_account}`
                : (tx.account_name ?? "N/A");

            const categoryDisplay = tx.transaction_type === "transfer"
                ? "N/A"
                : (tx.category_name ?? "N/A");

            const sign = tx.transaction_type === "income" ? "+" :
                tx.transaction_type === "expense" ? "-" : "±";

            transactionsBody.innerHTML += `
                <tr class="${tx.transaction_type}">
                    <td>${tx.transaction_time}</td>
                    <td class="${tx.transaction_type}">${tx.transaction_type.charAt(0).toUpperCase() + tx.transaction_type.slice(1)}</td>
                    <td>${categoryDisplay}</td>
                    <td>${tx.description}</td>
                    <td>${accountDisplay}</td>
                    <td class="${tx.transaction_type}">${sign} ৳${parseFloat(tx.amount).toFixed(2)}</td>
                </tr>
            `;
        });
    }

    // Fetch data from backend
    function fetchTransactions() {
        const filters = getFilters();

        const formData = new URLSearchParams(filters).toString();

        fetch(`${BASE_URL}app/controllers/TransactionsController.php?action=fetch`, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: formData
        })
        .then(response => response.json())
        .then(renderTransactions)
        .catch(err => {
            transactionsBody.innerHTML = `<tr><td colspan="6">Error loading transactions.</td></tr>`;
            console.error("Fetch error:", err);
        });
    }

    // Initialize event listeners
    function initFilters() {
        // Type filters
        filterButtons.forEach(btn => {
            btn.addEventListener("click", e => {
                e.preventDefault();
                filterButtons.forEach(b => b.classList.remove("active"));
                btn.classList.add("active");

                currentType = btn.dataset.type;
                fetchTransactions();
            });
        });

        // All inputs trigger re-fetch
        [searchInput, categorySelect, startDateInput, endDateInput, minAmountInput, maxAmountInput]
            .forEach(input => {
                input.addEventListener("input", fetchTransactions);
            });
    }

    // Init
    initFilters();
    fetchTransactions();
});
