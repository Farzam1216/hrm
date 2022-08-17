
$(function () {
    'use strict';
console.log('here');
    var bootstrapForm = $('.needs-validation'),
        jqForm = $('#time-off-type');

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
                'timeOffType': {
                    required: true
                },

            },
            messages: {
                'timeOffType':'Time Off Type Name is required.',
            }
        });
    }
});
