$(function () {
    'use strict';
    var lcForm = $('#payroll-decision-form');
    if (lcForm.length) {
        lcForm.validate({
        rules: {
            decision: {
                required: true
            },
            reason: {
                required: true
            },
        },
        messages:{
            decision: 'Decision is required',
            reason: 'Reason is required',
        }
      });
    }
});