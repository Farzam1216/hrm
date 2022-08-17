@extends('layouts.admin')
@section('title','Request a change')
@section('heading')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{__('language.Request a change')}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('language.Employee')}} </a></li>
                        <li class="breadcrumb-item active">{{__('language.Request a change')}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div> <!-- /.content-header -->
@stop
@section('heading')
@stop
@section('content')
    @if($requestApprovals != null && $requestedFields != null)
        @foreach($requestApprovals as $approval)
            @if($approval->id == $approvalId)
                <div class="row">
                    <div class="col-lg-12">

                        <div class="card">
                            <div class="card-body">
                            <form class="form"
                                  action="@if(isset($locale)){{url($locale.'/employees/'.$employeeId.'/request-change/'.$approvalId)}} @else {{url('en/employees/'.$employeeId.'/request-change/'.$approvalId)}} @endif"
                                  method="post"
                                  enctype="multipart/form-data" id="kt_form">
                                <input type="hidden" name="_method" value="PUT">
                                {{csrf_field()}}
                                <input type="hidden" name="fieldID" value="{{$requestedFieldID}}">
                                <h3>{{$approval->name}}</h3>
                                <hr>
                                @foreach($requestedFields as  $group => $selectedFields)
                                @if($group !== "Default")
                                <hr>
                                <h5 class="pl-2">{{$group}}</h5>
                                <br>
                                @endif
                                @foreach($selectedFields as $fields)
                                    @if(count($selectedFields)>1)
                                    <hr>
                                    @endif
                                @foreach($fields as $field => $details)
                                @if(isset($details['type']))
                                                    @if( $details['type'] == "list")

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group row">

                                                <label class="control-label text-right col-md-3"> {{$details['name']}}</label>
                                                        <div class="col-md-9">
                                                            <select class="form-control custom-select"
                                                                    @if(isset($details['selected'])) name="data[{{$details['model']}}][{{$field}}][{{$details['selected']['id']}}]" @else name="data[{{$details['model']}}][{{$field}}][0]" @endif>
                                                                @foreach($details['content']['options'] as $key=>$option)
                                                                    <option value="{{$key}}" @if(isset($details['selected']) && $details['selected']['value'] == $key) selected @endif>{{$option}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                            </div>
                                        </div>
                                    </div>
                                                    @elseif($details['type'] != "textarea")
                                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                        <label class="control-label text-right col-md-3"> {{$details['name']}}</label>
                                                        <div class="col-md-9">
                                                            <input type='{{$details['type']}}' @if(isset($details['selected'])) name="data[{{$details['model']}}][{{$field}}][{{$details['selected']['id']}}]" value="{{$details['selected']['value']}}" @else name="data[{{$details['model']}}][{{$field}}][0]" @endif
                                                                   class="form-control"
                                                                   @if($details['status'] == "static" || $details['status'] == "required" ) required @endif>
                                                        </div>
                                            </div>
                                        </div>
                                    </div>

                                    @endif
                                                @endif
                            
                            @endforeach @endforeach
                                    @foreach($selectedFields as $fields)
                                @foreach($fields as  $field => $details)
                                @if(isset($details['type']))
                                                    @if( $details['type'] == "textarea")
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <label class="control-label text-right col-md-3"> {{$details['name']}}</label>
                                                        <div class="col-md-9">
                                                            <textarea class="form-control" @if($field == "comments") name="comment" @else @if(isset($details['selected'])) name="data[{{$details['model']}}][{{$field}}][{{$details['selected']['id']}}]" @else name="data[{{$details['model']}}][{{$field}}][0]" @endif @endif @if($field != "comments" && ($details['status'] == "static" || $details['status'] == "required" )) required @endif >
                                                            </textarea>
                                                        </div>

                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                                @endif
                            @endforeach @endforeach
                            @endforeach

                            <!-- Fields end Here -->
                                <div class="modal-footer">

                                    <button class="btn btn-success" id="submit_update"
                                            type="submit"> {{__('language.Submit')}}
                                    </button>
                                </div>
                            </form>

                            </div>
                        </div>
                    </div>
                </div>
            @endif

        @endforeach
    @else
        <h3 class="text-red">There are no approval you can Request</h3>
    @endif
@stop