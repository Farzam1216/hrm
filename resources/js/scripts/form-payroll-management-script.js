$("#all_checkbox").on('click', function () {
    if ($(".checkboxes").length) {
        var table =  $('.dt-simple-header').DataTable();
        var rows = table.rows({ 'search': 'applied' }).nodes();

        if ($(this).prop('checked') == true) {
            $('input[type="checkbox"]', rows).prop('checked', true);
            $("#decision-button").removeClass('hidden')
        } else {
            $('input[type="checkbox"]', rows).prop('checked', false);
            $("#decision-button").addClass('hidden')
        }

        var selected_value = '';

        $.each($('input[type="checkbox"]', rows), function() {
            if($(this).prop('checked') == true) {
                ids = $("#ids").val();
                if (selected_value == '') {
                    selected_value = $(this).val();
                } else {
                    selected_value = selected_value+','+$(this).val();
                }
            }
        });

        $("#ids").val(selected_value);
    }
});

function checkboxClick() {
    var table =  $('.dt-simple-header').DataTable();
    var rows = table.rows({ 'search': 'applied' }).nodes();
    var selected_checkboxes = 0;
    var selected_value = '';

    $.each($('input[type="checkbox"]', rows), function() {
        if($(this).prop('checked') == true) {
            $("#decision-button").removeClass('hidden')
            ids = $("#ids").val();

            if (selected_value == '') {
                selected_value = $(this).val();
            } else {
                selected_value = selected_value+','+$(this).val();
            }

            selected_checkboxes = ++selected_checkboxes; 
        }
    });
    
    if ($('input[type="checkbox"]', rows).length == selected_checkboxes) {
        $("#all_checkbox").prop('checked', true);
    }
    
    if ($('input[type="checkbox"]', rows).length > selected_checkboxes) {
        $("#all_checkbox").prop('checked', false);
    }
    
    if (selected_value == '') {
        $("#all_checkbox").prop('checked', false);
        $("#decision-button").addClass('hidden');
    }

    $("#ids").val(selected_value);
}
