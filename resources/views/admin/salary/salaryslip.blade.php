<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>
        {{__('language.Salary')}} {{__('language.Slip')}} | HRM
    </title>
    <style>
        #header {
            text-align: center;
        }

        h1, h3 {
            line-height: 4pt;
        }

        table {
            width: 100%;
            border: 1px solid;
            border-collapse: collapse;
            margin-left: auto;
            margin-right: auto;
            text-align: left;
        }

        td {
            border: 1px solid;
            padding: 2px;
            padding-left: 3px;
        }

        #report {
            width: 50%;
        }
        img.slip-image {
            width: 30%;
        }

    </style>
</head>
<body id="printReport">

<div id="report">
    <div id="header">
        <img class="slip-image" src="{{asset('asset/media/logos/logo-1.png')}}"/>
        <h1>
            <span id="lblCompanyName">{{__('language.Human Resource Management')}}</span>
        </h1>
        <h3>
            <span id="lblAddress">{{__('language.Salary')}} {{__('language.Slip')}}</span>
        </h3>
{{--        <h5 style="line-height: 0">Phone: <span id="lblPhone">92-555-489-6335</span>--}}
{{--            ,--}}
{{--            Fax: <span id="lblFax">92-555-489-6335</span>--}}
{{--        </h5>--}}
{{--        <h5 style="line-height: 0">--}}
{{--            Email: <span id="lblEmail">hrm@gmail.com</span>--}}
{{--        </h5>--}}
        <table id="test">
            <tr>
                <td>
                    <b>{{__('language.Employee')}} ID: </b>
                </td>
                <td>
                    <span id="lblEmployeeID">{{$employee->id}}</span>
                </td>
                <td>
                    <b>{{__('language.Designation')}}: </b>
                </td>
                <td>
                    <span id="lblDesignation">{{$employee->designation}}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <b>{{__('language.Employee')}} {{__('language.Name')}}: </b>
                </td>
                <td>
                    <span id="lblName">{{$employee->firstname}} {{$employee->lastname}}</span>
                </td>
                <td>
                    <b>{{__('language.Department')}}: </b>
                </td>
                <td>
                    <span id="lblDepartment">@if(isset($employee->department->department_name)){{$employee->department->department_name}}@endif</span>
                </td>
            </tr>
            <tr>
                <td>
                    <b>{{__('language.Branch')}}: </b>
                </td>
                <td>
                    <span id="lblBranch">{{$employee->branch->name}}</span>
                </td>
            </tr>
        </table>
        <br/>
        <table>
            <thead>
            <th>{{__('language.Allowances')}}</th>
            <th style="text-align: right">{{__('language.Amount')}}</th>
            </thead>
            <tbody id="tbodyAllowances">

            @foreach($allowanceList as $a=>$al)
                <tr>
                    <td>{{$a}}</td>
                    <td style="text-align: right; width: 50%">{{$al}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <br/>
        <table>
            <thead>
            <th>{{__('language.Deductions')}}</th>
            <th style="text-align: right">{{__('language.Amount')}}</th>
            </thead>
            <tbody id="tbodyDeductions">
            @foreach($deductionList as $d=>$dl)
                <tr>
                    <td>{{$d}}</td>
                    <td style="text-align: right; width: 50%">{{$dl}}</td>
                </tr>
            @endforeach
            <tr>
                <td>{{__('language.Absent')}} {{__('language.Deduction')}}</td>
                <td style="text-align: right; width: 50%">{{round($absentDeduction[$employee->id])}}</td>
            </tr>
            </tbody>
        </table>
        <br/>
        <table>
            <tr>
                <td>
                    <b>
                        {{__('language.Gross')}} {{__('language.Salary')}}:
                    </b>
                </td>
                <td>
                    <span id="lblGrossSalary">{{$basic_salary+$addAllowance}}</span>
                </td>
                <td>
                    <b>{{__('language.Basic Salary')}}: </b>
                </td>
                <td>
                    <span id="lblBasicSalary">{{$basic_salary}}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <b>{{__('language.Net')}} {{__('language.Salary')}}: </b>
                </td>
                <td>
                    <span id="lblNetSalary">{{$netPayables[$employee->id]}}</span>
                </td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
</div>

<a href="#" onclick="printData()">{{__('language.Print')}}</a>

<script src="{{asset('OH/js/jquery.min.js')}}" type="text/javascript"></script>
<script>
    function printData() {
        var w = window.open();
        $('#report').css("width", "100%");
        var printOne = $('html').html();
        w.document.write(printOne);
        w.window.print();
        w.document.close();
        return false;
    }
</script>
</body>
</html>
