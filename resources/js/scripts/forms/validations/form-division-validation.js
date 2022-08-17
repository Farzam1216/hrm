$(function () {
    'use strict';
    var lcForm = $('#division-form');
    if (lcForm.length) {
        lcForm.validate({
        rules: {
            'division_name': {
            required: true
            },
        },
        messages:{
            'division_name': 'Please Enter Division Name',
        }
        });
    }
});