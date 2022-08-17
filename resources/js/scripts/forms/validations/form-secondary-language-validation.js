$(function () {
    'use strict';
    var jqForm = $('#language-form');
    // jQuery Validation
    // --------------------------------------------------------------------
    if (jqForm.length) {
      jqForm.validate({
        rules: {
          'language_name': {
            required: true
          }
        },
        messages: {
          'language_name': "Please Enter Language Name"
        }
      });
    }
  });