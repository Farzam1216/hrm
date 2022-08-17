$(function () {
    'use strict';
    var lcForm = $('#location-create-form');
    if (lcForm.length) {
        lcForm.validate({
        rules: {
            'name': {
            required: true
            },
            'street_1': {
            required: true,
            },
            'city': {
            required: true
            },
            'zip_code': {
            required: true,
            },
            'country': {
            required: true,
            },
        },
        messages:{
            'name': 'Please Enter Location Name',
                'street_1':'Please Enter Location Address',
                'city': 'Please Enter City Name',
                'zip_code':'Please Enter Zip Code',
        }
      });
    }
});