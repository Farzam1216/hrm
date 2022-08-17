$(function () {
    'use strict';
    var jqForm = $('#correction-request-decision-form');
    // jQuery Validation
    // --------------------------------------------------------------------
    if (jqForm.length) {
      jqForm.validate({
        rules: {
          'decision': {
            required: true
          }
        },
        messages:{
          'decision': "This field is required"
        }
      });
    }
  });