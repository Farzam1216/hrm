$(function () {
    'use strict';
    var jqForm = $('#smtp-details-form');
    // jQuery Validation
    // --------------------------------------------------------------------
    if (jqForm.length) {
      jqForm.validate({
        rules: {
          'mail_address': {
            required: true
          },
          'driver': {
            required: true
          },
          'host': {
            required: true
          },
          'port': {
            required: true
          },
          'username': {
            required: true
          },
          'password': {
            required: true
          },
          'status': {
            required: true
          },
        },
        messages: {
          'mail_address': "This field is required",
          'driver': "This field is required",
          'host': "This field is required",
          'port': "This field is required",
          'username': "This field is required",
          'password': "This field is required",
          'status': "This field is required",
        }
      });
    }
  });