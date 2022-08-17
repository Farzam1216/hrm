//toggle active class in list
$(document).on('click', '.list-group-item', function () {
    $(this).toggleClass("active");
});

//Move active employee from left to right card and remove active class
$(document).on('click', '#add-employee', function () {
    $('#right-list').append($('#left-list .active ').removeClass('active'));
});
//Move active employee from right to left card and remove active class
$(document).on('click', '#remove-employee', function () {
    $('#left-list').append($('#right-list .active').removeClass('active'));
});

function employeedata () {
    var employees = $('#right-list li').map(function() {
        return {
            employee_id : $(this).attr('value'),
        }
    }).get();
    $('.add_employees_hidden').empty();
    $.each(employees, function (indexInArray, valueOfElement) {
        $('.add_employees_hidden').append(
            '<input class="form-group" value="'+valueOfElement.employee_id+'" name="employees_Id[]" type="hidden">'
        );
    });
}
$('#plan-list li').click(function () {
    var id =  $(this).val();
    $.ajax({
        type: "get",
        url: "/getplan",
        data: {
            'id':id
        },
        success: function (benefitPlanArray) {
            let benefitPlan = benefitPlanArray[0];
            displayPlanDetail(benefitPlan);
        }
    });
});