$(function () {
    'use strict';
    var lcForm = $('#document-form');
    if (lcForm.length) {
        lcForm.validate({
        rules: {
            'document_name': {
            required: true
            },
            document: {
                required: true,
                accept: "docx|doc|pdf",
                },
            'status': {
            required: true,
            },
        },
        messages:{
            'document_name': 'Please Enter Document Name',
            document: "Please Upload .docx, .doc or pdf file",
        }
      });
    }
});