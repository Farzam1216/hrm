@extends('layouts.admin')
@section('title','Approvals')
@section('heading')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{__('language.Advance Approvals')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">{{__('language.Settings')}}</a></li>
                    <li class="breadcrumb-item active">{{__('language.Advance Approvals')}}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div> <!-- /.content-header -->
@stop
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="col-md-12  card-body">
            <div class="row">
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
                            @if($approval->advanceApprovalOptions->count() > 0)
                        <a class="nav-link" id="vert-tabs-tab-{{$approval->id}}" data-toggle="pill" href="#vert-tabs-{{$approval->id}}" role="tab" aria-controls="vert-tabs-{{$approval->id}}"
                            aria-selected="true">
                            {{$approval->name}}</a>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="col-8 col-sm-10">
                    <div class="tab-content" id="vert-tabs-tabContent">
                        @foreach ($approvals as $approval)
                            @if($approval->advanceApprovalOptions->count() > 0)
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
                                                <a class="dropdown-item" href="@if(isset($locale)){{url($locale.'/approvals/delete',$approval->id)}} @else {{url('en/approvals/delete',$approval->id)}} @endif">Delete Approval</a>
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
                                            Approval Form</a>
                                    </div>
                                </div>
                                @elseif($approval->approval_type_id == 3 && $approval->status==1)
                                <div class="row">
                                    <div class="col-md-8">
                                        <a class="btn btn-default" href="@if(isset($locale)){{url($locale.'/approvals',[$approval->id, 'edit'])}} @else {{url('en/approvals',[$approval->id, 'edit'])}} @endif">Edit
                                            Approval Form</a>
                                    </div>
                                </div>
                                @endif
                                <p class="text-muted mt-2">Emails will be sent to notify the appropriate people when an item is waiting for their review.</p>
                                <hr>

                                {{-- show advance approvals --}}
                            @if($approval->status == 1)
                                @if($approval->advanceApprovalOptions->count() > 0 && $approval->advanceApprovalOptions[0]->advance_approval_type !='none')
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3>Approval Paths by {{$approval->advanceApprovalOptions[0]->advance_approval_type}}</h3>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="@if(isset($locale)){{url($locale.'/advance-approvals',[$approval->id,'create'])}} @else {{url('en/advance-approvals',[$approval->id,'create'])}} @endif" class="btn border-info">
                                            + Add a {{$approval->advanceApprovalOptions[0]->advance_approval_type}} Approval</a>
                                    </div>
                                </div>
                                @foreach($approval->advanceApprovalOptions as $advanceApprovalOption)
                                @if(isset($advanceApprovalOption->approval_path))
                                @php $advanceApprovalOptionsData = json_decode($advanceApprovalOption->approval_path,true); @endphp
                                
                                <form action="@if(isset($locale)){{url($locale.'/approval-workflows',$approval->id)}} @else {{url('en/approval-workflows',$approval->id)}} @endif" method="post">
                                    @method('PUT')
                                    {{ csrf_field() }}
                                    <div class="col-md-12" style="background-color: #F4F4F4; padding: 3%">
                                        
                                    <div class="row">
                                        <h5>{{$approval->advanceApprovalOptions[0]->advance_approval_type}} :</h5>
                                        <div class="dropdown col-sm-2" id="">
                                            <a data-toggle="dropdown" class=" dropdown-toggle form-control" role="button" aria-haspopup="true" aria-expanded="false">
                                                -Select- <b class="caret"></b>
                                            </a>
                                            <ul class="dropdown-menu">

                                                @if($approval->advanceApprovalOptions[0]->advance_approval_type == 'Department')
                                                @foreach ($departments as $department)
                                                <li>
                                                    <label class="checkbox dropdown-item">
                                                        <input type="checkbox" name="approvalPath[]" value="{{$department->department_name}}" @if(isset($advanceApprovalOptionsData)) @foreach ($advanceApprovalOptionsData as $pathName)
                                                            @if($department->department_name == $pathName) checked @endif @endforeach @endif>
                                                        {{$department->department_name}}
                                                    </label>
                                                </li>

                                                @endforeach
                                                {{-- TODO: Need division after changing Employee Model --}}
                                                {{-- @elseif($approval->advanceApprovalOptions[0]->advance_approval_type == 'Division')
                                                @foreach ($divisions as $division)
                                                    <li>
                                                        <label class="checkbox dropdown-item">
                                                            <input type="checkbox" name="approvalPath[]" value="{{$division->name}}" @if(isset($advanceApprovalOptionsData)) @foreach ($advanceApprovalOptionsData as $pathName) @if($division->name == $pathName) checked
                                                            @endif @endforeach @endif>
                                                            {{$division->name}}
                                                        </label>
                                                    </li>
                                                @endforeach --}}
                                                @else
                                                @foreach ($locations as $location)
                                                    <li>
                                                        <label class="checkbox dropdown-item">
                                                            <input type="checkbox" name="approvalPath[]" value="{{$location->name}}" @if(isset($advanceApprovalOptionsData)) @foreach ($advanceApprovalOptionsData as $pathName) @if($location->name == $pathName) checked
                                                            @endif @endforeach @endif>
                                                            {{$location->name}}
                                                        </label>
                                                    </li>
                                                @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <br>
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
                                                        <input type="checkbox" name="makeTypeofRequest[{{$customLevel->name}}]" value="{{$customLevel->id}}">
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
                                        
                                        <p>Go ahead and select the order in which information updates are approved.</p>
                                        @php $levelNumber=0; @endphp
                                        @foreach ($advanceApprovalOption->approvalWorkflows as $workflow)
                                        <div class="dropdowns dropdowns-{{$approval->id}}-{{$workflow->id}}">
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
                                            
                                        </div>
                                        @endforeach
                                        {{-- make this addDropdown button just after dropdowns-{{$approval->id}} div --}}
                                        <button type="button" class="btn" id="addDropdown{{$approval->id}}" onclick="countLevel(this,{{$levelNumber}},{{$approval->id}},{{$workflow->id}})">+
                                            Add</button>
                                        <input type="hidden" id="countLevelsForDropdown{{$approval->id}}-{{$workflow->id}}" value="{{$levelNumber}}">
                                        <hr>
                                        <button type="submit" class="btn btn-info">{{__('language.Save')}}</button>
                                        <button type="button" class="btn btn-default"
                                            onclick="window.location.href='@if(isset($locale)){{url($locale.'/approvals')}} @else {{url('en/approvals')}} @endif'">{{__('language.Cancel')}}</button>
                                        <input type="hidden" name="approvalId" id="approvalId" value="{{$approval->id}}">
                                        <input type="hidden" name="advanceApprovalOptionId" value="{{$advanceApprovalOption->id}}">
                                    </div>
                            </div>
                            </form>
                            <br>
                            @endif
                            
                            @endforeach
                        {{-- 'All other' advance approval workflows paths --}}
                            <form action="@if(isset($locale)){{url($locale.'/approval-workflows',$approval->id)}} @else {{url('en/approval-workflows',$approval->id)}} @endif" method="post">
                                @method('PUT')
                                {{ csrf_field() }}
                                <div class="col-md-12" style="background-color: #F4F4F4; padding: 3%">
                                <h4><i class="fas fa-user-friends mr-2"></i> All Other {{$approval->advanceApprovalOptions[0]->advance_approval_type}}</h4>
                                <ul>
                                     @php
                                        $hold =[];
                                        foreach ($approval->advanceApprovalOptions as $option){
                                            $path = json_decode($option->approval_path,true);
                                            if(isset($path)){
                                                foreach ($path as $pathName) {
                                                    $pathArray = array($pathName);
                                                    $hold[] = array_pop($pathArray);
                                                }
                                            }
                                        }
                                        if($approval->advanceApprovalOptions[0]->advance_approval_type == "Location")
                                        $allOtherPath = $locations->pluck('name')->diff(collect($hold));
                                        // TODO: Need division after changing Employee Model
                                        // else if($approval->advanceApprovalOptions[0]->advance_approval_type == "Division")
                                        // $allOtherPath = $divisions->pluck('name')->diff(collect($hold));
                                        else
                                        $allOtherPath = $departments->pluck('department_name')->diff(collect($hold));
                                    @endphp 
                                    
                                    @foreach ($allOtherPath as $otherPathName)
                                        <li>
                                            {{$otherPathName}}
                                        </li>
                                    @endforeach
                                    <li>Employees without an assigned department</li>
                                    
                                </ul>
                                
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
                                                    <input type="checkbox" name="makeTypeofRequest[{{$customLevel->name}}]" value="{{$customLevel->id}}">
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
                                                            <input type="checkbox" class="customLevelCheckbox" name="customLevels{{$workflow->level_number}}[]" value="{{$customLevel->id}}" @if($customLevel->id==$value )
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
                                                onclick="window.location.href='@if(isset($locale)){{url($locale.'/approval-workflows',$workflow->id)}} @else {{url('en/approval-workflows',$workflow->id)}} @endif'" class="btn"
                                                id="removeDropdown"><i class="fas fa-times"></i></button>
                                            @endif
                                        </div>
                                        @php $levelNumber++; @endphp
                                        @endforeach
                                        @endif
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn" id="addDropdownForOtherPath{{$approval->id}}" onclick="countLevelForOtherPath({{$levelNumber}},{{$approval->id}})">+ Add</button>
                                    <input type="hidden" id="countLevelsForOtherPathDropdown{{$approval->id}}" value="{{$levelNumber}}">
                                    <hr>
                                    <button type="submit" class="btn btn-info">{{__('language.Save')}}</button>
                                    <button type="button" class="btn btn-default"
                                        onclick="window.location.href='@if(isset($locale)){{url($locale.'/approvals')}} @else {{url('en/approvals')}} @endif'">{{__('language.Cancel')}}</button>
                                    <input type="hidden" name="approvalId" id="approvalId" value="{{$approval->id}}">
                                </div>
                                </div>
                            </form>

                            @else
                            <div class="text-center">
                                <i class="fab fa-autoprefixer fa-3x mb-2"></i>
                                <h2>Advance Approval is not set yet</h2>
                                <p>Add advance approval and workflow for {{$approval->name}}.</p>
                                <a class="btn btn-info" href="@if(isset($locale)){{url($locale.'/advance-approvals',[$approval->id,'edit'])}} @else {{url('en/advance-approvals',[$approval->id,'edit'])}} @endif">
                                    Add Advance Approval</a>
                            </div>
                            @endif
                            @else
                                <div class="text-center">
                                    <i class="fas fa-exclamation-triangle fa-3x mb-2"></i>
                                    <h2>Approval Disabled</h2>
                                    <p>Enable this approval to edit it or to be able to request a change using this approval workflow.</p>
                                    <a class="btn btn-info" href="@if(isset($locale)){{url($locale.'/approvals/enable',$approval->id)}} @else {{url('en/approvals/enable',$approval->id)}} @endif">
                                        Enable Approval</a>
                                </div>
                            @endif 
                        </div>
                    </div>
                    @endif
                                @endforeach
                </div>
            </div>

            </div>
            <br>
            <button type="button" class="btn btn-lg btn-primary float-right"
                    onclick="window.location.href='@if(isset($locale)){{url($locale.'/approvals')}} @else {{url('en/approvals')}} @endif'">{{__('language.Back')}}</button>

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
    function countLevel(btnElement,level,approvalID,workflowID)
    {
        var levelValue= $('#countLevelsForDropdown'+approvalID+'-'+workflowID).val();
            levelValue++;
        // hide add button
        if(levelValue >= 5)
        {
        // $("#addDropdown"+approvalID).hide();
            $(btnElement).hide();
        }else{
            $(btnElement).show();
        }

        $('#countLevelsForDropdown'+approvalID+'-'+workflowID).val(levelValue);
        var number= $('#countLevelsForDropdown'+approvalID+'-'+workflowID).val();
        //add dropdown levels
        if(number<=5){
            // '.dropdowns-'+approvalID
         $($(btnElement).prev()).append( 
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
            '<button type="button" onclick="removeDropdowns(this,'+number+','+approvalID+','+workflowID+')" class="btn" id="removeDropdown"><i class="fas fa-times"></i></button></div>'
        );
        }//endif
    }
    
    function removeDropdowns(element,rowNum,approvalID,workflowID){
        
        var addDropdownButton = $(element).parent().parent().next(); //getting addDropdown button
        $(element).parent().remove(); //removing dropdown row div
        //get level number value
        var removeLevelNumber = $('#countLevelsForDropdown'+approvalID+'-'+workflowID).val();
        removeLevelNumber--;
        //set count level number after removeing dropdown
        $('#countLevelsForDropdown'+approvalID+'-'+workflowID).val(removeLevelNumber);
        if($('#countLevelsForDropdown'+approvalID+'-'+workflowID).val() <= 5){
            // $("#addDropdown"+approvalID).show();
            $(addDropdownButton).show();                        
        }
        var getDropdownDivs = document.getElementsByClassName("dropdowns-"+approvalID+'-'+workflowID);
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
{{-- 'All other' advance approval workflows paths --}}
<script>
    function countLevelForOtherPath(level,approvalID)
    {
        var levelValue= $('#countLevelsForOtherPathDropdown'+approvalID).val();
            levelValue++;
        // hide add button
        if(levelValue >= 5)
        {
            $("#addDropdownForOtherPath"+approvalID).hide();
        }else{
            $("#addDropdownForOtherPath"+approvalID).show();
        }

        $('#countLevelsForOtherPathDropdown'+approvalID).val(levelValue);
        var number= $('#countLevelsForOtherPathDropdown'+approvalID).val();
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
            '<button type="button" onclick="removeDropdownsForOtherPath(this,'+number+','+approvalID+')" class="btn" id="removeDropdown"><i class="fas fa-times"></i></button></div>'
        );
        }//endif
    }
    
    function removeDropdownsForOtherPath(element,rowNum,approvalID){
        
        $(element).parent().remove();
        //get level number value
        var removeLevelNumber = $('#countLevelsForOtherPathDropdown'+approvalID).val();
        removeLevelNumber--;
        //set count level number after removeing dropdown
        $('#countLevelsForOtherPathDropdown'+approvalID).val(removeLevelNumber);
        
        if($('#countLevelsForOtherPathDropdown'+approvalID).val() <= 5){
            $("#addDropdownForOtherPath"+approvalID).show();
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

</script>
@stop