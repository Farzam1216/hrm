
$(function () {
    'use strict';

    var bootstrapForm = $('.needs-validation'),
        jqForm = $('#adjust-balance-form');

    // Bootstrap Validation
    // --------------------------------------------------------------------
    if (bootstrapForm.length) {
        Array.prototype.filter.call(bootstrapForm, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    form.classList.add('invalid');
                }
                form.classList.add('was-validated');
                event.preventDefault();
            });
        });
    }

    // jQuery Validation
    // --------------------------------------------------------------------
    if (jqForm.length) {
        jqForm.validate({
            rules: {
                'adjust-hours': {
                    required: true
                },
                'effective-date': {
                    required: true,
                },


            },
            messages: {
                'adjust-hours':'Hours are required.',
                'effective-date': 'Effective date is required',
            }
        });
    }
});
