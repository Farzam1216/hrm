@extends('layouts.admin')
@section('title','Benefit Details')
@section('heading')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"> {{__('language.Benefits')}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('language.People Management')}}</a></li>
                        <li class="breadcrumb-item active">{{__('language.Employee Benefits')}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div> <!-- /.content-header -->
@stop
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <br/>
                    <h4>Benefits Overview</h4>
                    @if(Auth::user()->isAdmin() || isset($permissions['benefitGroup'][$employeeBenefitDetails['employee']->id]['benefitgroup name']))
                        Benefit
                        Group: @if(isset($employeeBenefitDetails['employee']->employeeInBenefitGroup->benefitGroup))
                            <b>{{$employeeBenefitDetails['employee']->employeeInBenefitGroup->benefitGroup->name}} </b>@endif
                    @endif
                    <table class="table">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col">Benefit</th>
                            <th scope="col">Effective</th>
                            <th scope="col">Coverage</th>
                            <th scope="col">Employee Pays</th>
                            @if(Auth::user()->isAdmin() || isset($permissions['benefits'][$employeeBenefitDetails['employee']->id]['employeebenefit company_payment']) || $employeeBenefitDetails['employee']->id != Auth::user()->id)
                                <th scope="col">Company Pays</th>
                            @endif
                            <th scope="col">Frequency</th>
                            @if(Auth::user()->isAdmin() || isset($permissions['benefitPlans'][$employeeBenefitDetails['employee']->id]))
                                <th scope="col"></th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($employeeBenefitDetails))
                            @foreach ($employeeBenefitDetails as $index=>$employeeBenefit)
                                @if($index == "employee")
                                    @continue;
                                @endif
                                @if(Auth::user()->isAdmin() || Auth::user()->id == $employeeBenefitDetails['employee']->id ||
    isset($permissions['benefitPlans'][$employeeBenefitDetails['employee']->id]['benefitplan '.$employeeBenefit['groupPlan']->plans->id]))
                                    <tr>
                                        <td>
                                            {{$employeeBenefit['groupPlan']->plans->name}}
                                            <br>

                                            @if($employeeBenefit['status']->count() >=1)
                                                @php
                                                    $count =0;
                                                    foreach($employeeBenefit['status'] as $key=>$status){
                                                    $statusDetails=json_decode($status->enrollment_status_tracking_field,true);
                                                    if($count == 0){$activeStatus=explode(' ',$statusDetails['event'])[0];
                                                    if($activeStatus == "Will" || $activeStatus == "will")
                                                        {
                                                           echo "<i> (".$statusDetails['event'].")</i>";
                                                           break;
                                                        }
                                                    else
                                                        {
                                                            echo '<i class="text-primary">'.$activeStatus.'</i>';
                                                            $count++;
                                                    continue;
                                                        }}
                                                    if($count == 1){$futureStatus=explode(' ',$statusDetails['event'])[0];
                                                      if($futureStatus == "Will" || $futureStatus == "will")
                                                          {
                                                                  echo "<i> (".$statusDetails['event'].")</i>";
                                                    $count++;
                                                          }
                                                    }
                                                    }
                                                @endphp
                                            @endif

                                        </td>

                                        <td>
                                            {{$employeeBenefit['status']->first()->effective_date}}
                                        </td>
                                        <td>
                                            @if($employeeBenefit['status']->first()->enrollment_status == "enroll")
                                                {{str_replace('_', ' ', $employeeBenefit['employeeBenefit']->employee_benefit_plan_coverage)}}
                                            @endif
                                        </td>
                                        <td>
                                        @if($employeeBenefit['status']->first()->enrollment_status == "enroll")
                                            @php
                                                $json_array = $employeeBenefit['employeeBenefit']->employee_payment;
                                                $employeePay = json_decode($json_array,true);
                                                if(isset($employeePay["employeePaysAmount"]))
                                                {echo $employeePay["employeePaysAmount"];}
                                            @endphp
                                        @endif
                                        @if(Auth::user()->isAdmin() || isset($permissions['benefits'][$employeeBenefitDetails['employee']->id]['employeebenefit company_payment']) || $employeeBenefitDetails['employee']->id != Auth::user()->id)
                                            <td>
                                                @if($employeeBenefit['status']->first()->enrollment_status == "enroll")
                                                    @php
                                                        $json_array = $employeeBenefit['employeeBenefit']->company_payment;
                                                        $companyPay = json_decode($json_array,true);
                                                        if(isset($companyPay["companyPaysAmount"]))
                                                        {echo $companyPay["companyPaysAmount"];}
                                                    @endphp
                                                @endif
                                            </td>
                                        @endif
                                        <td>
                                            {{ucwords(str_replace("_", " ", $employeeBenefit['employeeBenefit']->deduction_frequency))}}
                                        </td>
                                        @if(Auth::user()->isAdmin() || isset($permissions['benefitPlans'][$employeeBenefitDetails['employee']->id]['benefitplan '.$employeeBenefit['groupPlan']->plans->id])
            && $permissions['benefitPlans'][$employeeBenefitDetails['employee']->id]['benefitplan '.$employeeBenefit['groupPlan']->plans->id] != "view")

                                            <td>
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button"
                                                            id="dropdownMenuButton" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-cog"></i>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        @foreach($employeeBenefit['list'] as $statusList)
                                                            <a class="dropdown-item"
                                                               href="@if(isset($locale)){{url($locale.'/employee/benefit-status/'.$employeeBenefitDetails['employee']->id.'-'.$employeeBenefit['groupPlan']->id.'-'.$statusList['value'])}} @else {{url('en/employee/benefit-status/'.$employeeBenefitDetails['employee']>id.'-'.$employeeBenefit['groupPlan']->id.'-'.$statusList['value'])}} @endif">{{$statusList['label']}}</a>
                                                        @endforeach
                                                    </div>
                                                </div>

                                            </td>
                                        @endif
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td><h5>No plans are assigned to this group</h5></td>
                            </tr>
                        @endif
                        </tbody>

                    </table>
                    <br>
                    <hr/>
                    {{-- TODO: --}}
                    @if(Auth::user()->isAdmin() || isset($permissions['benefits'][$employeeBenefitDetails['employee']->id]['employee benefits history']))
                        <td>
                            <h4>Benefit History</h4>
                            <button id="showhidebtn" onclick="toggleBenefitHistory()">Hide</button>
                            <table class="table" id="benefitHistoryTable">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Changed by</th>
                                    <th scope="col">Event</th>
                                    <th scope="col">Comments</th>
                                    @if(Auth::user()->isAdmin() || isset($permissions['benefits'][$employeeBenefitDetails['employee']->id]['employee benefits history']) &&
$permissions['benefits'][$employeeBenefitDetails['employee']->id]['employee benefits history'] != "view")
                                    <th></th> {{-- for button --}}
                                        @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($employeeBenefitStatus as $benefitStatus)
                                    <tr>
                                        <td>
                                            {{date('d-M-Y', strtotime($benefitStatus->effective_date))}}
                                        </td>
                                        <td>
                                            @php
                                                $trackingField = json_decode($benefitStatus->enrollment_status_tracking_field,true);
                                                if(isset($trackingField["created_by"]))
                                                {echo $trackingField["created_by"];}
                                            @endphp
                                        </td>
                                        <td>
                                            @php
                                                $trackingField = json_decode($benefitStatus->enrollment_status_tracking_field,true);
                                                if(isset($trackingField["event"]))
                                                {echo $trackingField["event"];}
                                            @endphp
                                        </td>
                                        <td>
                                            @php
                                                $trackingField = json_decode($benefitStatus->enrollment_status_tracking_field,true);
                                                if(isset($trackingField["comment"]))
                                                {echo $trackingField["comment"];}
                                            @endphp
                                        </td>
                                        @if(Auth::user()->isAdmin() || isset($permissions['benefits'][$employeeBenefitDetails['employee']->id]['employee benefits history']) &&
$permissions['benefits'][$employeeBenefitDetails['employee']->id]['employee benefits history'] != "view")
                                        <td>
                                            <a class="mr-2 text-dark" title="Delete This History">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                            @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                    @endif
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
    @push('scripts')
        <script>
            function toggleBenefitHistory() {
                var x = document.getElementById("benefitHistoryTable");
                if (x.style.display === "none") {
                    $('#benefitHistoryTable').show();
                    $('#showhidebtn').text('Hide');
                } else {
                    x.style.display = "none";
                    $('#showhidebtn').text('Show');
                }
            }
        </script>
    @endpush
@stop