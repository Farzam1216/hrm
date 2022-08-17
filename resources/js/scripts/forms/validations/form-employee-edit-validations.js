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

  var bootstrapForm = $('.needs-validation'),
    jqForm = $('#edit-employee'),
    emconForm = $('#edit-employee-contact'),
    // jqForm = $('#edit-employee'),
    picker = $('#date_of_birth'),
    dateStart = $('#date_start'),
    dateEnd = $('#date_end'),
    jdPicker = $('#joining_date'),
    edPicker = $('#exit_date'),
    jobedPicker = $('.effective_date'),
    empedPicker = $('#effective_date'),
    // edusdPicker = $('#date_start'),
    // eduedPicker = $('#date_end'),
    visaidPicker = $('#issue_date'),
    visaedPicker = $('#expire_date'),
    createVisaForm = $('#create_visa'),
    createEducationForm = $('#create_education_form'),
    employmentStatusForm = $('#employment_status_form');

  // Picker
  if (picker.length) {
    picker.flatpickr({
      onReady: function (selectedDates, dateStr, instance) {
        if (instance.isMobile) {
          $(instance.mobileInput).attr('step', null);
        }
      }
    });
  }
  if (dateStart.length) {
    dateStart.flatpickr({
      onReady: function (selectedDates, dateStr, instance) {
        if (instance.isMobile) {
          $(instance.mobileInput).attr('step', null);
        }
      }
    });
  }
  if (dateEnd.length) {
    dateEnd.flatpickr({
      onReady: function (selectedDates, dateStr, instance) {
        if (instance.isMobile) {
          $(instance.mobileInput).attr('step', null);
        }
      }
    });
  }

  if (jdPicker.length) {
    jdPicker.flatpickr({
      onReady: function (selectedDates, dateStr, instance) {
        if (instance.isMobile) {
          $(instance.mobileInput).attr('step', null);
        }
      }
    });
  }
  if (edPicker.length) {
    edPicker.flatpickr({
      onReady: function (selectedDates, dateStr, instance) {
        if (instance.isMobile) {
          $(instance.mobileInput).attr('step', null);
        }
      }
    });
  }
  if (jobedPicker.length) {
    jobedPicker.flatpickr({
      onReady: function (selectedDates, dateStr, instance) {
        if (instance.isMobile) {
          $(instance.mobileInput).attr('step', null);
        }
      }
    });
  }
  if (empedPicker.length) {
    empedPicker.flatpickr({
      onReady: function (selectedDates, dateStr, instance) {
        if (instance.isMobile) {
          $(instance.mobileInput).attr('step', null);
        }
      }
    });
  }
  // if (edusdPicker.length) {
  //   edusdPicker.flatpickr({
  //     onReady: function (selectedDates, dateStr, instance) {
  //       if (instance.isMobile) {
  //         $(instance.mobileInput).attr('step', null);
  //       }
  //     }
  //   });
  // }
  // if (eduedPicker.length) {
  //   eduedPicker.flatpickr({
  //     onReady: function (selectedDates, dateStr, instance) {
  //       if (instance.isMobile) {
  //         $(instance.mobileInput).attr('step', null);
  //       }
  //     }
  //   });
  // }
  if (visaidPicker.length) {
    visaidPicker.flatpickr({
      onReady: function (selectedDates, dateStr, instance) {
        if (instance.isMobile) {
          $(instance.mobileInput).attr('step', null);
        }
      }
    });
  }
  if (visaedPicker.length) {
    visaedPicker.flatpickr({
      onReady: function (selectedDates, dateStr, instance) {
        if (instance.isMobile) {
          $(instance.mobileInput).attr('step', null);
        }
      }
    });
  }
  // Bootstrap Validation
  // --------------------------------------------------------------------
  if (bootstrapForm.length) {
    Array.prototype.filter.call(bootstrapForm, function (form) {
      form.addEventListener('submit', function (event) {
        if (form.checkValidity() === false) {
          form.classList.add('invalid');
        }
        form.classList.add('was-validated');
        event.preventDefault();
        // if (inputGroupValidation) {
        //   inputGroupValidation(form);
        // }
      });
      // bootstrapForm.find('input, textarea').on('focusout', function () {
      //   $(this)
      //     .removeClass('is-valid is-invalid')
      //     .addClass(this.checkValidity() ? 'is-valid' : 'is-invalid');
      //   if (inputGroupValidation) {
      //     inputGroupValidation(this);
      //   }
      // });
    });
  }

  // jQuery Validation
  // --------------------------------------------------------------------
  if (employmentStatusForm.length) {
    employmentStatusForm.validate({
      rules: {
        'effective_date': {
          required: true
        },
        'employment_status': {
          required: true
        },
        // 'comment':{
        //     required: true
        // }
      },
      messages: {
        'effective_date': 'Effective date is required',
        'employment_status': 'Employment status is required',
        // 'comment':'Comment in required',
      }
    });
  }

  if (createVisaForm.length) {
    createVisaForm.validate({
      rules: {
        'visa_type_id': {
          required: true
        },
        'country_id': {
          required: true
        },
        'issue_date': {
          required: true
        },
        'expire_date': {
          required: true
        },
      },
      messages: {
        'visa_type_id': 'Visa name is required',
        'country_id': 'Country name is required',
        'issue_date': 'Start date is required',
        'expire_date': 'End date is required',
      }
    });
  }
  if (createEducationForm.length) {
    createEducationForm.validate({
      rules: {
        'institute_name': {
          required: true
        },
        'education_type_id': {
          required: true
        },
        'major': {
          required: true
        },
        'cgpa': {
          required: true
        },
        'date_start': {
          required: true
        },
        'date_end': {
          required: true
        },
      },
      messages: {
        'institute_name': 'Institute name is required',
        'education_type_id': 'Eduaction Type is required',
        'major': 'Major is required',
        'cgpa': 'Cgpa is required',
        'date_start': 'Start date is required',
        'date_end': 'End date is required',
      }
    });
  }
  if (jqForm.length) {
    jqForm.validate({
      rules: {
        'firstname': {
          required: true
        },
        'lastname': {
          required: true,
        },
        'official_email': {
          required: true,
          email: true
        },
        nin: {
          nin: true,
        },
        contact_no: {
          phone_number: true,
        },
        emergency_contact: {
          phone_number: true,
        },
      }
      //   messages: {
      //     'firstname':'The First Name field is required.',
      //     nin: "Enter your firstname",
      //     contact_no: "Enter the SSN in Proper way",
      // }
    });
  }
  if (emconForm.length) {
    emconForm.validate({
      rules: {
        nin: {
          nin: true,
        },
        contact_no: {
          phone_number: true,
        },
        emergency_contact: {
          phone_number: true,
        },
      }
      //   messages: {
      //     'firstname':'The First Name field is required.',
      //     nin: "Enter your firstname",
      //     contact_no: "Enter the SSN in Proper way",
      // }
    });
  }
});
// validation for phone number
$.validator.addMethod("phone_number", function (phone_number, element) {
  phone_number = phone_number.replace(/\s+/g, "");
  return this.optional(element) || phone_number.length >= 9 &&
    phone_number.match(/(\+\d{0,2}-)?[\s.-]?\(?\d{3}\)?[\s.-]?\d{3}?\d{4,5}$/);
}, "Please specify a valid Phone");
//validation for ssn
$.validator.addMethod("nin", function (nin, element) {
  nin = nin.replace(/\s+/g, "");
  return this.optional(element) || nin.length >= 9 &&
    nin.match(/^[a-zA-Z0-9_.-]{8,15}$/);
}, "Please specify a valid NIN");
