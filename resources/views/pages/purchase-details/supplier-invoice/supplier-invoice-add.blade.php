@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
            <span><a href="{{ url('inventory/supplier-invoice') }}">SUPPLIER INVOICE</a></span>
            <span> SUPPLIER INVOICE</span>
		</div>
		<h4 class="az-content-title" style="font-size: 20px;">Supplier Invoice 
		  <div class="right-button">
			  
		  <div>  
		  </div> 
	  </div>
	</h4>
		<!-- <div class="az-dashboard-nav">
			<nav class="nav"> </nav>	
		</div> -->

		@if (Session::get('success'))
		<div class="alert alert-success " style="width: 100%;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<i class="icon fa fa-check"></i> {{ Session::get('success') }}
		</div>
		@endif
        @if (Session::get('error'))
		<div class="alert alert-success " style="width: 100%;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<i class="icon fa fa-check"></i> {{ Session::get('error') }}
		</div>
		@endif
        @foreach ($errors->all() as $errorr)
            <div class="alert alert-danger " role="alert" style="width: 100%;">
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-hidden="true">×</button>
                                        {{ $errorr }}
                                    </div>
                                @endforeach  
		@include('includes.purchase-details.purchase-work-order-tab')
		<div class="tab-content">
		<div class="row row-sm mg-b-20 mg-lg-b-0">
            <div class="table-responsive" style="margin-bottom: 13px;">
                <table class="table table-bordered mg-b-0">
                    <tbody>
                        <tr>
                            <style>
                                .select2-container .select2-selection--single {
                                    height: 26px;
                                    /* width: 122px; */
                                }
                                .select2-selection__rendered {
                                    font-size:12px;
                                }
                            </style>
                            <form autocomplete="off">
                                <th scope="row">
                                    <div class="row filter_search" style="margin-left: 0px;">
                                       <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row">
                                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                <label for="exampleInputEmail1" style="font-size: 12px;">Supplier</label>
                                                <input type="text" value="{{request()->get('supplier')}}" name="supplier" id="supplier" class="form-control" placeholder="SUPPLIER">
                                                
                                            </div>
                                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                <label style="font-size: 12px;">@if(request()->get('order_type')=='wo') WO @else PO @endif No:</label>
                                                <input type="text" value="{{request()->get('po_no')}}" name="po_no" id="po_no" class="form-control" placeholder="@if(request()->get('order_type')=='wo') WO NO @else PO NO @endif"> 
												<input type="hidden" value="{{request()->get('order_type')}}" id="order_type"  name="order_type">
                                            </div><!-- form-group -->        
                                                                 
                                        </div>
                                        <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
                                                <label style="width: 100%;">&nbsp;</label>
                                                <button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                @if(count(request()->all('')) > 1)
                                                    <a href="{{url()->current();}}" class="badge badge-pill badge-warning"
                                                    style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
                                                @endif
                                            </div> 
                                        </div>
                                    </div>
                                </th>
                            </form>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @isset($data)
        <form autocomplete="off"  id="form1" method="POST">
        {{ csrf_field() }}
		<div class="tab-pane active show " id="purchase">
            <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                        <i class="fas fa-address-card"></i> Add Supplier Invoice  
                    </label>
                    <div class="form-devider"></div>
                    <div class="row">
                        <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2" style="margin-top: 6px;padding:0px;text-align:right;">
                            <label>Supplier Invoice Number</label>
                        </div>
                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <input type="text"  name="invoice_number" id="invoice_number" class="form-control" placeholder="Invoice number">                            
                        </div>
                        <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2" style="margin-top: 6px;padding:0px;text-align:right;">
                            <label>Supplier Invoice Date</label>
                        </div>
                        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <input type="date"  name="invoice_date" id="invoice_date" value="{{date("d-m-Y")}}" class="form-control" placeholder="Invoice Date">                            
                        </div>
                    </div>
                </div>
            </div>
			<div class="table-responsive">
				<table class="table table-bordered mg-b-0" id="example1">
					<thead>
						<tr>
                            <th></th>
							<th style="width:120px;">@if(request()->get('order_type')=="wo") WO @else PO @endif number :</th>
							<th>Item Code</th>
							<th>Type</th>
							<th>Quantity</th>
                            <th>RATE</th>
                            <th>DISCOUNT</th>
							<th>GST</th>
                            <th>Supplier</th>
                            <th>Action</th>
						</tr>
					</thead>
					<tbody>
    					@foreach($data as $item)
                        <tr>
                            <td><input type="checkbox" name="po_item_id[]" id="po_item_id" supplier="{{$item['vendor']}}" value="{{$item['po_item']}}"></td>
                            <td>{{$item['po_number']}}</td>
                            <td><a href="#" style="color:#3b4863;" data-toggle="tooltip" data-placement="top" title="{{$item['short_description']}}" >{{$item['item_code']}}</td>
                            <td>{{$item['type']}}</td>
                            <td>@if($item['current_invoice_qty']!=0)
                                {{$item['current_invoice_qty']}} {{$item['unit_name']}}
                                @elseif($item['order_qty']==$item['qty_to_invoice'])
                                {{$item['order_qty']}} {{$item['unit_name']}}
                                @else
                                {{$item['qty_to_invoice']}} {{$item['unit_name']}}
                                @endif
                            </td>
                            <td>{{$item['rate']}}</td>
                            <td>{{$item['discount']}}</td>
                            <td>@if($item['igst']!=0)
                                    IGST:{{$item['igst']}}%<br/>
                                    @endif
                                @if($item['cgst']!=0)
                                    CGST:{{$item['cgst']}}%<br/>
                                    @endif
                                @if($item['sgst']!=0)
                                    SGST:{{$item['sgst']}}%
                                 @endif
                            </td>
                            <td>{{$item['vendor']}}</td>
                            <td><a href="" data-toggle="modal"  data-target="#invoicependingModal" class="invoice-pending-model badge badge-info"   id="invoice-add-model" poItem="{{$item['po_item']}}" itemCode="{{$item['item_code']}}" unit="{{$item['unit_name']}}" Orderqty="{{$item['order_qty']}}" description="{{$item['short_description']}}"   poId="{{$item['po_number']}}" style="font-size: 13px;" balanceQty="@if($item['current_invoice_qty']!=0)
                                {{$item['current_invoice_qty']}} 
                                @elseif($item['order_qty']==$item['qty_to_invoice'])
                                {{$item['order_qty']}}
                                @else
                                {{$item['qty_to_invoice']}} 
                                @endif"><i class="fas fa-plus"></i> Partial Invoice</a></td>
                        </tr>
                        @endforeach
					</tbody>
				</table>
				<div class="box-footer clearfix">
                
				</div>
                <div class="form-devider"></div>
                @if(count($data)>0)
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <button type="submit" class="btn btn-primary btn-rounded invoice-create-btn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                                Save 
                            </button>
                        </div>
                    </div>
                @endif
			</div>
		</div>
        </form>
		@endif
	</div>
</div>
	<!-- az-content-body -->
    <div id="invoicependingModal" class="modal">
        <div class="modal-dialog modal-xs" role="document">
            <form id="excess-order-form" method="post" action="{{url('inventory/partial-supplier-invoice')}}" autocomplete="off">
                {{ csrf_field() }} 
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">#Partial Invoice</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <label id="partial_invoice_qty-error" class="error" for="partial_invoice_qty" style="display:none;">  Entered value must be less than Balance Order Quantity .</label>
                            <table class="table table-bordered mg-b-0">
                                <thead>
                                    <tr>
                                        <th width="40%">Item Code</th>
                                        <th id="itemCode"></th>
                                    </tr>
                                    <tr>
                                        <th>Item Description</th>
                                        <th id="description"></th>
                                    </tr>
                                    <tr>
                                        <th>PO Number</th>
                                        <th id="poId"></th>
                                    </tr>
                                    <tr>
                                        <th>Order Quantity</th>
                                        <th id="orderQuantity"></th>
                                    </tr>
                                    <tr>
                                        <th>Balance Order Quantity
                                        <input type="hidden" id="balanceQuantityhidden" value="">
                                        </th>
                                        <th id="balanceQuantity"></th>
                                    </tr>
                                    <tr>
                                        <th>Partial Invoice Quantity</th>
                                        <th>
                                            <input type="hidden" name="po_item_id" class="po_item_id" value="">
                                            <div class="input-group mb-3">
                                                <input type="text" class="partial_invoice_qty" id="partial_invoice_qty" name="partial_invoice_qty"  aria-describedby="unit" >
                                                <div class="input-group-append">
                                                    <span class="input-group-text unit-div" id="unit">Unit</span>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="form-devider"></div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group col-sm-2 col-md-2 col-lg-2 col-xl-2">
                            <button type="submit" class="btn btn-primary btn-rounded partial-save-btn" style="float: right;width:88px;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
  
</div>


<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>

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
  $(document).ready(function() {
        $('body').on('click', '.invoice-pending-model', function (event) {
            event.preventDefault();
            var itemCode = $(this).attr('itemCode');
            var description = $(this).attr('description');
            var unit = $(this).attr('unit');
            var Orderqty = $(this).attr('Orderqty');
            var poId = $(this).attr('poId');
            var poItem = $(this).attr('poItem');
            var balanceQty = $(this).attr('balanceQty');
            //alert(poItem)
            $('.po_item_id').val(poItem);
            $('#itemCode').html(itemCode);
            $('#description').html(description);
            $('#unit').html(unit);
            $('#poId').html(poId);
            $('#orderQuantity').html(Orderqty+' '+unit);
            $('#balanceQuantity').html(balanceQty+' '+unit);
            $('#balanceQuantityhidden').val(balanceQty);
        });
  });
  $(".invoice-create-btn").on("click",function(event)
  {
    var suppliers = $("#po_item_id:checked").map(function(){
      return $(this).attr('supplier');
    }).get();
    
    var unique_suppler_count = $.unique(suppliers).length;
    console.log(unique_suppler_count);
    if(unique_suppler_count>1)
    {
        event.preventDefault();
        alert('Please select items of same supplier');
    }
    else
    {
        form.submit();
    }
  });
  $(".partial-save-btn").on("click", function(event){
    var partial_invoice_qty_entered= $('.partial_invoice_qty').val();
    var balanceQuantityhidden = $('#balanceQuantityhidden').val();
    
    if(parseFloat(partial_invoice_qty_entered)<parseFloat(balanceQuantityhidden))
    {
        form.submit();
    }
    else
    {
        event.preventDefault();
        $('#partial_invoice_qty-error').show();
    }
  });

  $("#form1").validate({
    rules: {
        invoice_number: {
            required: true,
        },
        po_item_id: {
            required: true,
        },
        submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
        }
    }
  });

  
	$('.search-btn').on( "click", function(e)  {
		var supplier = $('#supplier').val();
		var invoice_no = $('#invoice_no').val();
		var po_no = $('#po_no').val();
		var from = $('#from').val();
		if(!supplier & !invoice_no & !po_no & !from)
		{
			e.preventDefault();
		}
	});
</script>


@stop