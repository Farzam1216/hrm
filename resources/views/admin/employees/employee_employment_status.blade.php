<div class="card-header border-bottom mt-2">
    <div class="head-label">
        <h4 class="mb-0"><i class="font-medium-3 mr-25" data-feather='clipboard'></i>Employment Status</h4>
    </div>
    <div class="dt-action-buttons text-right">
        <div class="dt-buttons flex-wrap d-inline-flex">
            @if(Auth::user()->isAdmin() || (isset($perms['employmentStatus'][$employee->id]) && in_array('edit', $perms['employmentStatus'][$employee->id])))
                <button type="button" class="btn btn-primary btn-rounded float-right employee-edit-btns"
                        data-toggle="modal"
                        data-target="#create_employment_status"><i class="fa fa-plus"></i>&nbsp
                    {{__('language.Add')}}
                    {{__('language.Employment Status')}}
                </button>
            @endif
        </div>
    </div>
</div>
<!-----Create employee Employment status Modal---->
<div class="modal modal-slide-in fade" id="create_employment_status" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content pt-0">
            <form action="@if(isset($locale)){{url($locale.'/employees/'.$employee->id.'/employment-status')}}@else{{url('en/employees/'.$employee->id.'/employment-status')}}@endif"
                  id="employment_status_form" method="post">
                {{ csrf_field() }}
                <div class="modal-header">
                    {{__('language.Create')}} {{__('language.Employment Status')}}
                </div>
                <div class="modal-body">

                    @if(Auth::user()->isAdmin() || isset($perms['employmentStatus'][$employee->id]['employeeemploymentstatus effective_date']))
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="control-label"
                                           for="effective_date">{{__('language.Effective Date')}}</label>
                                    <input type="date" name="effective_date" id="effective_date"
                                           placeholder="{{__('language.Select')}} {{__('language.Effective Date')}}"
                                           class="form-control effective_date" required>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->isAdmin() || isset($perms['employmentStatus'][$employee->id]['employeeemploymentstatus employment_status_id']))
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Employment Status')}}</label>
                                    <select name="employment_status" class="form-control">
                                        @foreach($employment_statuses as $key => $employment_status)
                                            <option value="{{$employment_status->id}}">
                                                {{$employment_status->employment_status}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->isAdmin() || isset($perms['employmentStatus'][$employee->id]['employeeemploymentstatus comments']))
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Comment')}}</label>
                                    <textarea class="form-control" name="comment" id="" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">{{__('language.Cancel')}}</button>
                    <button type="submit"
                            class="btn btn-primary btn-ok">{{__('language.Add')}} {{__('language.Employment Status')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-----END Create employee Employment status Modal---->

<table class="dt-employee-edit table dtr-column">
    <thead>
    <tr>
        @if(Auth::user()->isAdmin() || isset($perms['employmentStatus'][$employee->id]['employeeemploymentstatus effective_date']))
            <th> {{__('language.Effective Date')}}</th>
        @endif
        @if(Auth::user()->isAdmin() || isset($perms['employmentStatus'][$employee->id]['employeeemploymentstatus employment_status_id']))
            <th> {{__('language.Employment Status')}}</th>
        @endif
        @if(Auth::user()->isAdmin() || isset($perms['employmentStatus'][$employee->id]['employeeemploymentstatus comments']))
            <th> {{__('language.Comment')}}</th>
        @endif
            @if(Auth::user()->isAdmin() || (isset($perms['employmentStatus'][$employee->id]) && in_array('edit', $perms['employmentStatus'][$employee->id])))
        <th>{{__('language.Actions')}}</th>
                @endif
    </tr>
    </thead>
    @if($employeeEmploymentStatuses->count() > 0)
        <tbody>
        @foreach($employeeEmploymentStatuses as $key => $employeeEmploymentStatus)
            <tr>
                @if(Auth::user()->isAdmin() || isset($perms['employmentStatus'][$employee->id]['employeeemploymentstatus effective_date']))
                    <td>{{$employeeEmploymentStatus->effective_date}}</td>
                @endif
                @if(Auth::user()->isAdmin() || isset($perms['employmentStatus'][$employee->id]['employeeemploymentstatus employment_status_id']))
                    <td>{{$employeeEmploymentStatus->employmentStatus->employment_status}}</td>
                @endif
                @if(Auth::user()->isAdmin() || isset($perms['employmentStatus'][$employee->id]['employeeemploymentstatus comments']))
                    @if($employeeEmploymentStatus->comment)
                        <td>{{Illuminate\Support\Str::limit($employeeEmploymentStatus->comment, 40) }}</td>
                    @else
                        <td><p>--</p></td>
                    @endif
                @endif
                    @if(Auth::user()->isAdmin() || (isset($perms['employmentStatus'][$employee->id]) && in_array('edit', $perms['employmentStatus'][$employee->id])))
                <td class="text-nowrap">
                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal"
                       data-target="#editEmploymentStatus{{$employeeEmploymentStatus->id}}"
                       data-original-title="Employment Status Edit"> <i data-feather='edit-2'></i></a>
                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal"
                       data-target="#deleteEmploymentStatus{{$employeeEmploymentStatus->id}}"
                       data-original-title="Close">
                        <i data-feather='trash-2'></i> </a>
                    <div class="modal fade" id="deleteEmploymentStatus{{$employeeEmploymentStatus->id}}" tabindex="-1"
                         role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form
                                        action="@if(isset($locale)){{url($locale.'/employees/'.$employee->id.'/employment-status/'.$employeeEmploymentStatus->id)}}@else{{url('en/employees/'.$employee->id.'/employment-status/'.$employeeEmploymentStatus->id)}}@endif"
                                        method="post">
                                    {{method_field('DELETE')}}
                                    {{ csrf_field() }}
                                    <div class="modal-header">
                                        {{__('language.Are you sure you want to delete this Employment Status')}}?
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
            <!-- Edit Employee Employment Status Modal -->
            <div class="modal modal-slide-in fade" id="editEmploymentStatus{{$employeeEmploymentStatus->id}}" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content pt-0">
                        <form action="@if(isset($locale)){{url($locale.'/employees/'.$employee->id.'/employment-status/'.$employeeEmploymentStatus->id)}}@else{{url('en/employees/'.$employee->id.'/employment-status/'.$employeeEmploymentStatus->id)}}@endif"
                                id="edit_employment_status_form" method="post">
                            {{ csrf_field() }}
                            @method('PUT')
                            <div class="modal-header">
                                {{__('language.Update')}} {{__('language.Employment Status')}}
                            </div>
                            <div class="container">
                                @if(Auth::user()->isAdmin() || isset($perms['employmentStatus'][$employee->id]['employeeemploymentstatus effective_date']))
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Effective Date')}}</label>
                                    <input type="date" id="effective_date" name="effective_date"
                                           placeholder="{{__('language.Select')}} {{__('language.Effective Date')}}"
                                           value="{{old('employmentStatuses',$employeeEmploymentStatus->effective_date)}}"
                                           @if(in_array('effective_date',$disableFields)) disabled
                                           @endif class="form-control">
                                </div>
                                @endif
                                    @if(Auth::user()->isAdmin() || isset($perms['employmentStatus'][$employee->id]['employeeemploymentstatus employment_status_id']))
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Employment Status')}}</label>
                                    <select name="employment_status"
                                            @if(in_array('employment_status',$disableFields)) disabled
                                            @endif class="form-control">
                                        @foreach($employment_statuses as $key => $employment_status)
                                            <option value="{{$employment_status->id}}"
                                                    @if ($employeeEmploymentStatus->employment_status_id == $employment_status->id) selected @endif>
                                                {{$employment_status->employment_status}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                    @endif
                                    @if(Auth::user()->isAdmin() || isset($perms['employmentStatus'][$employee->id]['employeeemploymentstatus comments']))
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Comment')}}</label>
                                    <textarea class="form-control" name="comment" id="" rows="2"
                                              required>{{$employeeEmploymentStatus->comment}}</textarea>
                                </div>
                                        @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">{{__('language.Cancel')}}</button>
                                <button type="submit"
                                        class="btn btn-primary btn-ok">{{__('language.Update')}} {{__('language.Employment Status')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end: edit Employee Employment Status Modal -->
        @endforeach
        @endif
        </tbody>
</table>
