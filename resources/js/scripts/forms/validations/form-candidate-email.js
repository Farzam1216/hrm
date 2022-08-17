
$(function () {
    'use strict';

    var bootstrapForm = $('.needs-validation'),
        jqForm = $('#candidate-email');

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
                'email_template': {
                    required: true
                },
                'subject': {
                    required: true
                },
                'message': {
                    required: true
                },
                

            },
            messages: {
                'email_template':'Select Email Template.',
                'subject':'Subject is Required.',
                'message':'Message is Required.',
            }
        });
    }
});
