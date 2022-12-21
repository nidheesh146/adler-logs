@extends('layouts.default')
@section('content')

@inject('stock', 'App\Http\Controllers\Web\PurchaseDetails\StockController')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="">Stock Issue To Production</a></span>
		</div>
		<h4 class="az-content-title" style="font-size: 20px;">Stock Issue To Production
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
        <div class="row row-sm mg-b-20 mg-lg-b-0">
            <div class="table-responsive" style="margin-bottom: 13px;">
                
            </div>
        </div>
		<form method="post" action="{{url('inventory/stock/issueToProduction')}}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label for="exampleInputEmail1">Batch Card*</label>
                        <select class="form-control  batch_card" name="batch_card">
                            </select> 
                    </div>
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label>SKU Code </label>
                        <input type="text" class="form-control" name="sku_code" id="sku_code" placeholder="SKU Code" readonly>
                    </div><!-- form-group -->
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label>SKU Quantity </label>
                        <input type="text" class="form-control" name="sku_qty" id="sku_qty" placeholder="SKU Quantity" readonly>
                    </div><!-- form-group -->
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label>Item Code </label>
                        <input type="text" class="form-control" name="item_code" id="item_code" placeholder="Item Code" readonly>
                        <input type="hidden"  name="raw_material_id" id="raw_material_id" value="">
                    </div><!-- form-group -->
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label>Item Description </label>
                        <textarea type="text" class="form-control" name="item_description" id="item_description" placeholder="Item Description" readonly></textarea>
                    </div><!-- form-group -->
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label for="exampleInputEmail1">Item Quantity Required</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="qty_required" id="qty_required" readonly placeholder="Quantity Required" aria-label="Recipient's username" aria-describedby="unit-div">
                            <div class="input-group-append">
                                <span class="input-group-text unit-div" id="unit-div">Unit</span>
                            </div>
                        </div>
                    </div>
                   
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label for="exampleInputEmail1">Lot/Batchcard Selected*</label>
                        <input type="text" class="form-control" name="lot_selected" id="lot_selected" value="" placeholder="Lot Selected">
                        <input type="hidden" class="form-control" name="lot_number" id="lot_number" value="" >
                        <input type="hidden" class="form-control" name="primary_batch_id" id="primary_batch_id" value="" >
                    </div>
                    <div class="form-groupcol-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label for="exampleInputEmail1">Quantity*</label>
                        <input type="text" class="form-control" name="quantity" id="quantity" value="" placeholder="Quantity">
                    </div>
                    <br/>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="data-bindings">
                        <!-- <label>Lotcard</label>
                        <table  class="table table-bordered mg-b-0" id="example1">
                            <tr>
                                <th>#</th>
                                <th>Lot Number</th>
                                <th>Item</th>
                                <th>Qty</th>
                            </tr>
                            <tbody class="data-bindings1">
                            
                            <tbody>
                        </table> -->
                        </div>
                    </div>
                </div>
            </div>
           <div class="form-devider"></div>
           
                <div class="row">
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
    $(".datepicker").datepicker({
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        // startDate: date,
        autoclose:true
    });
    $('.batch_card').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
          allowClear: true,
          ajax: {
          url: "{{ url('inventory/stock/find-batchcard') }}",
          processResults: function (data) {

            return { results: data };

          }
        }
      }).on('change', function (e) {
        $('.spinner-button').show();
        let res = $(this).select2('data')[0];
        if(res){
          $.get("{{ url('inventory/stock/fetchBatchCard-info') }}?batchcard_id="+res.id,function(data)
          {
            $('#item_code').val(data['batchcard']['item_code']);
            $('#item_description').val(data['batchcard']['discription']);
            $('#qty_required').val(data['batchcard']['input_material_qty']);
            $('#unit-div').text(data['batchcard']['unit_name']);
            $('#sku_code').val(data['batchcard']['sku_code']);
            $('#sku_qty').val(data['batchcard']['quantity']);
            $('#raw_material_id').val(data['batchcard']['rawmaterial_id']);
            $('.data-bindings').html(data['lot']);
            $('.data-bindings').html(data['batch']);
            // $('.spinner-button').hide();
          });
        }else{
          $('.data-bindings').html('');
          $('.spinner-button').hide();
        }
      });
      $('.data-bindings').on('change', 'input[name=lot_id]', function(){
            var value = $(this).val();
            var lot_number = $(this).attr('lot');
            var qty = $(this).attr('qty');
            $('#lot_selected').val(lot_number);
            $('#lot_number').val(value);
            $('#quantity').val(qty);
        });
        $('.data-bindings').on('change', 'input[id=batch_radio]', function(){
            var batch_id = $(this).val();
            var batch_no = $(this).data('batchno');
            var qty = $(this).data('qty');
            $('#quantity').val(qty);
            if(batch_id)
            {
                $.get("{{ url('inventory/stock/fetchBatchCard-info') }}?batchcard_id="+batch_id,function(data)
                {
                    $('#item_code').val(data['batchcard']['item_code']);
                    $('#item_description').val(data['batchcard']['discription']);
                    $('#qty_required').val(data['batchcard']['input_material_qty']);
                    $('#unit-div').text(data['batchcard']['unit_name']);
                    $('#lot_selected').val(data['batchcard']['batch_no']);
                    $('#primary_batch_id').val(data['batchcard']['id']);
                    //$('#quantity').val(qty);
                });
            }
            else
            {
                $('.data-bindings').html('');
                $('.spinner-button').hide();
            }
        });

</script>
@stop