@extends('layouts/contentLayoutMaster')
@section('title','Asset-Types')
@section('heading')

<!-- Content Header (Page header) -->
{{--<div class="content-header">--}}
{{--    <div class="container-fluid">--}}
{{--        <div class="row mb-2">--}}
{{--            <div class="col-sm-6">--}}
{{--                <h1 class="m-0 text-dark">{{__('language.Assets')}} {{__('language.Types')}}</h1>--}}
{{--            </div><!-- /.col -->--}}
{{--            <div class="col-sm-6">--}}
{{--                <ol class="breadcrumb float-sm-right">--}}
{{--                    <li class="breadcrumb-item"><a href="#">{{__('language.Settings')}}</a></li>--}}
{{--                    <li class="breadcrumb-item active">{{__('language.Assets')}} {{__('language.Types')}}</li>--}}
{{--                </ol>--}}
{{--            </div><!-- /.col -->--}}
{{--        </div><!-- /.row -->--}}
{{--        <div class="row justify-content-end">--}}

{{--            <div class="col-12">--}}
{{--                <button type="button" class="btn btn-info btn-rounded m-t-10 float-right" data-toggle="modal" data-target="#create"><i class="fa fa-plus"></i> {{__('language.Add')}} {{__('language.Asset')}} {{__('language.Type')}}</button>--}}

{{--            </div>--}}
{{--        </div>--}}
{{--    </div><!-- /.container-fluid -->--}}
{{--</div>--}}
<!-- /.content-header -->
@stop
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="@if(isset($locale)){{url($locale.'/asset-types/update',$asset_type->id)}} @else {{url('en/asset-types/update',$asset_type->id)}} @endif" id="asset-types" method="post">
                    {{ csrf_field() }}
                    <br>
                    <div class="row">
                        <div class="col-md-6 form-group pl-3">

                            <label class="control-label">{{__('language.Asset')}} {{__('language.Name')}}</label><span class="text-danger"> *</span>
                            <input  type="text" name="name" value="{{$asset_type->name}}" placeholder="{{__('language.Enter')}} {{__('language.Asset')}} {{__('language.Name')}} {{__('language.Here')}}" class="form-control">

                        </div>
                        <div class="col-md-6 form-group pr-3">

                            <label class="control-label">{{__('language.Status')}}</label>
                            <select name="status" class="form-control">
                                <option value="1" @if($asset_type->status==1) selected @endif>{{__('language.Active')}}</option>
                                <option value="0" @if($asset_type->status==0) selected @endif>{{__('language.InActive')}}</option>
                            </select>

                        </div>
                    </div>

                    <div class="col-12 d-flex flex-sm-row flex-column mt-2 mb-2">
                        <button  type="submit" class="btn btn-primary mb-1 mb-sm-0 ml-1 mr-0 mr-sm-1 waves-effect waves-float waves-light" >{{__('language.Update')}} {{__('language.Asset')}} {{__('language.Type')}}</button>
                        <button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/asset-types')}} @else {{url('en/asset-types')}} @endif'" class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}</button>
                    </div>
                </form>
            </div>
{{--                                                    </div>--}}
{{--                                                </div>--}}
</tr>
            </tbody>
            </table>
                            <!--end: Datatable -->
        </div>
            <!--end::card body-->
            </div>
        </div>
    </div>

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/validations/form-asset-types.js'))}}"></script>

@endsection
@stop