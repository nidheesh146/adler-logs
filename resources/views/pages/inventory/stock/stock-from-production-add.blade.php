@extends('layouts.default')
@section('content')

@inject('stock', 'App\Http\Controllers\Web\PurchaseDetails\StockController')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="">Add Stock Return From Production</a></span>
		</div>
		<h4 class="az-content-title" style="font-size: 20px;">Add Stock Return From Production
		  	<div class="right-button">
			  <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
				  <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
			  <div class="dropdown-menu">
			  <a href="#" class="dropdown-item">Excel</a>

			  </div> -->
				
	  		</div>
		</h4>
		<div class="az-dashboard-nav">
			<nav class="nav"> </nav>	
		</div>
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
        <form method="post" action="{{url('inventory/stock/return-FromProduction')}}">
            {{ csrf_field() }}
            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="exampleInputEmail1">Batch Card*</label>
                    <select class="form-control  batch_card" name="batch_card">
                    </select> 
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="exampleInputEmail1">Item Code*</label>
                    <select class="form-control  item_code" name="item_id">
                        <option>Select</option>
                    </select> 
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="exampleInputEmail1">Batchcard item quantity</label>
                    <div class="input-group mb-3">
                      <input type="text" class="form-control batchcard_item_qty" name="batchcard_item_qty" readonly  aria-describedby="unit-div1" readonly>
                      <div class="input-group-append">
                          <span class="input-group-text unit-div" id="unit-div1">Unit</span>
                      </div>
                    </div>
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="exampleInputEmail1">Lot Card</label>
                    <div class="input-group mb-3">
                      <input type="hidden" class="form-control lotcard_id" name="lotcard_id" >
                      <input type="hidden" class="form-control mac_item_id" name="mac_item_id" >
                      <input type="text" class="form-control lotcard" name="lotcard" readonly  aria-describedby="unit-div2" readonly>
                      <div class="input-group-append">
                          <span class="input-group-text unit-div" id="unit-div2">Unit</span>
                      </div>
                    </div>
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="exampleInputEmail1">Lot Card quantity(Accepted Quantity)*</label>
                    <div class="input-group mb-3">
                      <input type="text" class="form-control mac_qty" name="lotcard_qty" readonly  aria-describedby="unit-div3" readonly>
                      <div class="input-group-append">
                          <span class="input-group-text unit-div" id="unit-div3">Unit</span>
                      </div>
                    </div>
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="exampleInputEmail1">Quantity to be Return*</label>
                    <div class="input-group mb-3">
                      <input type="text" class="form-control qty_return" name="qty_return"   aria-describedby="unit-div4" >
                      <div class="input-group-append">
                          <span class="input-group-text unit-div" id="unit-div4">Unit</span>
                      </div>
                    </div>
                    
                </div>
            </div>       
           <div class="form-devider"></div>
            <div class="row save-btn" style="display:none;">
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                        Save 
                    </button>
                </div>
            </div>
        </form>
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
    //$('.item_code').select2();
    $('.batch_card').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
          allowClear: true,
          ajax: {
          url: "{{url('label/batchcardSearch')}}",
          processResults: function (data) {

            return { results: data };

          }
        }
      }).on('change', function (e) {
        $('.spinner-button').show();
        let res = $(this).select2('data')[0];
        if(res){
          $.get("{{ url('inventory/stock/fetchBatchCard-items') }}?batchcard_id="+res.id,function(response)
          {
            $.each(response,function(key, value)
            {
                $(".item_code").append('<option value=' + value.rawmaterial_id + '>' + value.item_code + '</option>');
            });
          });
        }else{
          $('.spinner-button').hide();
        }
      });
      $(".item_code").on('change', function()
      {
        var item_id = $(this).val();
        let res = $('.batch_card').select2('data')[0];
        if(res){
          $.get("{{ url('inventory/stock/fetchLotcard') }}?batchcard_id="+res.id+"&item_id="+item_id,function(response)
          {
            $('.lotcard').val(response['lot_number']);
            $('.lotcard_id').val(response['lot_id']);
            $('.mac_qty').val(response['accepted_quantity']);
            $('.batchcard_item_qty').val(response['batch_qty']);
            $('#unit-div1').html(response['unit_name']);
            $('#unit-div2').html(response['unit_name']);
            $('#unit-div3').html(response['unit_name']);
            $('#unit-div4').html(response['unit_name'])
            $('.save-btn').show();
          });
        }
      });


</script>
@stop