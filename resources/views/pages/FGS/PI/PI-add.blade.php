@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
            <span><a href=""> Proforma Invoice(PI)</a></span>
            <span> Proforma Invoice(PI)</span>
		</div>
		<h4 class="az-content-title" style="font-size: 20px;"> Proforma Invoice(PI)
		  <div class="right-button">  
		  <div> 
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
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ $errorr }}
        </div>
        @endforeach  
		
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
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                            <label for="exampleInputEmail1">Customer  *</label>
                                            <select  class="form-control customer" name="customer" class="form-control">
                                                <!-- @if(request()->get('supplier'))
                                                <option value="{{request()->get('supplier')}}" selected>{{$edit['mac']->invoice_number}}</option>
                                                @endif -->
                                            </select>
                                        </div> 
                                        <!-- <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                            <label for="exampleInputEmail1">Customer Biiling Address</label>
                                            <textarea name="billing_address" class="form-control" id="billing_address" readonly></textarea>
                                        </div> 
                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                            <label for="exampleInputEmail1">Customer Shipping Address</label>
                                            <textarea name="shipping_address"  class="form-control" id="shipping_address" readonly></textarea>
                                        </div>  -->
                                        <!-- <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                            <label>PI Date *</label>
                                            <input type="date" value="{{date('Y-m-d')}}" class="form-control pi_date" id="pi_date" name="pi_date"  placeholder="">
                                        </div> -->
                                        <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
                                                <label style="width: 100%;">&nbsp;</label>
                                                <button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                @if(count(request()->all('')) > 0)
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
        @isset($grs_items)
        <form autocomplete="off"  id="form1" method="POST">
        {{ csrf_field() }}
		<div class="tab-pane active show " id="purchase">
            <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                        <i class="fas fa-address-card"></i>  Proforma Invoice(PI) 
                    </label>
                    <div class="form-devider"></div>
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <label for="exampleInputEmail1">Customer  *</label>
                                <input type="text" value="" class="form-control" id="customer_name"  readonly placeholder="{{$customer['firm_name']}}">
                                <input type="hidden" class="form-control" name="customer_id"   value="{{$customer['id']}}">
                            </div> 
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <label for="exampleInputEmail1">Customer Biiling Address</label>
                                <textarea name="billing_address" class="form-control" id="billing_address" readonly>{{$customer['billing_address']}}</textarea>
                            </div> 
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <label for="exampleInputEmail1">Customer Shipping Address</label>
                                <textarea name="shipping_address"  class="form-control" id="shipping_address" >{{$customer['shipping_address']}}</textarea>
                            </div> 
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <label>PI Date *</label>
                                <input type="date" value="{{date('Y-m-d')}}" class="form-control pi_date" id="pi_date" name="pi_date"  placeholder="">
                            </div>
                        </div>
                </div>
            </div>
			<div class="table-responsive">
				<table class="table table-bordered mg-b-0" id="example1">
					<thead>
						<tr>
                            <th></th>
							<th style="width:120px;">GRS Number :</th>
							<th>SKU Code</th>
							<th>HSN Code</th>
                            <th>Customer</th>
                            <th>GRS Date</th>
                            <th>Qty</th>
                            <th>Action</th>
						</tr>
					</thead>
					<tbody>
    					@foreach($grs_items as $item)
                        <tr>
                            <td><input type="checkbox" name="grs_item_id[]" id="grs_item_id" supplier="{{$item['firm_name']}}" value="{{$item['id']}}"></td>
                            <td>{{$item['grs_number']}}</td>
                            <td><a href="#" style="color:#3b4863;" data-toggle="tooltip" data-placement="top" title="{{$item['discription']}}" >{{$item['sku_code']}}</td>
                            <td>{{$item['hsn_code']}}</td>
                            <td>{{$item['firm_name']}}</td>
                            <td>{{date('d-m-Y', strtotime($item['grs_date']))}}</td>
                            <td>@if($item['current_invoice_qty']!=0)
                                {{$item['current_invoice_qty']}} Nos
                                @elseif($item['remaining_qty_after_cancel']==$item['qty_to_invoice'])
                                {{$item['remaining_qty_after_cancel']}} Nos
                                @else
                                {{$item['qty_to_invoice']}} Nos
                                @endif
                            </td>
                            <td><a href="#" data-toggle="modal"  data-target="#PIpendingModal" class="invoice-pending-model badge badge-info"   id="invoice-pending-model" grsitem="{{$item['id']}}" skucode="{{$item['sku_code']}}"  orderqty="{{$item['remaining_qty_after_cancel']}}" description="{{$item['discription']}}"   grsid="{{$item['grs_number']}}" style="font-size: 13px;" balanceQty="@if($item['current_invoice_qty']!=0)
                                {{$item['current_invoice_qty']}} 
                                @elseif($item['remaining_qty_after_cancel']==$item['qty_to_invoice'])
                                {{$item['remaining_qty_after_cancel']}}
                                @else
                                {{$item['qty_to_invoice']}} 
                                @endif"><i class="fas fa-plus"></i> Partial Invoice</a>
                            </td>
                        </tr>
                        @endforeach
					</tbody>
				</table>
				<div class="box-footer clearfix">
                
				</div>
                <div class="form-devider"></div>
                @if(count($grs_items)>0)
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
    <div id="PIpendingModal" class="modal">
        <div class="modal-dialog modal-xs" role="document">
            <form id="excess-order-form" method="post" action="{{url('fgs/pi/partial-invoice')}}" autocomplete="off">
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
                                        <th width="40%">SKU Code</th>
                                        <th id="itemCode"></th>
                                    </tr>
                                    <tr>
                                        <th> Description</th>
                                        <th id="description"></th>
                                    </tr>
                                    <tr>
                                        <th>GRS Number</th>
                                        <th id="poId"></th>
                                    </tr>
                                    <tr>
                                        <th>Quantity</th>
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
                                            <input type="hidden" name="grs_item_id" class="grs_item_id" value="">
                                            <div class="input-group mb-3">
                                                <input type="text" class="partial_invoice_qty" id="partial_invoice_qty" name="partial_invoice_qty"  aria-describedby="unit" >
                                                <div class="input-group-append">
                                                    <span class="input-group-text unit-div" id="unit">Nos</span>
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
  $(".customer").select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 4,
        allowClear: true,
        ajax: {
            url: "{{ url('fgs/customersearch') }}",
            processResults: function (data) {
                return { results: data };
            }
        }
    });
    $(document).ready(function() {
        $('body').on('click', '#invoice-pending-model', function (event) {
            event.preventDefault();
            var skucode = $(this).attr('skucode');
            console.log('skucode');
            var description = $(this).attr('description');
           // var unit = $(this).attr('unit');
            var Orderqty = $(this).attr('orderqty');
            var grsId = $(this).attr('grsid');
            var grsitem = $(this).attr('grsitem');
            var balanceQty = $(this).attr('balanceQty');
            //alert(poItem)
            $('.grs_item_id').val(grsitem);
            $('#itemCode').text('itemCode');
            $('#description').html(description);
            $('#unit').html(unit);
            $('#poId').html(grsId);
            $('#orderQuantity').html(Orderqty+'Nos');
            $('#balanceQuantity').html(balanceQty+'Nos');
            $('#balanceQuantityhidden').val(balanceQty);
        });
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
</script>


@stop