
$(function () {
    'use strict';

    var bootstrapForm = $('.needs-validation'),
        jqForm = $('#assets-form'),
        assignDate = $('#assign_date'),
        returnDate = $('#return_date');

    // Picker
    if (assignDate.length) {
        assignDate.flatpickr();
    }

    if (returnDate.length) {
        returnDate.flatpickr({
            onReady: function (selectedDates, dateStr, instance) {
                if (instance.isMobile) {
                    $(instance.mobileInput).attr('step', null);
                }
            }
        });
    }

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
                'serial': {
                    required: true
                },
                'asset_description': {
                    required: true,
                },
                'assign_date': {
                    required: true,
                },

            },
              messages: {
                'serial':'Serial is required.',
               'asset_description': 'Description is required',
                  'assign_date': 'Assign date is required',
            }
        });
    }
});
