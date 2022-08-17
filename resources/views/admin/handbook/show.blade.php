@extends('layouts/contentLayoutMaster')

@section('title','Show Page')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/katex.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/monokai-sublime.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.snow.css')) }}">
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-quill-editor.css')) }}">
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="row justify-content-between pl-3 pr-3 pt-2">
                   <h3 style="padding-top: 4px;">{{$selected_page->title}}</h3>
            	<div class="pb-1">
                    <button type="button" onclick="window.location.href='@if(isset($locale)) {{route('chapter.index', [$locale])}} @else {{route('chapter.page', ['en'])}} @endif'" class="btn btn-primary waves-effect waves-float waves-light" data-toggle="tooltip" data-original-title="Back"><i data-feather="chevron-left"></i><span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Back')}}</span></button>
                </div>
            </div>

            <hr class="mt-0 mb-0">
            <div class="card-body border-0" id="view" style="overflow: auto;height: 700px;">
                <div class="ql-align-center">
                    <img class="img-fluid" @if(isset($selected_page->image)) src="{{ asset($selected_page->image) }}" @else src="{{ asset('not_available.png') }}" @endif alt="Page Image" height="150px">
                </div><br>
            	{!! html_entity_decode($selected_page->description) !!}
            </div>
            <!-- Full Editor End -->

            <!-- Pagination Start -->
            <div class="card-body">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        @foreach($chapter[0]['pages'] as $key => $chapter_page)
                            @if($chapter_page->id == $selected_page->id)
                                <li class="page-item prev-item"><a class="page-link" @if(isset($chapter[0]['pages'][$key-1])) href="@if(isset($locale)) {{route('chapter.page.show', [$locale, $chapter[0]->id, $chapter[0]['pages'][$key-1]->id])}} @else {{route('chapter.page.show', [$locale, $chapter[0]->id, $chapter[0]['pages'][$key-1]->id])}} @endif" @else href="javascript:void(0);" @endif></a></li>
                            @endif
                        @endforeach
                        @foreach($chapter[0]['pages'] as $key => $chapter_page)
                            <li class="page-item @if($chapter_page->id == $selected_page->id) active @endif" aria-current="page">
                                <a class="page-link" href="@if(isset($locale)) {{route('chapter.page.show', [$locale, $chapter[0]->id, $chapter_page->id])}} @else {{route('chapter.page.show', ['en', $chapter[0]->id, $chapter_page->id])}} @endif">{{$key+1}}</a>
                            </li>
                        @endforeach
                        @foreach($chapter[0]['pages'] as $key => $chapter_page)
                            @if($chapter_page->id == $selected_page->id)
                                <li class="page-item next-item"><a class="page-link" @if(isset($chapter[0]['pages'][$key+1])) href="@if(isset($locale)) {{route('chapter.page.show', [$locale, $chapter[0]->id, $chapter[0]['pages'][$key+1]->id])}} @else {{route('chapter.page.show', ['en', $chapter[0]->id, $chapter[0]['pages'][$key+1]->id])}} @endif" @else href="javascript:void(0);" @endif></a></li>
                            @endif
                        @endforeach
                    </ul>
                </nav>
            </div>
            <!-- Pagination End -->
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var Font = Quill.import('formats/font');
        Font.whitelist = ['sofia', 'slabo', 'roboto', 'inconsolata', 'ubuntu'];
        Quill.register(Font, true);
        var options ={
            readOnly: true,
        };
        var view = new Quill('#view', options);
    });
</script>
@stop
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/editors/quill/quill.min.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(mix('js/scripts/forms/form-quill-editor.js')) }}"></script>
@endsection