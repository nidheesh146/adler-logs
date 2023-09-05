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
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label for="exampleInputEmail1">PI Number </label>
                                <input type="text" value="{{$pi['pi_number']}}" class="form-control" id=""  readonly placeholder="">
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label for="exampleInputEmail1">Customer  *</label>
                                <input type="text" value="{{$pi->firm_name}}" class="form-control" id="customer_name"    readonly>
                                <input type="hidden" class="form-control" name="pi_id"   value="{{$pi['id']}}">
                            </div> 
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label for="exampleInputEmail1">Customer Biiling Address</label>
                                <textarea name="billing_address" class="form-control" id="billing_address" rows="4" readonly>{{$pi['billing_address']}}</textarea>
                            </div> 
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label for="exampleInputEmail1">Customer Shipping Address</label>
                                <textarea name="shipping_address"  class="form-control" id="shipping_address" rows="4" readonly>{{$pi['shipping_address']}}</textarea>
                            </div> 
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>PI Date *</label>
                                <input type="text" value="{{date('d-m-Y', strtotime($pi['pi_date']))}}" class="form-control pi_date" id="pi_date" name="pi_date"  placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded invoice-create-btn" style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                                    Save 
                                </button>
                            </div>
                    </div>
                </div>
            </div>
			
		</div>
        </form>
		
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
                                                <input type="number" class="partial_invoice_qty" id="partial_invoice_qty" name="partial_invoice_qty" min="1" max="" aria-describedby="unit" >
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
    $(".pi_date").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true,
    });
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
            $('#itemCode').text(skucode);
            $('#description').html(description);
            $('#unit').html(unit);
            $('#poId').html(grsId);
            $('#orderQuantity').html(Orderqty+'Nos');
            $('#balanceQuantity').html(balanceQty+'Nos');
            $('#balanceQuantityhidden').val(balanceQty);
            $('.partial_invoice_qty').attr('max',balanceQty);
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
  $(".check-all").click(function () {
     $('.check_pi').not(this).prop('checked', this.checked);
    });
</script>


@stop