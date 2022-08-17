$(function () {
    'use strict';
    var jqForm = $('#pay-schedule-form');
    // jQuery Validation
    // --------------------------------------------------------------------
    if (jqForm.length) {
      jqForm.validate({
        rules: {
          'name': {
            required: true
          },
          'frequency': {
            required: true
          },
          'pay_days': {
            required: true
          },
          'exceptional_pay_day': {
            required: true
          },
          'week_day': {
            required: true
          },
          'dates': {
            required: true
          },
          'first_date': {
            required: true
          },
          'second_date': {
            required: true
          },
          'date': {
            required: true
          },
          'first_month': {
            required: true
          },
          'second_month': {
            required: true
          },
          'third_date': {
            required: true
          },
          'third_month': {
            required: true
          },
          'fourth_date': {
            required: true
          },
          'fourth_month': {
            required: true
          },
        },
        messages: {
          'name': "This field is required",
          'frequency': "This field is required",
          'pay_days': "This field is required",
          'exceptional_pay_day': "This field is required",
          'week_day': "This field is required",
          'dates': "This field is required",
          'first_date': "This field is required",
          'second_date': "This field is required",
          'date': "This field is required",
          'first_month': "This field is required",
          'second_month': "This field is required",
          'third_date': "This field is required",
          'third_month': "This field is required",
          'fourth_date': "This field is required",
          'fourth_month': "This field is required",

        }
      });
    }
  });