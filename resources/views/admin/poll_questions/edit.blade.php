@extends('layouts/contentLayoutMaster')
@section('title','Poll Questions')

@section('vendor-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">

@endsection
@section('content')

<!-- client company edit start -->
<section class="users-edit">
  <div class="card">
    <div class="card-content">
      <div class="card-body">
        <!-- users edit account form start -->
        <form id="poll-questions" action="@if(isset($locale)){{route('polls.questions.update',[$locale,$pollId,$questionId])}} @else {{route('polls.questions.update',['en',$pollId,$questionId])}} @endif" method="post">
          {{csrf_field()}}
          <input type="hidden" name="_method" value="PUT">

          <div class="row">

          </div>
          <div class="row">
            <div class="col-12 ">

              <div class="form-group">
                <label>{{__('language.Question')}} <span style="color: red;">*</span></label>
                <input type="text" name="title" value="{{old('title',$questionTitle)}}" required class="form-control" placeholder="Question" data-validation-required-message="Question title is required">

              </div>
            </div>
          </div>
          <div class="divider divider-right">
            <div class="divider-text"> <button type="button" class="btn btn-md btn-clean btn-outline-primary" data-toggle="modal" data-target="#addAnswer">{{__('language.Add')}} {{__('language.Option')}}</button></div>
          </div>


          {{--Add Quiz Modal --}}
          <div class="modal fade" id="addAnswer" tabindex="-1" role="dialog" aria-labelledby="addAnswerTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="addAnswerTitle">{{__('language.Add')}} {{__('language.Option')}}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body p-2">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-12 col-12">
                        <div class="form-label-group">
                          <input type="text" id="answer_option" class="form-control" placeholder="Title *" name="answer_option">
                          <label for="first-name-column">{{__('language.Option')}} <span style="color: red;">*</span></label>
                        </div>
                      </div>
                      <div class="col-md-12 col-12">
                        <div class="form-group" data-select2-id="126">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success waves-effect waves-float waves-light" onclick="addQuestionOption(document.getElementById('answer_option').value);"><i data-feather="plus"></i> {{__('language.Add')}} </button>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-sm-6">
              <h5 class="mb-1 mt-2 mt-sm-0"></i>{{__('language.Options')}}</h5>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              {{---Table structure need to be rendered using JS----}}
              <div class="table-responsive">
                <table id="question-option-table" class="table">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>{{__('language.Title')}}</th>
                      <th>{{__('language.Actions')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(count($options)>0)
                    <input type="hidden" id="count-input" value="{{count($options)}}">
                    @else
                    <input type="hidden" id="count-input" value="1">
                    @endif

                    @foreach($options as $key => $o)
                    <tr role="row" class="@if($loop->iteration % 2 == 0){{'even'}}@else{{'odd'}}@endif">
                      <td class="dtr-control sorting_1" tabindex="0">{{$key+1}}</td>
                      <td><input type="hidden" name="question_option[]" value="{{$o['option_name']}}">{{$o['option_name']}}</td>
                      <td><button type="button" class="btn btn-sm btn-clean btn-icon btn-icon-md" id="{{$key+1}}" value="{{$o['option_name']}}" data-toggle="modal" data-target="#deleteAnswer"><i data-feather="trash-2"></i></button></td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="modal fade" id="deleteAnswer" tabindex="-1" role="dialog" aria-labelledby="deleteAnswerTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="deleteAnswerTitle">{{__('language.Delete')}} - <span id="title"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <h1 class="text-center text-lg"><i class="ficon feather icon-x-circle text-danger"></i>
                        </h1>
                        <p>{{__('language.Are you sure you want to delete this Option?')}}
                        </p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" id="" value="" onclick="deleteQuestionOption(this)" class="btn btn-danger delete-btn">Yes</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
            <button type="submit" style="color: white;" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">Save
              Changes
            </button>
            <button type="button" onclick="window.location.href='@if(isset($locale)){{route('polls.questions.index',[$locale,$pollId])}} @else {{route('polls.questions.index',['en',$pollId])}} @endif'" class="btn btn-outline-warning">Cancel
            </button>
          </div>

        </form>
        <!-- users edit Info form ends -->


      </div>
    </div>
  </div>
</section>
<!-- users edit ends -->

@endsection
@section('vendor-script')
{{-- Vendor js files --}}
<script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap4.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>

@endsection

@section('page-script')
{{-- Page js files --}}
<script src="{{ asset(mix('js/scripts/forms/validations/form-poll-questions.js'))}}"></script>

<script src="{{ asset(mix('js/scripts/tables/table-datatables-basic.js')) }}"></script>

<script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.2.7/js/dataTables.rowReorder.min.js"></script>

<script>

  var count = $('#count-input').val();
  var disable = "";
  var answerTable = $('#question-option-table').DataTable({
    rowReorder: true,
    responsive: true,
    language: {
      emptyTable: "Add Question Options"
    },
    columnDefs: [{
      "searchable": false,
      "orderable": false,
      "targets": 0
    }],
  });
  $('#deleteAnswer').on('show.bs.modal', function(event) {
    var id = $(event.relatedTarget).attr('id');
    var value = $(event.relatedTarget).val();
    $(this).find("#title").text(value);
    $(this).find(".delete-btn").val(value);
    $(this).find(".delete-btn").attr('id', id);
    $(this).find("#title").textContent = value;
  });

  function addQuestionOption(input) {

    if (input != '') {
      $('#answer_option').val('');
      $('.modal').modal('hide');
      inputQuestionOption = '<input type="hidden" name="question_option[]" value="' + input + '">';

      action = '<button type="button" class="btn btn-sm btn-clean btn-icon btn-icon-md" id="' + count + '" value="' + input + '" data-toggle="modal" ' +
        'data-target="#deleteAnswer" ><i data-feather="trash-2"></i></button>';
      answerTable.row.add([
        count,
        inputQuestionOption + input,
        action,
      ]).draw(false);
      feather.replace();
      count++;
    }
  }
  answerTable.on('order.dt search.dt', function() {
    answerTable.column(0, {
      search: 'applied',
      order: 'applied'
    }).nodes().each(function(cell, i) {
      cell.innerHTML = i + 1;
    });
  }).draw();

  function deleteQuestionOption(deletebtn) {
    var id = $(deletebtn).attr('id');
    var value = $(deletebtn).val();
    answerTable.row($('#question-option-table :input[value="' + value + '"]').parent('td').parent('tr')).remove().draw();
    $('.modal').modal('hide');
  }
</script>
@endsection