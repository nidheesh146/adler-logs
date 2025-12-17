@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				 <span>Cancellation Delivery Challan(CDC)</span>
				 <span><a href="">
                 CDC Item
				</a></span>
			</div>
			<h4 class="az-content-title" style="font-size: 20px;margin-bottom: 20px">
            CDC Item
            </h4>
            <div class="form-devider"></div>
           
            <form method="post" id="commentForm" novalidate="novalidate" >
                {{ csrf_field() }}	
                @if(Session::get('error'))
                <div class="alert alert-danger "  role="alert" style="width: 100%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{Session::get('error')}}
                </div>
                @endif
                @if (Session::get('success'))
                <div class="alert alert-success " style="width: 100%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                </div>
                @endif
                @foreach ($errors->all() as $errorr)
                <div class="alert alert-danger "  role="alert" style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ $errorr }}
                </div>
                @endforeach
                <div class="tab-content"> 
                    <div class="row " id="purchase"> 
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">Product</label>
                            <input type="text"  class="form-control" name="product" value="@if(!empty($oef_item)) {{$oef_item['sku_code']}} @elseif(!empty($grs_item)) {{$grs_item['sku_code']}} @endif" readonly>
                            
                            <input type="hidden" name="dc_id" value="{{$cdc_id}}" >
                        </div> 
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">Description</label>
                            <textarea readonly class="form-control">@if(!empty($oef_item)) {{$oef_item['discription']}} @else {{$grs_item['discription']}} @endif </textarea>
                        </div>
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">OEF Number</label>
                            <input type="text" class="form-control" name="oef_number" value="@if(!empty($oef_item)) {{$oef_item['oef_number']}} @else {{$grs_item['oef_number']}} @endif " readonly>
                        </div>
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">Unreserved Quantity</label>
                            <div class="input-group mb-6">
                                <input type="text" class="form-control" name="unreserved_qty" value="@if(!empty($oef_item)) {{$oef_item['quantity_to_allocate']}} @else {{$grs_item['batch_quantity']}} @endif" id="unreserved_qty" aria-describedby="unit-div2" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text unit-div" id="unit-div2">Nos</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">Batchcard</label>
                            <select class="form-control" name="batchcard" class="batchcard" id="batchcard" required>
                                
                                @if((!empty($oef_item)) && ($oef_item['batchcards']))
                                @foreach($oef_item['batchcards'] as $batchcard)
                                <option value="{{$batchcard['batch_id']}}" @if($batchcard['batch_id']==$dc_item->batchcard_id) selected="selected" @endif manufacturingDate="{{$batchcard['manufacturing_date']}}" expiryDate="{{$batchcard['expiry_date']}}" sterile="{{$batchcard['is_sterile']}}" qty="{{$batchcard['quantity']}}" mrnItemId="{{$batchcard['mrn_item_id']}}">
                                    {{$batchcard['batch_no']}}
                                </option>
                                @endforeach
                                
                                @endif
                            </select>
                        </div> 
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">Batchcard available Quantity</label>
                            <div class="input-group mb-6">
                                <input type="hidden" name="mrn_item_id" id="mrn_item_id"  value="" >
                                <input type="number" class="form-control" max="" min="" value="{{$stk->quantity}}" name="batch_qty1" id="batch_qty1" placeholder="" aria-describedby="unit-div2" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text unit-div" id="unit-div2">Nos</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">Batch Quantity Taken</label>
                            <div class="input-group mb-6">
                                <input type="number" class="form-control"  value="{{$cdc_item['quantity']}}"  max="" min="" name="batch_qty" id="batch_qty" placeholder="" aria-describedby="unit-div2" >
                                <div class="input-group-append">
                                    <span class="input-group-text unit-div" id="unit-div2">Nos</span>
                                </div>
                            </div>
                            <span id="error" style="color:red;"></span> 
                        </div>
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">Manufacturing date</label>
                            <input type="text" class="form-control datepicker manufacturing_date" name="manufacturing_date" value="{{date('d-m-Y', strtotime($cdc_item['manufacturing_date']))}}"  id="manufacturing_date" >
                        </div> 
                        @if($oef_item['is_sterile'] == 0)
    <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6"> 
        <label for="expiry_date">Expiry date</label>
        <input type="text" class="form-control datepicker expiry_date" name="expiry_date" id="expiry_date" 
               value="N.A" readonly>
    </div>
    
@else
    <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <label for="expiry_date">Expiry date</label>
        <input type="text" class="form-control datepicker expiry_date" name="expiry_date" id="expiry_date" 
               value="{{ ($cdc_item['expiry_date'] == '0000-00-00' ||$cdc_item['expiry_date'] == '1970-01-01' || $cdc_item['expiry_date'] == 'null') ? 'N.A' : date('d-m-Y', strtotime($cdc_item['expiry_date'])) }}" readonly>
    </div>
@endif



   
				    </div>
			    </div>
                <div class="form-devider"></div>
                <div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit" class="btn btn-primary btn-rounded " style="float: right;">
                                <span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true">
                                </span> 
                                <i class="fas fa-save"></i>
                                {{ request()->item ? 'Update' : 'Save' }}
                            </button>
                    </div>
                </div>
            <form>
		</div>
	</div>
	<!-- az-content-body -->
</div>

<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script> 
$(document).ready(function() {
    // Disable submit button on form submission
    $('form').submit(function() {
        $(this).find(':submit').prop('disabled', true);
    });

    // Datepicker initialization for manufacturing date
    $(function() {
        'use strict';
        $(".manufacturing_date").datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            endDate: new Date() // Prevent selecting future dates
        });
    });

    // Change event for batch card
    $('#batchcard').on('change', function() {
        var element = $("option:selected", this); 
        var batchqty = element.attr("qty"); 
        var manufacturing_date = element.attr("manufacturingDate"); 
        var mrn_item_id = element.attr('mrnItemId');
        var sterile = element.attr("sterile");

        $("#batch_qty1").val(batchqty);
        $("#batch_qty1").attr('max', batchqty);
        $("#batch_qty1").attr('qty', batchqty);
        $("#batch_qty").attr('min', 0);
        $("#batch_qty").attr(batchqty);
        $("#manufacturing_date").val(manufacturing_date);
        
        // Set expiry date based on sterility
        if (sterile == 1) {
            // If the product is sterile, set expiry date to N.A
            $("#expiry_date").val('N.A');
        } else {
            // Calculate expiry date if not sterile
            var manufacturingDate = new Date(manufacturing_date);
            var expiryDate = new Date(manufacturingDate);
            expiryDate.setFullYear(expiryDate.getFullYear() + 5);
            expiryDate.setDate(expiryDate.getDate() - 2); // Subtract 2 days
            var formattedDate = ('0' + expiryDate.getDate()).slice(-2) + '-' + ('0' + (expiryDate.getMonth() + 1)).slice(-2) + '-' + expiryDate.getFullYear();
            $("#expiry_date").val(formattedDate);
        }
        
        $("#mrn_item_id").val(mrn_item_id);
    });

    // Change event for batch quantity
    $(document).on('change', '#batch_qty', function (e) {
        var unreserved_qty = parseFloat($('#unreserved_qty').val());
        var quantity = parseFloat($('#batch_qty').val());
        var batchqty = $('#batch_qty1').attr("qty");
        $("#error").text('');
        if (quantity > batchqty) {
            $("#error").text('Quantity Taken cannot exceed Batch quantity...');
        }
    });

    // Manufacturing datepicker event
    var expiryDatepicker = $("#expiry_date");
    var initialExpiryDate = expiryDatepicker.val();
    
    $(".manufacturing_date").on("change", function() {
        if (initialExpiryDate.trim() === 'N.A') {
            $("#expiry_date").val('N.A').prop("readonly", true);
        } else {
            var manufacturingDate = $("#manufacturing_date").datepicker('getDate');
            var expiryDate = new Date(manufacturingDate);
            
            // Update expiry date logic based on sterility
            if ($('#batchcard option:selected').attr("sterile") == 1) {
                $("#expiry_date").val('N.A'); // Set to N.A if sterile
            } else {
                expiryDate.setFullYear(expiryDate.getFullYear() + 5);
                expiryDate.setDate(expiryDate.getDate() - 2); // Subtract 2 days
                
                var formattedDate = ('0' + expiryDate.getDate()).slice(-2) + '-' + ('0' + (expiryDate.getMonth() + 1)).slice(-2) + '-' + expiryDate.getFullYear();
                $("#expiry_date").val(formattedDate);
            }
        }
    });
});

</script>


@stop