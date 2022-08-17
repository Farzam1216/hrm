@extends('layouts.admin')
@section('title','Create Role')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"> {{__('language.Create')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{__('language.Roles Permission')}}</a></li>
                <li class="breadcrumb-item active"> {{__('language.Create')}}</li>
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
            <div class="card card-outline-info">
                <div style="margin-top:10px; margin-right: 10px;">
                    <a class="btn btn-info float-right" href="@if(isset($locale)){{url($locale.'/rolespermissions')}} @else {{url('en/rolespermissions')}} @endif"> {{__('language.Back')}}</a>
                </div>
                <div class="card-body">
                    <form  action="@if(isset($locale)){{url($locale.'/rolespermissions/store')}} @else {{url('en/rolespermissions/store')}} @endif" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="form-body">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{__('language.Name')}}</label>
                                    <input  type="text" name="name" placeholder="{{__('language.Enter Name Here')}}" class="form-control" value="{{old('name')}}">
                                    <input type="hidden" name="status" value="1">
                                </div>
                            </div>
                            <hr>
                            <div class="kt-checkbox-list">
                                <label class="kt-checkbox kt-checkbox--solid kt-checkbox--brand">
                            <input type="checkbox" id="check_all"/>{{__('language.Select')}} {{__('language.All')}}
                                <span></span>
                                </label>
                            </div>
                            <br>
                            <br>
                            <div class="form-group row">
                                @foreach ($all_controllers as $key => $row)
                                    <div class="col-md-4">
                                        <hr>
                                        <div class="kt-checkbox-list">
                                            <label class="kt-checkbox kt-checkbox--solid kt-checkbox--brand">
                                        <input type="checkbox" class="check_all_sub" id="{{$key}}">
                                        {{$key}}
                                                <span></span>
                                            </label>
                                        </div>
                                        <br>
                                        <div class="{{$key}}">
                                            @foreach ($row as $route)
                                                <div class="col-md-6">
                                                    <div class="kt-checkbox-list">
                                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--success">
                                                    <input type="checkbox" id="{{$key}}:{{$route}}" name="permissions[]" value="web:{{$key}}:{{$route}}">
                                                            {{$route}}
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-actions">
                            &nbsp;&nbsp;&nbsp;<button type="submit" class="btn btn-success">{{__('language.Create')}} {{__('language.Roles')}}</button>
                            <button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/rolespermissions')}} @else {{url('en/rolespermissions')}} @endif'" class="btn btn-inverse">{{__('language.Cancel')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                $(function () {
                    $("#check_all").click(function(){
                        $('input:checkbox').not(this).prop('checked', this.checked);
                    });
                    $(".check_all_sub").click(function(){
                        $('div.'+ this.id +' input:checkbox').prop('checked', this.checked);
                    });
                });
            });
        </script>
    @endpush
@stop