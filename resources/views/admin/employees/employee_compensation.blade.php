@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
@endsection
<div class="card-header border-bottom mt-2">
    <div class="head-label">
        <h4 class="mb-0"><i class="font-medium-3 mr-25" data-feather='dollar-sign'></i>Compensation</h4>
        </h6>
    </div>
    <div class="dt-action-buttons text-right">
        <div class="dt-buttons flex-wrap d-inline-flex">
            @if(Auth::user()->isAdmin() || isset($perms['compensation']['all']) && in_array('manage setting compensation', $perms['compensation']['all']))
                <button type="button" class="btn btn-primary btn-rounded float-right" data-toggle="modal" data-target="#create_compensation"><i class="fa fa-plus"></i>&nbsp {{__('language.Add')}}
                    {{__('language.Compensation')}}
                </button>
            @endif
        </div>
    </div>
</div>
<!-----Create Job Information Modal---->
<div class="modal modal-slide-in fade" id="create_compensation" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content pt-0">
            <form action="@if(isset($locale)) {{route('employee.compensations.store', [$locale, $employee->id])}} @else {{route('employee.compensations.store', ['en', $employee->id])}} @endif" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="employee_id" value="{{$employee->id}}">
                <div class="modal-header">
                    {{__('language.Create Compensation')}}
                </div>
                <div class="modal-body">
                    @if(Auth::user()->isAdmin() || isset($perms['compensation']['all']) && in_array('manage setting compensation', $perms['compensation']['all']))
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="control-label" for="effective_date">{{__('language.Effective Date')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control flatpickr-basic flatpickr-input effective_date" name="effective_date" id="effective_date" placeholder="YYYY-MM-DD" required>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->isAdmin() || isset($perms['compensation']['all']) && in_array('manage setting compensation', $perms['compensation']['all']))
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Pay')}} {{__('language.Schedule')}} <span class="text-danger">*</span></label>
                                    <select name="pay_schedule_id" id="pay_schedule_id" class="form-control" required>
                                        <option value="">Select Pay Schedule</option>
                                        @foreach($paySchedules as $key => $paySchedule)
                                            <option value="{{$paySchedule->id}}" @if(isset($employee->assignedPaySchedule->id) && $employee->assignedPaySchedule->id == $paySchedule->id) selected @endif>
                                                {{$paySchedule->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->isAdmin() || isset($perms['compensation']['all']) && in_array('manage setting compensation', $perms['compensation']['all']))
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Pay Type')}} <span class="text-danger">*</span></label>
                                    <select name="pay_type" id="pay_type" required class="form-control" required>
                                        <option value="salary">Salary</option>
                                        <option value="hourly">Hourly</option>
                                        <option value="commission">Commission Only</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->isAdmin() || isset($perms['compensation']['all']) && in_array('manage setting compensation', $perms['compensation']['all']))
                        <div class="row">
                            <div class="col">
                                <h6>{{__('language.Current Pay Rate')}}: 
                                    @if($employee['employeeCompensations']->count() > 0)
                                        @foreach($employee['employeeCompensations'] as $key => $employeeCompensation)
                                            @if($employeeCompensation->status == 'active')
                                                {{$employeeCompensation->pay_rate}}
                                            @endif
                                        @endforeach
                                    @else
                                        N/A
                                    @endif
                                </h6>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->isAdmin() || isset($perms['compensation']['all']) && in_array('manage setting compensation', $perms['compensation']['all']))
                        <div class="row">
                            <div class="col-5">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Pay Rate')}} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="pay_rate" name="pay_rate" placeholder="Enter pay rate" required>
                                </div>
                            </div>
                            <h6 class="mt-3">per</h6>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Pay Rate Frequency')}}</label>
                                    <select name="pay_rate_frequency" id="pay_rate_frequency" class="form-control">
                                        <option value="per per day">Day</option>
                                        <option value="per per week">Week</option>
                                        <option value="per per month">Month</option>
                                        <option value="per per quarter">Quarter</option>
                                        <option value="per per year">Year</option>
                                        <option value="per per pay period">Pay Period</option>
                                        <option value="per per piece">Piece</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->isAdmin() || isset($perms['compensation']['all']) && in_array('manage setting compensation', $perms['compensation']['all']))
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Overtime Status')}}</label>
                                    <select name="overtime_status" id="overtime_status" required class="form-control">
                                        <option value="exempt">Exempt</option>
                                        <option value="non exempt">Non-exempt</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->isAdmin() || isset($perms['compensation']['all']) && in_array('manage setting compensation', $perms['compensation']['all']))
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Change Reason')}} <span class="text-danger">*</span></label>
                                    <select name="change_reason_id" id="change_reason_id" class="form-control" required>
                                        @foreach($changeReasons as $key => $changeReason)
                                            <option value="{{$changeReason->id}} ">
                                                {{$changeReason->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->isAdmin() || isset($perms['compensation']['all']) && in_array('manage setting compensation', $perms['compensation']['all']))
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Comment')}} <span class="text-danger">*</span></label>
                                    <textarea name="comment" id="comment" class="form-control" placeholder="Enter comment here" required></textarea>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                    <button type="submit" class="btn btn-primary btn-ok">{{__('language.Add')}} {{__('language.Compensation')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-----END Create Job Information Modal---->

<div class="card-dataTable table-responsive">
    <table class="table table-sm" data-empty="No Employee Compensation Available">
        <thead>
            <tr>
                <th class="text-nowrap"> {{__('language.Effective Date')}}</th>

                @if(Auth::user()->isAdmin() || isset($perms['compensation']['all']) && in_array('manage setting compensation', $perms['compensation']['all']))
                    <th class="text-nowrap"> {{__('language.Pay Schedule')}}</th>
                @endif
                @if(Auth::user()->isAdmin() || isset($perms['compensation']['all']) && in_array('manage setting compensation', $perms['compensation']['all']))
                    <th class="text-nowrap"> {{__('language.Pay Type')}}</th>
                @endif
                
                <th class="text-nowrap"> {{__('language.Pay Rate')}}</th>

                <th class="text-nowrap"> {{__('language.Pay Rate Frequency')}}</th>

                @if(Auth::user()->isAdmin() || isset($perms['compensation']['all']) && in_array('manage setting compensation', $perms['compensation']['all']))
                    <th class="text-nowrap"> {{__('language.Overtime Status')}}</th>
                @endif

                <th class="text-nowrap">{{__('language.Change Reason')}}</th>

                <th class="text-nowrap">{{__('language.Status')}}</th>

                <th class="text-nowrap">{{__('language.Comment')}}</th>

                @if(Auth::user()->isAdmin() || isset($perms['compensation']['all']) && in_array('manage setting compensation', $perms['compensation']['all']))
                    <th class="text-nowrap">{{__('language.Actions')}}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @if($employee['employeeCompensations']->count() > 0)
                @foreach($employee['employeeCompensations'] as $key => $compensation)
                    <tr>
                        <td class="text-nowrap">{{$compensation->effective_date}}</td>

                        @if(Auth::user()->isAdmin() || isset($perms['compensation']['all']) && in_array('manage setting compensation', $perms['compensation']['all']))
                            <td class="text-nowrap">{{ucwords($compensation->paySchedule->name)}}</td>
                        @endif
                        @if(Auth::user()->isAdmin() || isset($perms['compensation']['all']) && in_array('manage setting compensation', $perms['compensation']['all']))
                            <td class="text-nowrap">{{ucwords($compensation->pay_type)}}</td>
                        @endif

                        <td class="text-nowrap">{{$compensation->pay_rate}}</td>

                        <td class="text-nowrap">{{ucwords($compensation->pay_rate_frequency)}}</td>

                        @if(Auth::user()->isAdmin() || isset($perms['compensation']['all']) && in_array('manage setting compensation', $perms['compensation']['all']))
                            <td class="text-nowrap">{{ucwords($compensation->overtime_status)}}</td>
                        @endif

                        <td class="text-nowrap">{{ucwords($compensation->changeReason->name)}}</td>

                        @if($compensation->status == 'active')
                            <td class="text-nowrap"><div class="badge badge-glow badge-success">Active</div></td>
                        @else
                            <td class="text-nowrap"><div class="badge badge-glow badge-danger">Inactive</div></td>
                        @endif

                        <td class="text-nowrap">
                            <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#comment{{$compensation->id}}"><i data-feather="eye"></i></a>
                        </td>

                        @if(Auth::user()->isAdmin() || isset($perms['compensation']['all']) && in_array('manage setting compensation', $perms['compensation']['all']))
                            <td class="text-nowrap">
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#edit{{$compensation->id}}"><i data-feather="edit-2"></i></a>

                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete{{$compensation->id}}"> <i data-feather='trash-2'></i> </a>
                            </td>
                        @endif
                    </tr>

                    <div class="modal modal-slide-in fade" id="comment{{$compensation->id}}" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content pt-0">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel1">{{__('language.Comment')}}</h4>
                                </div>
                                <div class="modal-body mt-1 mb-1">
                                    <textarea class="form-control" readonly >{{$compensation->comment}}</textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-warning" data-dismiss="modal">{{__('language.Close')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(Auth::user()->isAdmin() || isset($perms['compensation']['all']) && in_array('manage setting compensation', $perms['compensation']['all']))
                        <div class="modal fade" id="confirm-delete{{$compensation->id}}" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="@if(isset($locale)) {{route('employee.compensations.destroy', [$locale, $employee->id, $compensation->id])}} @else {{route('employee.compensations.destroy', ['en', $employee->id, $compensation->id])}} @endif" method="post">
                                        <input type="hidden" name="employee_id" value="{{$employee->id}}">
                                        <input type="hidden" name="compensation_id" value="{{$compensation->id}}">
                                        {{method_field('DELETE')}}
                                        {{ csrf_field() }}
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel1">{{__('language.Delete Compensation')}}</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {{__('language.Are you sure you want to delete this compensation?')}}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                            <button type="submit" class="btn btn-danger btn-ok">{{__('language.Delete')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Compensation Modal -->
                        <div class="modal modal-slide-in fade" id="edit{{$compensation->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content pt-0">
                                    <form action="@if(isset($locale)) {{route('employee.compensations.update', [$locale, $employee->id, $compensation->id])}} @else {{route('employee.compensations.update', ['en', $employee->id, $compensation->id])}} @endif" id="form-compensation" method="post">
                                        <input type="hidden" name="employee_id" value="{{$employee->id}}">
                                        <input type="hidden" name="compensation_id" value="{{$compensation->id}}">
                                        {{ csrf_field() }}
                                        @method('PUT')
                                        <div class="modal-header">
                                          <h4 class="modal-title" id="myModalLabel1">{{__('language.Update Compensation')}}</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="control-label" for="effective_date">{{__('language.Effective Date')}} <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control flatpickr-basic flatpickr-input effective_date" name="effective_date" id="effective_date" placeholder="YYYY-MM-DD" required value="{{old('effective_date', $compensation->effective_date)}}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('language.Pay')}} {{__('language.Schedule')}} <span class="text-danger">*</span></label>
                                                        <select name="pay_schedule_id" id="pay_schedule_id" class="form-control" required>
                                                                <option value="">Select Pay Schedule</option>
                                                            @foreach($paySchedules as $key => $paySchedule)
                                                                <option value="{{$paySchedule->id}}" @if(old('pay_schedule_id', $compensation->pay_schedule_id) == $paySchedule->id) selected @endif>
                                                                    {{$paySchedule->name}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('language.Pay Type')}} <span class="text-danger">*</span></label>
                                                        <select name="pay_type" id="pay_type" required class="form-control" required>
                                                            <option value="salary" @if(old('pay_type', $compensation->pay_type) == "salary") selected @endif>Salary</option>
                                                            <option value="hourly" @if(old('pay_type', $compensation->pay_type) == "hourly") selected @endif>Hourly</option>
                                                            <option value="commission" @if(old('pay_type', $compensation->pay_type) == "commission") selected @endif>Commission Only</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <h6>{{__('language.Current Pay Rate')}}: 
                                                        @if($employee['employeeCompensations']->count() > 0)
                                                            @foreach($employee['employeeCompensations'] as $key => $employeeCompensation)
                                                                @if($employeeCompensation->status == 'active')
                                                                    {{$employeeCompensation->pay_rate}}
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            N/A
                                                        @endif
                                                    </h6>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('language.Pay Rate')}} <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" id="pay_rate" name="pay_rate" placeholder="Enter pay rate" required value="{{$compensation->pay_rate}}">
                                                    </div>
                                                </div>
                                                <h6 class="mt-3">per</h6>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('language.Pay Rate Frequency')}}</label>
                                                        <select name="pay_rate_frequency" id="pay_rate_frequency" class="form-control">
                                                            <option value="per day" @if(old('pay_rate_frequency', $compensation->pay_rate_frequency) == "per day") selected @endif>Day</option>
                                                            <option value="per week" @if(old('pay_rate_frequency', $compensation->pay_rate_frequency) == "per week") selected @endif>Week</option>
                                                            <option value="per month" @if(old('pay_rate_frequency', $compensation->pay_rate_frequency) == "per month") selected @endif>Month</option>
                                                            <option value="per quarter" @if(old('pay_rate_frequency', $compensation->pay_rate_frequency) == "per quarter") selected @endif>Quarter</option>
                                                            <option value="per year" @if(old('pay_rate_frequency', $compensation->pay_rate_frequency) == "per year") selected @endif>Year</option>
                                                            <option value="per pay period" @if(old('pay_rate_frequency', $compensation->pay_rate_frequency) == "per pay period") selected @endif>Pay Period</option>
                                                            <option value="per piece" @if(old('pay_rate_frequency', $compensation->pay_rate_frequency) == "per piece") selected @endif>Piece</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('language.Overtime Status')}}</label>
                                                        <select name="overtime_status" id="overtime_status" required class="form-control">
                                                            <option value="exempt" @if(old('overtime_status', $compensation->overtime_status) == "exempt") selected @endif>Exempt</option>
                                                            <option value="non exempt" @if(old('overtime_status', $compensation->overtime_status) == "non exempt") selected @endif>Non-exempt</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('language.Change Reason')}} <span class="text-danger">*</span></label>
                                                        <select name="change_reason_id" id="change_reason_id" class="form-control" required>
                                                            @foreach($changeReasons as $key => $changeReason)
                                                                <option value="{{$changeReason->id}}" @if(old('change_reason_id', $compensation->change_reason_id) == $changeReason->id) selected @endif>
                                                                    {{$changeReason->name}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('language.Comment')}} <span class="text-danger">*</span></label>
                                                        <textarea name="comment" id="comment" class="form-control" placeholder="Enter comment here" required>{{old('comment', $compensation->comment)}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                            <button type="submit" class="btn btn-primary btn-ok">{{__('language.Update')}} {{__('language.Compensation')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--end: Edit Compensation Modal -->
                    @endif
                @endforeach
            @endif
        </tbody>
    </table>
</div>

@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection
@section('page-script')
  <!-- Page js files -->
  <script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
@endsection


