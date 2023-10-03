@extends('layouts.default')
@section('content')

@inject('fn', 'App\Http\Controllers\Web\PurchaseDetails\PurchaseController')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
	
			 <span><a href="">Supplier Invoice</a></span>
			 </div>
		<h4 class="az-content-title" style="font-size: 20px;">Supplier Invoice list
		  <div class="right-button">
			  
		  <div>   
		  </div>
		<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/supplier-invoice-add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Supplier Invoice</button> 
        <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/supplier-invoice/excel-export').'?'.http_build_query(array_merge(request()->all()))}}'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button>
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
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
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
                                       <div class="col-sm-10 col-md- col-lg-10 col-xl-10 row">
                        
                                           {{-- <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                <label>@if(request()->get('order_type')=='wo') WO @else PO @endif No:</label>
                                                <input type="text" value="{{request()->get('po_no')}}" name="po_no" id="po_no" class="form-control" placeholder="@if(request()->get('order_type')=='wo') WO NO @else PO NO @endif"> 
												<input type="text" value="{{request()->get('order_type')}}" id="order_type"  name="order_type">
                                            </div><!-- form-group -->--}}
                                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                <label>Invoice No:</label>
                                                <input type="text" value="{{request()->get('invoice_no')}}" name="invoice_no" id="invoice_no" class="form-control" placeholder="INVOICE NO">
                                                <input type="hidden" value="{{request()->get('order_type')}}" id="order_type"  name="order_type">
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                <label for="exampleInputEmail1" style="font-size: 12px;">Supplier</label>
                                                <input type="text" value="{{request()->get('supplier')}}" name="supplier" id="supplier" class="form-control" placeholder="SUPPLIER">
                                                
                                            </div>
											 <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                <label  style="font-size: 12px;">Invoice at </label>
                                                <input type="text" value="{{request()->get('from')}}" id="from" class="form-control datepicker" name="from" placeholder="Created at (MM-YYYY)">
                                            </div> 
                                                                 
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

		
	   

		<div class="tab-pane active show " id="purchase">
			<div class="table-responsive">

				<table class="table table-bordered mg-b-0" id="example1">
					<thead>
						<tr>
						
							<!-- <th style="width:120px;">@if(request()->get('order_type')=="wo") WO @else PO @endif number :</th> -->
							<th>Invoice number:</th>
							<th>Invoice date</th>
                            <th>PO Number</th>
							<th>Supplier</th>
							<th>Transaction Date</th>
							<th>Created By</th>
							<th>Action</th>
						
						</tr>
					</thead>
					<tbody>
						@foreach ($data['Requisition'] as $item)
						<tr>
							<!-- <td>{{$item->po_number}}</td> -->
							<td>{{$item->invoice_number}}</td>
							<td>{{date('d-m-Y',strtotime($item->invoice_date)) }}</td>
                            <td><?php $po_numbers = $fn->getPoNumber($item->id) ?>
                                @foreach($po_numbers as $po)
                                {{ $po->po_number}},
                                @endforeach
                            </td>
							<td>{{$item->vendor_id}} - {{$item->vendor_name}}</td>
							<td>{{date('d-m-Y',strtotime($item->created_at)) }}</td>
							<td>{{$item->f_name}} {{$item->l_name}}</td>
							<td>
								<!-- <a class="badge badge-info" style="font-size: 13px;" href="{{url('inventory/supplier-invoice-add/'.$item->id)}}">-->
								<a href="" data-toggle="modal"  data-target="#invoiceAddModal" class="invoice-add-model badge badge-info"   id="invoice-add-model" po="{{$item['po_number']}}" invoiceId="{{$item['id']}}" invoiceNo="{{$item['invoice_number']}}" invoiceDate="{{date('d-m-Y',strtotime($item->invoice_date)) }}" transactionDate="{{date('d-m-Y',strtotime($item->created_at)) }}"  poId="{{$item['po_master_id']}}" style="font-size: 13px;"><i class="fas fa-edit"></i> Edit</a> 
							{{--<a class="badge badge-danger" style="font-size: 13px;" href="{{url('inventory/supplier-invoice-delete/'.$item->id)}}" onclick="return confirm('Are you sure you want to delete this ?');"><i class="fa fa-trash"></i> Delete</a>--}}
								
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<div class="box-footer clearfix">
					{{ $data['Requisition']->appends(request()->input())->links() }}
				</div>
		
			</div>
		</div>
		
	</div>
</div>
	<!-- az-content-body -->
	<div id="invoiceAddModal" class="modal">
        <div class="modal-dialog modal-xl" role="document">
            <form id="excess-order-form" method="post" action="{{url('inventory/supplier-invoice-edit')}}" autocomplete="off">
                {{ csrf_field() }} 
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">#Edit Supplier Invoice <span class="invoice_number"></span></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                <label>
                                   Invoice Number
                                </label>
                                <input type="text" name="invoice_number" id="invoice_number"  placeholder="Invoice Number" readonly>
                                <input type="hidden" name="invoice_id" id="invoice_id" value="">
                            </div>
							<div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                <label>
                                   Invoice Date
                                </label>
                                <input type="text" name="invoice_date" id="invoice_date" value="{{date("d-m-Y")}}" class="datepicker1">
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                <label>
                                   Transaction Date
                                </label>
                                <input type="text" name="transaction_date" id="transaction_date" value=""  class="datepicker1">
                            </div>
                            {{--<div class="form-group col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                  <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                        Update
                                  </button>
                              </div>--}}
                        </div>
                        <div class="form-devider"></div>
                        <div class="row binding">
                        </div>
                        
                        
                    </div>
                    <div class="modal-footer">
                    <div class="form-group col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                  <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                        Update
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
	$(".datepicker1").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true
    });

  });
  $(document).ready(function() {
        $('body').on('click', '#invoice-add-model', function (event) {
            event.preventDefault();
            $('.binding').empty();
            var po_id = $(this).attr('poId');
			var invoice_id = $(this).attr('invoiceId');
            $('#invoice_id').val(invoice_id);
            var invoice_no = $(this).attr('invoiceNo');
            $('#invoice_number').val(invoice_no);
            var invoice_date = $(this).attr('invoiceDate');
            $('#invoice_date').val(invoice_date);
            var transaction_date = $(this).attr('transactionDate');
            if( transaction_date!=0)
            $('#transaction_date').val(transaction_date);
           
            $.ajax ({
                    type: 'GET',
                    url: "{{url('inventory/getPurchaseOrderItem')}}",
                    data: { invoice_id: '' + invoice_id + '' },
                    success : function(data) {
                        $('.binding').append(data);
                    }
            });

        });
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
    //$('.order-qty').on('input')
    $(".order-qty").on("input", function() {
        alert($(this).val()); 
    });
</script>


@stop