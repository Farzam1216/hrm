@extends('layouts/contentLayoutMaster')
@section('title','Locations')
@section('vendor-style')
  {{-- Vendor Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
  {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection
@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<form id="location-create-form" action="@if(isset($locale)){{url($locale.'/locations',$office_location->id)}} @else {{url('en/locations',$office_location->id)}} @endif" method="post" enctype="multipart/form-data">
				<input name="_method" type="hidden" value="PUT">
				{{csrf_field()}}
				<br>
				<div class="form-body">
						<div class="row">
							<div class="col-md-6 pl-3">
								<div class="form-group">
									<label class="control-label">{{__('language.Name')}}<span class="text-danger">*</span></label>
									<input  type="text" name="name" placeholder="{{__('language.Enter Name Here')}}*" class="form-control" value="{{old('name', $office_location->name)}}">
								</div>
							</div>
							<div class="col-md-6 pr-3">
								<div class="form-group">
									<label class="control-label">{{__('language.Phone')}}#</label>
									<input type="tel" name="phone_number" class="form-control" placeholder="{{__('language.Enter Phone Number here')}}" value="{{old('phone_number', $office_location->phone_number)}}">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 pl-3 pr-3">
								<h4 class="mb-1 mt-2 border-bottom pb-1"><i class="font-medium-3 mr-25" data-feather='map-pin'></i>{{__('language.Address')}}</h4>
								    <div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label">{{__('language.Street 1')}}<span class="text-danger">*</span></label>
											<input type="text" name="street_1"
											placeholder="{{__('language.Enter')}} {{__('language.Street 1')}}*"
											value="{{old('name', $office_location->street_1)}}"
											class="form-control">
											</div>
									</div>
									<div class="col-md-4">
									<div class="form-group">
									    <label class="control-label">{{__('language.Street 2')}}</label>
									    <input type="text" name="street_2"
										   placeholder="{{__('language.Enter')}} {{__('language.Street 2')}}"
										   value="{{old('name', $office_location->street_2)}}"
										   class="form-control">
										   </div>
									</div>
									<div class="col-md-4">
									<div class="form-group">
									    <label class="control-label">{{__('language.Enter')}} {{__('language.City')}}<span class="text-danger">*</span></label>
										<input type="text" name="city"
											   placeholder="{{__('language.Enter')}} {{__('language.City')}}*"
											   value="{{old('name', $office_location->city)}}"
											   class="form-control">
											   </div>
									</div>
                                      </div>
									<div class="row">
									<div class="col-md-4">
									<div class="form-group">
									    <label>{{__('language.State')}}/{{__('language.Province')}}</label>
										<input type="text" name="state"
											   placeholder="{{__('language.State')}}/{{__('language.Province')}}"
											   value="{{old('name', $office_location->state)}}"
											   class="form-control">
											   </div>
									</div>
									<div class="col-md-4">
									<div class="form-group">
									    <label>{{__('language.Zip')}}/{{__('language.Code')}}<span class="text-danger">*</span></label>
										<input type="text" name="zip_code"
											   placeholder="{{__('language.Zip')}}/{{__('language.Postal')}} {{__('language.Code')}}"*
											   value="{{old('name', $office_location->zip_code)}}"
											   class="form-control">
											   </div>
									</div>
								    <div class="col-md-4">
									<div class="form-group">
									<label>{{__('language.Country')}}</label>
									<select class="form-control custom-select" name="country">
										@foreach($countries as $country)
										<option @if($office_location->country == $country->name) selected @endif value="{{$country->name}}">{{$country->name}}</option>
										@endforeach

									</select>
									</div>
								</div>
							</div>
						</div>​
					</div>
					<div class="col-12 d-flex flex-sm-row flex-column mt-2 mb-2">
						<button type="submit" class="btn btn-primary mb-1 mb-sm-0 ml-1 mr-0 mr-sm-1 waves-effect waves-float waves-light">{{__('language.Update')}} {{__('language.Location')}}</button>
						<button type="button" onclick="window.location.href='@if(isset($locale)){{url($locale.'/locations')}} @else {{url('en/locations')}} @endif'" class="btn btn-outline-warning waves-effect">{{__('language.Cancel')}}</button>
					</div>
				</div>
			</form>
			</div>
		</div>
	</div>
@stop
@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection
@section('page-script')
  <!-- Page js files -->
<script src="{{ asset(mix('js/scripts/forms/validations/form-locations-validation.js')) }}"></script>
@endsection