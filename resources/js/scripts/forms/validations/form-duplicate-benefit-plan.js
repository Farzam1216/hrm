$(function () {
    'use strict';
    var lcForm = $('#duplicate-plan');
    if (lcForm.length) {
        lcForm.validate({
            rules: {
                'duplicatePlanName': {
                    required: true
                },
                'date_range': {
                    required: true,
                }
            },
            messages:{
                'duplicatePlanName': 'Please Enter Plan Name',
                'date_range': 'Please Enter Date Range of the plan',
            }
        });
    }
});