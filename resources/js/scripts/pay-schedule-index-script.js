$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    
    $(".dates-preview-table").DataTable({
        "lengthMenu": [ [-1], ["All"] ],
        "ordering": false
    });

    $.each($(".dates"), function(index, field){
        value = field.getAttribute("date");

        $(field).flatpickr({
            dateFormat: "d-m-Y"
        }).setDate(value);
    });
});

function changePayDate(date, date_id, pay_schedule_id)
{
    var data = {
        pay_date: date.value,
        date_id: date_id,
    };

    $.ajax({
        url: '/en/pay-schedule/updatePayDate',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        data: data,
        dataType: 'JSON',
        success: function(data) {
            if (data) {
                $("#success-message"+pay_schedule_id).removeClass("hidden");
                $("#success-message"+pay_schedule_id).addClass("show");
                document.getElementById("date-div"+date_id).innerHTML = '<div class="d-flex"><div class="col-9 p-0 m-0">'+data.pay_date+'</div><div class="col-2 p-0 m-0"><i data-toggle="tooltip" data-original-title="Pay date adjusted manually" data-feather="info"></i></div></div>';
                feather.replace();
                $('[data-toggle="tooltip"]').tooltip();
            } else {
                $("#error-message"+pay_schedule_id).removeClass("hidden");
                $("#error-message"+pay_schedule_id).addClass("show");
            }
        }
    });
}

function showEditField(id)
{
    $(".date-div"+id).addClass("hidden");
    $(".edit-div"+id).removeClass("hidden");
}

function hideEditField(id)
{
    $(".date-div"+id).removeClass("hidden");
    $(".edit-div"+id).addClass("hidden");
}