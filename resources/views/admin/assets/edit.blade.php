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
@stop
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="modal-content">
                <form action="@if(isset($locale)){{url($locale.'/employees/'.$employee_id.'/assets/update/'.$asset_id)}} @else {{url('en/employees/'.$employee_id.'/assets/update/'.$asset_id)}} @endif" id="assets-form" method="post">
                    {{ csrf_field() }}
                    <br>
                    <input type="hidden" value="{{$employee_id}}" name="employee_id">
                    <div class="row pr-2 pl-2">
                        <div class="col-md-6 form-group">

                                <label class="control-label">{{__('language.Asset')}} {{__('language.Type')}}</label><span class="text-danger"> *</span>
                                <select name="asset_category" class="form-control">
{{--                                    <option value="">Select Category</option>--}}
                                    @foreach($asset_types as $asset)
                                        <option value="{{$asset->id}}" @if($asset->id == $employee_asset->asset_type->id) selected @endif> {{$asset->name}} </option>
                                    @endforeach
                                </select>

                        </div>
                        <div class="col-md-6 form-group">

                                <label class="control-label">{{__('language.Serial')}}</label><span class="text-danger"> *</span>
                                <input id="serial" type="text" name="serial" value="{{$employee_asset->serial}}" class="form-control">

                        </div>
                    </div>
                    <div class="col-md-12 form-group pl-2 pr-2">

                            <label class="control-label">{{__('language.Description')}}</label><span class="text-danger"> *</span>
                            <textarea class="form-control" id="asset_description" name="asset_description" id="" cols="30" rows="3" >{{$employee_asset->asset_description}}</textarea>

                    </div>
                    <div class="row pl-2 pr-2">
                        <div class="col-md-6 form-group">
                            <label class="control-label text-right" >{{__('language.Date')}} {{__('language.Assign')}}</label><span class="text-danger"> *</span>
                            <input type="date" id="assign_date" name="assign_date" value="{{$employee_asset->assign_date}}" class="form-control flatpickr-disabled-range" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label text-right" for="date_of_birth">{{__('language.Date')}} {{__('language.Return')}}</label>
                            <input type="date" id="return_date" name="return_date" value="{{$employee_asset->return_date}}" class="form-control flatpickr-disabled-range"  />
                        </div>
{{--                        {{$employee_asset->return_date}}--}}

                    </div>
                    <div class="col-12 d-flex flex-sm-row flex-column mt-2 mb-2">
                        <button  type="submit" class="btn btn-primary mb-1 mb-sm-0 ml-1 mr-0 mr-sm-1 waves-effect waves-float waves-light" >{{__('language.Update')}} {{__('language.Asset')}}</button>
                        <button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/employees/'.$employee_id.'/assets')}}
                        @else {{url('en/employees/'.$employee_id.'/assets')}} @endif'" class="btn btn-inverse">{{__('language.Cancel')}}</button>
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
{{--    <script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>--}}
{{--    <script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>--}}
    <script src="{{ asset(mix('js/scripts/forms/validations/form-assets.js'))}}"></script>
@endsection
@stop