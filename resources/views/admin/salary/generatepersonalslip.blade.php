@extends('layouts.admin')
@section('title','Personal Salary Slip')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">{{__('language.Personal')}} {{__('language.Salary')}} {{__('language.Slip')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{__('language.Hiring')}}</a></li>
                <li class="breadcrumb-item">{{__('language.Salary')}}</li>
                <li class="breadcrumb-item active">{{__('language.Slip')}}</li>
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
                <div class="card-body">
                    <h3>{{__('language.Generate')}} {{__('language.Salary')}} {{__('language.Slip')}}</h3>
                    <form action="@if(isset($locale)){{url($locale.'/slip')}} @else {{url('en/slip')}} @endif" method="post" class="form-horizontal">
                        {{csrf_field()}}
                        <div class="form-body">
                            <hr class="m-t-0 m-b-40">
                            <input type="hidden" value="{{$employee->id}}" name="employee_id">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3">{{__('language.Select')}} {{__('language.Month')}} {{__('language.And')}} {{__('language.Year')}}</label>
                                        <div class="col-md-9">
                                            <input class="form-control" type="month" name="month">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/row-->
                            <!--/row-->
                        </div>
                        <hr>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn btn-success">{{__('language.Generate Slip')}}</button>
                                            <button type="button"
                                                    onclick="window.location.href='{{url($locale.'/dashboard')}}'"
                                                    class="btn btn-inverse">{{__('language.Cancel')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop