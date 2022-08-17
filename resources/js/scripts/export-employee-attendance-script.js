
function exportAttendance() {
    $('#attendance-table').DataTable().destroy();
    $('#attendance-table').DataTable({
        "pageLength": 1000,
        "lengthMenu": [ [30, 60, 90, -1], [30,60, 90, "All"] ],
        
    });
    var actionData = document.getElementsByClassName("actionData");
    action.remove();
    while(actionData.length > 0){
        actionData[0].parentNode.removeChild(actionData[0]);
    }
    
    $('#exportBtn').empty();
    
    $('#exportBtn').append('<div class="dt-buttons flex-wrap d-inline-flex"><button onclick="tableToCSV()" class="btn buttons-collection btn-outline-secondary" tabindex="0" aria-controls="DataTables_Table_0" type="button" aria-haspopup="true" aria-expanded="false"><span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share font-small-4 mr-50"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg> Export CSV </span></button></div>');

    $('#exportColumns').empty();

    $('#exportColumns').append(
        '<tr class="data" id="data">',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="name"><input type="text" value="NAME" class="custom-control-label" id="nameValue" style="width: 100px;"></div></th>',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="date"><input type="text" value="DATE" class="custom-control-label" id="dateValue" style="width: 100px;"></div></th>',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="timein"><input type="text" value="TIME IN" class="custom-control-label" id="timeinValue" style="width: 100px;"></div></th>',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="timeout"><input type="text" value="TIME OUT" class="custom-control-label" id="timeoutValue" style="width: 100px;"></div></th>',
            '<th><div class="custom-control custom-checkbox"><input type="checkbox" style="margin-right: 5px; margin-top: 7px; margin-left: -30px;" id="status"><input type="text" value="STATUS" class="custom-control-label" id="statusValue" style="width: 100px;"></div></th>',
        '</tr>'
    );

  }

function tableToCSV() {

    //  Check for no selection of fields
    if($("#name").prop("checked") == false && $("#date").prop("checked") == false && $("#timein").prop("checked") == false  && $("#timeout").prop("checked") == false && $("#status").prop("checked") == false)
    {
        // check for field error message already exist
        if(!$('#errorMessage').length){
            $('.div').append('<h1 id="errorMessage"><span class="error">Please select atleast one column.</span></h1>'); 
        }      
    } else {
        $("#errorMessage").addClass("hidden");

        var name ="";
        var date ="";
        var timeIn ="";
        var timeOut ="";
        var status ="";

        // store field value if it checked
        if ($("#name").prop("checked") == true){
            name =  $('#nameValue').val();
        }
        else
        {
            var nameData = document.getElementsByClassName("nameData");
            while(nameData.length > 0){
                nameData[0].parentNode.removeChild(nameData[0]);
            }
        }

        if ($("#date").prop("checked") == true){
            date =  $('#dateValue').val();
        }
        else
        {
            var dateData = document.getElementsByClassName("dateData");
            while(dateData.length > 0){
                dateData[0].parentNode.removeChild(dateData[0]);
            }
        }

        if ($("#timein").prop("checked") == true){
            timeIn =  $('#timeinValue').val();
        }
        else
        {
            var timeinData = document.getElementsByClassName("timeinData");
            while(timeinData.length > 0){
                timeinData[0].parentNode.removeChild(timeinData[0]);
            }
        }

        if ($("#timeout").prop("checked") == true){
            timeOut =  $('#timeoutValue').val();
        }
        else
        {
            var timeoutData = document.getElementsByClassName("timeoutData");
            while(timeoutData.length > 0){
                timeoutData[0].parentNode.removeChild(timeoutData[0]);
            }
        }

        if ($("#status").prop("checked") == true){
            status =  $('#statusValue').val();
        }
        else
        {
            var statusData = document.getElementsByClassName("statusData");
            while(statusData.length > 0){
                statusData[0].parentNode.removeChild(statusData[0]);
            }
        }
            $('#exportColumns').empty(); 
            var nameRow ="";
            var dateRow ="";
            var timeInRow ="";
            var timeOutRow ="";
            var statusRow ="";
            if(name != "")
            {
                nameRow = "<th>"+name+"</th>";
            }
            
            if(date != "")
            {
                dateRow = "<th>"+date+"</th>";
            }

            if(timeIn != "")
            {
                timeInRow = "<th>"+timeIn+"</th>";
            }

            if(timeOut != "")
            {
                timeOutRow = "<th>"+timeOut+"</th>";
            }

            if(status != "")
            {
                statusRow = "<th>"+status+"</th>";
            }
            $('#exportColumns').append('<tr class="text-nowrap">'+nameRow+' '+dateRow+' '+timeInRow+' '+timeOutRow+' '+statusRow+'</tr>');


        // Variable to store the final csv data
        var csv_data = [];

        // Get each row data
        var rows = document.getElementsByTagName('tr');
        for (var i = 0; i < rows.length; i++) {

            var cols = rows[i].querySelectorAll('td,th');

            var csvrow = [];
            for (var j = 0; j < cols.length; j++) {
                    csvrow.push(cols[j].innerHTML);
            }
            csv_data.push(csvrow.join(","));
        }

        // Combine each row data with new line character
        csv_data = csv_data.join('\n');

        // Call this function to download csv file 
        downloadCSVFile(csv_data);   
    }

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
    temp_link.download = "attendance "+timeStamp+".csv";
    var url = window.URL.createObjectURL(CSVFile);
    temp_link.href = url;

    // This link should not be displayed
    temp_link.style.display = "none";
    document.body.appendChild(temp_link);

    // Automatically click the link to
    // trigger download
    temp_link.click();
    document.body.removeChild(temp_link);
    location.reload()
}