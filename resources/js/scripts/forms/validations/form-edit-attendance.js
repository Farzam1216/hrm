$(function () {
    'use strict';

    var bootstrapForm = $('.needs-validation'),
        jqForm = $('#edit-attendance');

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
                'time_in': {
                    required: true
                },
                'time_out': {
                    required: true
                },
                'attendance_status': {
                    required: true
                },
            }
        });
    }
});
