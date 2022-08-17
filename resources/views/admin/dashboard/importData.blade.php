@extends('layouts.admin')
@section('title','Import Demo Data')
@section('heading')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">{{__('language.Add')}} {{__('language.Demo')}} {{__('language.Data')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">{{__('language.Settings')}}</a></li>
                <li class="breadcrumb-item active">{{__('language.Add')}} {{__('language.Demo')}} {{__('language.Data')}}</li>
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
                
                <div class="card-body">
                    <h4>{{__('language.Please click on import button for demo data.')}}</h4>
                    <form method="post" action="@if(isset($locale)){{url($locale.'/import')}} @else {{url('en/import')}} @endif">
                      {!! csrf_field() !!}
                      <button type="submit" class="btn btn-info btn-rounded m-t-10" style="width: 10%"> {{__('language.Import')}} {{__('language.Data')}}</button>
                  </form>
      
  <!--end: Datatable -->
              <!--end: Datatable -->
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->			
</div>


            
        </div>

@stop