@extends('layouts/contentLayoutMaster')

@section('title','Edit Page')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/katex.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/monokai-sublime.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.snow.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.bubble.css')) }}">
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-quill-editor.css')) }}">
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="handbook-form" action="@if(isset($locale)) {{route('chapter.page.update', [$locale, $page->chapter_id, $page->id])}} @else {{route('chapter.page.update', [$locale, $page->chapter_id, $page->id])}} @endif" method="post" enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="PUT">
                    {{ csrf_field() }}
                    <input type="text" name="chapter_id" value="{{$page->chapter_id}}" hidden>
                    <input type="text" name="page_id" value="{{$page->id}}" hidden>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="control-label">{{__('language.Page Title')}}<span class="text-danger">*</span></label>
                            <input type="text" value="{{ old('page_title', $page->title) }}" name="page_title" class="form-control" placeholder="{{__('language.Enter')}} {{__('language.Page Title')}}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label">{{__('language.Page Image')}}</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image" accept="image/*" />
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                            @if(isset($page->image))
                                <span class="small"><a href="{{ asset($page->image) }}" target="_blank" download><i data-feather='download'></i><i> {{__('language.Click Here To Download Previous Image')}}</i></a></span>
                            @else
                                <span class="small"><i> &nbsp;{{__('language.You Can Attatch only One File at a time.')}} </i></span>
                            @endif
                        </div>
                    </div>

                    <hr class="mt-0 pb-1">

                    <!-- Full Editor Start -->
                    <div class="full-editor">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="full-wrapper">
                                    <div id="full-container">
                                        <div class="editor" style="height: 700px;">
                                            {!! html_entity_decode(old('description', $page->description)) !!}
                                        </div>
                                        <div class="form-group">
                                            <textarea id="description" name="description" class="col-1 form-control" style="z-index: -1; position: absolute; top: 0px;"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Full Editor End -->

                    <hr class="mt-2">

                    <div class="d-flex">
                        <button type="button" onclick="editor();" class="btn btn-primary waves-effect waves-float waves-light" data-toggle="tooltip" data-original-title="Update Page"><span class="d-lg-none d-md-none d-sm-inline"><i data-feather="check-circle"></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Update')}}</span></button>
                        <button type="button" onclick="window.location.href='@if(isset($locale)) {{route('chapter.index', [$locale])}} @else {{route('chapter.index', ['en'])}} @endif'" class="btn btn-outline-warning waves-effect ml-1" data-toggle="tooltip" data-original-title="Cancel"><span class="d-lg-none d-md-none d-sm-inline"><i data-feather="x-circle"></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Cancel')}}</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function editor()
    {
        if($(".ql-editor").text() != '')
        {
            $('#description').text(document.getElementsByClassName("ql-editor")[0].innerHTML);
        }
        
        $("#handbook-form").valid();
        $("#handbook-form").submit();
    }
</script>
@stop

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/editors/quill/katex.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/editors/quill/highlight.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/editors/quill/quill.min.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(mix('js/scripts/forms/validations/form-handbook-validation.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-quill-editor.js')) }}"></script>
@endsection