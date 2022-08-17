$(function () {
    'use strict';
    var lcForm = $('#employee-document-form');
    if (lcForm.length) {
        lcForm.validate({
        rules: {
            'name': {
            required: true
            },
            file: {
                required: true,
                accept: "docx|doc|pdf",
                },
            'status': {
            required: true,
            },
        },
        messages:{
            'name': 'Please Enter Document Name',
            file: "Please Upload .docx, .doc or pdf file",
        }
      });
    }
});