$(document).ready(function() {
    $('#attendance-table').DataTable();
    getFilteredData();
});

function getFilteredData() 
{
    var month = $("#month").val();
    var year = $("#year").val();
    var id = $("#employee").val();
    var dataSet = [];
    $.ajax({
        url: '/en/getAttendance',
        type: 'get',
        dataType: 'json',
        data: { id: id, month:month, year:year },
        success: function (data) {
            console.log(data)
            $.each(data, function(index, val) {
                let current_datetime =  new Date(val.created_at);
                let year = current_datetime.getFullYear();
                let month = current_datetime.getMonth() + 1;
                let date = current_datetime.getDate();
                let formatted_date = date + "/" + month + "/" + year
                let reason = "";
                let editUrl = '/en/attendance-management/'+val.id+'/edit';
                let historyUrl = '/en/attendance-management/'+val.id+'/history';
                let employeeName = val.employee.full_name;
                dataSet[index] = [employeeName, formatted_date, val.time_in, val.time_out, val.attendance_status, '<div class="btn-group actionData "><a class="btn btn-sm dropdown-toggle hide-arrow  waves-effect waves-float waves-light" data-toggle="dropdown" aria-expanded="false"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical font-small-4"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg></a><div class="dropdown-menu dropdown-menu-right" style=""><a href="'+editUrl+'" class="dropdown-item text-left">Update</a><a href="'+historyUrl+'" class="dropdown-item text-left">History</a></div></div>']; 
            });
            $('#attendance-table').DataTable().destroy();
            $('#attendance-table').DataTable( {
                data: dataSet,
                "columnDefs": [
                    { className: "text-nowrap nameData", "targets": [ 0 ] },
                    { className: "text-nowrap dateData", "targets": [ 1 ] },
                    { className: "text-nowrap timeinData", "targets": [ 2 ] },
                    { className: "text-nowrap timeoutData", "targets": [ 3 ] },
                    { className: "text-nowrap statusData", "targets": [ 4 ] },
                ]
            });
        }, error: function (error) {
          console.log(error);
        }
    });
}
