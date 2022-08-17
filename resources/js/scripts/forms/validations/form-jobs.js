
$(function () {
    'use strict';

    var bootstrapForm = $('.needs-validation'),
        jqForm = $('#jobs');

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
                'designation_id': {
                    required: true
                },
                'job_status': {
                    required: true
                },
                'minimum_experience': {
                    required: true
                },
                'department_id': {
                    required: true
                },
                'hiring_lead_id': {
                    required: true
                },
                'employment_status_id': {
                    required: true
                },
                'location_id': {
                    required: true
                },
                

            },
            messages: {
                'title':'Title is required.',
                'designation_id':'Select Designation.',
                'job_status':'Select Job Status.',
                'minimum_experience':'Select Minimum Experience.',
                'department_id':'Select Department.',
                'hiring_lead_id':'Select Hiring Lead.',
                'employment_status_id':'Select Employment Status.',
                'location_id':'Select Location.',
            }
        });
    }
});
