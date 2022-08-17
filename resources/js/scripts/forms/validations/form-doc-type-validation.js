$(function () {
    'use strict';
    var lcForm = $('#doc-type-create-form');
    if (lcForm.length) {
        lcForm.validate({
        rules: {
            'doc_type_name': {
            required: true
            },
            
        },
        messages:{
            'doc_type_name': 'Please Enter Document Type Name',
                
        }
      });
    }
});