@extends('layouts/contentLayoutMaster')
@section('title','Visa Types')
@section('heading')
@stop
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form id="visa-types" action="@if(isset($locale)){{url($locale.'/visa-types',$visa_type->id)}} @else {{url('en/visa-types',$visa_type->id)}} @endif" method="post" enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="PUT">                    {{ csrf_field() }}
                    <br>
                    <div class="row">
                        <div class="col-md-6 form-group pl-3">

                            <label class="control-label">{{__('language.Visa')}} {{__('language.Type')}}</label><span class="text-danger"> *</span>
                            <input  type="text" name="visa_type" value="{{$visa_type->visa_type}}" placeholder="{{__('language.Enter')}} {{__('language.Visa')}} {{__('language.Type')}} {{__('language.Here')}}" class="form-control">

                        </div>
                        <div class="col-md-6 form-group pr-3">

                            <label class="control-label">{{__('language.Status')}}</label>
                            <select name="status" class="form-control">
                                <option value="1" @if($visa_type->status==1) selected @endif>{{__('language.Active')}}</option>
                                <option value="0" @if($visa_type->status==0) selected @endif>{{__('language.InActive')}}</option>
                            </select>

                        </div>
                    </div>

                    <div class="col-12 d-flex flex-sm-row flex-column mt-2 mb-2">
                        <button  type="submit" class="btn btn-primary mb-1 mb-sm-0 ml-1 mr-0 mr-sm-1 waves-effect waves-float waves-light" >{{__('language.Update')}} {{__('language.Visa')}} {{__('language.Type')}}</button>
                        <button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/visa-types')}} @else {{url('en/visa-types')}} @endif'" class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}</button>
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
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/validations/form-visa-types.js'))}}"></script>

@endsection
@stop