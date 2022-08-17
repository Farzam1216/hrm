@extends('layouts/contentLayoutMaster')
@section('title','Asset')
@section('heading')
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection
    <!-- Content Header (Page header) -->
{{--    <div class="content-header">--}}
{{--        <div class="container-fluid">--}}
{{--            <div class="row mb-2">--}}
{{--                <div class="col-sm-6">--}}
{{--                    <h1 class="m-0 text-dark">{{__('language.Assets')}}</h1>--}}
{{--                </div><!-- /.col -->--}}
{{--                <div class="col-sm-6">--}}
{{--                    <ol class="breadcrumb float-sm-right">--}}
{{--                        <li class="breadcrumb-item"><a href="#">{{__('language.Settings')}}</a></li>--}}
{{--                        <li class="breadcrumb-item active">{{__('language.Assets')}}</li>--}}
{{--                    </ol>--}}
{{--                </div><!-- /.col -->--}}
{{--            </div><!-- /.row -->--}}
{{--        </div><!-- /.container-fluid -->--}}
{{--    </div>--}}
    <!-- /.content-header -->
@stop
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="modal-content">
                <form action="@if(isset($locale)){{url($locale.'/assets/store')}} @else {{url('en/assets/store')}} @endif" id="assets-form" method="post">
                    {{ csrf_field() }}
                    <br>
                    <input type="hidden" value="{{$employee_id}}" name="employee_id">
                    <div class="row pl-2 pr-2">
                        <div class="col-md-6 form-group">
                                <label class="control-label"> {{__('language.Category')}}</label><span class="text-danger"> *</span>
                                <select name="asset_category" class="form-control">
                                    @foreach($assets_type as $asset)
                                        <option value="{{$asset->id}}" >{{$asset->name}}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="col-md-6 form-group">

                                    <label class="control-label">{{__('language.Serial')}}</label><span class="text-danger"> *</span>
                                    <input  type="text" id="serial" name="serial" placeholder="{{__('language.Enter')}} {{__('language.Asset')}} {{__('language.Serial')}} {{__('language.Number')}} {{__('language.Here')}}" class="form-control">
                        </div>
                    </div>
                        <div class="col-md-12 form-group pl-2 pr-2">
                                <label class="control-label">{{__('language.Description')}}</label><span class="text-danger"> *</span>
                                <textarea class="form-control" name="asset_description" id="asset_description" cols="30" rows="3" placeholder="{{__('language.Enter')}} {{__('language.Description')}} {{__('language.Here')}}"></textarea>
                        </div>
                    <div class="row pl-2 pr-2">
                        <div class="col-md-6 form-group">
                            <label class="control-label text-right" >{{__('language.Date')}} {{__('language.Assign')}}</label><span class="text-danger"> *</span>
                            <input type="date" name="assign_date" id="assign_date" class="form-control flatpickr-disabled-range" placeholder="YYYY-MM-DD" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label text-right" for="date_of_birth">{{__('language.Date')}} {{__('language.Return')}}</label>
                            <input type="date" name="return_date" id="return_date" class="form-control flatpickr-disabled-range" placeholder="YYYY-MM-DD" />
                        </div>

                    </div>


                    <div class="col-12 d-flex flex-sm-row flex-column mt-2 mb-2">
                    <button  type="submit" class="btn btn-primary mb-1 mb-sm-0 ml-1 mr-0 mr-sm-1 waves-effect waves-float waves-light" >{{__('language.Add')}} {{__('language.Asset')}} </button>
{{--                    <a type="button" class="btn btn-inverse " data-toggle="tooltip" data-placement="top" title="" href="@if(isset($locale)){{url($locale.'/employees/'.$employee_id.'/assets')}}--}}
{{--                    @else {{url('en/employees/'.$employee_id.'/assets')}} @endif"> Cancel--}}
{{--                    </a>--}}
                    <button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/employees/'.$employee_id.'/assets')}} @else {{url('en/employees/'.$employee_id.'/assets')}} @endif'" class="btn btn-inverse">{{__('language.Cancel')}}</button>
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
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/validations/form-assets.js'))}}"></script>

@endsection
@stop