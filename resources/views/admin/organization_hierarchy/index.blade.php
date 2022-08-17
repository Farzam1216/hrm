@extends('layouts.admin')
@section('title','Organization Hierarchy')
@section('heading')
<div class="content-header">
<div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"> {{__('language.People Management')}}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{__('language.Organization')}} {{__('language.Hierarchy')}}</a></li>
              <li class="breadcrumb-item active">Org {{__('language.Chart')}}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      
      <div class="row justify-content-end">
		
            <div class="col-12">
            <button type="button" class="btn btn-info btn-rounded m-t-10 float-right" onclick="window.location.href='@if(isset($locale)){{url($locale.'/organization-hierarchy/create')}} @else {{url('es/organization-hierarchy/create')}} @endif'"><i class="fa fa-plus"></i> {{__('language.Add')}} Org {{__('language.Employee')}}</button>
    </div> 
      </div>
    </div><!-- /.container-fluid -->   
</div>
@stop
@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
                <h6 class="card-subtitle"></h6>
                
				<br>
				<div class="table table-responsive">
				<div id="chart-container" class="table-responsive">
				</div>
				<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<form id="delete" action="" method="post">
								<input name="_method" type="hidden" value="DELETE">
								{{ csrf_field() }}
								<div class="modal-header">
									{{__('language.Are you sure you want to delete this Employee')}} {{__('language.& his subordinates from Organization Hierarchy?')}}
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">{{__('language.Cancel')}}</button>
									<button  type="submit" class="btn btn-danger btn-ok">{{__('language.Delete')}}</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				{{--END Orgnisation chart--}}
				</div>
			</div>
		</div>
	</div>
</div>
@stop

@push('scripts')
<script type="text/javascript" src="{{asset('OH/js/jquery.mockjax.min.js')}}"></script>
<script type="text/javascript" src="{{asset('OH/js/jquery.orgchart.js')}}"></script>
<script type="text/javascript">
function assignFormAction(id){
	$('form#delete').attr('action',  "{{url($locale.'/organization-hierarchy')}}/" + id);
}
$(function() {
    $.mockjax({
        url: '/orgchart/initdata',
        responseTime: 1000,
        contentType: 'application/json'
        @if ($hierarchy)
        ,responseText: {!! $hierarchy !!}
        @endif
    });

    $('#chart-container').orgchart({
        'data' : '/orgchart/initdata',
        'nodeContent': 'title',
        'width': '100%',
        'createNode': function($node, data) {
            var secondMenuIcon = $('<li>', {
                'class': 'fas fa-plus-circle create-menu-icon',
            	'onclick' : "location.href='/{!! $locale !!}/organization-hierarchy/create'"
            	// 'onclick' : "location.href='{{route('organization-hierarchy.create')}}/" + data.id+"'"
            });
            $node.append(secondMenuIcon);

            var thirdMenuIcon = $('<li>', {
                'class': 'fas fa-edit edit-menu-icon',
            	'onclick' : "location.href='/{!! $locale !!}/organization-hierarchy/" + data.id+"/edit'"
            });

            $node.append(thirdMenuIcon);

            var deleteIcon = $('<li>', {
                'class': 'fas fa-trash delete-menu-icon',
                'data-toggle' : 'modal',
                'data-target' : '#confirm-delete',
            	'onclick' : "assignFormAction("+data.employee_id+")",
            });

            $node.append(deleteIcon);
        }
    });
});
</script>

{{--Organisational Structure--}}
<link rel="icon" href="{{asset('OH/img/logo.png')}}">
<link rel="stylesheet" href="{{asset('OH/css/jquery.orgchart.css')}}">
{{--Add ICON--}}
<style type="text/css">
    .orgchart{ width: 100% }

    .orgchart .create-menu-icon {
        transition: opacity .5s;
        opacity: 0;
        right: 112px;
        top: -5px;
        z-index: 2;
        color: rgba(68, 157, 68, 0.5);
        font-size: 18px;
        position: absolute;
        color: black;
    }
    .orgchart .create-menu-icon:hover { color:black; }
    .orgchart .node:hover .create-menu-icon { opacity: 1; }

	.orgchart .edit-menu-icon {
        transition: opacity .5s;
        opacity: 0;
        right: -5px;
        top: -5px;
        z-index: 2;
        color: rgba(68, 157, 68, 0.5);
        font-size: 18px;
        position: absolute;
        color: black;
    }
    .orgchart .edit-menu-icon:hover { color:black; }
    .orgchart .node:hover .edit-menu-icon { opacity: 1; }

    .orgchart .delete-menu-icon {
        transition: opacity .5s;
        opacity: 0;
        right: -1px;
        top: 30px;
        z-index: 2;
        color: rgba(68, 157, 68, 0.5);
        font-size: 18px;
        position: absolute;
        color: black;
    }
    .orgchart .delete-menu-icon:hover { color:black; }
    .orgchart .node:hover .delete-menu-icon { opacity: 1; }

</style>

{{--END Organisational Structure--}}
@endpush