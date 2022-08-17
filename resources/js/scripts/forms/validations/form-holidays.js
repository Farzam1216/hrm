$(function () {
    'use strict';

    var bootstrapForm = $('.needs-validation'),
        jqForm = $('#holidays-form');

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
                'holiday_name': {
                    required: true
                },
                'single_date': {
                    required: true
                },
                'multiple_dates': {
                    required: true
                },
            }
        });
    }
});
