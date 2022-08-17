$(function () {
    'use strict';

    var bootstrapForm = $('.needs-validation'),
        jqForm = $('#work-schedule');

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
                'title': {
                    required: true
                },
                'start_time': {
                    required: true
                },
                'flex_time_in': {
                    required: true
                },
                'flex_time_break': {
                    required: true
                },
                'break_time': {
                    required: true
                },
                'back_time': {
                    required: true
                },
                'end_time': {
                    required: true
                },
            }
        });
    }
});
