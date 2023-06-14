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
        @foreach ($errors->all() as $errorr)
                <div class="alert alert-danger "  role="alert" style="width: 100%;">
                   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  {{ $errorr }}
                </div>
               @endforeach 
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
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>Quantity To Production</label>
                    <div class="input-group mb-6">
                        <input type="text" class="form-control" name="qty_to_production" id="qty_to_production" placeholder="Quantity To Production" aria-describedby="unit-div2">
                        <div class="input-group-append">
                            <span class="input-group-text unit-div" id="unit-div">Unit</span>
                        </div>
                    </div>
                    <label id="qty_to_production-error" class="error" for="qty_to_production" style="display:none;">This field is required.</label>
                </div>
                <div class="form-group col-sm-12col-md-4 col-lg-4 col-xl-4">
                    <label>Transaction Slip No</label>
                    <input type="text"  class="form-control" name="transaction_slip" id="transaction_slip" >
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
                                            <input type="text" name="request_qty" id="request_qty"  materialQtyPerSku="0"><span id="unit"></span>
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
            if(res.unit_name){
                $("#unit-div").text(res.unit_name);
            }
            $('.batchcards').empty();
            $('.lotcards').empty();
            $('.spinner-button').hide();
            $('.savebtn').hide();
            $.get("{{ url('inventory/stock/fetchBatchCards') }}?item_id="+res.id,function(data)
            {
                //alert('kk');
                if(data!=0)
                {
                    if(data['batchcards']){
                        $('.batchcards').html(data['batchcards']);
                    }
                    if(data['lotcards']){
                        $('.lotcards').html(data['lotcards']);
                    }

                        if(data['batchcards'] && data['lotcards'])
                        {
                            $('.spinner-button').show();
                            $('.savebtn').show();
                        }
                }
                else
                {
                    alert('There is no batchcard and lotcard exist for particular rawmaterial..')
                }
               
            });
        }

    });  
   

    $('.submitbtn').on('click', function (e) {
        var batch_tot = 0;
        var lot_qty = parseFloat($('.lot-radio:checked').attr('lotqty'));
        if(lot_qty=='NaN')
        alert('Please Check One of the lotcard...')
        $(".batchcard-checkbox:checked").each(function() {
            checkbox = $(this);
            batch_tot= batch_tot+parseFloat(checkbox.closest('tr').find('.qty_to_production').val());
        });
        //alert(batch_tot);
       if(batch_tot>lot_qty)
       {
            e.preventDefault();
            alert('Selected batch item quantity is not match with selected Lotcard.You Need change the batchcard quantity.');
       }
       else
       {
            form.submit();
       }
    });
    function enableTextBox(cash) {
        const checkbox = $(cash);
        // var unit = $("#unit-div").text();
        // var qty_prdtn = $('#qty_to_production').val();
        if(checkbox.is(':checked')){
            checkbox.closest('tr').find('.qty_to_production').attr("disabled", false);
            checkbox.closest('tr').find('.qty_to_production').attr("required", "true");
        }else{
            checkbox.closest('tr').find('.qty_to_production').val('');
            checkbox.closest('tr').find('.qty_to_production').attr("required", "false");
            checkbox.closest('tr').find('.qty_to_production').attr("disabled", true);
        }
    }
        
     
          

</script>
@stop