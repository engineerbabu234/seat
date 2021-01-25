@extends('layouts.app')
@section('content')
    <!--building office-->
    <section class="reaserve-seat reaserve-seat-page">
        <div class="container">
            <div class="building-office-list">
                <div class="heading">

                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div class="title">
                                 <h1>Your Seat Questionaries</h1>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                            <div class="btns">
                                <a href="#" class="add-asset btn btn-info" data-target="#user_questions" data-toggle="modal"  ><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="your-history">
                   <div class="custom-data-table">
                        <div class="data-table">
                    <div class="custom-table-height">
                        <div class="table-responsive">
                            <table class="table table-striped text-center" id="laravel_datatable">
                                        <thead>
                                            <tr>
                                                <th><span class="iconWrap iconSize_32" data-content="Answare Id" title="Answare Id."  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/no-label.png" class="icon bl-icon" width="20" ></span></th>
                                                <th><span class="iconWrap iconSize_32" data-content="Answare Date" title="Date"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/order_date.png" class="icon bl-icon" width="20" ></span></th>
                                                <th><span class="iconWrap iconSize_32" data-content="Question Name" title="Name"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/question.png" class="icon bl-icon" width="20" ></span></th>
                                                <th><span class="iconWrap iconSize_32" data-content="Restrict Seat" title="Restrict Seat "  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/restrict-seat.png" class="icon bl-icon" width="20" ></span> </th>
                                                <th><span class="iconWrap iconSize_32" data-content="Expired date" title="Expired date"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/expire_after.png" class="icon bl-icon" width="20" ></span></th>
                                                <th><span class="iconWrap iconSize_32" data-content="Exam Status" title="Exam Status"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/exam_status.png" class="icon bl-icon" width="20" ></span> </th>
                                                <th><span class="iconWrap iconSize_32" data-content="Active Status" title="Active Status"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/status.png" class="icon bl-icon" width="20" ></span></th>
                                            </tr>
                                        </thead>
                                        <tbody >

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal" id="user_questions">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Your Seat Booking Quesionaire</span></h4>
                <button type="button"   class="close_new close_question_modal"  >&times;</button>
              </div>
              <div class="modal-body" >
                <form action="#"  method="post" id="add-office-asset-seat-question-form">
                 <div class="row">
                    <div class="col-sm-12">
                         <div class="form-group">
                            <label for="Quesionaire">Quesionaire</label>
                            <select class="form-control get_questions_list" id="quesionaire_id" name="quesionaire_id">
                                <option value="">-- Select Quesionaire --</option>
                                @foreach ($Quesionaire as $qkey => $qvalue)
                              <option value="{{ $qvalue->id }}">{{ $qvalue->title }} @if($qvalue->restriction) {{ ' (Required to pass)'}}  @endif</option>
                              @endforeach
                            </select>
                            <span class="error question_error" id="quesionaire_id_error">
                          </div>
                    </div>
                </div>
                <h4 id="Quesionaire_name"></h4>
                    <div id="logic_questions_list" class="w-100"></div>
                <hr>
                 <div class="row">
                    <div class="col-sm-12">
                        <div class="add-product-btn text-center ">
                            <input type="submit" value="Send Answare" class="btn btn-info add-answare">

                        </div>
                    </div>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>

    </section>




@endsection
@push('css')
<link  href="{{asset('front_end')}}/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush

@push('js')
<script src="{{asset('front_end')}}/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        //var current_date='{{date('Y-m-d')}}';
        urls = base_url+'/questionaries';
        var asset_datatable =$('#laravel_datatable').DataTable({
            processing: true,
            serverSide: true,
            "ordering": false,
            destroy: true,
            ajax:urls ,

            columns: [
                { data: 'number_key', name: 'number_key' },
                { data: 'date', name: 'date' },
                { data: 'name', name: 'name' },
                { data: 'restriction', name: 'restriction' },
                { data: 'expired_date', name: 'expired_date' },
                { data: 'exam_status', name: 'exam_status' },
                { data: 'active_status', name: 'active_status' },
            ]
        });




$(document).on("click", ".add-answare", function(e) {
    e.preventDefault();
    var data = jQuery(this).parents('form:first').serialize();

    var myThis = $(this);

    $.ajax({
        url: base_url + '/checklogic',
        type: 'post',
        dataType: 'json',
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function(response) {
            if (response.status == 400) {
                 if(response.responseJSON.errors){
                    $.each(response.responseJSON.errors, function(k, v) {

                        $('#' + k + '_error').text(v);
                        $('#' + k + '_error').addClass('text-danger');
                    });
                }

            }
        },
        success: function(response) {
            if (response.success) {
                $('#user_questions').modal('hide');
                $("#add-office-asset-seat-question-form").trigger('reset');
                    var redrawtable = jQuery('#laravel_datatable').dataTable();
                redrawtable.fnDraw();
                swal("Success!", response.message, "success");
            }
        },
    });
});



    });

     $(document).on('click','.close_question_modal',function(){
        $('#user_questions').modal('hide');
        $("form#add-office-asset-seat-question-form")[0].reset();
        $('#logic_questions_list').html('');
        $('.error').html('');
         $('#Quesionaire_name').text('');
    });

      $(document).on('change','.get_questions_list',function(){
        var quesionaire_id = $("#quesionaire_id option").filter(":selected").val();
            if(quesionaire_id){
                $('#Quesionaire_name').text($("#quesionaire_id option").filter(":selected").text());
             $.ajax({
                url: base_url + "/getuserLogicQuestions/"+quesionaire_id,
                type: "GET",
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {

                    if(res.html ){
                      $("#user_questions").modal("show");

                      $('#logic_questions_list').html(res.html);

                    }
                },
                error: function(err) {
                  console.log(err);

                }

            });

             } else{
                $('#logic_questions_list').html('');
                $('#Quesionaire_name').text('');
             }
    });


</script>
@endpush
