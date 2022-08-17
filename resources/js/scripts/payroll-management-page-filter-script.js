function getFilteredData() 
{
    var dataSet = [];
    var employee_id = $("#employee_id").val();
    var pay_schedule_id = $("#pay_schedule").val();

    $.ajax({
        url: '/en/payroll-management/getFilteredPayroll',
        type: 'get',
        dataType: 'json',
        data: { 
            employee_id: employee_id, 
            pay_schedule_id: pay_schedule_id 
        },
        success: function (data) {
            count = 0;
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            $.each(data, function(key, payrolls) {
                $.each(payrolls, function(index, payroll) {
                    date = payroll.month_year.split('-');
                    let year = date[1];
                    let month = date[0];
                    let current_date =  new Date(year+"-"+month+"-01");
                    month = monthNames[current_date.getMonth()];
                    let formatted_date = month + "-" + year;
                    let employeeName = payroll.employee.full_name;

                    if (payroll.status == 'pending' || payroll.status == '') {
                        decisionUrl = '/en/payroll-management/'+payroll.id+'/decision/create';

                        dataSet[count++] = ['<div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input checkboxes" id="customCheck'+count+'" value="'+payroll.id+'" onclick="checkboxClick();"><label class="custom-control-label" for="customCheck'+count+'"></label></div>', employeeName, payroll.employee.contact_no, formatted_date, payroll.basic_salary, payroll.home_allowance, payroll.travel_expanse, payroll.income_tax, payroll.bonus, payroll.absent_count, payroll.deduction, payroll.net_payable, '<div class="badge badge-secondary">Pending</div>', '-', '<a type="button" class="btn btn-sm btn-clean btn-icon btn-icon-md waves-effect waves-light" href="'+decisionUrl+'" data-placement="top" data-toggle="tooltip" data-original-title="Submit Evaluation Decision"><i data-feather="user-check"></i></a> <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete'+payroll.id+'" data-original-title="Delete"> <i data-feather="trash-2"></i> </a>'];
                    }

                    if (payroll.status == 'approved') {
                        dataSet[count++] = ['-', employeeName, payroll.employee.contact_no, formatted_date, payroll.basic_salary, payroll.home_allowance, payroll.travel_expanse, payroll.income_tax, payroll.bonus, payroll.absent_count, payroll.deduction, payroll.net_payable, '<div class="badge badge-success">Approved</div>', '-', '<a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete'+payroll.id+'" data-original-title="Delete"> <i data-feather="trash-2"></i> </a>'];
                    }

                    if (payroll.status == 'rejected') {
                        if (payroll.reason != '') {
                            dataSet[count++] = ['-', employeeName, payroll.employee.contact_no, formatted_date, payroll.basic_salary, payroll.home_allowance, payroll.travel_expanse, payroll.income_tax, payroll.bonus, payroll.absent_count, payroll.deduction, payroll.net_payable, '<div class="badge badge-danger">Rejected</div>', '<div class="hidden reason_text">'+payroll.reason+'</div><a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#reason'+payroll.id+'"><i data-toggle="tooltip"  data-original-title="View Reason" data-feather="eye"></i> </a>', '<a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete'+payroll.id+'" data-original-title="Delete"> <i data-feather="trash-2"></i> </a>'];
                        } else {
                            dataSet[count++] = ['-', employeeName, payroll.employee.contact_no, formatted_date, payroll.basic_salary, payroll.home_allowance, payroll.travel_expanse, payroll.income_tax, payroll.bonus, payroll.absent_count, payroll.deduction, payroll.net_payable, '<div class="badge badge-danger">Rejected</div>', '-', '<a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete'+payroll.id+'" data-original-title="Delete"> <i data-feather="trash-2"></i> </a>'];
                        }
                    }
                });
            });
            console.log(dataSet);

            $('#payroll-table').DataTable().destroy();
            $('#payroll-table').DataTable( {
                data: dataSet,
                "columnDefs": [
                    { className: "checkboxValue text-nowrap", "targets": [ 0 ] },
                    { className: "nameValue text-nowrap text-center", "targets": [ 1 ] },
                    { className: "numberValue text-nowrap text-center", "targets": [ 2 ] },
                    { className: "monthValue text-nowrap text-center", "targets": [ 3 ] },
                    { className: "basicSalaryValue text-nowrap text-center", "targets": [ 4 ] },
                    { className: "housingAllowanceValue text-nowrap text-center", "targets": [ 5 ] },
                    { className: "travelingExpanseValue text-nowrap text-center", "targets": [ 6 ] },
                    { className: "incomeTaxValue text-nowrap text-center", "targets": [ 7 ] },
                    { className: "bonusValue text-nowrap text-center", "targets": [ 8 ] },
                    { className: "absentsValue text-nowrap text-center", "targets": [ 9 ] },
                    { className: "deductionValue text-nowrap text-center", "targets": [ 10 ] },
                    { className: "netPayableValue text-nowrap text-center", "targets": [ 11 ] },
                    { className: "statusValue text-nowrap text-center", "targets": [ 12 ] },
                    { className: "reasonValue text-nowrap text-center", "targets": [ 13 ] },
                    { className: "actionsValue text-nowrap text-center", "targets": [ 14 ] }
                ]
            });
            feather.replace();
        }, error: function (error) {
          console.log(error);
        }
    });
}