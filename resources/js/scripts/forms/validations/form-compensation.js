
$(function () {
    'use strict';

    var bootstrapForm = $('.needs-validation'),
        jqForm = $('#form-compensation');

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
                'effective_date': {
                    required: true
                },
                'pay_schedule_id': {
                    required: true
                },
                'pay_type': {
                    required: true
                },
                'pay_rate': {
                    required: true
                },
                'change_reason_id': {
                    required: true
                },
                'comment': {
                    required: true
                },

            },
            messages: {
                'effective_date':'Effective date is required.',
                'pay_schedule_id':'Pay schedule is required.',
                'pay_type':'Pay type is required.',
                'pay_rate':'Pay rate is required.',
                'change_reason_id':'Change reason is required.',
                'comment':'Comment is required.',
            }
        });
    }
});
