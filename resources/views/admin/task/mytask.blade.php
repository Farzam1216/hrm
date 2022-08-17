@extends('layouts.admin')
@section('title','Task Management')
@section('heading')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{__('language.Settings')}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('language.People Management')}} </a></li>
                        <li class="breadcrumb-item active">{{__('language.Task')}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
            <div class="row justify-content-end">

                <div class="col-12">
                @if(Auth::user()->isAdmin())
                    <button onclick="window.location.href='@if(isset($locale)){{url($locale.'/task/create')}} @else {{url('en/task/create')}} @endif'"
                            class="btn btn-info btn-rounded m-t-10 float-right"><i
                                class="fa fa-plus"></i>&nbsp {{__('language.Add')}} {{__('language.Task')}}
                    </button>
                    @endif
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>  <!-- /.content-header -->
@stop
@section('heading')
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
            {{__('language.People Management')}}</h3>
        <span class="kt-subheader__separator kt-hidden"></span>
        <div class="kt-subheader__breadcrumbs">
            <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a href="" class="kt-subheader__breadcrumbs-link">
                {{__('language.Task')}}</a>
        </div>
    </div>
@stop
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h4 class="mt-5 ">My Tasks</h4>
            <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-content-above-home-tab" data-toggle="pill"
                       href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home"
                       aria-selected="false">Tasks To Do</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-content-above-profile-tab" data-toggle="pill"
                       href="#custom-content-above-profile" role="tab" aria-controls="custom-content-above-profile"
                       aria-selected="false">Completed Task</a>
                </li>
            </ul>
            <div class="tab-custom-content">
                <p class="lead mb-0">Tasks To Do</p>
            </div>
            <div class="tab-content" id="custom-content-above-tabContent">
                <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel"
                     aria-labelledby="custom-content-above-home-tab">
                    <div class="card">
                        <div class="card-body">
                            @if($employeesTaskFor->count() > 0)
                                <table class="table table-striped- table-bordered table-hover table-checkable"
                                       id="unCompletedTask">
                                    <thead>
                                    <tr>

                                        <th> {{__('language.Task Name')}}</th>
                                        <th> {{__('language.Task Description')}}</th>
                                        <th> {{__('language.Due Date')}}</th>
                                        <th> {{__('language.Task Assigned To')}}</th>
                                        <th> {{__('language.Task Assigned For')}}</th>
                                        <th> {{__('language.Actions')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employeesTaskFor as $key => $employeeTask)
                                        <tr>
                                            <td>{{$employeeTask->task->task_name}}</td>
                                            <td>{{html_entity_decode($employeeTask->task->task_description)}}</td>
                                            <td>{{$employeeTask->status_value}}</td>
                                            <td>{{$employeeTask->assignedto->firstname}} {{$employeeTask->assignedto->lastname}}</td>
                                            <td>{{$employeeTask->assignedfor->firstname}} {{$employeeTask->assignedfor->lastname}}</td>
                                            <td class="text-nowrap">
                                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                    <input type="checkbox"
                                                           taskCompletionStatusId="{{$employeeTask->id}}"
                                                           class="custom-control-input"
                                                           name="completedStatus{{$employeeTask->id}}"
                                                           id="completedSwitch{{$employeeTask->id}}">
                                                    <label class="custom-control-label"
                                                           for="completedSwitch{{$employeeTask->id}}">Completed
                                                        Task</label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-center"> {{__('language.No Task Found')}}</p>
                            @endif
                            ​
                            <!--end: Datatable -->
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="custom-content-above-profile" role="tabpanel">
                    <div class="card" aria-labelledby="custom-content-above-profile-tab">
                        <div class="card-body">
                            @if($completedStatusTasks->count() > 0)
                                <table class="table table-striped- table-bordered table-hover table-checkable"
                                       id="completedTask">
                                    <thead>
                                    <tr>
                                        <th> {{__('language.Task Name')}}</th>
                                        <th> {{__('language.Task Description')}}</th>
                                        <th> {{__('language.Due Date')}}</th>
                                        <th> {{__('language.Task Assigned To')}}</th>
                                        <th> {{__('language.Task Assigned For')}}</th>
                                        <th> {{__('language.Actions')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($completedStatusTasks as $key => $employeeTask)
                                        <tr>
                                            <td>{{$employeeTask->task->task_name}}</td>
                                            <td>{{html_entity_decode($employeeTask->task->task_description)}}</td>
                                            <td>{{$employeeTask->status_value}}</td>
                                            <td>{{$employeeTask->assignedto->firstname}} {{$employeeTask->assignedto->lastname}}</td>
                                            <td>{{$employeeTask->assignedfor->firstname}} {{$employeeTask->assignedfor->lastname}}</td>
                                            <td class="text-nowrap">
                                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                    <input type="checkbox" taskcompletionstatusid="{{$employeeTask->id}}"
                                                           class="custom-control-input" checked
                                                           name="completedStatus{{$employeeTask->id}}"
                                                           id="completedSwitch{{$employeeTask->id}}">
                                                    <label class="custom-control-label"
                                                           for="completedSwitch{{$employeeTask->id}}">Completed
                                                        Task</label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-center"> {{__('language.No Task Found')}}</p>
                            @endif
                            ​
                            <!--end: Datatable -->
                        </div>
                    </div>
                </div>
            </div>
            ​
            <!--end::Portlet-->
        </div>
    </div>


    <script>

            $(document).on('change', 'input[type=checkbox]', function() {
                if ($(this).prop('checked') == true) {
                    var row = $(this);
                    var taskCompletionStatusId = $(this).attr('taskcompletionstatusid');
                    var employeeID={!! $employee->id !!}
                    jQuery.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "/mytask/completedstatus",
                        data: {
                            taskCompletionStatusId: taskCompletionStatusId,
                            employeeID: employeeID,
                        },
                        cache: false,
                        success: function (data) {
                            console.log(taskCompletionStatusId);
                            row.parent().parent().parent().remove();
                            console.log(data);

                            $('#completedTask').append('<tr><td>' + data.task.task_name + '</td>' +
                                '<td>' + data.task.task_description + '</td>' +
                                '<td>' + data.status_value + '</td>' +
                                '<td>' + data.assigned_to.firstname +' '+ data.assigned_to.lastname+ '</td>' +
                                '<td>' + data.assigned_for.firstname +' '+ data.assigned_for.lastname+ + '</td>' +
                                '<td class="text-nowrap">' +
                                '<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">' +
                                '<input type="checkbox" taskcompletionstatusid=' + data.id + ' class="custom-control-input" checked="false" name="completedStatus' + data.id + '" id="completedSwitch' + data.id + '">' +
                                '<label class="custom-control-label" for="completedSwitch' + data.id + '">Task Completed</label>' +
                                '</div>' +
                                '</td></tr>');
                        }

                    });
                }
                else {
                    var row = $(this);
                    var taskCompletionStatusId = $(this).attr('taskcompletionstatusid')
                    var employeeID={!! $employee->id !!}
                    jQuery.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "/mytask/incompletedstatus",
                        data: {
                            taskCompletionStatusId: taskCompletionStatusId,
                            employeeID: employeeID,
                        },
                        cache: false,
                        success: function (data) {
                            console.log(taskCompletionStatusId);
                            row.parent().parent().parent().remove();
                            console.log(data);
                            $('#unCompletedTask').append('<tr><td>' + data.task.task_name + '</td>' +
                                '<td>' + data.task.task_description + '</td>' +
                                '<td>' + data.status_value + '</td>' +
                                '<td>' + data.assigned_to.firstname +' '+ data.assigned_to.lastname+ '</td>' +
                                '<td>' + data.assigned_for.firstname +' '+ data.assigned_for.lastname+ + '</td>' +
                                '<td class="text-nowrap">' +
                                '<div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">' +
                                '<input type="checkbox" taskcompletionstatusid=' + data.id + '  class="custom-control-input"   name="completedStatus' + data.id + '" id="completedSwitch' + data.id + '">' +
                                '<label class="custom-control-label" for="completedSwitch' + data.id + '">Task Completed</label>' +
                                '</div>' +
                                '</td></tr>');
                        }

                    });
                }


            });
    </script>
@stop