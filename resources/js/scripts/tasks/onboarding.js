    var filters=[];
    var addEvent=false;
    function addFilters(){
    addEvent=true;
    $('#employees-filter-sidebar').modal('toggle');
}

    $(document).ready(function () {


    $("#due-date-days").on('focus',function (){
        $('#specific-radio').prop('checked', true);
    });

    $("#required-for").on('change', function ()
{

    if($(this).val() == "some")
{
    $("#edit-employees").removeClass('d-none');
    $("#employees-filter-sidebar").modal();
}
    else
{
    $("#edit-employees").addClass('d-none');
    filters=[];
    $('.employees-filter:checkbox').prop('checked', false);
}

})


    $('#employees-filter-sidebar').on('shown.bs.modal', function () {
    filters=[];
    $(".employees-filter:checkbox:checked").each(function(){
    filters.push($(this).attr('id'));
});
    addEvent=false;

    console.log(filters);
})



    $('#employees-filter-sidebar').on('hidden.bs.modal', function () {
    if(!addEvent)
{
    $('.employees-filter:checkbox').prop('checked', false);;
    $.each(filters, function (index, value)
{
    // console.log(value);
    id="#"+value
    $(id).prop('checked','checked');
    // console.log($("#"+value))
})
    // $("#employees-filter-sidebar").modal('toggle');
}
    if ($(".employees-filter:checkbox:checked").length <= 0){
    $("#required-for").val("all")
    $("#required-for").trigger('change')

}
    // $(".employees-filter:checkbox:checked").each(function(){
    //     filters.push($(this).attr('id'));
    // });

    // console.log(filters);
})

    var count = 0;

// document.getElementById('textarea').value = '{{$task->task_description}}';

    $('#add').click(function(e){ //click event on add more fields button having class add_more_button
    e.preventDefault();
    $('#optionaldocument').append('<div class="row"><div class="col-md-4"><div class="custom mb-2 form-group"><div class="custom-file"><input type="file" accept=".pdf,.doc" class="custom-file-input"  name="optionalDocument['+ count + '][document]"/><label class="custom-file-label" for="customFile">Choose file</label><button class="remove_field btn btn-outline-danger btn-sm ml-2 mt-1"><i data-feather="trash-2"></i></button></div></div></div></div>'); //add input field
    //$('#optionaldocument').append('<div class="custom mb-2"><div class="custom-switch-off-danger  "><label for="form-control"> Custom File</label></div><input type="file" accept=".pdf,.doc" class="form-control-file"  name="optionalDocument['+ count + '][document]"/><button class="remove_field btn btn-danger btn-sm ml-2"><i class="fas fa-trash"></i></button></div>'); //add input field

    count++;
});
    $('#optionaldocument').on("click", ".remove_field", function (e) { //user click on remove text links
    e.preventDefault();

    $(this).parents('.custom').remove();
})

    $('.remove_field').on('click',function(){
    var task_id = $(this).attr('id');
    $.ajax({
    type: 'get',
    url: '/en/delete/task_template/document',
    data: {
    'id': task_id
},
    success: function (taskDocumentId) {
    var id = taskDocumentId;
    $('#'+id).parent().remove();
    $('#taskDocumentDeleted').modal('toggle');
}
});
});
});

    function removeFile(file_id)
    {
        $('#file-'+file_id).prop('checked', false);
        $('#file-selected-'+file_id).addClass('d-none');

    }

    $('.company-files').on('click',function(){
    let file_id=$(this).attr('doc-id');
    if(this.checked){
    $('#file-selected-'+file_id).removeClass('d-none');
}else{
    $('#file-selected-'+file_id).addClass('d-none');
}
});