@extends('layouts.admin')
@section('title','Approvals')
@section('heading')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-thumbs-up"></i> {{__('language.Edit Approval Form')}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">{{__('language.Settings')}}</li>
                    <li class="breadcrumb-item">
                        <a href="@if(isset($locale)){{url($locale.'/approvals')}} @else {{url('en/approvals')}} @endif">{{__('language.Approvals')}}</a>
                    </li>
                    <li class="breadcrumb-item active"> {{__('language.Edit')}}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div> <!-- /.content-header -->
@stop
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="col-md-12 card-header">
        <button type="button" class="btn btn-default float-right"
                onclick="window.location.href='@if(isset($locale)){{url($locale.'/approvals')}} @else {{url('en/approvals')}} @endif'">{{__('language.Back')}}</button>

        <div class="card-body">
             <form action="@if(isset($locale)){{url($locale.'/approvals', $approval->id)}} @else {{url('en/approvals', $approval->id)}} @endif" method="post" enctype="multipart/form-data">
                <input name="_method" type="hidden" value="PUT">
                {{ csrf_field() }}


                <div class="form-group col-md-12 mt-3">

                    <div class="form-group ">
                        <label class="control-label">Approval Name <span>*</span></label>
                        <input class="form-control col-md-2" maxlength="100" type="text" required="required" value="{{$approval->name}}" name="name" />
                    </div>
                    <div class="form-group ">
                        <label class="control-label">Description <span>*</span></label>
                        <textarea class=" form-control col-md-3" rows="4" required="required" name="description">{{$approval->description}}</textarea>
                    </div>

                    <hr><br>
                    @if(isset($defaultFields['Default']))
                    <label for="Approval_fields">Approval Fields</label>
                    <p>By default, these fields will be on the approval form. You can add additional fields below.</p>

                    @foreach ($defaultFields['Default'] as $defaultField)
                    <div class="form-control col-md-2">{{$defaultField['name']}}</div>
                    @endforeach
                    @endif
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group col-md-5">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="">Browse fields</h5>

                                </div>
                                <div class="add_additional_fields_hidden">
                                </div>
                                <div class="card-body overflow-auto" style="overflow: scroll;">


                                    <div class="panel-group">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">
                                                    <a data-toggle="collapse" href="#collapse1">Personal</a>
                                                </h5>
                                            </div>
                                            <div id="collapse1" class="panel-collapse collapse">
                                                <ul class="list-group" id="left-list">
                                                    <div id="Personal">

                                                        <li value="" name="id" class="list-group-item" id='list1'>Employee No</li>

                                                        <li value="" name="status" class="list-group-item" id='list2'>Status</li>


                                                        <li value="" name="firstname" class="list-group-item" id='list3'>First Name</li>



                                                        <li value="" name="lastname" class="list-group-item" id='list4'>Last Name</li>



                                                        <li value="" name="date_of_birth" class="list-group-item" id='list6'>Birth Date</li>

                                                        <li value="" name="nin" class="list-group-item" id='list5'>NIN</li>

                                                        <li value="" name="gender" class="list-group-item" id='list7'>Gender</li>

                                                        <li value="" name="current_address" class="list-group-item" id='list8'>Current Address</li>

                                                        <li value="" name="permanent_address" class="list-group-item" id='list9'>Permanent Address</li>

                                                        <li value="" name="city" class="list-group-item" id='list10'>City</li>

                                                        <li value="" name="official_email" class="list-group-item" id='list11'>Official Email</li>

                                                        <li value="" name="personal_email" class="list-group-item" id='list12'>Personal Email</li>




                                                        <details class="list-group-item" id="sub-group-education" open>
                                                            <summary>Education</summary>
                                                            <li value="" name="institute_name" class="list-group-item" id='list13'>Institute Name</li>

                                                            <li value="" name="major" class="list-group-item" id='list14'>Major</li>

                                                            <li value="" name="cgpa" class="list-group-item" id='list15'>CGPA</li>

                                                            <li value="" name="date_start" class="list-group-item" id='list16'>Date Start</li>

                                                            <li value="" name="date_end" class="list-group-item" id='list17'>Date End</li>

                                                        </details>






                                                        <details class="list-group-item" id="sub-group-visa" open>
                                                            <summary>Visa</summary>
                                                            <li value="" name="visa_type_id" class="list-group-item" id='list18'>Visa Name</li>

                                                            <li value="" name="country_id" class="list-group-item" id='list19'>Issuing Country</li>

                                                            <li value="" name="visa_note" class="list-group-item" id='list20'>Visa Note</li>

                                                            <li value="" name="issue_date" class="list-group-item" id='list21'>Visa Date Start</li>

                                                            <li value="" name="expire_date" class="list-group-item" id='list22'>Visa Date End</li>

                                                        </details>


                                                    </div>
                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="panel-group">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">
                                                    <a data-toggle="collapse" href="#collapse2">Job</a>
                                                </h5>
                                            </div>
                                            <div id="collapse2" class="panel-collapse collapse">
                                                <ul class="list-group" id="left-list">
                                                    <div id="Job">
                                                        <li value="" name="joining_date" class="list-group-item" id='list23'>Hire Date</li>
                                                    </div>

                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                    <hr>


                                    <div class="panel-group">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">
                                                    <a data-toggle="collapse" href="#collapse3">TimeOff</a>
                                                </h5>
                                            </div>
                                            <div id="collapse3" class="panel-collapse collapse">
                                                <ul class="list-group" id="left-list">
                                                    <div id="TimeOff">
                                                        <li value="" name="accrual_date" class="list-group-item" id='list24'>Accrual Level Start Date</li>

                                                    </div>

                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="panel-group">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">
                                                    <a data-toggle="collapse" href="#collapse4">Notes</a>
                                                </h5>
                                            </div>
                                            <div id="collapse4" class="panel-collapse collapse">
                                                <ul class="list-group" id="left-list">
                                                    <div id="Notes">
                                                        <li value="" name="note" class="list-group-item" id='list25'>Notes</li>
                                                    </div>

                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="panel-group">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">
                                                    <a data-toggle="collapse" href="#collapse5">Benefits</a>
                                                </h5>
                                            </div>
                                            <div id="collapse5" class="panel-collapse collapse">
                                                <ul class="list-group" id="left-list">
                                                    <div id="Benefits">
                                                        <li value="" name="first_name" class="list-group-item" id='list26'>Dependent First Name</li>
                                                        <li value="" name="middle_name" class="list-group-item" id='list27'>Dependent Middle Name</li>
                                                        <li value="" name="last_name" class="list-group-item" id='list27'>Dependent Last Name</li>
                                                        <li value="" name="dependent_date_of_birth" class="list-group-item" id='list28'>Dependent Birth Date</li>
                                                        <li value="" name="SSN" class="list-group-item" id='list29'>Dependent SSN</li>
                                                        <li value="" name="SIN" class="list-group-item" id='list30'>Dependent SIN</li>
                                                        <li value="" name="dependent_gender" class="list-group-item" id='list31'>Dependent Gender</li>
                                                        <li value="" name="relationship" class="list-group-item" id='list32'>Dependent Relationship</li>
                                                        <li value="" name="home_phone" class="list-group-item" id='list33'>Dependent Home Phone</li>
                                                        <li value="" name="address" class="list-group-item" id='list34'>Dependent Address 1</li>
                                                        <li value="" name="is_us_citizen" class="list-group-item" id='list35'>Dependent Is US Citizen</li>
                                                        <li value="" name="is_student" class="list-group-item" id='list36'>Dependent Is Student</li>
                                                    </div>

                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="panel-group">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">
                                                    <a data-toggle="collapse" href="#collapse6">Assets</a>
                                                </h5>
                                            </div>
                                            <div id="collapse6" class="panel-collapse collapse">
                                                <ul class="list-group" id="left-list">
                                                    <div id="Assets">
                                                        <li value="" name="asset_category" class="list-group-item" id='list37'>Asset Category</li>
                                                        <li value="" name="asset_description" class="list-group-item" id='list38'>Asset Description</li>
                                                        <li value="" name="serial" class="list-group-item" id='list39'>Serial</li>
                                                        <li value="" name="assign_date" class="list-group-item" id='list40'>Date Loaned</li>
                                                        <li value="" name="return_date" class="list-group-item" id='list41'>Date Return</li>
                                                    </div>

                                                </ul>

                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-5">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="">Select the fields <span class="text-bold text-green text-large">*</span> = Required</h5>
                                </div>
                                <div class="card-body" id="right-list">
                                    <ul class="list-group" id="right-items">
                                        <div id="Personal">
                                            @if(isset($defaultFields['Personal']))
                                            @foreach($defaultFields['Personal'] as $key => $field)

                                            <div class="row">
                                                <div class="col-md-10">
                                                    <li class="list-group-item" name="{{$key}}" value="">{{$field['name']}}</li>
                                                </div>
                                                <div class="col-md-2" id="">
                                                    <div class="switch">
                                                        <div class="custom-control custom-switch" id="{{str_replace(' ', '', $field['name'])}}">

                                                            <input type="checkbox" class="custom-control-input" id="personal{{$field['name']}}" "@if($field['status'] == 'required') checked @endif">
                                                            <label class="custom-control-label" for="personal{{$field['name']}}">*</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @endforeach
                                            @endif


                                            <div id="sub-group-education">



                                                @if(isset($defaultFields['Education']))
                                                @foreach($defaultFields['Education'] as $key => $field)

                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <li value="" name="{{$key}}" class="list-group-item">{{$field['name']}}</li>
                                                    </div>
                                                    <div class="col-md-2" id="">
                                                        <div class="switch">
                                                            <div class="custom-control custom-switch" id="{{str_replace(' ', '', $field['name'])}}">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="sub-group-education{{$field['name']}}" "@if($field['status'] == 'required') checked @endif">
                                                                <label class="custom-control-label" for="sub-group-education{{$field['name']}}">*</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                @endforeach
                                                @endif
                                            </div>
                                            <div id="sub-group-visa">

                                                @if(isset($defaultFields['Visa']))
                                                @foreach($defaultFields['Visa'] as $key => $field)


                                                @if($field['name'] == 'visa_note')
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <li value="" name="visa_{{$key}}" class="list-group-item">{{$field['name']}}</li>
                                                    </div>
                                                    <div class="col-md-2" id="">
                                                        <div class="switch">
                                                            <div class="custom-control custom-switch" id="{{str_replace(' ', '', $field['name'])}}">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="sub-group-visa{{$field['name']}}" "@if($field['status'] == 'required') checked @endif">
                                                                <label class="custom-control-label" for="sub-group-visa{{$field['name']}}">*</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @php continue; @endphp
                                                @endif



                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <li value="" name="{{$key}}" class="list-group-item">{{$field['name']}}</li>
                                                    </div>
                                                    <div class="col-md-2" id="">
                                                        <div class="switch">
                                                            <div class="custom-control custom-switch" id="{{str_replace(' ', '', $field['name'])}}">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="sub-group-visa{{$field['name']}}" "@if($field['status'] == 'required') checked @endif">
                                                                <label class="custom-control-label" for="sub-group-visa{{$field['name']}}">*</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div id="Job">
                                            @if(isset($defaultFields['Job']))
                                            @foreach($defaultFields['Job'] as $key => $field)

                                            <div class="row">
                                                <div class="col-md-10">
                                                    <li value="" name="{{$key}}" class="list-group-item">{{$field['name']}}</li>
                                                </div>
                                                <div class="col-md-2" id="">
                                                    <div class="switch">
                                                        <div class="custom-control custom-switch" id="{{str_replace(' ', '', $field['name'])}}">
                                                            <input type="checkbox" class="custom-control-input" id="Job{{$field['name']}}" "@if($field['status'] == 'required') checked @endif">
                                                            <label class="custom-control-label" for="Job{{$field['name']}}">*</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @endforeach
                                            @endif
                                        </div>
                                        <div id="Benefits">
                                            @if(isset($defaultFields['Benefits']))
                                            @foreach($defaultFields['Benefits'] as $key => $field)

                                            @if($field['name'] == 'Dependent Birth Date')
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <li value="" name="dependent_{{$key}}" class="list-group-item">{{$field['name']}}</li>
                                                </div>
                                                <div class="col-md-2" id="">
                                                    <div class="switch">
                                                        <div class="custom-control custom-switch" id="{{str_replace(' ', '', $field['name'])}}">
                                                            <input type="checkbox" class="custom-control-input" id="Benefits{{$field['name']}}" "@if($field['status'] == 'required') checked @endif">
                                                            <label class="custom-control-label" for="Benefits{{$field['name']}}">*</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @php continue; @endphp
                                            @endif



                                            @if($field['name'] == 'Dependent Gender')
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <li value="" name="dependent_{{$key}}" class="list-group-item">{{$field['name']}}</li>
                                                </div>
                                                <div class="col-md-2" id="">
                                                    <div class="switch">
                                                        <div class="custom-control custom-switch" id="{{str_replace(' ', '', $field['name'])}}">
                                                            <input type="checkbox" class="custom-control-input" id="Benefits{{$field['name']}}" "@if($field['status'] == 'required') checked @endif">
                                                            <label class="custom-control-label" for="Benefits{{$field['name']}}">*</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @php continue; @endphp
                                            @endif


                                            <div class="row">
                                                <div class="col-md-10">
                                                    <li value="" name="{{$key}}" class="list-group-item">{{$field['name']}}</li>
                                                </div>
                                                <div class="col-md-2" id="">
                                                    <div class="switch">
                                                        <div class="custom-control custom-switch" id="{{str_replace(' ', '', $field['name'])}}">
                                                            <input type="checkbox" class="custom-control-input" id="Benefits{{$field['name']}}" "@if($field['status'] == 'required') checked @endif">
                                                            <label class="custom-control-label" for="Benefits{{$field['name']}}">*</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @endforeach
                                            @endif
                                        </div>
                                        <div id="Assets">
                                            @if(isset($defaultFields['Assets']))
                                            @foreach($defaultFields['Assets'] as $key => $field)

                                            <div class="row">
                                                <div class="col-md-10">
                                                    <li value="" name="{{$key}}" class="list-group-item">{{$field['name']}}</li>
                                                </div>
                                                <div class="col-md-2" id="">
                                                    <div class="switch">
                                                        <div class="custom-control custom-switch" id="{{str_replace(' ', '', $field['name'])}}">
                                                            <input type="checkbox" class="custom-control-input" id="Assets{{$field['name']}}" "@if($field['status'] == 'required') checked @endif">
                                                            <label class="custom-control-label" for="Assets{{$field['name']}}">*</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @endforeach
                                            @endif
                                        </div>
                                        <div id="TimeOff">
                                            @if(isset($defaultFields['TimeOff']))
                                            @foreach($defaultFields['TimeOff'] as $key => $field)

                                            <div class="row">
                                                <div class="col-md-10">
                                                    <li value="" name="{{$key}}" class="list-group-item">{{$field['name']}}</li>
                                                </div>
                                                <div class="col-md-2" id="">
                                                    <div class="switch">
                                                        <div class="custom-control custom-switch" id="{{str_replace(' ', '', $field['name'])}}">
                                                            <input type="checkbox" class="custom-control-input" id="TimeOff{{$field['name']}}" "@if($field['status'] == 'required') checked @endif">
                                                            <label class="custom-control-label" for="TimeOff{{$field['name']}}">*</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @endforeach
                                            @endif
                                        </div>
                                        <div id="Notes">
                                            @if(isset($defaultFields['Notes']))
                                            @foreach($defaultFields['Notes'] as $key => $field)

                                            <div class="row">
                                                <div class="col-md-10">
                                                    <li value="" name="{{$key}}" class="list-group-item">{{$field['name']}}</li>
                                                </div>
                                                <div class="col-md-2" id="">
                                                    <div class="switch">
                                                        <div class="custom-control custom-switch" id="{{str_replace(' ', '', $field['name'])}}">
                                                            <input type="checkbox" class="custom-control-input" id="Notes{{$field['name']}}" "@if($field['status'] == 'required') checked @endif">
                                                            <label class="custom-control-label" for="Notes{{$field['name']}}">*</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @endforeach
                                            @endif
                                        </div>
                                    </ul>
                                </div>
                                <button class="btn btn-primary nextBtn btn-lg" type="submit" id="btnSubmit" onClick="fielddata()">Save</button>
                                <div class="switch">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </form>
        </div>
    </div>
    </div>
</div>
<script>
    $(document).ready(function(){
    $('li').css('cursor', 'pointer');
    var leftList = $('#left-list li');
    var rightList = $('#right-items li');
 
    for(var i =0; i<leftList.length; i++){
        
        for(var j=0; j<rightList.length; j++){
            
            if (leftList[i] . innerHTML == rightList[j] . innerHTML) {
              $('#left-list #' + leftList[i] . id).remove();
}

        }
    }
});
        

        // Move active employee from left to right card and remove active class
        $(document).on('click', '#left-list li', function () {
                
            if($(this).parent().attr('id') == 'sub-group-visa' || $(this).parent().attr('id') == 'sub-group-education'){
                $('#right-list #right-items #' + $(this).parent().parent().attr('id') + ' #' + $(this).parent().attr('id')).prepend('<div class="row"><div class="col-md-10"><li value="" name="'+$(this).attr('name')+'" class="list-group-item">'+ $(this).text() +'</li></div><div class="col-md-2" id=""><div class="switch"><div class="custom-control custom-switch" id="'+ $(this).text().replace(/ /g,'') + '"><input type="checkbox" class="custom-control-input" id="'+ $(this).parent().attr('id') + $(this).text().replace(/ /g,'') +'" checked><label class="custom-control-label" for="'+ $(this).parent().attr('id') + $(this).text().replace(/ /g,'') + '">*</label></div></div></div></div>'); 
                $(this).remove(); 
            }else{
                $('#right-list #right-items #' + $(this).parent().attr('id')).prepend('<div class="row"><div class="col-md-10"><li value="" name="'+$(this).attr('name')+'" class="list-group-item">'+ $(this).text() +'</li></div><div class="col-md-2" id=""><div class="switch"><div class="custom-control custom-switch" id="'+ $(this).text().replace(/ /g,'') + '"><input type="checkbox" class="custom-control-input" id="'+ $(this).parent().attr('id') + $(this).text().replace(/ /g,'')+'" checked><label class="custom-control-label" for="'+ $(this).parent().attr('id') + $(this).text().replace(/ /g,'')+ '">*</label></div></div></div></div>');
                $(this).remove(); 
}
               
        });
        // Move active employee from right to left card and remove active class
        $(document).on('click', '#right-list #right-items li', function () {            
            
             if($(this).parent().attr('id') == 'sub-group-visa' || $(this).parent().attr('id') == 'sub-group-education'){
                    
                 $('#left-list #' + $(this).parent().parent().attr('id') + ' #' + $(this).parent().attr('id')).prepend($(this).removeClass('active'));
                 $(".col-md-2 #" + $(this).text().replace(/ /g,'')).remove();
             }else{
                 

                 $('#left-list #' + $(this).parent().parent().parent().attr('id')).prepend($(this).removeClass('active'));
                 $(".row .col-md-2 #" + $(this).text().replace(/ /g,'')).remove();
             }
            
        });

        function fielddata () {  
            var fields = $('#right-list li').map(function() {
                
                return {
                field_data :{
                   "status": $('#' + $(this).text().replace(/ /g,'') + ' input').is(':checked') ? "required" : "notRequired",
                   "field_name": $(this).attr('name'),
                   "name": $(this).text()
                },
                
            };
            }).get();
            
            $('.add_additional_fields_hidden').empty();
            $.each(fields, function (index, value) {
                $.each(value, function (indexInArray, valueOfElement) {
                    $('.add_additional_fields_hidden').append(
                    '<input class="form-group" value="['+valueOfElement.name+', '+valueOfElement.status+']" name="field_data['+valueOfElement.field_name+']" type="hidden">'
                    );
                });
            });
        }
</script>
@stop