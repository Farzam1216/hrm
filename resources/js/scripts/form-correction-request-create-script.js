function change(e) {
    if ($("#time_in"+$(e).attr('number')).val() || $("#time_out"+$(e).attr('number')).val()) {
        $("#absent"+$(e).attr('number')).addClass("hidden");
        $("#holiday"+$(e).attr('number')).addClass("hidden");
        $("#paid_time_off"+$(e).attr('number')).addClass("hidden");
        $("#leave_without_pay"+$(e).attr('number')).addClass("hidden");

        $("#present"+$(e).attr('number')).removeClass("hidden");
    } else {
        $("#absent"+$(e).attr('number')).removeClass("hidden");
        $("#holiday"+$(e).attr('number')).removeClass("hidden");
        $("#paid_time_off"+$(e).attr('number')).removeClass("hidden");
        $("#leave_without_pay"+$(e).attr('number')).removeClass("hidden");

        $("#present"+$(e).attr('number')).addClass("hidden");
    }
}

// To add and remove anything dynamically on button click //NEW Procedure
$(document).ready(function() 
{
    // Assigning div and button class to variables fr add and remove process
    var entry = $("#total_entries").val();
    var dummy_ids = $("#dummy_ids").val();
    var wrapper = $(".input_fields_wrap");
    var add_button = $(".add_field_button");

    //on add button click
    $(add_button).click(function(e)
    {
        e.preventDefault();

        $("#no_attendance_error").addClass('hidden');
        $("#total_entries").val(++entry);
        $("#dummy_ids").val(++dummy_ids);
        var id = $("#dummy_ids").val();

        // Adding Fields 
        $(wrapper).append(
            '<div class="row">\n'+
                '<a href="#" class="col-12 text-right remove_field" attendance_id="">Remove</a>\n'+

                '<div class="col-md-3 col-6">\n'+
                  '<div class="form-group text-left">\n'+
                    '<label class="form-label">Time In</label>\n'+
                    '<input type="time" class="form-control time_in" name="time_in[]" id="time_in'+id+'" number="'+id+'" onchange="change(this)">\n'+
                    '<span id="error_time_in'+id+'" class="error hidden">Time in should be smaller than time out.</span>\n'+
                  '</div>\n'+
                '</div>\n'+

                '<div class="col-md-3 col-6">\n'+
                  '<div class="form-group text-left">\n'+
                    '<label class="form-label">Time Out</label>\n'+
                    '<input type="time" class="form-control time_out" name="time_out[]" id="time_out'+id+'" number="'+id+'" onchange="change(this)">\n'+
                    '<span id="error_time_out'+id+'" class="error hidden">Time out should be greater than time in.</span>\n'+
                  '</div>\n'+
                '</div>\n'+

                '<div class="col-md-3 col-6">\n'+
                  '<div class="form-group text-left">\n'+
                    '<label class="form-label">Time In Status</label>\n'+
                    '<select class="form-control time_in_status" name="time_in_status[]" id="time_in_status'+id+'" number="'+id+'">\n'+
                      '<option value="none">None</option>\n'+
                      '<option value="Late">Late</option>\n'+
                    '</select>\n'+
                  '</div>\n'+
                '</div>\n'+
                
                '<div class="col-md-3 col-6">\n'+
                  '<div class="form-group text-left">\n'+
                    '<label class="form-label">Attendance Status</label>\n'+
                    '<select class="form-control attendance_status" name="attendance_status[]" id="attendance_status'+id+'" number="'+id+'">\n'+
                      '<option value="">Select Attendance Status</option>\n'+
                      '<option value="Present" id="present'+id+'" class="hidden">Present</option>\n'+
                      '<option value="Absent" id="absent'+id+'">Absent</option>\n'+
                      '<option value="Holiday" id="holiday'+id+'">Holiday</option>\n'+
                      '<option value="Paid Time Off" id="paid_time_off'+id+'">Paid Time Off</option>\n'+
                      '<option value="Leave Without Pay" id="leave_without_pay'+id+'">Leave Without Pay</option>\n'+
                    '</select>\n'+
                    '<span id="error_attendance_status'+id+'" class="error hidden">Attendance status is required</span>\n'+
                  '</div>\n'+
                '</div>\n'+
                
                '<div class="col-md-6 col-10">\n'+
                  '<div class="form-group text-left">\n'+
                    '<label class="form-label">Reason For Leaving</label>\n'+
                    '<input type="text" class="form-control reason_for_leaving" name="reason_for_leaving[]" id="reason_for_leaving'+id+'" number="'+id+'" placeholder="Enter Reason For Leaving">\n'+
                  '</div>\n'+
                '</div>\n'+

                '<div class="col-12 pb-1"><div class="border-bottom"></div></div>\n'+
            '</div>'
        );
    });

    // on remove button click
    $(wrapper).on("click",".remove_field", function(e)
    {
        e.preventDefault();

        $("#total_entries").val(--entry);

        // Deleting Fields 
        $(this).parent('div').remove();
    });

    // on remove button click
    $(".remove_field").on("click", function(e)
    {
        e.preventDefault();

        attendance_id = $(this).attr("attendance_id");
        $("#remove_attendance_div").append('<input type="hidden" name="remove_attendance_id[]" value="'+attendance_id+'">');

        $("#total_entries").val(--entry);

        // Deleting Fields 
        $(this).parent('div').remove();
    });
});

function validate()
{
    check = true;
    total_entries = $("#total_entries").val();
    if (total_entries <= 0) {
        check = false;
        $("#no_attendance_error").removeClass('hidden');
    }

    if (total_entries > 0) {
        for (index = 0; index < total_entries; index++) {
            time_in = $(".time_in")[index];
            time_out = $(".time_out")[index];
            attendance_status = $(".attendance_status")[index];

            if (time_in.value && time_out.value) {
                if (time_out.value < time_in.value) {
                    $("#error_"+$(time_in).attr('id')).removeClass('hidden');
                    document.getElementById("error_"+$(time_in).attr('id')).innerHTML = "Time in should be smaller than time out";
                    $(time_in).addClass('error');
                    $("#error_"+$(time_out).attr('id')).removeClass('hidden');
                    document.getElementById("error_"+$(time_out).attr('id')).innerHTML = "Time out should be greater than time in";
                    $(time_out).addClass('error');
                    check = false;
                }
                if (time_in.value == time_out.value) {
                    $("#error_"+$(time_in).attr('id')).removeClass('hidden');
                    document.getElementById("error_"+$(time_in).attr('id')).innerHTML = "Time in shouldn't be equal to time out";
                    $(time_in).addClass('error');
                    $("#error_"+$(time_out).attr('id')).removeClass('hidden');
                    document.getElementById("error_"+$(time_out).attr('id')).innerHTML = "Time out shouldn't be equal to time in";
                    $(time_out).addClass('error');
                    check = false;
                }
                if (time_out.value > time_in.value) {
                    $("#error_"+$(time_in).attr('id')).addClass('hidden');
                    $(time_in).removeClass('error');
                    $("#error_"+$(time_out).attr('id')).addClass('hidden');
                    $(time_out).removeClass('error');
                }
            }

            if (time_in.value && time_out.value == '') {
                $("#error_"+$(time_out).attr('id')).removeClass('hidden');
                document.getElementById("error_"+$(time_out).attr('id')).innerHTML = "Time out is required";
                $(time_out).addClass('error');
                check = false;
            }

            if (time_in.value == '' && time_out.value) {
                $("#error_"+$(time_in).attr('id')).removeClass('hidden');
                document.getElementById("error_"+$(time_in).attr('id')).innerHTML = "Time in is required";
                $(time_in).addClass('error');
                check = false;
            }

            if (time_in.value && time_out.value == '') {
                $("#error_"+$(time_out).attr('id')).removeClass('hidden');
                document.getElementById("error_"+$(time_out).attr('id')).innerHTML = "Time out is required";
                $(time_out).addClass('error');
                check = false;
            }

            if (attendance_status.value == '') {
                $("#error_"+$(attendance_status).attr('id')).removeClass('hidden');
                $(attendance_status).addClass('error');
                check = false;
            } else {
                $("#error_"+$(attendance_status).attr('id')).addClass('hidden');
                $(attendance_status).removeClass('error');
            }
        }
    }

    if (check == true) {
      $("#request-attendance-correction").submit();
    }
}