@extends('layouts/contentLayoutMaster')
@section('title','Employee Evaluations')
@section('vendor-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-bottom pt-1 pb-1">
                <div class="head-label">
                    <h6 class="mb-0">{{$employee->firstname}} {{$employee->lastname}}</h6>
                </div>
                <div class="dt-action-buttons text-right">
                    <div class="dt-buttons flex-wrap d-inline-flex">
                        <button type="button" class="btn create-new btn-primary mr-1" onclick="window.location.href='@if(isset($locale)){{route('evaluations.index', [$locale])}} @else {{route('evaluations.index', ['en'])}} @endif'">
                            <i data-feather="chevron-left"></i> {{__('language.Back')}}
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive pt-0" style="padding: 15px;">
                <table class="table table-sm dt-simple-header">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{__('language.Submitted By')}}</th>
                            <th>{{__('language.Submittion')}} {{__('language.Date')}} (dd-mm-yyyy)</th>
                            <th>{{__('language.Approving Authority')}}</th>
                            <th>{{__('language.Status')}}</th>
                            <th>{{__('language.Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($questionnaires as $key => $questionnaire)
                            @if(Auth::user()->isAdmin() || isset($permissions['performance'][Auth::id()]['performance review decision']) || $questionnaire->submitter_id == Auth::id())
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$questionnaire->submitters->firstname}} {{$questionnaire->submitters->lastname}}</td>
                                    <td>{{$questionnaire->created_at->day}}-{{$questionnaire->created_at->month}}-{{$questionnaire->created_at->year}}</td>
                                    <td>@if(isset($questionnaire->decision_authority->id))
                                            {{$questionnaire->decision_authority->firstname}} {{$questionnaire->decision_authority->lastname}}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($questionnaire->status == '1')
                                            @php $status = 'approved'; @endphp
                                            <div class="badge badge-light-success">Approved</div>
                                        @elseif($questionnaire->status == '0')
                                            @php $status = 'rejected'; @endphp
                                            <div class="badge badge-light-danger">Rejected</div>
                                        @else
                                            @php $status = ''; @endphp
                                            <div class="badge badge-light-secondary">Pending</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if(Auth::user()->isAdmin() || $status == '')
                                            @if(Auth::user()->isAdmin() || isset($permissions['performance'][Auth::id()]['performance review decision']))
                                                <a type="button" class="btn btn-sm btn-clean btn-icon btn-icon-md waves-effect waves-light" href="@if(isset($locale)){{route('evaluations.decision', [$locale, $questionnaire->employees->id, $questionnaire->id])}} @else {{route('evaluations.decision', ['en', $questionnaire->employees->id, $questionnaire->id])}} @endif" data-placement="top" data-toggle="tooltip" data-original-title="Submit Evaluation Decision">
                                                    <i data-feather='user-check'></i>
                                                </a>
                                            @endif
                                        @endif 
                                        
                                        @if(Auth::user()->isAdmin() || isset($permissions['performance'][Auth::id()]['performance review']))
                                            <a type="button" class="btn btn-sm btn-clean btn-icon btn-icon-md waves-effect waves-light" href="@if(isset($locale)){{route('evaluations.view', [$locale, $questionnaire->employees->id, $questionnaire->id])}} @else {{route('evaluations.view', ['en', $questionnaire->employees->id, $questionnaire->id])}} @endif" data-placement="top" data-toggle="tooltip" data-original-title="Show Employee Evaluation">
                                                <i data-feather="eye"></i>
                                            </a>

                                            @if(Auth::user()->isAdmin())
                                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="@if(isset($locale)){{route('evaluations.employee.edit', [$locale, $questionnaire->employees->id, $questionnaire->id])}} @else {{route('evaluations.employee.edit', ['en', $questionnaire->employees->id, $questionnaire->id])}} @endif" data-placement="top" data-toggle="tooltip" data-original-title="Edit Evaluation">
                                                    <i data-feather="edit-2"></i>
                                                </a>

                                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete{{ $questionnaire->id }}" data-original-title="Delete Evaluation">
                                                    <i data-feather="trash-2"></i>
                                                </a>
                                            @endif

                                            @if(!Auth::user()->isAdmin() && $questionnaire->submitter_id == Auth::id()  && $status == '' || isset($permissions['performance'][Auth::id()]['performance review decision'])  && $status == '')
                                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="@if(isset($locale)){{route('evaluations.employee.edit', [$locale, $questionnaire->employees->id, $questionnaire->id])}} @else {{route('evaluations.employee.edit', ['en', $questionnaire->employees->id, $questionnaire->id])}} @endif" data-placement="top" data-toggle="tooltip" data-original-title="Edit Evaluation">
                                                    <i data-feather="edit-2"></i>
                                                </a>

                                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete{{ $questionnaire->id }}" data-original-title="Delete Evaluation">
                                                    <i data-feather="trash-2"></i>
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                <div class="modal fade" id="confirm-delete{{ $questionnaire->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="@if(isset($locale)){{route('evaluations.destroy', [$locale, $questionnaire->id, $questionnaire->employee_id])}} @else {{route('evaluations.destroy', ['en', $questionnaire->id, $questionnaire->employee_id])}} @endif" method="post">
                                                <input name="_method" type="hidden" value="DELETE">
                                                {{ csrf_field() }}
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel1">Delete Questionnaire</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body mt-1">
                                                    <h5>Are you sure you want to delete this Questionnaire?</h5>
                                                    <br>
                                                    Questionnaire Creation Date: {{$questionnaire->created_at->day}}-{{$questionnaire->created_at->month}}-{{$questionnaire->created_at->year}}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                    <button type="submit" class="btn btn-danger waves-effect waves-float waves-light btn-ok">{{__('language.Delete')}}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
@section('vendor-script')
{{-- Vendor js files --}}
<script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap4.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/jszip.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection
@section('page-script')
{{-- Page js files --}}
<script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
@endsection