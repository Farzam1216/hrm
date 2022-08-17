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
        <h4 class="mb-0"><i class="font-medium-3 mr-25" data-feather='info'></i>Job Information</h4>
        </h6>
    </div>
    <div class="dt-action-buttons text-right">
        <div class="dt-buttons flex-wrap d-inline-flex">
            @if(Auth::user()->isAdmin() || (isset($perms['job'][$employee->id]) && in_array('edit', $perms['job'][$employee->id])))
                <button type="button" class="btn btn-primary btn-rounded float-right employee-edit-btns"
                        data-toggle="modal"
                        data-target="#create_job"><i class="fa fa-plus"></i>&nbsp {{__('language.Add')}}
                    {{__('language.Job')}} {{__('language.Information')}}
                </button>
            @endif
        </div>
    </div>
</div>
<!-----Create Job Information Modal---->
<div class="modal modal-slide-in fade" id="create_job" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content pt-0">
            <form action="@if(isset($locale)){{url($locale.'/employees/'.$employee->id.'/jobs')}}@else{{url('en/employees/'.$employee->id.'/jobs')}}@endif"
                  id="create_job_form" method="post">
                {{ csrf_field() }}
                <div class="modal-header">
                    {{__('language.Create')}} {{__('language.Job')}}
                </div>
                <div class="modal-body">
                    @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob effective_date']))
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="control-label"
                                           for="effective_date">{{__('language.Effective Date')}}</label>
                                    <input type="text" class="form-control flatpickr-basic flatpickr-input effective_date" name="effective_date" id="effective_date" placeholder="YYYY-MM-DD">
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob designation_id']))
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Job')}} {{__('language.Title')}}</label>
                                    <select name="designation_id" id="designation_id" class="form-control">
                                        @foreach($designations as $key => $designation)
                                            <option value="{{$designation->id}} ">
                                                {{$designation->designation_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob report_to']))
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Reports To')}}</label>
                                    <select name="report_to" id="report_to" required class="form-control">
                                        @foreach($employees as $key => $reportingEmployee)
                                            <option value="{{$reportingEmployee->id}} ">
                                                {{$reportingEmployee->FullName}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob department_id']))
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Department')}}</label>
                                    <select name="department_id" id="department_id" required class="form-control">
                                        @foreach($departments as $key => $department)
                                            <option value="{{$department->id}} ">
                                                {{$department->department_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob division_id']))
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Division')}}</label>
                                    <select name="division_id" id="division_id" required class="form-control">
                                        @foreach($divisions as $key => $division)
                                            <option value="{{$division->id}} ">
                                                {{$division->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob location_id']))
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Location')}}</label>
                                    <select name="location_id" id="location_id" required class="form-control">
                                        @foreach($locations as $key => $location)
                                            <option value="{{$location->id}} ">
                                                {{$location->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">{{__('language.Cancel')}}</button>
                    <button type="submit" class="btn btn-primary btn-ok"
                            onclick="createJob()">{{__('language.Add')}} {{__('language.Job')}} {{__('language.Information')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-----END Create Job Information Modal---->

<table class="table dt-job-information  " data-empty="No Job Information Available">
    <thead>
    <tr>
        @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob effective_date']))
            <th> {{__('language.Effective Date')}}</th>
        @endif
        @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob location_id']))
            <th> {{__('language.Location')}}</th>
        @endif
        @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob division_id']))
            <th> {{__('language.Division')}}</th>
        @endif
        @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob department_id']))
            <th> {{__('language.Department')}}</th>
        @endif
        @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob designation_id']))
            <th> {{__('language.Job Title')}}</th>
        @endif
        @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob report_to']))
            <th> {{__('language.Reports To')}}</th>
        @endif
        @if(Auth::user()->isAdmin() || (isset($perms['job'][$employee->id]) && in_array('edit', $perms['job'][$employee->id])))
            <th>{{__('language.Actions')}}</th>
        @endif

    </tr>
    </thead>

    <tbody>
    @if($jobs->count() > 0)
        @foreach($jobs as $key => $job)
            <tr>
                @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob effective_date']))
                    <td>{{$job->effective_date}}</td>
                @endif
                @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob location_id']))
                    <td>{{$job->location->name}}</td>
                @endif
                @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob division_id']))
                    <td>{{$job->division->name}}</td>
                @endif
                @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob department_id']))
                    <td>{{$job->department->department_name}}</td>
                @endif
                @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob designation_id']))
                    <td>{{$job->designation->designation_name}}</td>
                @endif
                @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob report_to']))
                    <td>{{$job->manager->FullName}}</td>
                @endif
                @if(Auth::user()->isAdmin() || (isset($perms['job'][$employee->id]) && in_array('edit', $perms['job'][$employee->id])))
                    <td class="text-nowrap">
                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal"
                           data-target="#edit{{$job->id}}" data-original-title="Edit"><i data-feather="edit-2"
                                                                                         class="mr-40"></i></a>
                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal"
                           data-target="#confirm-delete{{$job->id}}" data-original-title="Close"> <i
                                    data-feather='trash-2'></i> </a>
                        <div class="modal fade" id="confirm-delete{{$job->id}}" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="@if(isset($locale)){{url($locale.'/employees/'.$employee->id.'/jobs/'.$job->id)}}@else{{url('en/employees/'.$employee->id.'/jobs/'.$job->id)}}@endif"
                                          method="post">
                                        {{method_field('DELETE')}}
                                        {{ csrf_field() }}
                                        <div class="modal-header">
                                          <h4 class="modal-title" id="myModalLabel1">{{__('language.Delete Job Information')}}</h4>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                            {{__('language.Are you sure you want to delete this Job Info?')}}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">{{__('language.Cancel')}}</button>
                                            <button type="submit"
                                                    class="btn btn-danger btn-ok">{{__('language.Delete')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                @endif
            </tr>
            <!-- Edit Job Information Modal -->
            <div class="modal modal-slide-in fade" id="edit{{$job->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content pt-0">
                        <form action="@if(isset($locale)){{url($locale.'/employees/'.$employee->id.'/jobs/'.$job->id)}}@else{{url('en/employees/'.$employee->id.'/jobs/'.$job->id)}}@endif"
                              id="edit_job_form" method="post">
                            {{ csrf_field() }}
                            @method('PUT')
                            <div class="modal-header">
                              <h4 class="modal-title" id="myModalLabel1">{{__('language.Update Job Information')}}</h4>
                            </div>
                            <div class="modal-body">
                                @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob effective_date']))
                                    <div class="form-group">
                                        <label class="control-label">{{__('language.Effective Date')}}</label>
                                        <input type="text" class="form-control flatpickr-basic flatpickr-input" name="effective_date" id="effective_date" required placeholder="YYYY-MM-DD" value="{{old('employmentStatuses',$job->effective_date)}}" @if(in_array('effective_date',$disableFields)) disabled @endif>
                                    </div>
                                @endif
                                @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob designation_id']))
                                    <div class="form-group">
                                        <label class="control-label">{{__('language.Job Title')}}</label>
                                        <select name="designation_id" id="designation_id" required
                                                @if(in_array('designation_id',$disableFields)) disabled
                                                @endif class="form-control">
                                            @foreach($designations as $key => $designation)
                                                <option value="{{$designation->id}}"
                                                        @if($job->designation_id == $designation->id) selected @endif>
                                                    {{$designation->designation_name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob report_to']))
                                    <div class="form-group">
                                        <label class="control-label">{{__('language.Report To')}}</label>
                                        <select name="report_to" id="report_to" required
                                                @if(in_array('report_to',$disableFields)) disabled
                                                @endif class="form-control">
                                            @foreach($employees as $key => $employ)
                                                <option value="{{$employ->id}}"
                                                        @if($employ->id == $job->report_to) selected @endif>
                                                    {{$employ->FullName}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob department_id']))
                                    <div class="form-group">
                                        <label class="control-label">{{__('language.Department')}}</label>
                                        <select name="department_id" id="department_id" required
                                                @if(in_array('department_id',$disableFields)) disabled
                                                @endif class="form-control">
                                            @foreach($departments as $key => $department)
                                                <option value="{{$department->id}} "
                                                        @if($department->id == $job->department_id) selected @endif>
                                                    {{$department->department_name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob division_id']))
                                    <div class="form-group">
                                        <label class="control-label">{{__('language.Division')}}</label>
                                        <select name="division_id" id="division_id" required
                                                @if(in_array('division_id',$disableFields)) disabled
                                                @endif class="form-control">
                                            @foreach($divisions as $key => $division)
                                                <option value="{{$division->id}} "
                                                        @if($division->id == $job->division_id) selected @endif>
                                                    {{$division->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                @if(Auth::user()->isAdmin() || isset($perms['job'][$employee->id]['employeejob location_id']))
                                    <div class="form-group">
                                        <label class="control-label">{{__('language.Location')}}</label>
                                        <select name="location_id" id="location_id" required
                                                @if(in_array('location_id',$disableFields)) disabled
                                                @endif class="form-control">
                                            @foreach($locations as $key => $location)
                                                <option value="{{$location->id}} "
                                                        @if($location->id == $job->location_id) selected @endif>
                                                    {{$location->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">{{__('language.Cancel')}}</button>
                                <button type="submit"
                                        class="btn btn-primary btn-ok">{{__('language.Update')}} {{__('language.Job Information')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end: Edit Job Information Modal -->
        @endforeach
    @endif
    </tbody>

</table>

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


