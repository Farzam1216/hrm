$(function () {
    'use strict';
    var lcForm = $('#compensation-change-reason-form');
    if (lcForm.length) {
        lcForm.validate({
        rules: {
            name: {
                required: true
            },
        },
        messages:{
            name: 'Name is required',
        }
      });
    }
});