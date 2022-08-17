$(function () {
    'use strict';
    var jqForm = $('#designation-form');
    // jQuery Validation
    // --------------------------------------------------------------------
    if (jqForm.length) {
      jqForm.validate({
        rules: {
          'designation_name': {
            required: true
          }
        },
        messages:{
          'designation_name': "Please Enter Designation Name"
        }
      });
    }
  });