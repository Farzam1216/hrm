@extends('layouts/contentLayoutMaster')
@section('title','Evaluations')
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
            @if(Auth::user()->isAdmin() || isset($permissions['performance'][Auth::id()]['performance review assign']))
                <div class="card-header border-bottom pt-1 pb-1">
                    <div class="head-label">
                        <h6 class="mb-0"></h6>
                    </div>
                    <div class="dt-action-buttons text-right">
                        <div class="dt-buttons flex-wrap d-inline-flex">
                            <button type="button" class="btn create-new btn-primary mr-1" onclick="window.location.href='@if(isset($locale)){{route('evaluations.assign', [$locale])}} @else {{route('evaluations.assign', ['en'])}} @endif'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"class="feather feather-link-2">
                                    <path d="M15 7h3a5 5 0 0 1 5 5 5 5 0 0 1-5 5h-3m-6 0H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3"></path>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg> {{__('language.Assign')}} {{__('language.Evaluation')}}
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card-datatable table-responsive pt-0" style="padding: 15px;">
                @if(Auth::user()->isAdmin() || isset($permissions['performance'][Auth::id()]))
                    <table class="dt-simple-header table">
                        @if(Auth::user()->isAdmin() || isset($permissions['performance'][Auth::id()]['performance review assign']))
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('language.Employee Name')}}</th>
                                    <th>{{__('language.Assigned To')}}</th>
                                    <th>{{__('language.Assigned Form')}}</th>
                                    <th>{{__('language.Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $key => $employee)
                                    @if(!$employee->isAdmin())
                                        <tr>
                                            <td>{{$key}}</td>
                                            <td>{{$employee->firstname}} {{$employee->lastname}}</td>
                                            <td>
                                                @if($employee['performance_assigned'] != '[]')
                                                    @foreach($employee['performance_assigned'] as $assignment)
                                                        {{$assignment->manager->firstname}} {{$assignment->manager->lastname}}
                                                    @endforeach
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($employee->assignedForm)
                                                    {{$employee->assignedForm->form->name}}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="@if(isset($locale)){{route('evaluations.fill', [$locale, $employee->id])}} @else {{route('evaluations.fill', ['en', $employee->id])}} @endif" data-placement="top" data-toggle="tooltip" data-original-title="Fill Evaluation">
                                                    <i data-feather="file-text"></i>
                                                </a>

                                                <a type="button" class="btn btn-sm btn-clean btn-icon btn-icon-md waves-effect waves-light" href="@if(isset($locale)){{route('evaluations.employee-evaluations', [$locale, $employee->id])}} @else {{route('evaluations.employee-evaluations', ['en', $employee->id])}} @endif" data-placement="top" data-toggle="tooltip" data-original-title="Show Employee Evaluation">
                                                    <i data-feather="eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        @elseif(isset($permissions['performance'][Auth::id()]['performance review']))
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('language.Employee Name')}}</th>
                                    <th>{{__('language.Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $key = 0; @endphp
                                @foreach($employees as $employee)
                                    @if(!$employee->isAdmin() && $employee->manager_id == Auth::id())
                                        @php $key++; @endphp
                                        <tr>
                                            <td>{{$key}}</td>
                                            <td>{{$employee->firstname}} {{$employee->lastname}}</td>
                                            <td>
                                                @foreach($assignments as $assignment)
                                                    @if(!$employee->isAdmin() && $employee->id == $assignment->employee_id && $assignment->manager_id == Auth::id())
                                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md" href="@if(isset($locale)){{route('evaluations.fill', [$locale, $employee->id])}} @else {{route('evaluations.fill', ['en', $employee->id])}} @endif" data-placement="top" data-toggle="tooltip" data-original-title="Fill Evaluation">
                                                            <i data-feather="file-text"></i>
                                                        </a>
                                                    @endif
                                                @endforeach
                                                
                                                <a type="button" class="btn btn-sm btn-clean btn-icon btn-icon-md waves-effect waves-light" href="@if(isset($locale)){{route('evaluations.employee-evaluations', [$locale, $employee->id])}} @else {{route('evaluation.employee-evaluations', ['en', $employee->id])}} @endif" data-placement="top" data-toggle="tooltip" data-original-title="Show Employee Evaluation">
                                                    <i data-feather="eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        @endif
                    </table>
                @else
                    <table class="dt-simple-header table dt-responsive">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__('language.Submitted By')}}</th>
                                <th>{{__('language.Submittion')}} {{__('language.Date')}} (dd-mm-yyyy)</th>
                                <th>{{__('language.Status')}}</th>
                                <th>{{__('language.Actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($authEmployeeQuestionnaires as $key => $questionnaire)
                                @if($questionnaire->employee_can_view == true)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$questionnaire->submitters->firstname}} {{$questionnaire->submitters->lastname}}</td>
                                        <td>{{$questionnaire->created_at->day}}-{{$questionnaire->created_at->month}}-{{$questionnaire->created_at->year}}</td>
                                        <td>
                                            @if($questionnaire->status == true)
                                                <div class="badge badge-light-success">Approved</div>
                                            @else
                                                <div class="badge badge-light-danger">Rejected</div>
                                            @endif
                                        </td>
                                        <td>
                                            <a type="button" class="btn btn-sm btn-clean btn-icon btn-icon-md waves-effect waves-light" href="@if(isset($locale)){{route('evaluations.show', [$locale, $questionnaire->id])}} @else {{route('evaluations.show', ['en', $questionnaire->id])}} @endif" data-placement="top" data-toggle="tooltip" data-original-title="Show Evaluation">
                                                <i data-feather="eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @endif
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