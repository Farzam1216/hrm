
$(function () {
    'use strict';

    var bootstrapForm = $('.needs-validation'),
        jqForm = $('#email_type');

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
                'template_name': {
                    required: true
                },
                'subject' :{
                    required:true
                },
                'message' :{
                    required:true
                },
                

            },
            messages: {
                'template_name':'Template Name is required.',
                'subject' : 'Subject is required',
                'message':'Message Is Required'
            }
        });
    }
});
