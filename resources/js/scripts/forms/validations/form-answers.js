/*=========================================================================================
  File Name: form-validation.js
  Description: jquery bootstrap validation js
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: PIXINVENT
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(function () {
  'use strict';
  var jqForm = $('#answers-form');


  // jQuery Validation
  // --------------------------------------------------------------------
  if (jqForm.length) {
    jqForm.validate({
      rules: {
        'answer[]': {
          required: true
        },
      },
      messages: {
        'answer[]': 'Please select one of these options',
      },

      errorPlacement: function (error, element) {
        if (element.is(':radio')) {
          error.insertAfter(element.next());
        }
        else {
          error.insertAfter(element);
        }
      }
    });
  }

});
