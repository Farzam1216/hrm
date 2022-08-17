$(function () {
    'use strict';
    var lcForm = $('#handbook-form');
    if (lcForm.length) {
        lcForm.validate({
        rules: {
            chapter_name: {
                required: true
            },
            page_title: {
                required: true,
            },
            description: {
                required: true,
            },
        },
        messages:{
            chapter_name: 'Chapter name is required',
            page_title: 'Page title is required',
            description: 'Description is required',
        }
      });
    }
});