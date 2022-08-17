@extends('layouts/contentLayoutMaster')

@section('title', 'Contact Us')
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection
@section('content')
<section id="multiple-column-form">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          {{--  <h4 class="card-title">Multiple Column</h4>  --}}
        </div>
        <div class="card-body">
            <form id="contact-us" class="form" action="@if(isset($locale)){{url($locale.'/contact-us')}} @else {{url('en/contact-us')}} @endif" 
            method="post" enctype="multipart/form-data"> {{ csrf_field() }}
                <div class="row">
                <div class="col-md-6 col-12">
                    <div class="form-group pl-2">
                    <label for="first-name-column">First Name</label><span
                    class="text-danger"> *</span>
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Enter First Name..."
                        name="firstname"
                    />
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group pr-2">
                    <label for="last-name-column">Last Name</label><span
                    class="text-danger"> *</span>
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Enter Last Name..."
                        name="lastname"
                    />
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group pl-2">
                    <label for="first-name-column">Email</label><span
                    class="text-danger"> *</span>
                    <input
                        type="email"
                        class="form-control"
                        placeholder="Enter Email..."
                        name="email"
                    />
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group pr-2">
                    <label for="last-name-column">Subject</label><span
                    class="text-danger"> *</span>
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Enter Subject..."
                        name="subject"
                    />
                    </div>
                </div>
                <div class="col-md-12 col-12">
                    <div class="col-md-12 form-group pl-2 pr-2">
                        <label class="control-label">Message</label><span
                        class="text-danger"> *</span>
                        <textarea class="form-control" rows="5" cols="5" name="message"  placeholder="{{__('language.Enter')}} Message"></textarea>

                    </div>
                </div>
                <div class="col-md-12 col-12">
                    <div class="col-md-12 form-group pl-2 pr-2">
                        <label class="control-label">{{__('File Upload')}}</label>
                        <input type="file" class="form-control" name="file[]" multiple/>
                    </div>
                </div>
                <div class="col-12 pl-2 mt-2 ml-1">
                    <button type="submit" class="btn btn-primary mr-1">Send</button>
                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                </div>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
@section('page-script')
    <script src="{{ asset(mix('js/scripts/forms/validations/form-contact-us.js'))}}"></script>
@endsection
