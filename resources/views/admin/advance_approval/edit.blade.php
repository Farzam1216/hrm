@extends('layouts.admin')
@section('title','Approvals')
@section('heading')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{__('language.Set Advance Approval')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">{{__('language.Settings')}}</li>
                    <li class="breadcrumb-item">
                        <a href="@if(isset($locale)){{url($locale.'/advance-approvals')}} @else {{url('en/advance-approvals')}} @endif">
                            {{__('language.Advance Approvals')}}</a></li>
                    <li class="breadcrumb-item active">{{__('language.edit')}}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div> <!-- /.content-header -->
@stop
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="col-md-12">
            <br>
            <div class="row">
                <div class="col-4 col-sm-2">
                    <div class="row">
                        <div class="col-md-8">
                            <h4>{{__('language.Approvals')}}</h4>
                        </div>
                        <div class="col-md-4"><a href="#" class="btn btn-default float-sm-right"><i class="fas fa-plus-circle"></i></a></div>
                    </div>
                    <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                        @foreach ($approvals as $approval)
                        <a class='nav-link @if($approval->id != $id) disabled @else active @endif' id="vert-tabs-tab-{{$approval->id}}" data-toggle="pill" href="#vert-tabs-{{$approval->id}}"
                            role="tab" aria-controls="vert-tabs-{{$approval->id}}" aria-selected="true">
                            {{$approval->name}}</a>
                        @endforeach
                    </div>
                </div>
                <div class="col-8 col-sm-10">
                    <div class="tab-content" id="vert-tabs-tabContent">
                        @foreach ($approvals as $approval)
                        <div class='tab-pane text-left fade show @if($approval->id == $id) active  @endif' id="vert-tabs-{{$approval->id}}" role="tabpanel"
                            aria-labelledby="vert-tabs-tab-{{$approval->id}}">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4 id="approvalName"><i class="fas fa-thumbs-up"></i> {{$approval->name}}</h4>
                                        </div>
                                        @if($approval->advanceApprovalOptions->count() > 0)
                                        <div class="col-md-4 text-right">
                                            <form action="@if(isset($locale)){{url($locale.'/advance-approvals',$approval->id)}} @else {{url('en/advance-approvals',$approval->id)}} @endif" method="post">
                                                @method('DELETE')
                                                {{ csrf_field() }}
                                                <button class="btn-link text-danger" type="submit" style="outline:none"> <u>Remove Advance Approval</u></button>
                                            </form>
                                        </div>
                                        @endif
                                    </div>
                                    <p>{{$approval->description}}</p>
                                    <hr>
                                <form action="@if(isset($locale)){{url($locale.'/advance-approvals',$approval->id)}} @else {{url('en/advance-approvals',$approval->id)}} @endif" method="post">
                                        @method('PUT')
                                        {{ csrf_field() }}
                                    <div class="container">
                                        <p>You can set different approval paths by location, <!--TODO: division,--> or department.
                                            All future approvals will follow this pattern.</p><br>
                                        <p>Set up specific approval paths by:</p>
                                        @php
                                        $advanceApprovalType = $approval->advanceApprovalOptions->pluck('advance_approval_type')->first() ;
                                        @endphp
                                        <div class="row" id="advanceApprovalsTypeDiv">
                                            <div class="col-md-3 col-sm-6 col-xs-12 department">
                                                <div class="info-box" for="checkbox-department">
                                                    <input type="radio" name="advance_approval_type" id="checkbox-department" @if($advanceApprovalType=="Department" ) checked @endif value="Department"
                                                        onclick="radioButton(this)">
                                                    <span class="info-box-icon bg-aqua "><i class="fas fa-user-friends"></i></span>

                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Department</span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>

                                        {{-- TODO: Need division after changing Employee Model --}}
                                            {{-- <div class="col-md-3 col-sm-6 col-xs-12 division">
                                                <div class="info-box">
                                                    <input type="radio" name="advance_approval_type" onclick="radioButton(this)" value="Division" @if($advanceApprovalType=="Division" ) checked @endif
                                                        id="checkbox-division">
                                                    <span class="info-box-icon bg-aqua "><i class="fas fa-sitemap"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Division</span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div> --}}
                                            <div class="col-md-3 col-sm-6 col-xs-12 location">
                                                <div class="info-box">
                                                    <input type="radio" name="advance_approval_type" onclick="radioButton(this)" value="Location" @if($advanceApprovalType=="Location" ) checked @endif
                                                        id="checkbox-location">
                                                    <span class="info-box-icon bg-aqua"><i class="fa fa-map-marker"></i></span>

                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Location</span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                        </div>
                                        <hr>
                                        <button type="submit" id="btnSave" class="btn btn-info btnSave">{{__('language.Save')}}</button>
                                        <button type="button" class="btn btn-default"
                                            onclick="window.location.href='@if(isset($locale)){{url($locale.'/advance-approvals')}} @else {{url('en/advance-approvals')}} @endif'">{{__('language.Cancel')}}</button>
                                    </div>
                                    <input type="hidden" id="typeName" value="{{$advanceApprovalType}}">
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <br>

            </div>
        </div>
    </div>
</div>
<script>
    $(".btnSave").prop('disabled',true);
    function radioButton(element,approvalID){
        const form = $(element).closest("form");
        const btnSave = $(form).find('#btnSave');
        const advanceApprovalsTypeDiv = $(form).find('#advanceApprovalsTypeDiv');
        var approvalName = $(form).closest('div').find('#approvalName').text();

        var inputTypeName = $(element).val();        
        var advanceApprovalTypeName =  $(form).find('#typeName').val();
        if(inputTypeName == advanceApprovalTypeName){
            $(btnSave).prop('disabled',true);
            $('#changeApprovalTypeText').remove();
        } else if(advanceApprovalTypeName == ''){
            $(btnSave).prop('disabled',false);
        } else{            
            $('#changeApprovalTypeText').remove();
            $(btnSave).prop('disabled',false);
            $(advanceApprovalsTypeDiv).after(
                '<center><div id="changeApprovalTypeText" class="text-danger">'+
                '<p><i class="fas fa-exclamation-triangle mb-2"></i> Current approvals are set by '+advanceApprovalTypeName+'.<br>'+
                'Changing this will delete all approvals for "'+approvalName+'". </p></div></center>'
            );
        }
    }
</script>
@stop