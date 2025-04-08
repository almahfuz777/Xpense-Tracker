document.addEventListener("DOMContentLoaded", () => {
    const income = window.analyticsData.income;
    const expense = window.analyticsData.expense;
    const categoryData = window.analyticsData.categoryExpenses;
    const cashFlow = window.analyticsData.cashFlow;

    // Chart 1: Income vs Expense
    new Chart(document.getElementById('incomeExpenseChart'), {
        type: 'bar',
        data: {
            labels: ['Income', 'Expense'],
            datasets: [{
                label: 'Amount',
                data: [income, expense],
                backgroundColor: ['#2ecc71', '#e74c3c']
            }]
        }
    });

    // Chart 2: Category-wise Expenses
    const catLabels = categoryData.map(row => row.category_name);
    const catAmounts = categoryData.map(row => parseFloat(row.total));

    new Chart(document.getElementById('categoryExpenseChart'), {
        type: 'pie',
        data: {
            labels: catLabels,
            datasets: [{
                data: catAmounts,
                backgroundColor: [
                    '#3498db', '#9b59b6', '#f1c40f', '#e67e22', '#1abc9c',
                    '#e74c3c', '#34495e', '#95a5a6', '#2ecc71', '#ff6b6b'
                ]
            }]
        }
    });

    // Chart 3: Cash Flow Trend
    const cashDates = [...new Set(cashFlow.map(row => row.date))].sort();
    const incomeMap = {}, expenseMap = {};
    cashFlow.forEach(row => {
        if (row.type === 'income') incomeMap[row.date] = parseFloat(row.amount);
        else expenseMap[row.date] = parseFloat(row.amount);
    });

    const incomeSeries = cashDates.map(date => incomeMap[date] || 0);
    const expenseSeries = cashDates.map(date => expenseMap[date] || 0);

    new Chart(document.getElementById('cashFlowChart'), {
        type: 'line',
        data: {
            labels: cashDates,
            datasets: [
                {
                    label: 'Income',
                    data: incomeSeries,
                    borderColor: '#2ecc71',
                    backgroundColor: 'rgba(46, 204, 113, 0.1)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Expense',
                    data: expenseSeries,
                    borderColor: '#e74c3c',
                    backgroundColor: 'rgba(231, 76, 60, 0.1)',
                    fill: true,
                    tension: 0.3
                }
            ]
        }
    });
});
