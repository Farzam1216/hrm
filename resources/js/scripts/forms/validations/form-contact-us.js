$(function () {
    'use strict';

    var bootstrapForm = $('.needs-validation'),
        jqForm = $('#contact-us');

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
                    required: true
                },
                'email': {
                    required: true,
                    email: true
                },
                'subject': {
                    required: true
                },
                'message': {
                    required: true
                },
                

            },
            messages: {
                'firstname':'First Name is required.',
                'lastname':'Last Name is required.',
                'email':'Email is required.',
                'subject':'Subject is required.',
                'message' :'Message is required,',
            }
        });
    }
});
