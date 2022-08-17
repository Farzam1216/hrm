@extends('layouts/contentLayoutMaster')
@section('title','Correction Requests')
@section('vendor-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection
@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table id="kt_table_1" class="table table-sm dt-simple-header">
                    <thead>
                        <tr>
                            @if(!Auth::user()->isAdmin() && !isset($permissions['attendance']['all']))
                                <th>SR.#</th>
                            @endif
                            @if(Auth::user()->isAdmin() || isset($permissions['attendance']['all']) && array_intersect(['manage employees attendance'], $permissions['attendance']['all'] ))
                                <th>Employee</th>
                            @endif
                            <th>Date</th>
                            <th>#</th>
                            <th>Time In</th>
                            <th>Time out</th>
                            <th>Reason</th>
                            <th>Attendance Status</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(Auth::user()->isAdmin() || isset($permissions['attendance']['all']) && array_intersect(['manage employees attendance'], $permissions['attendance']['all'] ))
                            @foreach($attendanceCorrections as $key => $correction)
                                <tr>
                                    @php $time_ins = explode(',',$correction->time_in); $count = 0; @endphp
                                    <td>{{$correction->employee->firstname}} {{$correction->employee->lastname}}</td>
                                    <td>
                                        {{\Carbon\Carbon::parse($correction->date)->format('d-m-Y')}}
                                    </td>
                                    <td>
                                        @php $total_entries = explode(',',$correction->time_in); $count = 0; @endphp
                                        @foreach($total_entries as $entrie)
                                            @php $count++; @endphp
                                            @if($entrie)
                                                {{$count}})
                                                @if($count > 0)
                                                <br>
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @php $time_ins = explode(',',$correction->time_in); $count = 0; @endphp
                                        @php $time_in_statuses = explode(',',$correction->time_in_status); $count = 0; @endphp
                                        @foreach($time_ins as $key => $time_in)
                                            @php $count++; @endphp
                                            @if($time_in != ' ')
                                                {{$time_in}}
                                                @if($time_in_statuses[$key] == 'Late')
                                                    <div class="avatar bg-danger">
                                                        <div class="badge">Late</div>
                                                    </div>
                                                @endif 
                                                @if($count > 0)
                                                <br>
                                                @endif
                                            @else
                                                -
                                                @if($count > 0)
                                                <br>
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @php $time_outs = explode(',',$correction->time_out); $count = 0; @endphp
                                        @foreach($time_outs as $time_out)
                                            @php $count++; @endphp
                                            @if($time_out != ' ')
                                                {{$time_out}}
                                                @if($count > 0)
                                                <br>
                                                @endif
                                            @else
                                                -
                                                @if($count > 0)
                                                <br>
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @php $reason_for_leavings = explode(',',$correction->reason_for_leaving); $count = 0; @endphp
                                        @foreach($reason_for_leavings as $reason_for_leaving)
                                            @php $count++; @endphp
                                            @if($reason_for_leaving != ' ')
                                                <a data-toggle="modal"
                                                   data-target="#reason-modal{{$correction->id}}{{$count}}">
                                                    <i data-feather="eye"></i>
                                                </a>
                                                @if($count > 0)
                                                <br>
                                                @endif
                                            @else
                                                -
                                                @if($count > 0)
                                                <br>
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @php $attendance_statuses = explode(',',$correction->attendance_status); $count = 0; @endphp
                                        @foreach($attendance_statuses as $attendance_status)
                                            @php $count++; @endphp
                                            @if($attendance_status != ' ')
                                                {{$attendance_status}}
                                                @if($count > 0)
                                                <br>
                                                @endif
                                            @else
                                                -
                                                @if($count > 0)
                                                <br>
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($correction->status)
                                            @if($correction->status == 'approved')
                                                <div class="badge badge-success">{{$correction->status}}</div>
                                            @endif
                                            @if($correction->status == 'rejected')
                                                <div class="badge badge-danger">{{$correction->status}}</div>
                                            @endif
                                            @if($correction->status == 'pending')
                                                <div class="badge badge-secondary">{{$correction->status}}</div>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($correction->status == 'pending')
                                            <a class="btn btn-sm hide-arrow waves-effect waves-float waves-light p-0" href="@if(isset($locale)) {{route('correction-requests.edit', [$locale, $correction->id])}} @else {{route('correction-requests.edit', ['en', $correction->id])}} @endif" data-toggle="tooltip" data-original-title="Edit"><i data-feather='edit-2'></i></a>
                                            <a class="btn btn-sm hide-arrow waves-effect waves-float waves-light p-0" href="@if(isset($locale)) {{route('correction-requests.decision.create', [$locale, $correction->id])}} @else {{route('correction-requests.decision.create', ['en', $correction->id])}} @endif" data-toggle="tooltip" data-original-title="Decision"><i data-feather='user-check'></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @php $count = 0; @endphp
                                @foreach($reason_for_leavings as $reason_for_leaving)
                                    @php $count++; @endphp
                                    @if($reason_for_leaving != ' ')
                                        <div class="modal modal-slide-in sidebar-todo-modal fade" id="reason-modal{{$correction->id}}{{$count}}">
                                            <div class="modal-dialog sidebar-lg">
                                                <div class="modal-content p-0 ">
                                                    <div class="modal-header align-items-center mb-1">
                                                        <h5 class="modal-title">Reason</h5>
                                                    </div>
                                                    <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                                                        <div class="action-tags">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <textarea disabled
                                                                                  class="form-control"
                                                                                  id="exampleFormControlTextarea1"
                                                                                  rows="3"
                                                                        >{{$reason_for_leaving}}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                        @else
                            @foreach($attendanceCorrections as $key => $correction)
                                @if($correction->employee->id == Auth::id())
                                    <tr>
                                        @php $time_ins = explode(',',$correction->time_in); $count = 0; @endphp
                                        <td>{{$key+1}}</td>
                                    <td>
                                        {{\Carbon\Carbon::parse($correction->date)->format('d-m-Y')}}
                                    </td>
                                    <td>
                                        @php $total_entries = explode(',',$correction->time_in); $count = 0; @endphp
                                        @foreach($total_entries as $entrie)
                                            @php $count++; @endphp
                                            @if($entrie)
                                                {{$count}})
                                                @if($count > 0)
                                                <br>
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @php $time_ins = explode(',',$correction->time_in); $count = 0; @endphp
                                        @php $time_in_statuses = explode(',',$correction->time_in_status); $count = 0; @endphp
                                        @foreach($time_ins as $key => $time_in)
                                            @php $count++; @endphp
                                            @if($time_in != ' ')
                                                {{$time_in}}
                                                @if($time_in_statuses[$key] == 'Late')
                                                    <div class="avatar bg-danger">
                                                        <div class="badge">Late</div>
                                                    </div>
                                                @endif 
                                                @if($count > 0)
                                                <br>
                                                @endif
                                            @else
                                                -
                                                @if($count > 0)
                                                <br>
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @php $time_outs = explode(',',$correction->time_out); $count = 0; @endphp
                                        @foreach($time_outs as $time_out)
                                            @php $count++; @endphp
                                            @if($time_out != ' ')
                                                {{$time_out}}
                                                @if($count > 0)
                                                <br>
                                                @endif
                                            @else
                                                -
                                                @if($count > 0)
                                                <br>
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @php $reason_for_leavings = explode(',',$correction->reason_for_leaving); $count = 0; @endphp
                                        @foreach($reason_for_leavings as $reason_for_leaving)
                                            @php $count++; @endphp
                                            @if($reason_for_leaving != ' ')
                                                <a data-toggle="modal"
                                                   data-target="#reason-modal{{$correction->id}}{{$count}}">
                                                    <i data-feather="eye"></i>
                                                </a>
                                                @if($count > 0)
                                                <br>
                                                @endif
                                            @else
                                                -
                                                @if($count > 0)
                                                <br>
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                        <td>
                                            @php $attendance_statuses = explode(',',$correction->attendance_status); $count = 0; @endphp
                                            @foreach($attendance_statuses as $attendance_status)
                                                @php $count++; @endphp
                                                @if($attendance_status != ' ')
                                                    {{$attendance_status}}
                                                    @if($count > 0)
                                                    <br>
                                                    @endif
                                                @else
                                                    -
                                                    @if($count > 0)
                                                    <br>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($correction->status)
                                                @if($correction->status == 'approved')
                                                    <div class="badge badge-success">{{$correction->status}}</div>
                                                @endif
                                                @if($correction->status == 'rejected')
                                                    <div class="badge badge-danger">{{$correction->status}}</div>
                                                @endif
                                                @if($correction->status == 'pending')
                                                    <div class="badge badge-secondary">{{$correction->status}}</div>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($correction->status == 'pending')
                                                <a class="btn btn-sm hide-arrow waves-effect waves-float waves-light p-0" href="@if(isset($locale)) {{route('correction-requests.edit', [$locale, $correction->id])}} @else {{route('correction-requests.edit', ['en', $correction->id])}} @endif" data-toggle="tooltip" data-original-title="Edit"><i data-feather='edit-2'></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                    @php $count = 0; @endphp
                                    @foreach($reason_for_leavings as $reason_for_leaving)
                                        @php $count++; @endphp
                                        @if($reason_for_leaving != ' ')
                                            <div class="modal modal-slide-in sidebar-todo-modal fade" id="reason-modal{{$correction->id}}{{$count}}">
                                                <div class="modal-dialog sidebar-lg">
                                                    <div class="modal-content p-0 ">
                                                        <div class="modal-header align-items-center mb-1">
                                                            <h5 class="modal-title">Reason</h5>
                                                        </div>
                                                        <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                                                            <div class="action-tags">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <textarea disabled
                                                                                      class="form-control"
                                                                                      id="exampleFormControlTextarea1"
                                                                                      rows="3"
                                                                            >{{$reason_for_leaving}}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('vendor-script')
{{-- vendor files --}}
<script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap4.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/jszip.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
@endsection
@stop