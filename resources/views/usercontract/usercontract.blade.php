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
                                 <h1>Contract list</h1>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                            <div class="btns">
                                <a href="#" class=" btn btn-info" data-target="#user_contract" data-toggle="modal"  ><i class="fas fa-plus"></i></a>
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
                                                <th><span class="iconWrap iconSize_32" title="Contract Templates Id."  data-trigger="hover" data-content="Contract Templates Id" data-placement="left"><img src="{{asset('admin_assets')}}/images/id.png" class="icon bl-icon" width="30" ></span></th>
                                                <th><span class="iconWrap iconSize_32" title="Contract Provider"  data-content="Contract Provider" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/provider.png" class="icon bl-icon" width="30" ></span> </th>
                                                 <th><span class="iconWrap iconSize_32" title="Provider Contract Template"  data-content="Provider Contract Template" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/document.png" class="icon bl-icon" width="30" ></span> </th>
                                                <th><span class="iconWrap iconSize_32" title="Contract Title" data-content="Contract Title"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/Name.png" class="icon bl-icon" width="30" ></span>  </th>
                                                <th><span class="iconWrap iconSize_32" title="Restrict Seat" data-content="Restrict Seat"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/restrict-seat.png" class="icon bl-icon" width="30" ></span> </th>
                                                <th><span class="iconWrap iconSize_32" title="Contract Description"  data-content="Contract Description"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="30" ></span> </th>
                                                <th><span class="iconWrap iconSize_32" title="Update Date" data-content="Update Date"   data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/order_date.png" class="icon bl-icon" width="30" ></span> </th>
                                                <th nowrap><span class="iconWrap iconSize_32" title="Action" data-content="Action"   data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/action.png" class="icon bl-icon" width="30" ></span> </th>
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

        <div class="modal" id="user_contract">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Your Booking Contracts</span></h4>
                <button type="button"   class="close_new close_contract_modal"  >&times;</button>
              </div>
              <div class="modal-body" >
                <form   method="post" id="add-contract-form">
                     <div class="row">
                        <div class="col-sm-12">
                             <div class="form-group">
                                <label for="document_id">Contracts to sign</label>
                                <select class="form-control  " id="document_id" name="document_id">
                                    <option value="">-- Select Documents --</option>
                                    @foreach ($Contract as $qkey => $qvalue)
                                  <option value="{{ $qvalue->id }}">{{ $qvalue->contract_title }}  </option>
                                  @endforeach
                                </select>
                                <span class="error  " id="document_id_error">
                              </div>
                        </div>
                    </div>

                    <hr>
                     <div class="row">
                        <div class="col-sm-12">
                            <div class="add-product-btn text-center ">
                                <input type="submit" value="Select & Sign" class="btn btn-info add-contract">
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
        urls = base_url+'/contracts';
        var asset_datatable =$('#laravel_datatable').DataTable({
            processing: true,
            serverSide: true,
            "ordering": false,
            destroy: true,
            ajax:urls ,

            columns: [
                { data: 'number_key', name: 'number_key' },
                { data: 'contract_provider', name: 'contract_provider' },
                { data: 'document_title', name: 'document_title' },
                { data: 'contract_title', name: 'contract_title' },
                { data: 'contract_restrict_seat', name: 'contract_restrict_seat' },
                { data: 'contract_description', name: 'contract_description'},
                { data: 'updated_at', name: 'updated_at' },
                { data: 'id', name: 'id' ,
                    render: function (data, type, column, meta) {
                        return ' ';
                    }
                }
            ]
        });


     $(document).on('click','.close_contract_modal',function(){
        $('#user_contract').modal('hide');
        $("form#add-contract-form")[0].reset();
        $('.error').html('');
    });


     $(document).on("click", ".add-contract", function(e) {
        e.preventDefault();
        var data = jQuery(this).parents('form:first').serialize();
        showPageSpinner();
        $.ajax({
            url: base_url + '/add_contract',
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
                    $('#user_contract').modal('hide');
                    $("#add-contract-form").trigger('reset');
                    var redrawtable = jQuery('#laravel_datatable').dataTable();
                    redrawtable.fnDraw();
                    hidePageSpinner();
                    swal("Success!", response.message, "success");
                }
            },
        });
    });


 });



</script>
@endpush
