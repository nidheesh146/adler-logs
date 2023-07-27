@extends('layouts.default')
@section('content')

@inject('stock', 'App\Http\Controllers\Web\PurchaseDetails\StockController')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="" style="color: #97a3b9;">Transaction Slip</a></span>
		</div><br/>
		<h4 class="az-content-title" style="font-size: 20px;">Transaction Slip
		  	<div class="right-button">
			  <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
				  <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
			  <div class="dropdown-menu">
			  <a href="#" class="dropdown-item">Excel</a>

			  </div> -->
				
	  		</div>
		</h4>
		
        @if (Session::get('success'))
		<div class="alert alert-success " style="width: 100%;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<i class="icon fa fa-check"></i> {{ Session::get('success') }}
		</div>
		@endif
        @if (Session::get('error'))
		<div class="alert alert-danger " style="width: 100%;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<i class="icon fa fa-check"></i> {{ Session::get('error') }}
		</div>
		@endif
        @foreach ($errors->all() as $errorr)
                <div class="alert alert-danger "  role="alert" style="width: 100%;">
                   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  {{ $errorr }}
                </div>
               @endforeach 
        <hr/>
        <div class="tab-content">
            <div id="tabCont1" class="tab-pane active show"">
                <form method="post" id="commentForm" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">Lot Number*</label>
                            <select class="form-control  lot_number" name="lot_id">
                                
                            </select> 
                            <label id="item_code-error" class="error" for="item_code" style="display:none;">This field is required.</label>
                        </div>
                        <div class="form-group col-sm-12col-md-6 col-lg-6 col-xl-6">
                            <label>Item Code</label>
                            <input type="text"  class="form-control" name="itemcode" id="itemcode" readonly >
                        </div>
                        <div class="form-group col-sm-12col-md-6 col-lg-6 col-xl-6">
                            <label>Description</label>
                            <textarea type="text" readonly class="form-control" id="item_description" name="Description"                            placeholder="Description"></textarea>
                        </div>
                        <div class="form-group col-sm-12col-md-6 col-lg-6 col-xl-6">
                            <label>Available Stock Quantity</label>
                            <div class="input-group mb-3">
                                <input type="hidden" name="stock_id" id="stock_id">
                                <input type="text" class="form-control" name="available_stock_qty" id="available_stock_qty"  aria-describedby="unit-div1" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text unit-div" id="unit-div1">Unit</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-12col-md-6 col-lg-6 col-xl-6">
                            <label>Quantity taken</label>
                            <div class="input-group mb-6">
                                <input type="text" class="form-control" name="quantity" id="quantity" placeholder="Quantity" aria-describedby="unit-div2">
                                <div class="input-group-append">
                                    <span class="input-group-text unit-div" id="unit-div2">Unit</span>
                                </div>
                            </div>
                            <label id="qty_to_production-error" class="error" for="qty_to_production" style="display:none;">This field is required.</label>
                        </div>
                        <div class="form-group col-sm-12col-md-6 col-lg-6 col-xl-6">
                            <label>Transaction Slip No</label>
                            <input type="text"  class="form-control" name="transaction_slip" id="transaction_slip" >
                        </div>
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">Created By *</label>
                            <select class="form-control  created_by" name="created_by" id="created_by">
                                <option>..select one..</option>
                                @foreach($data['users'] as $users)
                                <option value="{{ $users['user_id'] }}">{{$users['f_name']}} {{$users['l_name']}}</option>
                                @endforeach
                            </select> 
                        </div>
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">Accepted By *</label>
                            <select class="form-control  accepted_by" name="accepted_by" id="accepted_by">
                            <option>..select one..</option>
                                @foreach($data['users'] as $users)
                                <option value="{{ $users['user_id'] }}">{{$users['f_name']}} {{$users['l_name']}}</option>
                                @endforeach
                            </select> 
                        </div>
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">Work Centre*</label>
                            <select class="form-control  work_centre" name="work_centre" id="work_centre" required>
                                <option></option>
                                @foreach($data['work_centre'] as $centre)
                                <option value="{{ $centre['id'] }}">{{$centre['centre_code']}}</option>
                                @endforeach
                            </select> 
                        </div>
                    </div>
                    <div class="form-devider"></div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit submit-btn" id="submit-btn" class="btn btn-primary btn-rounded " style="float: right;">
                                <span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> 
                                <i class="fas fa-save"></i>
                                        Save
                            </button>
                        </div>
                    </div>
                <form>
            </div>
		</div>
	</div>
</div>
	<!-- az-content-body -->
	<!-- Modal content-->

	
      

<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script>
    $('.lot_number1').select2({
        placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
          allowClear: true
    });
    $('.work_centre').select2({
        placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
          allowClear: true
    });
    $('.accepted_by').select2({
        placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
          allowClear: true
    });
    $('.created_by').select2({
        placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
          allowClear: true
    });
    $(".datepicker").datepicker({
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        // startDate: date,
        autoclose:true
    });
    $('.lot_number').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
          allowClear: true,
          ajax: {
          url: "{{ url('inventory/lotnumbersearch') }}",
          processResults: function (data) {
            return { results: data };

          }
        }
    }).on('change', function (e) 
    {
         $("#item_description").text('');
         $('#available_stock_qty').val('');
        let res = $(this).select2('data')[0];
        if(typeof(res) != "undefined" )
        {
            if(res.discription){
                $("#item_description").text(res.discription);
            }
            if(res.item_code){
                $("#itemcode").val(res.item_code);
            }
            if(res.unit_name){
                $('#unit-div1').text(res.unit_name);
                $('#unit-div2').text(res.unit_name);
            }
            if(res.stock_qty){
                $('#available_stock_qty').val(res.stock_qty);
            }
        }

    }); 
    $('#submit-btn').on('click', function(e){
        
        var available_qty = parseFloat($('#available_stock_qty').val());
        var quantity = parseFloat($('#quantity').val());
        if(quantity>available_qty)
        {
        alert('Quantity Taken do not exceed available stock quantity...');
        e.preventDefault();
        }

        //alert(available_qty);
    }) ;

    $("#commentForm").validate({
            rules: {
                lot_id: {
                    required: true,
                },
                quantity: {
                    required: true,
                    number: true,
                },
                transaction_slip:{
                    required: true, 
                },
               
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });
     
          

</script>
@stop