@extends('layouts.admin')
@section('title','Error Page')
@section('heading')
	<div class="kt-subheader__main">
		<h3 class="kt-subheader__title">
			Error</h3>
		<span class="kt-subheader__separator kt-hidden"></span>
	</div>
@stop
@section('content')
	<div class="row">
		<div class="col-lg-12">

			<!--begin::Portlet-->
			<div class="kt-portlet kt-portlet--mobile">
				<div class="kt-portlet__body">
					<h3>You are not allowed to view this page.</h3>
					<!--end: Datatable -->
				</div>
			</div>

			<!--end::Portlet-->
		</div>
	</div>
@endsection