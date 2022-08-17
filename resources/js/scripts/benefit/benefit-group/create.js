$(document).ready(function () {
    var navListItems = $('div.setup-panel div a'),
        allWells = $('.setup-content'),
        allNextBtn = $('.nextBtn');
    allWells.hide();
    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
            $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-primary').addClass('btn-default');
            $item.addClass('btn-primary');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });
    allNextBtn.click(function () {
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;
        $(".form-group").removeClass("has-error");
        for (var i = 0; i < curInputs.length; i++) {
            if (!curInputs[i].validity.valid) {
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }
        if (isValid)
            nextStepWizard.removeAttr('disabled').trigger('click');
    });

    $('div.setup-panel div a.btn-primary').trigger('click');
});

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

function employeedata() {
    var employees = $('#right-list li').map(function () {
        return {
            employee_id: $(this).attr('value'),
        }
    }).get();
    $('.add_employees_hidden').empty();
    $.each(employees, function (indexInArray, valueOfElement) {
        $('.add_employees_hidden').append(
            '<input class="form-group" value="' + valueOfElement.employee_id + '" name="employees_Id[]" type="hidden">'
        );
    });
}

$('#plan-list li').click(function () {
    var id = $(this).val();
    $.ajax({
        type: "get",
        url: "/getplan",
        data: {
            'id': id
        },
        success: function (benefitPlanArray) {
            let benefitPlan = benefitPlanArray[0];
            displayPlanDetail(benefitPlan);
        }
    });
});
let levelCount = 0;
let coverageCount = 0;

function displayPlanDetail(benefitPlan) {
    let plan_coverages = benefitPlan.plan_coverages;
    let eligibility = "levels[" + levelCount + "][eligibilityType]";
    let deduction_exception = "levels[" + levelCount + "][deduction_exception]";
    let coverage_detail = "levels[" + levelCount + "][coverage_detail]";
    let plan_id = "levels[" + levelCount + "][plan_id]";
    $('.level-section').removeClass('current');
    console.log(benefitPlan.id);
    $('#add-plan').append(
        '<div class="level-section col-md-12 current" level="' + levelCount + '" plan-id="' + benefitPlan.id + '">\n' +
        '<label class="control-label">When will this Benefit Group become eligible for the <span class="text-success">' + benefitPlan.name + '</span> ?</label>\n' +
        '<div class="form-group eligibilityType' + levelCount + ' row">\n' +
        '<select class="col-md-6 form-control eligibilityType_select" name=' + eligibility + ' level="' + levelCount + '">\n' +
        '    <option value="manual" selected>When it is marked manually</option>\n' +
        '    <option value="hire_date" >Immediately upon hire</option>\n' +
        '    <option value="waiting_period" >After a waiting period</option>\n' +
        '    <option value="month_after_waiting_period" >First of the month following a waiting period</option>\n' +
        '</select>\n' +
        '</div>\n' +
        '<div class="form-group deductionExceptionType' + levelCount + '">\n' +
        '    <label class="control-lable">Does this benefit deduction happen every paycheck?</label><br>\n' +
        '    <input onclick="removeDeductionExceptionType(' + levelCount + ')" type="radio" name="' + deduction_exception + '" value="yes" checked level="' + levelCount + '"> Yes (Most common)<br>\n' +
        '    <input name=' + plan_id + ' value="' + benefitPlan.id + '" type="hidden"><input onclick="appendDeductionExceptionType(' + levelCount + ')" type="radio" name="' + deduction_exception + '" value="no" level="' + levelCount + '"> No, it skips some paychecks\n' +
        '</div>\n' +
        '<div class="form-group">\n' +
        '<div class=" card-datatable table-responsive">\n' +
        '<table class="table ">\n' +
        '    <thead>\n' +
        '        <tr>\n' +
        '            <th>Plan ID</th>\n' +
        '            <th>Coverage ID</th>\n' +
        '            <th>Coverage Level</th>\n' +
        '            <th>Employee Cost</th>\n' +
        '            <th>Company Cost</th>\n' +
        '            <th>Total Cost</th>\n' +
        '        </tr>\n' +
        '    </thead>\n' +
        '    <tbody id ="coverage-detail' + levelCount + '" >\n' +
        '    </tbody>\n' +
        '</table>\n' +
        '<button class="btn btn-success float-right remove-policy" id="' + levelCount + '" type="button">Remove!</button>\n' +
        '<br>\n' +
        '<br>\n' +
        '<hr>\n' +
        '</div>\n' +
        '</div>\n' +
        '</div><br><br>'
    );

    $.each(plan_coverages, function (index, value) {
        let selector = '#coverage-detail' + levelCount;
        let coverage_name = "levels[" + levelCount + "][plan][" + coverageCount + "][coverage_name]]";
        if(coverage_name.includes('_')){
            let converageNameWithoutUnderscore = value.coverage_name.replace('_',' ');
            value.coverage_name = converageNameWithoutUnderscore;
        }
        let coverage_id = "levels[" + levelCount + "][plan][" + coverageCount + "][coverage_id]]";
        let employee_cost = "levels[" + levelCount + "][plan][" + coverageCount + "][employee_cost]]";
        let company_cost = "levels[" + levelCount + "][plan][" + coverageCount + "][company_cost]]";
        $(selector).append(
            '<tr>\n' +
            '   <td><input size="1" style="border:0px;" type="text" name=' + plan_id + ' value="' + benefitPlan.id + '" readonly ></td>\n' +
            '   <td><input size="1"  style="border:0px;" type="text" name=' + coverage_id + ' value="' + value.id + '" readonly></td>\n' +
            '   <td><input  style="border:0px;" type="text" name=' + coverage_name + ' value="' + value.coverage_name + '" readonly></td>\n' +
            '   <td><input size="2" type="text" name=' + employee_cost + ' value="0"></td>\n' +
            '   <td><input size="2" type="text" name=' + company_cost + ' value="' + value.total_cost + '"></td>\n' +
            '   <td>' + value.total_cost + '</td>\n' +
            '</tr>'
        );
        coverageCount++;
    });
    levelCount++;
}

$(document).on('click', '.remove-policy', function () {
    let currentLevel = $(this).attr('id');
    $('.level-section[level=' + currentLevel + ']').remove();
});
$(document).on('change', '.eligibilityType_select', function () {
    let level = $(this).attr('level');
    let waitingPeriodType_value = $("option:selected", this).val();
    let waitingPeriodType = "levels[" + level + "][waitingPeriodType]";
    if (waitingPeriodType_value == 'manual') {
        $('.waitingPeriodType_value' + level).remove();
        $('.eligibilityType_select' + level).remove();
    }
    if (waitingPeriodType_value == 'hire_date') {
        $('.waitingPeriodType_value' + level).remove();
        $('.eligibilityType_select' + level).remove();
    }
    if (waitingPeriodType_value == 'waiting_period') {
        $('.waitingPeriodType_value' + level).remove();
        $('.eligibilityType_select' + level).remove();
        $('.eligibilityType' + level).append(
            '<div class="col-md-2 waitingPeriodType_value' + level + '">\n' +
            '    <input type="text" name="levels[' + level + '][waiting_period]" class="form-control">\n' +
            '</div>\n' +
            '<select class="col-md-3 form-control eligibilityType_select' + level + '" name=' + waitingPeriodType + ' id="eligibility' + level + '"  level="' + level + '">\n' +
            '    <option value="days" selected>Days</option>\n' +
            '    <option value="week" >Weeks</option>\n' +
            '    <option value="months" >Months</option>\n' +
            '    <option value="years" >Years</option>\n' +
            '</select>'
        );
    }
    if (waitingPeriodType_value == 'month_after_waiting_period') {
        $('.waitingPeriodType_value' + level).remove();
        $('.eligibilityType_select' + level).remove();
        $('.eligibilityType' + level).append(
            '<div class="col-md-2 waitingPeriodType_value' + level + '">\n' +
            '    <input type="text" name="levels[' + level + '][waiting_period]" class="form-control">\n' +
            '</div>\n' +
            '<select class="col-md-3 form-control eligibilityType_select' + level + '" name=' + waitingPeriodType + ' id="eligibility' + level + '"  level="' + level + '">\n' +
            '    <option value="days" selected>Days</option>\n' +
            '    <option value="week" >Weeks</option>\n' +
            '    <option value="months" >Months</option>\n' +
            '    <option value="years" >Years</option>\n' +
            '</select>'
        );
    }
});

function appendDeductionExceptionType(level) {
    $('#deductionException' + level).remove();
    let deductionExceptionType = "levels[" + level + "][deductionExceptionType]";
    $('.deductionExceptionType' + level).after(
        '<select class="col-md-3 form-control" id="deductionException' + level + '" name=' + deductionExceptionType + ' level="' + level + '">\n' +
        '    <option value="monthly" selected>Monthly</option>\n' +
        '    <option value="quarterly" >Quarterly</option>\n' +
        '    <option value="twice_a_year" >Twice a yearly</option>\n' +
        '    <option value="years" >Yearly</option>\n' +
        '</select>'
    );
}

function removeDeductionExceptionType(level) {
    $('#deductionException' + level).remove();
}


/**
 * initialize addons/provisionings table
 */
