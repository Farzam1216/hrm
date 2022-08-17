
$(function () {
    'use strict';

    var bootstrapForm = $('.needs-validation'),
        jqForm = $('#add-non-employee-form');

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
                'firstname': {
                    required: true
                },
                'lastname': {
                    required: true,
                },
                'official_email': {
                    required: true,
                },


            },
            messages: {
                'firstname':'First name is required.',
                'lastname': 'Last name is required',
                'official_email': 'Email is required',
            }
        });
    }
});
