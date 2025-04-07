// public/assets/js/currency_converter.js
function convertCurrency() {
    const baseCurrency = document.getElementById("base-currency").value;
    const targetCurrency = document.getElementById("target-currency").value;
    const amount = parseFloat(document.getElementById("amount").value);
    const resultEl = document.getElementById("conversion-result");
    const loadingEl = document.getElementById("conversion-loading");

    if (isNaN(amount) || amount <= 0) {
        alert("Please enter a valid amount greater than 0.");
        return;
    }

    // Show loading, hide result
    loadingEl.style.display = 'block';
    resultEl.innerHTML = '';

    // Prepare encoded query parameters
    const params = new URLSearchParams({
        action: 'convert',
        base: baseCurrency,
        target: targetCurrency,
        amount: amount
    });

    // Fetch conversion result from controller
    fetch(`${BASE_URL}app/controllers/CurrencyConverterController.php?${params}`)
    .then(response => response.json())
        .then(data => {
            loadingEl.style.display = 'none';   // Hide loading

            if (data.error) {
                resultEl.innerHTML = `<span class="error">${data.error}</span>`;
            } else {
                resultEl.innerHTML = `
                    <strong>${amount} ${baseCurrency}</strong> = 
                    <strong>${data.converted_amount} ${data.target_currency}</strong> 
                    <br><small>(Rate: ${data.rate})</small>
                `;
            }
        })
        .catch(error => {
            console.error("Currency conversion error:", error);
            loadingEl.style.display = 'none';
            resultEl.innerHTML = `<span class="error">Failed to convert currency.</span>`;
        });
}
