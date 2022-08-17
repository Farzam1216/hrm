$(function () {
    'use strict';
    var lcForm = $('#import-form');
    if (lcForm.length) {
        lcForm.validate({
        rules: {
            file: {
                required: true,
                accept: "xlsx",
            },
        },
        messages:{
            file: "Please upload excel file",
        }
      });
    }
});