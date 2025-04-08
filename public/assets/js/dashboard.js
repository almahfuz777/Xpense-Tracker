// render pie-chart on dashboard
let expenseChart;

document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById("expenseChart").getContext("2d");

    // Initial load
    loadExpenseChart();
});

// Load real expense breakdown chart from backend
function loadExpenseChart(period = "month") {
    const ctx = document.getElementById("expenseChart").getContext("2d");

    fetch(`${BASE_URL}app/controllers/DashboardController.php?action=getExpenseBreakdown`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `period=${period}`
    })
    .then(res => res.json())
    .then(data => {
        const labels = data.map(item => item.category_name);
        const values = data.map(item => parseFloat(item.total));

        const backgroundColors = [
            '#e74c3c', '#f39c12', '#3498db', '#2ecc71', '#9b59b6',
            '#1abc9c', '#34495e', '#e67e22', '#7f8c8d', '#d35400'
        ];

        if (expenseChart) expenseChart.destroy();

        expenseChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Expense Categories',
                    data: values,
                    backgroundColor: backgroundColors.slice(0, values.length)
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    })
    .catch(err => {
        console.error("Error loading expense chart:", err);
    });
}

function addAccount() {
    window.location.href = "<?= BASE_URL ?>app/controllers/AccountsController.php";
}

function changePeriod() {
    const period = document.getElementById("period").value;

    fetch(`${BASE_URL}app/controllers/DashboardController.php?action=getCashFlow`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `period=${period}`
    })
    .then(res => res.json())
    .then(data => {
        document.querySelector(".cash-flow-income .cash-amount").textContent = `৳${parseFloat(data.income).toFixed(2)}`;
        document.querySelector(".cash-flow-expense .cash-amount").textContent = `৳${parseFloat(data.expense).toFixed(2)}`;
        loadExpenseChart(period); // Reload pie chart too
    })
    .catch(err => {
        console.error("Error fetching cash flow:", err);
    });
}
