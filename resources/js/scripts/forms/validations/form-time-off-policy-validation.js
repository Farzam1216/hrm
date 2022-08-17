$(function () {
    'use strict';
    var lcForm = $('#time-off-policy-form');
    if (lcForm.length) {
        lcForm.validate({
        rules: {
            'time_off_type': {
            required: true
            },
            'policy_name': {
            required: true
            },
            'first-accrual': {
            required: true
            },
            'carryover-date': {
            required: true
            },
            'carryover-happen': {
            required: true
            },
        },
        messages:{
            'time_off_type': 'Please Select Time Off Type',
            'policy_name': 'Please Enter Policy Name',
            'first-accrual': 'Please Select First Accural',
            'carryover-date': 'Please Select Carryover Date',
            'carryover-happen': 'Please Select Carryover Happen',
        }
      });
    }
});