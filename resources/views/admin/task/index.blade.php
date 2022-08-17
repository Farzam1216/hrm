@extends('layouts/contentLayoutMaster')
@section('title','Tasks')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-bottom pb-1 pt-1">
                <div class="head-label">
                    <h6 class="mb-0"></h6>
                </div>
                <div class="dt-action-buttons text-right dt-buttons flex-wrap d-inline-flex pr-1">
                    <a href="@if(strcasecmp($task_type, "onboarding") == 0)
                    @if(isset($locale)){{url($locale.'/onboarding-tasks/create')}} @else {{url('en/onboarding-tasks/create')}} @endif
                            @else
                    @if(isset($locale)){{url($locale.'/offboarding-tasks/create')}} @else {{url('en/offboarding-tasks/create')}} @endif @endif" class="btn btn-brand btn-primary btn-elevate btn-icon-sm">
                        <i data-feather='plus'></i>
                        {{__('language.Add')}} {{__('language.Task')}}
                    </a>
                </div>
            </div> <!--end card-header-->
            <div class="card-datatable table-responsive pt-0 p-1" >
                <table class="dt-simple-header table dataTable dtr-column">
                    <thead class="thead-light" >
                        <tr>
                            <th>#</th>
                            <th> {{__('language.Task Name')}}</th>
                            <th> {{__('language.Task Category')}}</th>
                            <th> {{__('language.Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $key => $task)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$task->name}}</td>
                                <td>@if(isset($task->taskCategory)){{$task->taskCategory->task_category_name}} @else <div class="text-center"> - </div> @endif</td>
                                <td>
                                    <a data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Task" class="btn btn-sm btn-clean btn-icon btn-icon-md" href="
                                    @if($task->type == 0)
                                    @if(isset($locale))
                                    {{url($locale.'/onboarding-tasks/'.$task->id.'/edit')}}
                                    @else {{url('/en/onboarding-tasks/'.$task->id.'/edit')}} @endif
                                    @else
                                    @if(isset($locale))
                                    {{url($locale.'/offboarding-tasks/'.$task->id.'/edit')}}
                                    @else {{url('/en/offboarding-tasks/'.$task->id.'/edit')}} @endif @endif" > <i data-feather="edit-2"></i></a>

                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="modal" data-target="#confirm-delete{{ $task->id }}"> <i data-feather="trash-2"></i> </a>
                                </td>
                            </tr>

                            <div class="modal fade" id="confirm-delete{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5>Delete Task</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="
                                        @if($task->type == 0) 
                                            @if(isset($locale)){{url($locale.'/onboarding-tasks/'.$task->id)}} @else {{url('en/onboarding-tasks/'.$task->id)}} @endif
                                        @else
                                            @if(isset($locale)){{url($locale.'/offboarding-tasks/'.$task->id)}} @else {{url('en/offboarding-tasks/'.$task->id)}} @endif
                                        @endif" method="post">
                                            <input name="_method" type="hidden" value="DELETE">
                                            {{ csrf_field() }}
                                            <div class="modal-body">
                                                <h5>Are you sure you want to delete "{{$task->name}}" task? </h5>
                                                <hr>
                                                <h5 class="text-secondary">Delete this task for...</h5>
                                                <div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="delete-some" name="delete_all" class="custom-control-input" checked value="false"/>
                                                        <label class="custom-control-label text-dark" for="delete-some">Just new employees, but leave it for existing</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="delete-all" name="delete_all" class="custom-control-input" value="true"/>
                                                        <label class="custom-control-label text-dark" for="delete-all">Anywhere it is used</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-warning waves-effect" data-dismiss="modal">{{__('language.Cancel')}}</button>
                                                <button type="submit" class="btn btn-danger waves-effect waves-float waves-light btn-ok">{{__('language.Delete')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div> <!--end card-datatable-->
        </div> <!--end card-->
    </div> <!--end col-lg-12-->
</div> <!--end row-->

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

@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
@endsection
@stop