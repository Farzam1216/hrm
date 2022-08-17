$(document).ready(function() {
    $('#payroll-table').DataTable();
    getFilteredData();
});

function getFilteredData() 
{
    var month = $("#month").val();
    var year = $("#year").val();
    var id = $("#employee").val();
    var dataSet = [];
    $.ajax({
        url: '/en/filter-payroll-history',
        type: 'get',
        dataType: 'json',
        data: { id: id, month:month, year:year },
        success: function (data) {
            console.log(data)
            $.each(data, function(index, val) {
                let current_datetime =  new Date(val.created_at);
                let year = current_datetime.getFullYear();
                let month =  current_datetime.toLocaleString('default', { month: 'long' });
                let formatted_date =  month + ' ' + year;
                let employeeName = val.employee.full_name;
                let mobileNumber = val.employee.contact_no;
                dataSet[index] = [employeeName, mobileNumber, formatted_date, val.basic_salary,val.home_allowance,val.travel_expense,val.income_tax,val.bonus,val.absent_count,val.deduction,val.custom_deduction,val.net_payable]; 
            });
            $('#payroll-table').DataTable().destroy();
            $('#payroll-table').DataTable( {
                data: dataSet,
                "columnDefs": [
                    { className: "nameValue text-nowrap text-center", "targets": [ 0 ] },
                    { className: "numberValue text-nowrap text-center", "targets": [ 1 ] },
                    { className: "monthValue text-nowrap text-center", "targets": [ 2 ] },
                    { className: "basicSalaryValue text-nowrap text-center", "targets": [ 3 ] },
                    { className: "housingAllowanceValue text-nowrap text-center", "targets": [ 4 ] },
                    { className: "travelingExpanseValue text-nowrap text-center", "targets": [ 5 ] },
                    { className: "incomeTaxValue text-nowrap text-center", "targets": [ 6 ] },
                    { className: "bonusValue text-nowrap text-center", "targets": [ 7 ] },
                    { className: "absentsValue text-nowrap text-center", "targets": [ 8 ] },
                    { className: "deductionValue text-nowrap text-center", "targets": [ 9 ] },
                    { className: "customDeductionValue text-nowrap text-center", "targets": [ 10 ] },
                    { className: "netPayableValue text-nowrap text-center", "targets": [ 11 ] },
                ]
            });
        }, error: function (error) {
          console.log(error);
        }
    });
}