@extends('layouts.admin')
@section('title','Access Level')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{__('language.Edit Access Levels')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">{{__('language.Settings')}}</a></li>
                    <li class="breadcrumb-item ">{{__('language.Edit Access Levels')}}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
@stop
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div style="margin-top:10px; margin-right: 10px;">
                <h2 style="float: left; margin-left: 15px;">{{__('language.Edit Access Levels')}}</h2>
                <button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/access-level')}} @else {{url('en/access-level')}} @endif'"
                    class="btn btn-info float-right">{{__('language.Back')}}</button>
            </div>
            <div class="card-body">
                <hr>
                <form action="@if(isset($locale)){{url($locale.'/task/store')}} @else {{url('en/task/store')}} @endif" method="post">
                    <input name="_method" type="hidden" value="PUT">
                    {{csrf_field()}}
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radio1" @if($employeeRole=="admin" ) checked="" @endif>
                            <label class="form-check-label">{{__('language.Full Admin')}}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radio1" @if($employeeRole=="" ) checked="" @endif{>
                            <label class="form-check-label">{{__('language.No Access')}}</label>
                        </div>
                        <hr>
                        <b>{{__('language.Employee')}}</b>
                        <hr>
                        @foreach($employeeRoles as $employeeRole)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radio1" @if($employeeRole==$employeeRole->name) checked @endif>
                            <label class="form-check-label">{{$employeeRole->name}}</label>
                        </div>
                        @endforeach
                        <hr>
                        <b>{{__('language.Manager')}}</b>
                        <hr>
                        @foreach($managerRoles as $managerRole)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radio1" @if($employeeRole==$managerRole->name) checked="" @endif>
                            <label class="form-check-label">{{$managerRole->name}}</label>
                        </div>
                        @endforeach
                        <hr>
                        <b>{{__('language.Custom')}}</b>
                        <hr>
                        @foreach($customRoles as $customRole)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radio1" @if($employeeRole==$customRole->name) checked="" @endif>
                            <label class="form-check-label">{{$customRole->name}}</label>
                        </div>
                        @endforeach
                    </div>
                    {{--                        <div class="col-12 ">--}}
                    {{--                            <div class="card card-primary card-outline card-tabs">--}}
                    {{--                                <div class="card-header p-0 pt-1 border-bottom-0">--}}
                    {{--                                    <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">--}}
                    {{--                                        <li class="nav-item">--}}
                    {{--                                            <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill"--}}
                    {{--                                               href="#custom-tabs-two-home" role="tab"--}}
                    {{--                                               aria-controls="custom-tabs-two-home" aria-selected="true">Full Admin</a>--}}
                    {{--                                        </li>--}}
                    {{--                                        <li class="nav-item">--}}
                    {{--                                            <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill"--}}
                    {{--                                               href="#custom-tabs-two-profile" role="tab"--}}
                    {{--                                               aria-controls="custom-tabs-two-profile" aria-selected="false">Employee--}}
                    {{--                                                Level</a>--}}
                    {{--                                        </li>--}}
                    {{--                                        <li class="nav-item">--}}
                    {{--                                            <a class="nav-link" id="custom-tabs-two-messages-tab" data-toggle="pill"--}}
                    {{--                                               href="#custom-tabs-two-messages" role="tab"--}}
                    {{--                                               aria-controls="custom-tabs-two-messages" aria-selected="false">Manager--}}
                    {{--                                                Level</a>--}}
                    {{--                                        </li>--}}
                    {{--                                        <li class="nav-item">--}}
                    {{--                                            <a class="nav-link" id="custom-tabs-two-settings-tab" data-toggle="pill"--}}
                    {{--                                               href="#custom-tabs-two-settings" role="tab"--}}
                    {{--                                               aria-controls="custom-tabs-two-settings" aria-selected="false">Custom--}}
                    {{--                                                Level</a>--}}
                    {{--                                        </li>--}}
                    {{--                                    </ul>--}}
                    {{--                                </div>--}}
                    {{--                                <div class="card-body">--}}
                    {{--                                    <div class="tab-content" id="custom-tabs-two-tabContent">--}}
                    {{--                                        <div class="tab-pane fade active show" id="custom-tabs-two-home" role="tabpanel"--}}
                    {{--                                             aria-labelledby="custom-tabs-two-home-tab">--}}
                    {{--                                            <p>Select only one from all tabs</p>--}}
                    {{--                                            <div class="form-check">--}}
                    {{--                                                <input class="form-check-input" type="radio" name="radio1">--}}
                    {{--                                                <label class="form-check-label">{{__('language.Full Admin')}}</label>--}}
                    {{--                                            </div>--}}
                    {{--                                        </div>--}}
                    {{--                                        <div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel"--}}
                    {{--                                             aria-labelledby="custom-tabs-two-profile-tab">--}}
                    {{--                                            <p>Select only one from all tabs</p>--}}
                    {{--                                            @foreach($employeeRoles as $employeeRole)--}}
                    {{--                                                <div class="form-check">--}}
                    {{--                                                    <input class="form-check-input" type="radio" name="radio1">--}}
                    {{--                                                    <label class="form-check-label">{{$employeeRole->name}}</label>--}}
                    {{--                                                </div>--}}
                    {{--                                            @endforeach--}}
                    {{--                                        </div>--}}
                    {{--                                        <div class="tab-pane fade" id="custom-tabs-two-messages" role="tabpanel"--}}
                    {{--                                             aria-labelledby="custom-tabs-two-messages-tab">--}}
                    {{--                                            <p>Select only one from all tabs</p>--}}
                    {{--                                            @foreach($managerRoles as $managerRole)--}}
                    {{--                                                <div class="form-check">--}}
                    {{--                                                    <input class="form-check-input" type="radio" name="radio1">--}}
                    {{--                                                    <label class="form-check-label">{{$managerRole->name}}</label>--}}
                    {{--                                                </div>--}}
                    {{--                                            @endforeach--}}
                    {{--                                        </div>--}}
                    {{--                                        <div class="tab-pane fade" id="custom-tabs-two-settings" role="tabpanel"--}}
                    {{--                                             aria-labelledby="custom-tabs-two-settings-tab">--}}
                    {{--                                            <p>Select only one from all tabs</p>--}}
                    {{--                                            @foreach($customRoles as $customRole)--}}
                    {{--                                                <div class="form-check">--}}
                    {{--                                                    <input class="form-check-input" type="radio" name="radio1">--}}
                    {{--                                                    <label class="form-check-label">{{$customRole->name}}</label>--}}
                    {{--                                                </div>--}}
                    {{--                                            @endforeach--}}
                    {{--                                            <button type="submit" style="float: right;"--}}
                    {{--                                                    class="btn btn-primary btn-ok">{{__('language.Update Access Level')}}</button>--}}
                    {{--                                        </div>--}}
                    {{--                                    </div>--}}
                    {{--                                </div>--}}
                    {{--                                <!-- /.card -->--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                </form>
            </div>
        </div>
    </div>
    <!--end::Portlet-->
</div>
</div>
@stop