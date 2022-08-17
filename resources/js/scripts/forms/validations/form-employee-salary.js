$(function () {
    'use strict';

    var bootstrapForm = $('.needs-validation'),
        jqForm = $('#edit-employee-salary');

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
                'basic_salary': {
                  required: true,
                },
                'home_allowance': {
                  required: true,
                },
            }
        });
    }
});
