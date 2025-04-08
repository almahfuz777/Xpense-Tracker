// public/assets/js/profile.js
$(document).ready(function () {
    $('.editable-field').each(function () {
        const fieldWrapper = $(this);
        const span = fieldWrapper.find('.field-text');
        const input = fieldWrapper.find('.field-input');
        const editBtn = fieldWrapper.find('.edit-btn');
        const saveBtn = fieldWrapper.find('.save-btn');

        let originalValue = input.val();

        // Enable edit mode
        editBtn.on('click', function (e) {
            e.preventDefault();
            originalValue = input.val(); // store original value
            span.hide();
            input.show().focus();
            editBtn.hide();
            saveBtn.show();
        });

        // Save changes
        saveBtn.on('click', function (e) {
            e.preventDefault();
            const fieldName = input.attr('name');
            const newValue = input.val();

            $.ajax({
                url: 'app/controllers/ProfileController.php?action=updateField',
                type: 'POST',
                data: {
                    field: fieldName,
                    value: newValue
                },
                success: function (response) {
                    try {
                        const res = JSON.parse(response);
                        if (res.success) {
                            span.text(newValue || 'Not set');
                        } else {
                            alert(res.message || 'Update failed.');
                        }
                    } catch (err) {
                        console.error("Invalid JSON response", err);
                        alert("Unexpected error.");
                    }
                    input.hide();
                    editBtn.show();
                    saveBtn.hide();
                    span.show();
                },
                error: function () {
                    alert("Request failed. Please try again.");
                }
            });
        });

        // Enter to save, Esc to cancel
        input.on('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                saveBtn.click();
            } else if (e.key === 'Escape') {
                e.preventDefault();
                input.val(originalValue); // restore original
                input.hide();
                span.show();
                editBtn.show();
                saveBtn.hide();
            }
        });
    });
});
