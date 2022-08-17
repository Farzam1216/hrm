$(document).ready(function(){
    $("#all_attendance_table").DataTable();

    // Refilter the table
    $(".flatpickr-range").change(function () {
        if (this.value.includes('to')) {
            var data = {
                    date: this.value,
                    id: $("#employee_id").val()
                };
            $.ajax({
                url: '/en/employees/attendance-filter',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: data,
                dataType: 'JSON',
                success: function(data) {
                    var dataSet = [];
                    var i = 0;
                    $.each(data, function(index, attendance) {
                        created_at = attendance.created_at.split('-');
                        created_at = created_at[2]+'-'+created_at[1]+'-'+created_at[0];
                        employee_id = $("#employee_id").val();

                        var correctionUrl = '/en/employees/'+employee_id+'/employee-attendance/'+attendance.created_at+'/correction-requests/create';

                        if (attendance.id != '') {
                            var viewAttendanceUrl = '/en/employees/'+employee_id+'/employee-attendance/'+attendance.created_at;

                            if (attendance.time_in_status == 'Late') {
                                dataSet[i++] = ['<td>'+created_at+'</td>', attendance.time_in+'&nbsp;<div class="avatar bg-danger"><div class="badge">Late</div></div>', attendance.time_out, attendance.reason_for_leaving, attendance.attendance_status, '<a class="btn btn-sm hide-arrow waves-effect waves-float waves-light p-0" href="'+viewAttendanceUrl+'" data-toggle="tooltip" data-original-title="View Attendance"><i data-feather="eye"></i></a> <a class="btn btn-sm hide-arrow waves-effect waves-float waves-light p-0" data-toggle="modal" data-target="#new-comments-modal'+attendance.id+'"><i  data-toggle="tooltip" data-original-title="View Comments" data-feather="message-square"></i></a> <a href="'+correctionUrl+'" class="btn btn-sm hide-arrow waves-effect waves-float waves-light p-0" data-toggle="tooltip" data-original-title="Create Correction Request"><i class="fas fa-file-signature"></i></a>'];
                            } else {
                                dataSet[i++] = ['<td>'+created_at+'</td>', attendance.time_in, attendance.time_out, attendance.reason_for_leaving, attendance.attendance_status, '<a class="btn btn-sm hide-arrow waves-effect waves-float waves-light p-0" href="'+viewAttendanceUrl+'" data-toggle="tooltip" data-original-title="View Attendance"><i data-feather="eye"></i></a> <a class="btn btn-sm hide-arrow waves-effect waves-float waves-light p-0" data-toggle="modal" data-target="#new-comments-modal'+attendance.id+'"><i data-toggle="tooltip" data-original-title="View Comments" data-feather="message-square"></i></a> <a href="'+correctionUrl+'" class="btn btn-sm hide-arrow waves-effect waves-float waves-light p-0" data-toggle="tooltip" data-original-title="Create Correction Request"><i class="fas fa-file-signature"></i></a>'];
                            }
                        } else {
                            if (attendance.time_in_status == 'Late') {
                                dataSet[i++] = ['<td>'+created_at+'</td>', attendance.time_in+'&nbsp;<div class="avatar bg-danger"><div class="badge">Late</div></div>', attendance.time_out, attendance.reason_for_leaving, attendance.attendance_status, '<a class="btn btn-sm hide-arrow waves-effect waves-float waves-light p-0" data-toggle="modal" data-target="#new-comments-modal'+attendance.id+'"><i data-toggle="tooltip" data-original-title="View Comments" data-feather="message-square"></i></a> <a href="'+correctionUrl+'" class="btn btn-sm hide-arrow waves-effect waves-float waves-light p-0" data-toggle="tooltip" data-original-title="Create Correction Request"><i class="fas fa-file-signature"></i></a>'];
                            } else {
                                dataSet[i++] = ['<td>'+created_at+'</td>', attendance.time_in, attendance.time_out, attendance.reason_for_leaving, attendance.attendance_status, '<a class="btn btn-sm hide-arrow waves-effect waves-float waves-light p-0" data-toggle="modal" data-target="#new-comments-modal'+attendance.id+'"><i data-toggle="tooltip" data-original-title="View Comments" data-feather="message-square"></i></a> <a href="'+correctionUrl+'" class="btn btn-sm hide-arrow waves-effect waves-float waves-light p-0" data-toggle="tooltip" data-original-title="Create Correction Request"><i class="fas fa-file-signature"></i></a>'];
                            }
                        }
                    });
                    $('#all_attendance_table').DataTable().destroy();
                    $('#all_attendance_table').DataTable({
                        data: dataSet,
                        "drawCallback": function( settings ) {
                            feather.replace();
                        }
                    });
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    });
});

var StartTime = '{!!$startTime !!}';
var approvalPermission = '{!!$hasApprovalPermission !!}';
var EndTime = '{!!$endTime !!}';
var timePickr = $('.flatpickr-time');
if (StartTime != null) {
    if (timePickr.length) {
        timePickr.flatpickr({
            enableTime: true,
            noCalendar: true,
            minTime: StartTime,
            maxTime: EndTime,
        });
    }
} else {
    if (timePickr.length) {
        timePickr.flatpickr({
            enableTime: true,
            noCalendar: true,

        });
    }
}


function UpdateText(string) {
    if (string == 'Add Clock Out') {
        var employeeID = $('#employeeID').val();
        var csrf_field= $('meta[name="csrf-token"]').attr('content');
        $('#sidebar-content').empty();
        $('.sidebar-title').text(string);
        $('#sidebar-content').append(`<form id="reasonForLeaving" 
                  class="todo-modal needs-validation"
                  method="post">
        <button
                type="button"
                class="close font-large-1 font-weight-normal py-0"
                data-dismiss="modal"
                aria-label="Close"
        >
            ×
        </button>
        <div class="modal-header align-items-center mb-1">
            <h5 class="modal-title sidebar-title">Add Clock Out</h5>
            <div class="todo-item-action d-flex align-items-center justify-content-between ml-auto">


            </div>
        </div>
        <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
            <div class="action-tags">

                <div class="form-group">
                    <label for="fp-time">Reason For Leaving</label>
                    <input type="text" id = "reason_for_leaving" name="reason_for_leaving"
                           class="form-control text-left"
                           placeholder="Reason For leaving" />
                </div>

                <input type="text" name="clock_type" hidden value="OUT">
                <div class=" d-flex flex-sm-row flex-column mt-2 ">
                    <button type="submit"
                            class="btn btn-primary mb-1 mb-sm-0  mr-0 mr-sm-1 waves-effect waves-float waves-light">Add</button>


        <button type="button"
                data-dismiss="modal"
                class="btn btn-inverse">Cancel</button>
                        </div>
                    </div>
            </form>`);

            $('#reasonForLeaving').submit(function(e) {
                
                var reason_for_leaving = $('#reason_for_leaving').val();
                if (reason_for_leaving === '') {
                    e.preventDefault();
                  $('#reason_for_leaving').after('<span class="error">Reason for leaving is required.</span>');
                }
                else{
                    $('#reason_for_leaving').after('<input type="hidden" name="_token" value="'+csrf_field+'">')
                    var action_src = "en/employees/"+employeeID+"/employee-attendance";
                    var your_form = document.getElementById('reason_for_leaving');
                    your_form.action = action_src ;
                    $("#reasonForLeaving").submit();
                }
              });


    } else {

        $('#sidebar-content').empty();

        $('#sidebar-content').append(`<form id="form-modal-todo"
                  action="@if(isset($locale)){{url($locale.'/employee-attendance-approval/')}}
        @else {{url('en/employee-attendance-approval/')}} @endif"
                  class="todo-modal needs-validation"
                  method="post">
                @csrf
        <button
                type="button"
                class="close font-large-1 font-weight-normal py-0"
                data-dismiss="modal"
                aria-label="Close"
        >
            ×
        </button>
        <div class="modal-header align-items-center mb-1">
            <h5 class="modal-title sidebar-title">Approval Status</h5>
                <div class="todo-item-action d-flex align-items-center justify-content-between ml-auto">


                </div>
            </div>
            <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                <div class="action-tags">
                    <div class="form-group">
                        <label class="control-label">Approval Decision</label><span class="text-danger"> *</span>
                        <select name="status" class="form-control">
                            <option value="approve" >Approve</option>
                            <option value="reject" >Rejected</option>
                            </select>
                    </div>

                    <div class="form-group">
                        <label for="fp-time">Comment</label>
                        <textarea type="text" name="comment"
                               class="form-control text-left"
                               placeholder="Comments"></textarea>
                    </div>

    <input type="text" name="month" hidden value="{{\Carbon\Carbon::now()->format('F')}}-{{\Carbon\Carbon::now()->format('Y')}}">
                        <input type="text" name="employee_id" hidden value="{{$employeeID}}">
                        <div class=" d-flex flex-sm-row flex-column mt-2 ">
                            <button type="submit"
                                    class="btn btn-primary mb-1 mb-sm-0  mr-0 mr-sm-1 waves-effect waves-float waves-light">Add </button>

                            <button type="button"
                                    data-dismiss="modal"
                                    class="btn btn-inverse">Cancel</button>
                        </div>
                    </div>
            </form>`);

    }
}

