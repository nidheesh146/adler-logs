@extends('layouts.default')
@section('content')

@inject('stock', 'App\Http\Controllers\Web\PurchaseDetails\StockController')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="">Stock Issue To Production - Direct</a></span>
		</div>
		<h4 class="az-content-title" style="font-size: 20px;">Stock Issue To Production - Direct
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
        <div class="card-header bg-gray-400 bd-b-0-f pd-b-0">
            <nav class="nav nav-tabs">
                <a class="nav-link active" href="">Stock Issue To Production -Direct</a>
                <a class="nav-link"  href="{{url('inventory/Stock/ToProduction/Indirect')}}">Stock Issue To Production -Indirect</a>
            </nav> 
        </div><br/>
		<form method="post" action="">
            {{ csrf_field() }}
            <div class="row">
                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label for="exampleInputEmail1">Item Code*</label>
                    <select class="form-control  item_code" name="item_code" id="item_code">
                    </select> 
                </div>
                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label>Item Description </label>
                    <textarea type="text" class="form-control" name="item_description" id="item_description" placeholder="Item Description" readonly></textarea>
                </div><!-- form-group -->
                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label for="exampleInputEmail1">Work Centre*</label>
                    <select class="form-control  work_centre" name="work_centre" id="work_centre" required>
                        <option></option>
                        @foreach($work_centre as $centre)
                        <option value="{{ $centre['id'] }}">{{$centre['centre_code']}}</option>
                        @endforeach
                    </select> 
                </div>
                <br/>
            </div>
            <div class="form-devider"></div>
            <div class="hiddendiv" style="">
                <div class ="lotcards">
                </div>
                <div class ="batchcards">
                </div>
            </div>
            <div class="form-devider"></div>
            <div class="row savebtn" style="display:none;">
           
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <button type="submit" class="btn btn-primary btn-rounded submitbtn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
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
    <div id="requestModal" class="modal">
        <div class="modal-dialog modal-lg" role="document">
            <form id="status-change-form" method="post" action="{{ url('inventory/stock/quantity-updation-request')}}">
                {{ csrf_field() }} 
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">#Quantity Update Request(<span class="batch_number"></span>)</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div style='color:#3366ff;font: size 15px;'><i class='fas fa-address-card' style='font-size:21px;'></i>&nbsp;<strong>LotCard Info</strong></div>
                                <table class="table table-bordered mg-b-0">
                                    <tr>
                                        <th>Item</th>
                                        <td id="itemcode"></td>
                                        <th >Item Description</th>
                                        <td id="description"></td>
                                    </tr>
                                    <tr>
                                        <th>Lot Number</th>
                                        <td id="lotno"></td>
                                        <th>Lot Quantity</th>
                                        <td id="lotqty"></td>
                                    </tr>
                                </table>
                                <div style='color:#3366ff;font: size 15px;'><i class='typcn typcn-tabs-outline' style='font-size:21px;'></i>&nbsp;<strong>BatchCard Info</strong></div>
                                <table class="table table-bordered mg-b-0">
                                    <tr>
                                        <th>BatchCard Number</th>
                                        <td id="batchno"></td>
                                        <th >SKU Code</th>
                                        <td id="skucode"></td>
                                    </tr>
                                    <tr>
                                        <th>SKU Quantity<br/>(Actual Sku Qty:<span id="skuqty"></span>)</th>
                                        <td><input type="text" name="request_sku_qty" id="request_sku_qty" value="" style="width: 60px;" readonly></td>
                                        <th>Upadate Quantity <br/>(Actual Qty:<span id="batchqty"></span>)</th>
                                        <td>
                                            <input type="hidden" name="item_id" id="item_id" value="">
                                            <input type="hidden" name="batch_id" id="batchid" value="">
                                            <input type="hidden" name="batchcard_material_id" id="batchcard_material_id" value="">
                                            <input type="text" name="request_qty" id="request_qty" readonly><span id="unit"></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                        <button type="submit" class="btn btn-primary" id="save"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
								role="status" aria-hidden="true"></span> <i class="fas fa-save"></i> Send Request</button>
                    </div>
                </div>
            </form>
        </div>
    </div><!-- modal-dialog -->
	
      

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
    $('.work_centre').select2({
        placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
          allowClear: true
    });
    $('.item_code').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
          allowClear: true,
          ajax: {
          url: "{{ url('inventory/itemcodesearch') }}",
          processResults: function (data) {
            return { results: data };

          }
        }
    }).on('change', function (e) 
    {
         $("#item_description").text('');
        let res = $(this).select2('data')[0];
        if(typeof(res) != "undefined" )
        {
            if(res.discription){
                $("#item_description").text(res.discription);
            }
            $.get("{{ url('inventory/stock/fetchBatchCards') }}?item_id="+res.id,function(data)
            {
                //alert('kk');
                $('.batchcards').html(data['batchcards']);
                $('.lotcards').html(data['lotcards']);
                if(data['batchcards'] && data['lotcards'])
                {
                $('.spinner-button').show();
                $('.savebtn').show();
                }
            });
        }

    });  
   

    $('.submitbtn').on('click', function (e) {
        var batch_tot = 0;
        var lot_qty = parseInt($('.lot-radio:checked').attr('lotqty'));
        //alert(lot_qty);
        $(".batchcard-checkbox:checked").each(function() {
            batch_tot= batch_tot+parseInt($(this).attr('batchqty'));
        });
        //alert(batch_tot);
       if(batch_tot!=lot_qty)
       {
            e.preventDefault();
            alert('Selected batch item quantity is not match with selected Lotcard.You Need to send  batchcard item quantity update request. For this click on "Quality Upadte Request" button.');
            $(".batchcard-checkbox:checked").each(function() {
                $(this).closest('th[class="qty"]').find('button').show();
            });
            $('.request-btn').show();
       }
       else
       {
            form.submit();
       }
    });
    $(document).ready(function() {
        $('body').on('click', '#request-btn', function (event) {
            var batchid = $(this).attr('batchid');
            var batchno = $(this).attr('batchno');
            var batchqty = $(this).attr('batchqty');
            var skucode = $(this).attr('skucode');
            var skuqty = $(this).attr('skuqty');
            var unit = $(this).attr('unit');
            var item_code = $('#item_code').text();
            var item_id = $('#item_code').val();
            var item_description = $('#item_description').text();
            var lot_qty = $('.lot-radio:checked').attr('lotqty');
            var lot_no = $('.lot-radio:checked').attr('lotno');
            var batchmaterialId = $(this).attr('batchmaterialId');
            $('#batchno').html(batchno);
            $('#skucode').html(skucode);
            $('#skuqty').html(skuqty);
            $('#batchid').val(batchid);
            $('#batchqty').text(batchqty+' '+unit);
            $('#unit').text(unit);
            $('.batch_number').text(batchno);
            //$('#request_sku_qty').val(skuqty);
            $('#description').text(item_description);
            $('#itemcode').text(item_code);
            $('#lotqty').text(lot_qty+' '+unit);
            $('#lotno').text(lot_no);
            $('#item_id').val(item_id);
            $('#batchcard_material_id').val(batchmaterialId);
            var material_qty_per_sku = parseInt(batchqty)/parseInt(skuqty);
            var request_sku_qty = parseInt(lot_qty)/material_qty_per_sku;
            //alert(Math.floor(request_sku_qty));
            $('#request_sku_qty').val(Math.floor(request_sku_qty));
            $('#request_qty').val(lot_qty);
        });
    });
     
          

</script>
@stop