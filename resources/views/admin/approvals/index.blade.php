@extends('layouts.admin')
@section('title','Approvals')
@section('heading')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{__('language.Approvals')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">{{__('language.Settings')}}</a></li>
                    <li class="breadcrumb-item active">{{__('language.Approvals')}}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div> <!-- /.content-header -->
@stop
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="col-md-12">
            <div class="row card-body">
                <div class="col-4 col-sm-2">
                    <div class="row">
                        <div class="col-md-8">
                            <h4>{{__('language.Approvals')}}</h4>
                        </div>
                        <div class="col-md-4"><a href="@if(isset($locale)){{url($locale.'/approvals/create')}} @else {{url('en/approvals/create')}} @endif" class="btn btn-default float-sm-right"><i
                                    class="fas fa-plus-circle"></i></a></div>
                    </div>
                    <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                        @foreach ($approvals as $approval)
                        <a class="nav-link" id="vert-tabs-tab-{{$approval->id}}" data-toggle="pill" href="#vert-tabs-{{$approval->id}}" role="tab" aria-controls="vert-tabs-{{$approval->id}}"
                            aria-selected="true">
                            {{$approval->name}}</a>
                        @endforeach
                    </div>
                </div>
                <div class="col-8 col-sm-10">
                    <div class="tab-content" id="vert-tabs-tabContent">
                        @foreach ($approvals as $approval)
                        <div class="tab-pane text-left fade show" id="vert-tabs-{{$approval->id}}" role="tabpanel" aria-labelledby="vert-tabs-tab-{{$approval->id}}">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h4><i class="fas fa-thumbs-up"></i> {{$approval->name}}</h4>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" id="approvalAction" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-cog"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="approvalAction">
                                                @if($approval->status == 1)
                                                <a class="dropdown-item advanced-approvals"
                                                    href="@if(isset($locale)){{url($locale.'/advance-approvals',[$approval->id,'edit'])}} @else {{url('en/advance-approvals',[$approval->id,'edit'])}} @endif">
                                                    Advanced Approvals
                                                </a>
                                                @else
                                                <a class="dropdown-item"
                                                    href="@if(isset($locale)){{url($locale.'/approvals/enable',$approval->id)}} @else {{url('en/approvals/enable',$approval->id)}} @endif">
                                                    Enable Approval </a>
                                                @endif
                                                @if ($approval->approval_type_id == 2 && $approval->status==1)
                                                <a class="dropdown-item"
                                                    href="@if(isset($locale)){{url($locale.'/approvals/disable',$approval->id)}} @else {{url('en/approvals/disable',$approval->id)}} @endif">
                                                    Disable Approval </a>
                                                @elseif ($approval->approval_type_id == 3 && $approval->status==1)
                                                <a class="dropdown-item"
                                                    href="@if(isset($locale)){{url($locale.'/approvals/disable',$approval->id)}} @else {{url('en/approvals/disable',$approval->id)}} @endif">
                                                    Disable Approval</a>
                                                {{-- FIXME: should use resource route --}}
                                                <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/approvals/delete',$approval->id)}} @else {{url('en/approvals/delete',$approval->id)}} @endif">Delete
                                                    Approval</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p>{{$approval->description}}</p>
                                @if($approval->approval_type_id == 2 && $approval->status==1)
                                <div class="row">
                                    <div class="col-md-8">
                                        <a class="btn btn-default" href="@if(isset($locale)){{url($locale.'/approvals',[$approval->id, 'edit'])}} @else {{url('en/approvals',[$approval->id, 'edit'])}} @endif">Edit
                                            Approval Form</a> &nbsp;
                                        @foreach ($approval->approvalRequestedFields as $requestedfields)
                                        @php
                                        $formFields = json_decode($requestedfields->form_fields,true);
                                        $totalField=0;
                                        foreach ($formFields as $key => $fields){
                                        $totalField += count(array_column($fields, 'name'));
                                        }
                                        @endphp
                                        @endforeach
                                        @php

                                            echo $totalField;
                                        @endphp
                                        fields | <button class="btn btn-default" type="button" data-toggle="modal" data-target="#preview-form-{{$approval->id}}">Preview Form</button>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <a class="btn btn-info"
                                            href="@if(isset($locale)){{url($locale.'/approvals/restore-approval',$approval->id)}} @else {{url('en/approvals/restore-approval',$approval->id)}} @endif">
                                            Restore Default Approval
                                        </a>
                                    </div>
                                </div>
                                @elseif($approval->approval_type_id == 3 && $approval->status==1)
                                <div class="row">
                                    <div class="col-md-8">
                                        <a class="btn btn-default" href="@if(isset($locale)){{url($locale.'/approvals',[$approval->id, 'edit'])}} @else {{url('en/approvals',[$approval->id, 'edit'])}} @endif">Edit
                                            Approval Form</a> &nbsp;
                                        @foreach ($approval->approvalRequestedFields as $requestedfields)
                                            @php
                                                $formFields = json_decode($requestedfields->form_fields,true);
                                                $totalField=0;
                                                foreach ($formFields as $key => $fields){
                                                $totalField += count(array_column($fields, 'name'));
                                                }
                                            @endphp
                                        @endforeach
                                        @php

                                            echo $totalField;
                                        @endphp
                                        fields | <button class="btn btn-default" type="button" data-toggle="modal" data-target="#preview-form-{{$approval->id}}">Preview Form</button>
                                    </div>
                                </div>
                                @endif
                                <hr>

                                {{-- show advance approvals TODO: --}}
                                @if($approval->advanceApprovalOptions->count() > 0 && $approval->status == 1)
                                <div class="text-center">
                                    <i class="fas fa-thumbs-up fa-3x mb-2"></i>
                                    <h2>Advance Approval is set</h2>
                                    <p>Go to advance approval page for its workflow hierarchy.</p>
                                    <a class="btn btn-info" href="@if(isset($locale)){{url($locale.'/advance-approvals')}} @else {{url('en/advance-approvals')}} @endif">
                                        Go to Advance Approval</a>
                                </div>
                                @else {{-- Show approvals --}}
                                <form action="@if(isset($locale)){{url($locale.'/approval-workflows',$approval->id)}} @else {{url('en/approval-workflows',$approval->id)}} @endif" method="post">
                                    @method('PUT')
                                    {{ csrf_field() }}
                                    <div class="container">
                                        @if ($approval->approval_type_id != 1 && $approval->status==1)
                                        <b>Who can make this type of request?</b> <br><br>
                                        <div class="dropdown " id="">
                                            <a data-toggle="dropdown" class=" dropdown-toggle form-control col-sm-2" role="button" aria-haspopup="true" aria-expanded="false">
                                                -Select- <b class="caret"></b>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <label class="checkbox dropdown-item">
                                                        <input type="checkbox" name="makeTypeofRequest[AccountOwner]" value="none"> Account Owner
                                                    </label>
                                                </li>
                                                <li>
                                                    <label class="checkbox dropdown-item">
                                                        <input type="checkbox" name="makeTypeofRequest[FullAdmin]" value="none"> Full Admin(s)
                                                    </label>
                                                </li>
                                                <li>
                                                    <label class="checkbox dropdown-item">
                                                        <input type="checkbox" name="makeTypeofRequest[Manager]" value="none"> Manager (Reports to)
                                                    </label>
                                                </li>
                                                <li>
                                                    <label class="checkbox dropdown-item">
                                                        <input type="checkbox" name="makeTypeofRequest[Manager'sManager]" value="none"> Manager's Manager
                                                    </label>
                                                </li>
                                                <div class="dropdown-divider"></div>
                                                <label class="dropdown-item disabled">Access Levels</label>
                                                @foreach ($allCustomLevels as $customLevel)
                                                <li>
                                                    <label class="checkbox dropdown-item">
                                                        <input type="checkbox" name="makeTypeofRequest[{{$customLevel->name}}]" value="{{$customLevel->id}}" >
                                                        {{$customLevel->name}}
                                                    </label>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <br>
                                        @endif
                                        @if ($approval->approval_type_id == 1)
                                        <b> Who can approve {{$approval->name}} for an employee?</b>
                                        @elseif($approval->status==1)
                                        <b> Who can approve these requests?</b>
                                        @endif
                                        {{-- show approval if it is enabled --}}
                                        @if($approval->status==1)
                                        <p>Go ahead and select the order in which information updates are approved.</p>
                                        <div class="dropdowns dropdowns-{{$approval->id}}">
                                            @php $levelNumber=0; @endphp
                                            @foreach ($approval->approvalWorkflow as $workflow)
                                            @if(is_null($workflow->advance_approval_option_id))
                                            @php $approvalHierarchy = json_decode($workflow->approval_hierarchy,true); @endphp
                                            @foreach ($approvalHierarchy as $key => $value)

                                            <div class="row dropdownRow-{{$workflow->approval->id}}-{{$workflow->level_number}}">
                                                <div><span class="mt-2 mr-3 badge badge-pill badge-dark levelBadge" id="levelBadge">{{$workflow->level_number}}</span></div>
                                                <select class="form-control col-sm-2 dropdownLevel" onchange="appendDropdown(this,{{$approval->id}})" name="dropdownLevel[{{$workflow->level_number}}]"
                                                    id="dropdownLevel[{{$workflow->level_number}}]">
                                                    <option value="FullAdmin" @if($key=="FullAdmin" ) selected @endif>Full Admin(s)</option>
                                                    <option value="AccessLevels" @if($key=="AccessLevels" ) selected @endif>Access Levels</option>
                                                    <option value="AccountOwner" @if($key=="AccountOwner" ) selected @endif>Account Owner</option>
                                                    <option value="Manager" @if($key=="Manager" ) selected @endif>Manager (Reports to)</option>
                                                    <option value="Manager'sManager" @if($key=="Manager'sManager" ) selected @endif>Manager's Manager</option>
                                                    <option value="SpecificPerson" @if($key=="SpecificPerson" ) selected @endif>Specific Person</option>
                                                </select>
                                                {{-- dropdown for access level and specific person --}}
                                                @if($key=="SpecificPerson")
                                                <select class="form-control col-sm-2 ml-3" name="specific_employee[]" id="specific_employee_Dropdown">
                                                    <option selected disabled>-Select-</option>
                                                    @foreach ($allEmployees as $employee)
                                                    <option value="{{$employee->id}}" @if($employee->id==$value ) selected @endif> {{$employee->firstname}} {{$employee->lastname}} </option>
                                                    @endforeach
                                                </select>
                                                @elseif($key=="AccessLevels")
                                                <div class="ml-3 dropdown col-sm-2" id="accessLevelDropdown">
                                                    <a data-toggle="dropdown" class=" dropdown-toggle form-control" role="button" aria-haspopup="true" aria-expanded="false">
                                                        -Select- <b class="caret"></b>
                                                    </a>
                                                    <ul class="dropdown-menu col-sm-3">
                                                        @foreach ($allCustomLevels as $customLevel)
                                                        <li>
                                                            <label class="checkbox dropdown-item">
                                                                <input type="checkbox" class="customLevelCheckbox" name="customLevels{{$workflow->level_number}}[]" value="{{$customLevel->id}}"
                                                                    @if($customLevel->id==$value )
                                                                checked @endif>
                                                                {{$customLevel->name}}
                                                            </label>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif
                                                {{-- delete dropdown level button --}}
                                                @if($workflow->level_number > 1)
                                                <button type="button"
                                                    onclick="window.location.href='@if(isset($locale)){{url($locale.'/approval-workflows',$workflow->id)}} @else {{url('en/approval-workflows',$workflow->id)}} @endif'"
                                                    class="btn" id="removeDropdown"><i class="fas fa-times"></i></button>
                                                @endif
                                            </div>
                                            @php $levelNumber++; @endphp
                                            @endforeach
                                            @endif
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn" id="addDropdown{{$approval->id}}" onclick="countLevel({{$levelNumber}},{{$approval->id}})">+ Add</button>
                                        <input type="hidden" id="countLevelsForDropdown{{$approval->id}}" value="{{$levelNumber}}">
                                        <hr>
                                        <p class="text-muted">Emails will be sent to notify the appropriate people when an item is waiting for their review.</p>
                                        <button type="submit" class="btn btn-info">{{__('language.Save')}}</button>
                                        <button type="button" class="btn btn-default"
                                            onclick="window.location.href='@if(isset($locale)){{url($locale.'/approvals')}} @else {{url('en/approvals')}} @endif'">{{__('language.Cancel')}}</button>
                                        @else
                                        <div class="text-center">
                                            <i class="fas fa-exclamation-triangle fa-3x mb-2"></i>
                                            <h2>Approval Disabled</h2>
                                            <p>Enable this approval to edit it or to be able to request a change using this approval workflow.</p>
                                            <a class="btn btn-info"
                                                href="@if(isset($locale)){{url($locale.'/approvals/enable',$approval->id)}} @else {{url('en/approvals/enable',$approval->id)}} @endif">
                                                Enable Approval</a>
                                        </div>
                                        @endif
                                        <input type="hidden" name="approvalId" id="approvalId" value="{{$approval->id}}">
                                    </div>
                                </form>
                                @endif
                            </div>
                        </div>
                        <!-- PREVIW FORM -->
                        <div class="modal fade" id="preview-form-{{$approval->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog" style="overflow-y: scroll; max-height:85%;  margin-top: 50px; margin-bottom:50px;">
                                <div class="modal-content">
                                        <div class="modal-header">
                                            <strong> {{__('language.Preview')}} {{__('language.Form')}}</strong>
                                        </div>
                                <div class="container">
                                    <label class="control-label"> {{$approval->name}}</label>
                                       <div class="container">
                                                @foreach($approval->approvalRequestedFields as $requestedFields)
                                                     @php $formFields = json_decode($requestedFields->form_fields,true) @endphp
                                                    @foreach ($formFields as $categories => $details)
                                                            <div class="col-md-12">
                                                                @foreach ($details as $key => $detail)
                                                                  <label for="{{$key}}">{{$detail['name']}}</label><br>
                                                                    @if($detail['name']== 'Reports To')                                            
                                                                        <select class="form-control col-md-4 ml-3" name="{{$key}}" >
                                                                            <option>-Select-</option>
                                                                             @foreach ($allEmployees as $employee)
                                                                              <option> {{$employee->firstname}} {{$employee->lastname}} </option>
                                                                             @endforeach
                                                                        </select>
                                                                    @elseif($detail['name']== 'Location')                                            
                                                                        <select class="form-control col-md-4 ml-3" name="{{$key}}" >
                                                                                <option>-Select-</option>
                                                                            @foreach ($locations as $location)
                                                                                <option> {{$location->name}} </option>
                                                                             @endforeach
                                                                        </select>
                                                                    @elseif($detail['name']== 'Department')                                            
                                                                        <select class="form-control col-md-4 ml-3" name="{{$key}}" >
                                                                            <option>-Select-</option>
                                                                                @foreach ($departments as $department)
                                                                                <option> {{$department->department_name}} </option>
                                                                                @endforeach
                                                                            </select>
                                                                     @elseif($detail['name']== 'Employment Status')                                            
                                                                        <select class="form-control col-md-4 ml-3" name="{{$key}}" >
                                                                                <option>-Select-</option>
                                                                                @foreach ($statuses as $status)
                                                                                    <option> {{$status->employment_status}} </option>
                                                                                @endforeach
                                                                        </select>
                                                                    @elseif(($detail['name']== 'Gender') || ($detail['name']== 'Dependent Gender'))
                                                                        <select class="form-control col-md-4 ml-3" name="{{$key}}" >
                                                                                <option>-Select-</option>
                                                                                <option>Male</option>
                                                                                <option>Female</option>
                                                                        </select>
                                                                    @elseif($detail['type']== 'list')
                                                                        <select class="form-control col-md-4 ml-3" name="{{$key}}" >
                                                                                <option>-Select-</option>
                                                                        </select>    
                                                                    @else
                                                                        <input class="form-control col-md-4 ml-3" type="{{$detail['type']}}" name="{{$key}}" value="" {{$detail['status']}}>
                                                                     @endif <br>
                                                                @endforeach
                                                            </div>
                                                         @endforeach
                                                @endforeach
                                            </div>
                                            <div class="modal-footer">
                                                    <button type="button" href="#" class="btn btn-info btn-ok" data-dismiss="modal">{{__('language.Done')}}</button>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function appendDropdown(option,approvalID){
        const selectedDropdown= $(option).parent();
        var number = selectedDropdown.children("div").children("#levelBadge").text();
        const accessLevelDropdown = selectedDropdown.children("#accessLevelDropdown");        
        const specificEmployeeDropdown = selectedDropdown.children("#specific_employee_Dropdown");
        if(option.value=="AccessLevels"){
            $(specificEmployeeDropdown).remove();
            $(selectedDropdown).append(
                '<div class="ml-3 dropdown col-sm-2" id="accessLevelDropdown"> '+
                    '<a data-toggle="dropdown" class="dropdown-toggle form-control" role="button" aria-haspopup="true" aria-expanded="false"> -Select- <b class="caret"></b> </a> '+
                    '<ul class="dropdown-menu col-sm-3"> '+
                        '@foreach ($allCustomLevels as $customLevel) '+
                        '<li><label class="checkbox dropdown-item"> <input type="checkbox" class="customLevelCheckbox" name="customLevels'+number+'[]" value="{{$customLevel->id}}"> {{$customLevel->name}} </label></li> '+
                        ' @endforeach '+
                    '</ul> '+
                '</div> '
            );
        }
        else if(option.value=="SpecificPerson"){
            $(accessLevelDropdown).remove();
            $(selectedDropdown).append(
            '<select class="form-control col-sm-2 ml-3" name="specific_employee[]" id="specific_employee_Dropdown"> '+
                '<option selected disabled>-Select-</option> '+
                '@foreach ($allEmployees as $employee) '+
                '<option value="{{$employee->id}}"> {{$employee->firstname}} {{$employee->lastname}} </option> '+
                '@endforeach '+
                '</select> '
            );
        }else{         
            $(accessLevelDropdown).remove();
            $(specificEmployeeDropdown).remove();
        }
    }
    // var number=1;
    function countLevel(level,approvalID)
    {
        var levelValue= $('#countLevelsForDropdown'+approvalID).val();
            levelValue++;
        // hide add button
        if(levelValue >= 5)
        {
            $("#addDropdown"+approvalID).hide();
        }else{
            $("#addDropdown"+approvalID).show();
        }

        $('#countLevelsForDropdown'+approvalID).val(levelValue);
        var number= $('#countLevelsForDropdown'+approvalID).val();
        //add dropdown levels
        if(number<=5){
         $('.dropdowns-'+approvalID).append( 
            '<div class="row dropdownRow-'+approvalID+'-' + number+'">'+
            '<div><span class="mt-2 mr-3 badge badge-pill badge-dark levelBadge" id="levelBadge">'+number+'</span></div>'+
            '<select class="form-control col-sm-2 dropdownLevel" onchange="appendDropdown(this,'+approvalID+')" name="dropdownLevel['+number+']" id="dropdownLevel['+ number +']">'+
            '<option value="FullAdmin">Full Admin(s)</option>'+
            '<option value="AccessLevels">Access Levels</option>'+
            '<option value="AccountOwner">Account Owner</option>'+
            '<option value="Manager">Manager (Reports to)</option>'+
            '<option value="Manager\'sManager">Manager\'s Manager</option>'+
            '<option value="SpecificPerson">Specific Person</option>'+
            '</select>'+
            '<button type="button" onclick="removeDropdowns(this,'+number+','+approvalID+')" class="btn" id="removeDropdown"><i class="fas fa-times"></i></button></div>'
        );
        }//endif
    }
    
    function removeDropdowns(element,rowNum,approvalID){
        
        $(element).parent().remove();
        //get level number value
        var removeLevelNumber = $('#countLevelsForDropdown'+approvalID).val();
        removeLevelNumber--;
        //set count level number after removeing dropdown
        $('#countLevelsForDropdown'+approvalID).val(removeLevelNumber);
        
        if($('#countLevelsForDropdown'+approvalID).val() <= 5){
            $("#addDropdown"+approvalID).show();
        }
        var getDropdownDivs = document.getElementsByClassName("dropdowns-"+approvalID);
        //change workflow level numbers
        var workflowHierarchyNumbers = $(getDropdownDivs).children().children().children(".levelBadge");        
        $.each(workflowHierarchyNumbers, function (index, hierarchyNumber) { 
             hierarchyNumber.innerText= ++index; //workflow level numbers
             var dropdownRows = $(hierarchyNumber).parent().parent(); //dropdown row for each level 
             $(dropdownRows).attr("class","row dropdownRow-"+approvalID+'-'+index);
        });
        //change name for dropdowns w.r.t workflow level
        var getDropdownElements = $(getDropdownDivs).children().children(".dropdownLevel");        
        $.each(getDropdownElements, function (index, selectElement) {             
           $(selectElement).attr("name","dropdownLevel["+ ++index +"]");
        });
        //change custom level dropdown names w.r.t workflow level
        var getCustomLevelCheckboxDropdowns = $(getDropdownDivs).find("div#accessLevelDropdown");    
        $.each(getCustomLevelCheckboxDropdowns, function (index, checkbox) {
            var badgeNumber = $(checkbox).parent().find(".levelBadge").html();
            $(checkbox).find("input").attr("name","customLevels"+ badgeNumber +"[]");
        });
    }

    $("#vert-tabs-1").addClass('active');
    $("#vert-tabs-tab-1").addClass('active');
    
</script>
@stop