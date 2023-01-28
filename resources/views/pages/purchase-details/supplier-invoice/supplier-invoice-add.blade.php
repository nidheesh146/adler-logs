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
						</tr>
					</thead>
					<tbody>
    					@foreach($data as $item)
                        <tr>
                            <td><input type="checkbox" name="po_item_id[]" id="po_item_id" value="{{$item['po_item']}}"></td>
                            <td>{{$item['po_number']}}</td>
                            <td><a href="#" style="color:#3b4863;" data-toggle="tooltip" data-placement="top" title="{{$item['short_description']}}" >{{$item['item_code']}}</td>
                            <td>{{$item['type']}}</td>
                            <td>{{$item['order_qty']}}</td>
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
                            <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
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
        $('body').on('click', '#invoice-add-model', function (event) {
            event.preventDefault();
            $('.binding').empty();
            $('#invoice_number').val("");
            var po_id = $(this).attr('poId');
            $('#po_id').val(po_id);
            $.ajax ({
                    type: 'GET',
                    url: "{{url('inventory/getPurchaseOrderItem')}}",
                    data: { po_id: '' + po_id + '' },
                    success : function(data) {
                        $('.binding').append(data);
                    }
                });

        });
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