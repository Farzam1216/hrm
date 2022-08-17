$(function () {
    'use strict';
    var lcForm = $('#form');
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