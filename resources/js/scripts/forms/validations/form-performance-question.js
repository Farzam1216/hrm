$(function () {
    'use strict';
    var lcForm = $('#question-form');
    if (lcForm.length) {
        lcForm.validate({
        rules: {
            field_type: {
                required: true
            },
            placement: {
                required: true
            },
            question: {
                required: true
            },
        },
        messages:{
            field_type: 'Field type is required',
            placement: 'Question placement is required',
            question: 'Question is required',
        }
      });
    }
});