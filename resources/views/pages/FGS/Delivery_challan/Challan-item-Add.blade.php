@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				 <span>Delivery Challan(DC)</span>
				 <span><a href="">
                 Challan Item
				</a></span>
			</div>
			<h4 class="az-content-title" style="font-size: 20px;margin-bottom: 20px">
            Challan Item
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
                            @if(!empty($oef_item))
                            <input type="hidden" name="oef_item_id" value="{{$oef_item['id']}}" >
                            @elseif(!empty($grs_item))
                            <input type="hidden" name="grs_item_id" value="{{$grs_item['id']}}" >
                            @endif
                            <input type="hidden" name="dc_id" value="{{$dc_id}}" >
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
                                <option value="">..Select One..</option>
                                @if((!empty($oef_item)) && ($oef_item['batchcards']))
                                @foreach($oef_item['batchcards'] as $batchcard)
                                <option value="{{$batchcard['batchcard_id']}}" manufacturingDate="{{$batchcard['manufacturing_date']}}" expiryDate="{{$batchcard['expiry_date']}}" qty="{{$batchcard['batchcard_available_qty']}}" mrnItemId="{{$batchcard['mrn_item_id']}}">
                                    {{$batchcard['batch_no']}}
                                </option>
                                @endforeach
                                @elseif((!empty($grs_item)) && ($grs_item['batchcards']))
                                @foreach($grs_item['batchcards'] as $batchcard)
                                <option value="{{$batchcard['batchcard_id']}}" @if($grs_item['batchcard_id']==$batchcard['batchcard_id']) selected @endif manufacturingDate="{{$batchcard['manufacturing_date']}}" expiryDate="{{$batchcard['expiry_date']}}" qty="{{$batchcard['batchcard_available_qty']}}" mrnItemId="{{$batchcard['mrn_item_id']}}">
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
                                <input type="number" class="form-control" max="" min="" name="batch_qty1" id="batch_qty1" placeholder="" aria-describedby="unit-div2" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text unit-div" id="unit-div2">Nos</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">Batch Quantity Taken</label>
                            <div class="input-group mb-6">
                                <input type="number" class="form-control" @if((!empty($grs_item)) && $grs_item['batchcard_id']) value="{{$grs_item['batch_quantity']}}"  @endif max="" min="" name="batch_qty" id="batch_qty" placeholder="" aria-describedby="unit-div2" >
                                <div class="input-group-append">
                                    <span class="input-group-text unit-div" id="unit-div2">Nos</span>
                                </div>
                            </div>
                            <span id="error" style="color:red;"></span> 
                        </div>
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">Manufacturing date</label>
                            <input type="text" class="form-control" name="manufacturing_date" @if((!empty($grs_item)) && $grs_item['batchcard_id']) value="{{date('d-m-Y', strtotime($grs_item['manufacturing_date']))}}"  @endif id="manufacturing_date" readonly>
                        </div> 
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="exampleInputEmail1">Expiry date</label>
                            <input type="text" class="form-control" name="expiry_date" id="expiry_date" @if((!empty($grs_item)) && $grs_item['batchcard_id']) value="{{date('d-m-Y', strtotime($grs_item['expiry_date']))}}"  @endif  readonly >
                        </div>    
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
    


  $(function(){
    'use strict'
	var date = new Date();
    date.setDate(date.getDate());
	$(".datepicker").datepicker({
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months",
        // startDate: date,
        autoclose:true
    });
	
  });
  	
    $('#batchcard').on('change', function ()
    {
        var element = $("option:selected", this); 
        var batchqty = element.attr("qty"); 
        var manufacturing_date = element.attr("manufacturingDate"); 
        var expiry_date = element.attr("expiryDate"); 
        var mrn_item_id = element.attr('mrnItemId');
        $("#batch_qty1").val(batchqty);
        $("#batch_qty1").attr('max',batchqty);
        $("#batch_qty1").attr('qty',batchqty);
        $("#batch_qty").attr('min',0);
        $("#batch_qty").attr(batchqty);
        $("#manufacturing_date").val(manufacturing_date);
        $("#mrn_item_id").val(mrn_item_id);
        if(expiry_date!='0000-00-00')
        $("#expiry_date").val(expiry_date);
        else
        $("#expiry_date").val('N.A');
        
           // alert(stock_qty);
    }); 
    $(document).on('change', '#batch_qty', function (e) {
        var unreserved_qty = parseFloat($('#unreserved_qty').val());
        var quantity = parseFloat($('#batch_qty').val());
        //var batchqty = parseFloat($('#batch_qty').attr());
        var batchqty = $('#batch_qty1').attr("qty");
        $("#error").text('');
        if(quantity>unreserved_qty)
        {
            $("#error").text('Quantity Taken do not exceed unreserved quantity...');
            // alert('Quantity Taken do not exceed unreserved quantity...');
                //e.preventDefault();
        }
        if(quantity>batchqty)
        {
            $("#error").text('Quantity Taken do not exceed Batch quantity...');
            //alert('');
            //e.preventDefault();
        }
        

        //alert(available_qty);
    }) ;
</script>
<script>
    $(document).ready(function() {
        $('form').submit(function() {
            $(this).find(':submit').prop('disabled', true);
        });
    });
</script>

@stop