@extends('layouts/contentLayoutMaster')
@section('title','Handbook')
@section('content')
<div class="row">
   <div class="col-lg-12">
      <div class="card">
         <div class="row justify-content-between pl-3 pr-3 pt-1">
            <div class="d-flex">
               <h3 style="padding-top: 5px;">Company Handbook</h3>
               <div class="ml-1 form-group">
                  <input class="form-control input" id="searchbar" onkeyup="search()" type="text" name="search" placeholder="Search">
               </div>
            </div>
            @if(Auth::user()->isAdmin() || $permissions['handbook'])
               <div class="pb-1">
                  <a href="@if(isset($locale)) {{route('chapter.create', [$locale])}} @else {{route('chapter.create', ['en'])}} @endif" class="btn btn-brand btn-primary btn-elevate btn-icon-sm" data-toggle="tooltip" data-original-title="Add Chapter">
                     <i data-feather='plus'></i>
                     <span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Add Chapter')}}</span>
                  </a>
               </div>
            @endif
         </div>
      </div>
   </div>
</div>

<div class="text-center mt-5 pt-5" id="result" style="display: none;">
   <h4>No Results Found</h4>
</div>

@foreach($chapters as $chapter)
   <div class="row handbook_content">
      <div class="col-12">
         <div class="card">
            <div class="row justify-content-between pl-3 pr-3 pt-1 pb-1">
               <div id="edit{{$chapter->id}}" @if(old('chapter_name') != '' && old('chapter_id') == $chapter->id) style="display: none;" @else style="padding-top: 10px;" @endif class=" pl-1 pr-2">
                  <div class="row">
                     <h4>{{$chapter->name}}</h4>
                     @if(Auth::user()->isAdmin() || $permissions['handbook'])
                        <a class="text-primary pl-1" onclick="editChapter(this);" chapter_id="{{$chapter->id}}" data-toggle="tooltip" data-original-title="Edit Chapter"><i data-feather='edit-2'></i></a>
                        <a class="text-danger pl-1" data-toggle="modal" data-target="#deleteChapter{{$chapter->id}}">
                           <i data-feather='trash-2'></i>
                        </a>
                     @endif
                  </div>
               </div>
               
               <div id="save{{$chapter->id}}" @if(old('chapter_name') != '' && old('chapter_id') == $chapter->id) style="display: block;" @else style="display: none;" @endif class="pr-2">
                  <form action="@if(isset($locale)) {{route('chapter.update', [$locale, $chapter->id])}} @else {{route('chapter.update', ['en', $chapter->id])}} @endif" method="post">
                     <input name="_method" type="hidden" value="PUT">
                     {{ csrf_field() }}
                     <input type="text" value="{{$chapter->id}}" name="chapter_id" hidden>
                     <div class="row pl-1">
                        <div class="input-group">
                           <div class="input-group-prepend">
                              <button type="button" onclick="cancel(this);" chapter_id="{{$chapter->id}}" class="btn btn-outline-primary btn-sm rounded-left waves-effect waves-float waves-light" data-toggle="tooltip" data-original-title="Cancel Update"><i data-feather="x-circle"></i></button>
                           </div>
                           <input type="text" class="form-control" name="chapter_name" value="{{ old('chapter_name', $chapter->name) }}" placeholder="Enter Chapter Name"/>
                           <div class="input-group-append">
                              <button type="submit" class="btn btn-primary btn-sm waves-effect waves-float waves-light" data-toggle="tooltip" data-original-title="Update Chapter Name"><span class="d-lg-none d-md-none d-sm-inline"><i data-feather="check-circle"></i></span><span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Update')}}</span></button>
                           </div>
                        </div>
                     </div>
                  </form>
                  <span class="d-lg-none d-md-none d-sm-none"><div class="pt-1"></div></span>
               </div>
               @if(Auth::user()->isAdmin() || $permissions['handbook'])
                  <div>
                     <a href="@if(isset($locale)) {{route('chapter.page.create', [$locale, $chapter->id])}} @else {{route('chapter.page.create', ['en', $chapter->id])}} @endif" class="btn btn-brand btn-primary btn-elevate btn-icon-sm" data-toggle="tooltip" data-original-title="Add Page">
                        <i data-feather='plus'></i>
                        <span class="d-none d-lg-inline d-md-inline d-sm-none">{{__('language.Add Page')}}</span>
                     </a>
                  </div>
               @endif
            </div>
         </div>
      </div>

      @foreach($chapter['pages'] as $page)
         @php $description = $page->description; @endphp
         <div class="col-md-6 col-lg-4 col-sm-6 handbook_content">
            <div class="card">
               <div class="card-body d-flex justify-content-between" style="height: 70px;">
                  <p class="card-title">{{$page->title}}</p>
                  
                  @if(Auth::user()->isAdmin() || $permissions['handbook'])
                     @if(isset($chapter['pages'][1]))
                        <!-- Delete Page Modal -->
                        <div class="basic-modal">
                           <a class="text-danger" data-toggle="modal" data-target="#deletePage{{$page->id}}">
                              <i data-feather='trash-2'></i>
                           </a>
                           <div class="modal fade text-left" id="deletePage{{$page->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <h4 class="modal-title" id="myModalLabel1">Delete Page</h4>
                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                       </button>
                                    </div>
                                    <div class="modal-body pt-2">
                                       <p>Are you sure you want to delete "{{$page->title}}" page?</p>
                                    </div>
                                    <div class="modal-footer">
                                       <button type="submit" class="btn btn-outline-warning waves-effect waves-float waves-lightclose" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-original-title="Cancel"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='x-circle'></i></span><span class="d-none d-lg-block d-md-block d-sm-none">Cancel</span></button>
                                       
                                       <form action="@if(isset($locale)) {{route('chapter.page.destroy', [$locale, $chapter->id, $page->id])}} @else {{route('chapter.page.destroy', ['en', $chapter->id, $page->id])}} @endif" method="post">
                                          <input name="_method" type="hidden" value="DELETE">
                                          {{ csrf_field() }}
                                          <button type="submit" class="btn btn-danger waves-effect waves-float waves-light" data-toggle="tooltip" data-original-title="Delete"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='check-circle'></i></span><span class="d-none d-lg-block d-md-block d-sm-none">Delete</span></button>
                                       </form>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- Delete Page Modal -->
                     @else
                        <!-- Delete Page With Chapter Modal -->
                        <div class="basic-modal">
                           <a class="text-danger" data-toggle="modal" data-target="#deletePageWithChapter{{$page->id}}">
                              <i data-feather='trash-2'></i>
                           </a>
                           <div class="modal fade text-left" id="deletePageWithChapter{{$page->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <h4 class="modal-title" id="myModalLabel1">Delete Page</h4>
                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                       </button>
                                    </div>
                                    <div class="modal-body pt-2">
                                       <p>Only this page is in chapter "{{$chapter->name}}". Do you want to delete chapter with this page?</p>
                                    </div>
                                    <div class="modal-footer">
                                       <form action="@if(isset($locale)) {{route('chapter.page.destroy', [$locale, $chapter->id, $page->id])}} @else {{route('chapter.page.destroy', ['en', $chapter->id, $page->id])}} @endif" method="post">
                                          <input name="_method" type="hidden" value="DELETE">
                                          {{ csrf_field() }}
                                          <button type="submit" class="btn btn-outline-warning waves-effect waves-float waves-light" data-toggle="tooltip" data-original-title="No"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='x-circle'></i></span><span class="d-none d-lg-block d-md-block d-sm-none">No</span></button>
                                       </form>
                                       <form action="@if(isset($locale)) {{route('chapter.page.destroyPageWithChapter', [$locale, $chapter->id, $page->id])}} @else {{route('chapter.page.destroyPageWithChapter', ['en', $chapter->id, $page->id])}} @endif" method="post">
                                          {{ csrf_field() }}
                                          <button type="submit" class="btn btn-primary waves-effect waves-float waves-light" data-toggle="tooltip" data-original-title="Yes"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='check-circle'></i></span><span class="d-none d-lg-block d-md-block d-sm-none">Yes</span></button>
                                       </form>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- Delete Page With Chapter Modal -->
                     @endif
                  @endif
               </div>
               <img class="img-fluid" @if(isset($page->image)) src="{{ asset($page->image) }}" @else src="{{ asset('not_available.png') }}" @endif alt="Page Image" style="height: 200px;">
               <div class="card-body" style="height: 70px;">
                  @php $description = strip_tags($page->description); @endphp
                  {{substr($description, 0, 62) . '...'}}
               </div>

               <hr>

               <div class="card-body pt-0 d-flex justify-content-between">
                  @if(Auth::user()->isAdmin() || $permissions['handbook'])
                     <a class="text-primary" href="@if(isset($locale)) {{route('chapter.page.edit', [$locale, $chapter->id, $page->id])}} @else {{route('chapter.page.edit', ['en', $chapter->id, $page->id])}} @endif" data-toggle="tooltip" data-original-title="Edit Page"><i data-feather='edit-2'></i></a>
                  @else
                     <div>
                     </div>
                  @endif
                  <input type="text" name="{{$chapter->name}}" value="{{$chapter->name}}" hidden>
                  <a class="text-primary" href="@if(isset($locale)) {{route('chapter.page.show', [$locale, $chapter->id, $page->id])}} @else {{route('chapter.page.show', [$locale, $chapter->id, $page->id])}} @endif" data-toggle="tooltip" data-original-title="View Page"><i data-feather='eye'></i></a>
               </div>
            </div>
         </div>
      @endforeach
   </div>

   <!-- Delete Chapter Modal -->
   @if(Auth::user()->isAdmin() || $permissions['handbook'])
      <div class="basic-modal">
         <div class="modal fade text-left" id="deleteChapter{{$chapter->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <h4 class="modal-title" id="myModalLabel1">Delete Chapter</h4>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body pt-2">
                     <p>Are you sure you want to delete chapter "{{$chapter->name}}" with pages?</p>
                  </div>
                  <div class="modal-footer">
                     <form action="@if(isset($locale)) {{route('chapter.destroy', [$locale, $chapter->id])}} @else {{route('chapter.destroy', ['en', $chapter->id])}} @endif" method="post">
                        <input name="_method" type="hidden" value="DELETE">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger waves-effect waves-float waves-light" data-toggle="tooltip" data-original-title="Delete Chapter"><span class="d-lg-none d-md-none d-sm-block"><i data-feather='trash-2'></i></span><span class="d-none d-lg-block d-md-block d-sm-none">Delete</span></button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   @endif
   <!-- Delete Chapter Modal -->
@endforeach
<script>
   function editChapter(e)
   {
      chapter_id = e.getAttribute('chapter_id');
      document.getElementById('save' + chapter_id).style.display = "block";
      document.getElementById('edit' + chapter_id).style.display = "none";
   }
   function cancel(e)
   {
      chapter_id = e.getAttribute('chapter_id');
      document.getElementById('save' + chapter_id).style.display = "none";
      document.getElementById('edit' + chapter_id).style.display = "inline";
   }
   function search() {
      let input = document.getElementById('searchbar').value;
      input = input.toLowerCase();
      let x = document.getElementsByClassName('handbook_content');

      for (i = 0; i < x.length; i++) { 
         if (!x[i].innerHTML.toLowerCase().includes(input)) {
            x[i].style.display="none";
         }
         else {
            x[i].style.display="";
         }
      }

      text = $(".handbook_content").text();
      if (text.toLowerCase().search(input) == -1) {
         document.getElementById('result').style.display = "";
      } else {
         document.getElementById('result').style.display = "none";
      }
   }
</script>
@stop