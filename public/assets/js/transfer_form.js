document.addEventListener('DOMContentLoaded', function () {
    const fromSelect = document.getElementById('from_account_id');
    const toSelect = document.getElementById('to_account_id');

    fromSelect.addEventListener('change', function () {
        const selectedFrom = this.value;

        // Show all to-account options
        Array.from(toSelect.options).forEach(option => {
            option.hidden = false;
        });

        // Hide the selected from-account in to-account options
        if (selectedFrom) {
            const toOption = toSelect.querySelector(`option[value="${selectedFrom}"]`);
            if (toOption) {
                toOption.hidden = true;

                // Auto-reset to-account if same
                if (toSelect.value === selectedFrom) {
                    toSelect.value = '';
                }
            }
        }
    });
});