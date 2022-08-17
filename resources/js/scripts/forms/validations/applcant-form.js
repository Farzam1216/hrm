
$(function () {
    'use strict';

    var bootstrapForm = $('.needs-validation'),
        jqForm = $('#applicant-form');

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
                'name': {
                    required: true
                },
                'fname':{
                    required:true
                },
                'email':{
                    required:true
                },
                'avatar':{
                    required:true
                },
                 'position':{
                    required:true
                },

            },
              messages: {
                'name':'Name is required.',
                'fname':'Father Name is required.',
                'email':'Email is required.',
                'avatar':'Avatar is required.',
                'position':'Select Job Position.',
            }
        });
    }
});
