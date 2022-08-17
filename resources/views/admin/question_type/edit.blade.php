@extends('layouts/contentLayoutMaster')
@section('title','Question')
@section('heading')
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection
@stop
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="@if(isset($locale)){{url($locale.'/question-types',$GetQuestionType->id)}} @else {{url('en/question-types',$GetQuestionType->id)}} @endif" id="education-types" method="post" enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="PUT">

                    {{ csrf_field() }}
                    <br>
                    <div class="row">
                        <div class="col-md-6 form-group pl-3">

                            <label class="control-label">  {{__('language.Question')}} {{__('language.Type')}}</label><span class="text-danger"> *</span>
                            <input  type="text" name="question_type" value="{{$GetQuestionType->type}}" placeholder="{{__('language.Enter')}} {{__('language.Education')}} {{__('language.Type')}} {{__('language.Here')}}" class="form-control">

                        </div>
                    </div>

                    <div class="col-12 d-flex flex-sm-row flex-column mt-2 mb-2">
                        <button  type="submit" class="btn btn-primary mb-1 mb-sm-0 ml-1 mr-0 mr-sm-1 waves-effect waves-float waves-light" >{{__('language.Update')}} {{__('language.Question')}} {{__('language.Type')}}</button>
                        <button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/question-types')}}@else {{url('en/question-types')}} @endif'" class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}</button>
                    </div>
                </form>
            </div>
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
@endsection
@stop