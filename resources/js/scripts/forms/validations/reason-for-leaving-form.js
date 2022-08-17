$(function () {
    'use strict';

    var bootstrapForm = $('.needs-validation'),
        jqForm = $('#reasonForLeaving');

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
                'reason_for_leaving': {
                    required: true
                },

            },
            messages: {
                'reason_for_leaving':'Reason for leaving is required.',
            }
        });
    }
});
