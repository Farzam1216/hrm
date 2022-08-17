function exportFunction() {
    $('#exportBtn').empty();
    $('#exportBtn').append('<button onclick="tableData()" class="btn buttons-collection btn-outline-secondary" tabindex="0" aria-controls="DataTables_Table_0" type="button" aria-haspopup="true" aria-expanded="false"><span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share font-small-4 mr-50"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>Export CSV</span></button>');
    $('#exportColumns').empty();
    
    var actionsValue = document.getElementsByClassName("actionsValue");
    var checkboxValue =  document.getElementsByClassName("checkboxValue");

    while(actionsValue.length > 0){
        actionsValue[0].parentNode.removeChild(actionsValue[0]);
    }

    while(checkboxValue.length > 0){
        checkboxValue[0].parentNode.removeChild(checkboxValue[0]);
    }
    
    $('#exportColumns').append(
        '<tr class="data text-nowrap" id="data">',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="name"><input type="text" value="Name" class="custom-control-label" id="nameValue" style="width: 100px;"></div></th>',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="number"><input type="text" value="Mobile Number" class="custom-control-label" id="numberValue" style="width: 100px;"></div></th>',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="month"><input type="text" value="Month" class="custom-control-label" id="monthValue" style="width: 100px;"></div></th>',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="basicSalary"><input type="text" value="Basic Salary" class="custom-control-label" id="basicSalaryValue" style="width: 100px;"></div></th>',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="housingAllowance"><input type="text" value="Housing Allowance" class="custom-control-label" id="housingAllowanceValue" style="width: 100px;"></div></th>',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="travelingExpanse"><input type="text" value="Traveling Expanse" class="custom-control-label" id="travelingExpanseValue" style="width: 100px;"></div></th>',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="incomeTax"><input type="text" value="Income Tax" class="custom-control-label" id="incomeTaxValue" style="width: 100px;"></div></th>',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="bonus"><input type="text" value="Bonus" class="custom-control-label" id="bonusValue" style="width: 100px;"></div></th>',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="absents"><input type="text" value="Absents" class="custom-control-label" id="absentsValue" style="width: 100px;"></div></th>',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="deduction"><input type="text" value="Deduction" class="custom-control-label" id="deductionValue" style="width: 100px;"></div></th>',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="customDeduction"><input type="text" value="Custom Deduction" class="custom-control-label" id="customDeductionValue" style="width: 100px;"></div></th>',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="netPayable"><input type="text" value="Net Payable" class="custom-control-label" id="netPayableValue" style="width: 100px;"></div></th>',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="status"><input type="text" value="Status" class="custom-control-label" id="statusValue" style="width: 100px;"></div></th>',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="reason"><input type="text" value="Reason" class="custom-control-label" id="reasonValue" style="width: 100px;"></div></th>',
        '</tr>'
    );
}

function tableData() {
    // Check for no checkbox selected
    if($("#name").prop("checked") == false && $("#number").prop("checked") == false && $("#month").prop("checked") == false && $("#basicSalary").prop("checked") == false && $("#housingAllowance").prop("checked") == false && $("#travelingExpanse").prop("checked") == false && $("#incomeTax").prop("checked") == false && $("#bonus").prop("checked") == false && $("#absents").prop("checked") == false && $("#deduction").prop("checked") == false && $("#customDeduction").prop("checked") == false && $("#netPayable").prop("checked") == false && $("#status").prop("checked") == false && $("#reason").prop("checked") == false)
    {
        // Show error message if no checkbox is selected
        if(!$('#errorMessage').length){
            $('.div').append('<h1 id="errorMessage"><span class="error">Please select atleast one column.</span></h1>'); 
        }                    
    } else {
        $("#errorMessage").addClass("hidden");

        var name ="";
        var number ="";
        var month ="";
        var basicSalary ="";
        var housingAllowance ="";
        var travelingExpanse ="";
        var incomeTax ="";
        var bonus ="";
        var absents ="";
        var deduction ="";
        var customDeduction ="";
        var netPayable ="";
        var reason ="";
        var status ="";
    
        // store field value if it checked
        if ($("#name").prop("checked") == true){
            name =  $('#nameValue').val();
        }
        else
        {
            var nameData = document.getElementsByClassName("nameValue");
            while(nameData.length > 0){
                nameData[0].parentNode.removeChild(nameData[0]);
            }
        }
    
        if ($("#month").prop("checked") == true){
            month =  $('#monthValue').val();
        }
        else
        {
            var monthValue = document.getElementsByClassName("monthValue");
            while(monthValue.length > 0){
                monthValue[0].parentNode.removeChild(monthValue[0]);
            }
        }
    
        if ($("#number").prop("checked") == true){
            number =  $('#numberValue').val();
        }
        else
        {
            var numberValue = document.getElementsByClassName("numberValue");
            while(numberValue.length > 0){
                numberValue[0].parentNode.removeChild(numberValue[0]);
            }
        }
    
        if ($("#basicSalary").prop("checked") == true){
            basicSalary =  $('#basicSalaryValue').val();
        }
        else
        {
            var basicSalaryValue = document.getElementsByClassName("basicSalaryValue");
            while(basicSalaryValue.length > 0){
                basicSalaryValue[0].parentNode.removeChild(basicSalaryValue[0]);
            }
        }
    
        if ($("#housingAllowance").prop("checked") == true){
            housingAllowance =  $('#housingAllowanceValue').val();
        }
        else
        {
            var housingAllowanceValue = document.getElementsByClassName("housingAllowanceValue");
            while(housingAllowanceValue.length > 0){
                housingAllowanceValue[0].parentNode.removeChild(housingAllowanceValue[0]);
            }
        }
    
        if ($("#travelingExpanse").prop("checked") == true){
            travelingExpanse =  $('#travelingExpanseValue').val();
        }
        else
        {
            var travelingExpanseValue = document.getElementsByClassName("travelingExpanseValue");
            while(travelingExpanseValue.length > 0){
                travelingExpanseValue[0].parentNode.removeChild(travelingExpanseValue[0]);
            }
        }
    
        if ($("#incomeTax").prop("checked") == true){
            incomeTax =  $('#incomeTaxValue').val();
        }
        else
        {
            var incomeTaxValue = document.getElementsByClassName("incomeTaxValue");
            while(incomeTaxValue.length > 0){
                incomeTaxValue[0].parentNode.removeChild(incomeTaxValue[0]);
            }
        }
    
        if ($("#bonus").prop("checked") == true){
            bonus =  $('#bonusValue').val();
        }
        else
        {
            var bonusValue = document.getElementsByClassName("bonusValue");
            while(bonusValue.length > 0){
                bonusValue[0].parentNode.removeChild(bonusValue[0]);
            }
        }
    
    
        if ($("#absents").prop("checked") == true){
            absents =  $('#absentsValue').val();
        }
        else
        {
            var absentsValue = document.getElementsByClassName("absentsValue");
            while(absentsValue.length > 0){
                absentsValue[0].parentNode.removeChild(absentsValue[0]);
            }
        }
    
        if ($("#deduction").prop("checked") == true){
            deduction =  $('#deductionValue').val();
        }
        else
        {
            var deductionValue = document.getElementsByClassName("deductionValue");
            while(deductionValue.length > 0){
                deductionValue[0].parentNode.removeChild(deductionValue[0]);
            }
        }
    
        if ($("#customDeduction").prop("checked") == true){
            customDeduction =  $('#customDeductionValue').val();
        }
        else
        {
            var customDeductionValue = document.getElementsByClassName("customDeductionValue");
            while(customDeductionValue.length > 0){
                customDeductionValue[0].parentNode.removeChild(customDeductionValue[0]);
            }
        }
    
        if ($("#netPayable").prop("checked") == true){
            netPayable =  $('#netPayableValue').val();
        }
        else
        {
            var netPayableValue = document.getElementsByClassName("netPayableValue");
            while(netPayableValue.length > 0){
                netPayableValue[0].parentNode.removeChild(netPayableValue[0]);
            }
        }
    
        if ($("#reason").prop("checked") == true){
            console.log('hi');
            reason =  $('#reasonValue').val();
        }
        else
        {
            var reasonValue = document.getElementsByClassName("reasonValue");
            while(reasonValue.length > 0){
                reasonValue[0].parentNode.removeChild(reasonValue[0]);
            }
        }
    
        if ($("#status").prop("checked") == true){
            status =  $('#statusValue').val();
        }
        else
        {
            var statusValue = document.getElementsByClassName("statusValue");
            while(statusValue.length > 0){
                statusValue[0].parentNode.removeChild(statusValue[0]);
            }
        }

        $('#exportColumns').empty(); 
        var nameRow ="";
        var numberRow ="";
        var monthRow ="";
        var basicSalaryRow ="";
        var housingAllowanceRow ="";
        var travelingExpanseRow ="";
        var incomeTaxRow ="";
        var bonusRow ="";
        var absentsRow ="";
        var deductionRow ="";
        var customDeductionRow ="";
        var netPayableRow ="";
        var statusRow ="";
        var reasonRow ="";
        
        if(name != "")
        {
            nameRow = "<th>"+name+"</th>";
        }
        
        if(number != "")
        {
            numberRow = "<th>"+number+"</th>";
        }

        if(month != "")
        {
            monthRow = "<th>"+month+"</th>";
        }

        if(basicSalary != "")
        {
            basicSalaryRow = "<th>"+basicSalary+"</th>";
        }

        if(housingAllowance != "")
        {
            housingAllowanceRow = "<th>"+housingAllowance+"</th>";
        }
        
        if(travelingExpanse != "")
        {
            travelingExpanseRow = "<th>"+travelingExpanse+"</th>";
        }

        if(incomeTax != "")
        {
            incomeTaxRow = "<th>"+incomeTax+"</th>";
        }

        if(bonus != "")
        {
            bonusRow = "<th>"+bonus+"</th>";
        }

        if(absents != "")
        {
            absentsRow = "<th>"+absents+"</th>";
        }

        if(deduction != "")
        {
            deductionRow = "<th>"+deduction+"</th>";
        }

        if(customDeduction != "")
        {
            customDeductionRow = "<th>"+customDeduction+"</th>";
        }
        
        if(netPayable != "")
        {
            netPayableRow = "<th>"+netPayable+"</th>";
        }

        if(status != "")
        {
            statusRow = "<th>"+status+"</th>";
        }

        if(reason != "")
        {
            reasonRow = "<th>"+reason+"</th>";
        }

        $('#exportColumns').append('<tr class="text-nowrap text-center">'+nameRow+' '+numberRow+' '+monthRow+' '+basicSalaryRow+' '+housingAllowanceRow+' '+travelingExpanseRow+' '+incomeTaxRow+' '+bonusRow+' '+absentsRow+' '+deductionRow+' '+customDeductionRow+' '+netPayableRow+' '+statusRow+' '+reasonRow+'</tr>');

        exportCSV();
    }
}

function exportCSV() {

    // Variable to store the final csv data
    var csv_data = [];
    $(".reason_text").removeClass('hidden');

    // Get each row data
    var rows = document.getElementsByTagName('tr');
    for (var i = 0; i < rows.length; i++) {

        if (i > 0) {
            // Get each column data
            var cols = rows[i].querySelectorAll('td');
            console.log(cols.length);
        }

        if (i <= 0) {
            // Get each column data
            var cols = rows[i].querySelectorAll('td,th');
            console.log(cols.length);
        }

        // Stores each csv row data
        var csvrow = [];
        for (var j = 0; j < cols.length; j++) {
            //ignore month data
            // Get the text data of each cell
            // of a row and push it to csvrow
            if (cols[j].innerText != "No data available in table") {
                csvrow.push(cols[j].innerText);
            }
        }
        // Combine each column value with comma
        csv_data.push(csvrow.join(","));
    }

    // Combine each row data with new line character
    csv_data = csv_data.join('\n');

    // Call this function to download csv file 
    downloadCSVFile(csv_data);
}

function downloadCSVFile(csv_data) {
    var today = new Date();
    var timeStamp = today.getFullYear() +'-'+ (today.getMonth()+1) +'-'+ today.getDate() +'T'+ today.getHours() +''+ today.getMinutes();

    // Create CSV file object and feed
    // our csv_data into it
    CSVFile = new Blob([csv_data], {
        type: "text/csv"
    });

    // Create to temporary link to initiate
    // download process
    var temp_link = document.createElement('a');

    // Download csv file
    temp_link.download = "pay-roll "+timeStamp+".csv";
    var url = window.URL.createObjectURL(CSVFile);
    temp_link.href = url;

    // This link should not be displayed
    temp_link.style.display = "none";
    document.body.appendChild(temp_link);

    // Automatically click the link to
    // trigger download
    temp_link.click();
    document.body.removeChild(temp_link);
    deleteData();
    location.reload()
}

function deleteData() {
    $.ajax({
       type:'get',
       url:'/en/payroll-delete',
       success:function(data) {
       }
    });
}
