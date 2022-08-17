$('#attendance-summary-table').DataTable({
    "pageLength": 40,
    "lengthMenu": [ [40, 80, -1], [40,80, "All"] ],
    "drawCallback": function( settings )
    {
        feather.replace();
    }
});

$("#save_btn").on('click', function(){
    $("#csv").val('0')
    $("#save-form").submit();
});