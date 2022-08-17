$(function () {
    'use strict';
    var jqForm = $('#department-form');
    // jQuery Validation
    // --------------------------------------------------------------------
    if (jqForm.length) {
      jqForm.validate({
        rules: {
          'department_name': {
            required: true
          }
        },
        messages: {
          'department_name': "Please Enter Department Name"
        }
      });
    }
  });