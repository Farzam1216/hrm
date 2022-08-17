$(function () {
    'use strict';
    var lcForm = $('#evaluation-decision-form');
    if (lcForm.length) {
        lcForm.validate({
        rules: {
            decision: {
                required: true
            },
        },
        messages:{
            decision: 'Decision is required',
        }
      });
    }
});