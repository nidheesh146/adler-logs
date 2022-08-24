@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
			 <span><a href="">Lot Number Allocation</a></span>
		</div>
		<h4 class="az-content-title" style="font-size: 20px;">Lot Number Allocation list
		  	<div class="right-button">
			  <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
				  <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
			  <div class="dropdown-menu">
			  <a href="#" class="dropdown-item">Excel</a>

			  </div> -->
				<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/lot-allocation-add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> LOT Allocation</button> 
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
        <div class="row row-sm mg-b-20 mg-lg-b-0">
            <div class="table-responsive" style="margin-bottom: 13px;">
                <table class="table table-bordered mg-b-0">
                    <tbody>
                        <tr>
                            <style>
                                .select2-container .select2-selection--single {
                                    height: 26px;
                                    width: 122px;
                                }
                                .select2-selection__rendered {
                                    font-size:12px;
                                }
                            </style>
                            <form autocomplete="off">
                                <th scope="row">
                                    <div class="row filter_search" style="margin-left: 0px;">
                                       <div class="col-sm-10 col-md- col-lg-10 col-xl-12 row">
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <label>Lot No:</label>
                                                <!-- <input type="text" value="{{request()->get('lot_no')}}" name="lot_no" class="form-control" placeholder="LOT NO"> -->
                                                <select name="lot_no" id="lot_no" class="form-control">
                                                    <option value="">---</option>
                                                    @foreach($data['lot_nos'] as $lot) 
                                                    <option value="{{$lot['id']}}" {{(request()->get('lot_no') == $lot['id']) ? 'selected' : ''}}>{{$lot['lot_number']}}</option>
                                                    @endforeach
                                                </select>
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <label>PO No:</label>
                                                <!-- <input type="text" value="{{request()->get('po_no')}}" name="po_no" class="form-control" placeholder="PO NO"> -->
                                                <select name="po_no" id="po_no" class="form-control">
                                                    <option value="">---</option> 
                                                    @foreach($data['po_nos'] as $po)
                                                    <option value="{{$po['id']}}" {{(request()->get('po_no') == $po['id']) ? 'selected' : ''}}>{{$po['po_number']}}</option>
                                                    @endforeach
                                                </select>
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <label>Invoice No:</label>
                                                <!-- <input type="text" value="{{request()->get('invoice_no')}}" name="invoice_no" class="form-control" placeholder="INVOICE NO"> -->
                                                <select name="invoice_no" id="invoice_no" class="form-control">
                                                    <option value="">---</option> 
                                                    @foreach($data['invoice_nos'] as $no)
                                                    <option value="{{$no['id']}}" {{(request()->get('invoice_no') == $no['id']) ? 'selected' : ''}}>{{$no['invoice_number']}}</option>
                                                    @endforeach
                                                </select>
                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <label>Item Code:</label>
                                                <!-- <input type="text" value="{{request()->get('item_code')}}" name="item_code" id="item_code" class="form-control" placeholder="ITEM CODE"> -->
                                                <select name="item_code" id="item_code" class="form-control">
                                                    <option value="">---</option>
                                                    @foreach($data['items'] as $item) 
                                                    <option value="{{$item['id']}}" {{(request()->get('item_code') == $item['id']) ? 'selected' : ''}}>{{$item['item_code']}}</option>
                                                    @endforeach
                                                </select>

                                            </div><!-- form-group -->
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <label for="exampleInputEmail1" style="font-size: 12px;">Supplier</label>
                                                
                                                <select name="supplier" id="supplier" class="form-control">
                                                   <option value="">---</option>
                                                    @foreach($data['suppliers'] as $supplier)
                                                    <option value="{{$supplier['id']}}" {{(request()->get('supplier') == $supplier['id']) ? 'selected' : ''}}>{{$supplier['vendor_id']}}-{{$supplier['vendor_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="padding: 0 0 0px 6px;">
                                                <label style="width: 100%;">&nbsp;</label>
                                                <button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                @if(count(request()->all('')) > 1)
                                                    <a href="{{url()->current();}}" class="badge badge-pill badge-warning"
                                                    style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
                                                @endif
                                            </div>
                                            <!-- <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                <label  style="font-size: 12px;">Created at </label>
                                                <input type="text" value="{{request()->get('from')}}"  class="form-control datepicker" name="from" placeholder="Created at (MM-YYYY)">
                                            </div>                       -->
                                        </div>
                                        <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                            <!-- <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
                                                <label style="width: 100%;">&nbsp;</label>
                                                <button type="submit" class="badge badge-pill badge-primary" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                @if(count(request()->all('')) > 1)
                                                    <a href="{{url()->current();}}" class="badge badge-pill badge-warning"
                                                    style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
                                                @endif
                                            </div> -->
                                        </div>
                                    </div>
                                </th>
                            </form>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
		
		<div class="table-responsive">
			<table class="table table-bordered mg-b-0" id="example1">
				<thead>
					<tr>
						<th>Lot No:</th>
                        <th>PO number :</th>
                        <th>Item Code:</th>
						<th>Invoice No.</th>
						<th>Invoice Qty</th>
						<th>Supplier</th>
						<th>Received Qty</th>
						<th>Accepted Qty</th>
						<th>Rejected Qty</th>
						{{-- <th>Transporter</th>
						<th>Vehicle No</th> --}}
						<th>Action</th>
					
					</tr>
				</thead>
				<tbody>
					@foreach($lot_data as $data)
					<tr>
						<td>{{$data['lot_number']}}</td>
                        <td>{{$data['po_number']}}</td>
                        <td>{{$data['item_code']}}</td>
						<td>{{$data['invoice_number']}}</td>
						<td>{{$data['inv_odr_qty']}}</td>
						<td>{{$data['vendor_id']}}-{{$data['vendor_name']}}</td>
						<td>{{$data['qty_received']}}</td>
						<td>{{$data['qty_accepted']}}</td>
						<td>{{$data['qty_rejected']}}</td>
						{{-- <td>{{$data['transporter_name']}}</td>
						<td>{{$data['vehicle_number']}}</td> --}}
						<td>
							<!-- <a class="badge badge-info" style="font-size: 13px;" href="http://localhost/adler/public/inventory/final-purchase-add/3"><i class="fas fa-edit"></i> Edit</a> -->
							<a class="badge badge-info lot-edit" style="font-size: 13px;" href="#" data-lotid ="{{$data['id']}}" data-invoiceitem="{{$data['invoice_number']}}" data-toggle="modal" data-target="#myModal"><i class="fas fa-edit"></i> Edit</a>                                    
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			<div class="box-footer clearfix">
				<style>
				.pagination-nav{
					width:100%;
				}
				.pagination{
					float:right;
					margin:0px;   
					margin-top: -16px;
				}

				</style>
		   </div> 
		</div>
	</div>
</div>
	<!-- az-content-body -->
	<!-- Modal content-->

	<div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg" style="max-width: 97% !important;">
            
              <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="display: block;">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Edit Lot Number Allocation  (<span id="lot_number"></span>) </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                            <form method="POST" id="commentForm" action="{{url('inventory/lot-allocation-edit')}}" novalidate="novalidate">
                                {{ csrf_field() }}
                                
                       
                                <div class="row">
                             
                                    {{-- <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                        <label for="exampleInputEmail1">Lot Number *</label>
                                        <input type="text" class="form-control lot-number" name="lot_number" id="lot_number" placeholder="Lot Number">
									
									</div> --}}
                                    <input type="hidden"  value="" class="form-control" name="lot_id" id="lot_id">
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Document No *</label>
                                        <input type="text"  class="form-control document-no" value="" name="document_no" id="document_no" placeholder="Document No">
                                    </div><!-- form-group -->
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Rev No *</label>
                                        <input type="text" class="form-control" id="rev_no" name="rev_no"
                                            placeholder="Rev No">
                                    </div><!-- form-group -->
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Rev Date *</label>
                                        <input type="date"  value="" class="form-control" name="rev_date" id="rev_date" placeholder="Rev Date">
                                    </div><!-- form-group -->
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Item Description *</label>
                                        <textarea value="" class="form-control" name="item_description" id="item_description"
                                            placeholder="Item Description" readonly></textarea>
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Material Code *</label>
                                        <input type="text"  value="" class="form-control" name="material_code" id="material_code" readonly placeholder="Material Code">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Material Description *</label>
                                        <textarea value="" class="form-control" name="material_description"  id="material_description"
                                            placeholder="Material Description" readonly></textarea>
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Invoice No *</label>
                                        <input type="text"  value="" class="form-control" name="invoice_no" id="invoice_no" readonly placeholder="Invoice No">
                                        <input type="hidden"  value="" class="form-control" name="invoice_id" id="invoice_id">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Invoice Date *</label>
                                        <input type="date"  value="" class="form-control" name="invoice_date" id="invoice_date" readonly placeholder="Invoice Date">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Invoice Qty *</label>
                                        <input type="text"  value="" class="form-control" name="invoice_qty" id="invoice_qty" readonly placeholder="Invoice Qty">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Qty Received *</label>
                                        <input type="text"  value="" class="form-control" name="qty_received" id="qty_received" placeholder="Qty Received">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Qty accepted *</label>
                                        <input type="text"  value="" class="form-control" name="qty_accepted" id="qty_accepted" placeholder="Qty Aceepted">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Qty rejected *</label>
                                        <input type="text"  value="" class="form-control" name="qty_rejected" id="qty_rejected" placeholder="Qty Rejected">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Unit *</label>
                                        <input type="text"  value="" class="form-control" name="unit_name" id="unit_name" readonly  placeholder="Unit">
                                        <input type="hidden"  value="" class="form-control" name="unit" id="unit">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>PO Number *</label>
                                        <input type="text"  value="" class="form-control" name="po_number" disabled id="po_number" placeholder="PO number">
                                        <input type="hidden"  value="" class="form-control" name="po_id" id="po_id">
                                    </div>
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Supplier *</label>
                                        <input type="text"  value="" class="form-control" name="supplier_name" id="supplier_name" disabled placeholder="Supplier">
                                        <input type="hidden"  value="" class="form-control" name="supplier" id="supplier">
                                    </div>
    
    
                                    <!-- form-group -->
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Vehicle Number*</label>
                                        <input type="text" value="" class="form-control" name="vehicle_no" id="vehicle_no" placeholder="Vehicle Number">
                                    </div><!-- form-group -->
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label> Transporter Name *</label>
                                        <input type="text" value="" class="form-control" name="transporter_name" id="transporter_name" placeholder="Transporter Name">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label> MRR Number*</label>
                                        <input type="text"  class="form-control" value="" id="mrr_no" name="mrr_no" placeholder="MRR Number">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label> MRR Date*</label>
                                        <input type="date"  class="form-control" value="" id="mrr_date" name="mrr_date" placeholder="MRR Date">
                                    </div>
    
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label> Test Report No *</label>
                                        <input type="text" class="form-control" value="" name="test_report_no" id="test_report_no"  placeholder="Test Report No">
                                    </div>
                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label> Test Report Date *</label>
                                        <input type="date" class="form-control" value="" id="test_report_date" name="test_report_date" placeholder="Test Report Date">
                                    </div>

									<div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label> Prepared By*</label>
                                        <select class="form-control user_list" name="prepared_by" id="prepared_by">
                                            @foreach ($users as $item)
                                             <option value="{{$item['user_id']}}"
                                             @if(!empty($data['simaster']) && $data['simaster']->created_by == $item['user_id']) selected @endif
                                             >{{$item['employee_id']}} - {{$item['f_name']}} {{$item['l_name']}}</option>
                                            @endforeach
                                        </select>                                    </div>

                                    <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                        <label>Approved By*</label>
                                        <select class="form-control user_list" name="approved_by" id="approved_by">
                                            @foreach ($users as $item)
                                             <option value="{{$item['user_id']}}"
                                             @if(!empty($data['simaster']) && $data['simaster']->created_by == $item['user_id']) selected @endif
                                             >{{$item['employee_id']}} - {{$item['f_name']}} {{$item['l_name']}}</option>
                                            @endforeach
                                        </select>                                  
									</div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span
                                                class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                                role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                            Update
                                        </button>
                                    </div>
                                </div>
                  
                            </form>
    
    
    
    
    
    
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <br>
                  {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}
                </div>
              </div>
              
            </div>
        </div>


<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>

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

jQuery.validator.addMethod("checkPrevValuePaxTo", function (value, element) {
let qty_received =  $('#qty_received').val();
let qtyaccepted = (qty_received - ((+$('#qty_accepted').val()) + (+$('#qty_rejected').val())));
if(qtyaccepted == 0 ){
        return true;
    }else{
        return false;
    }
}, "if Quantity accepted and Quantity rejected are added , The value of Quantity Received should be !");

$('#qty_received').on('input',function(){
    let qty_received =  $('#qty_received').val();
    let qtyaccepted = (qty_received - (+$('#qty_rejected').val()));
    $('#qty_accepted').attr('value',qtyaccepted);
});
$('#qty_accepted').on('input',function(){
    let qty_received =  $('#qty_received').val();
    let qtyaccepted = (qty_received - (+$('#qty_accepted').val()));
    $('#qty_rejected').attr('value',qtyaccepted);
});

$('#qty_rejected').on('input',function(){
    let qty_received =  $('#qty_received').val();
    let qtyaccepted = (qty_received - (+$('#qty_rejected').val()));
    $('#qty_accepted').attr('value',qtyaccepted);
});

$('#item_code').select2({
    placeholder: 'Item code',
    searchInputPlaceholder: 'Search',
    // minimumInputLength: 6,
    // allowClear: true,
 });
 $('#supplier').select2({
    placeholder: 'Supplier',
    searchInputPlaceholder: 'Search',
 });
 $('#lot_no').select2({
    placeholder: 'Lot No',
    searchInputPlaceholder: 'Search',
 });
 $('#invoice_no').select2({
    placeholder: 'Invoice No',
    searchInputPlaceholder: 'Search',
 });
 $('#po_no').select2({
    placeholder: 'PO No',
    searchInputPlaceholder: 'Search',
 });

 $('.search-btn').on( "click", function(e)  {
		var supplier = $('#supplier').val();
		var lot_no = $('#lot_no').val();
        var invoice_no = $('#invoice_no').val();
		var po_no = $('#po_no').val();
		var from = $('#from').val();
		if(!supplier & !lot_no & !invoice_no & !po_no & !from)
		{
			e.preventDefault();
		}
	});

$("#commentForm").validate({
                            rules: {

                                 document_no: {
                                     required: true,
                                },
                                rev_no: {
                                   required: true,
                                },
                                rev_date: {
                                     required: true,
                                },
                                supplier: {
                                    required: true,
                                },
                                item_description: {
                                     required: true,
                                 },
                                material_description: {
                                    required: true,
                                },
                                material_code: {
                                     required: true,
                                },
                                invoice_no: {
                                     required: true,
                                 },
                                 invoice_date: {
                                    required: true,
                                },
                                invoice_qty: {
                                     required: true,
                                     number: true
                                },
                                qty_accepted: {
                                     required: true,
                                     number: true
                                 },
                                qty_rejected: {
                                    required: true,
                                    number: true
                                },
                                qty_received: {
                                    required: true,
                                    number: true
                                },
                                unit: {
                                    required: true,
                                },
                                po_number: {
                                    required: true,
                                },
                                supplier: {
                                    required: true,
                                },
                                vehicle_no: {
                                    required: true,
                                },
                                transporter_name: {
                                    required: true,
                                },
                                mrr_no: {
                                    required: true,
                                },
                                mrr_date: {
                                    required: true,
                                },
                                test_report_no: {
                                    required: true,
                                },
                                test_report_date: {
                                    required: true,
                                },
                                test_report_date: {
                                    required: true,
                                },
                                

                            },
                            // submitHandler: function(form) {
                            //       form.submit();
                            // }
    });
    $(".lot-edit").on( "click", function() {
        $('#item_description').text('');
        $('#material_description').text('');
        $('#material_code').val('');
        $('#invoice_no').val('');
        $('#invoice_date').val('');
        $('#invoice_qty').val('');
        $('#po_number').val('');
        $('#supplier').val('');
        $('#supplier_name').val('');
        $('#po_id').val('');
        $('#unit_name').val('');
        $('#unit').val('');
        $('#invoice_id').val('');
		
		$('#lot_id').val('');
		$('#rev_no').val('');
        $('#rev_date').val('');
    	$('#qty_received').val('');
        $('#qty_accepted').val('');
        $('#qty_rejected').val('');
        $('#vehicle_no').val('');
        $('#transporter_name').val('');
        $('#mrr_no').val('');
        $('#mrr_date').val('');
        $('#test_report_no').val('');
        $('#test_report_date').val('');
        $('#document_no').val('');
		$('#prepared_by').val('');
		$('#approved_by').val('');
        var invoice_item_id = $(this).data('invoiceitem');
		var lot_allocation_id = $(this).data('lotid');
        $.get("{{ url('inventory/get-single-lot-allocation') }}/"+lot_allocation_id,function(data){
            $('#item_description').text(data.discription);
                $('#material_description').text(data.specification);
                $('#material_code').val(data.item_code);
                $('#invoice_no').val(data.invoiceNumber);
                $('#invoice_date').val(data.invoice_date);
                $('#invoice_qty').val(data.invoice_qty);
                $('#po_number').val(data.po_number);
                $('#po_id').val(data.po_id);
                $('#supplier').val(data.supplier_id);
                $('#supplier_name').val(data.vendor_id+"-"+data.vendor_name);
                $('#supplier').val(data.supplier_id);
                $('#unit_name').val(data.unit_name);
                $('#unit').val(data.unit_id);
                $('#invoice_id').val(data.invoice_item_id);

				$('#lot_number').text(data.lot_number);
                            $('#rev_no').val(data.rev_number);
                            $('#rev_date').val(data.rev_date);
                            $('#qty_received').val(data.qty_received);
                            $('#qty_accepted').val(data.qty_accepted);
                            $('#qty_rejected').val(data.qty_rejected);
                            $('#vehicle_no').val(data.vehicle_number);
                            $('#transporter_name').val(data.transporter_name);
                            $('#mrr_no').val(data.mrr_number);
                            $('#mrr_date').val(data.mrr_date);
                            $('#test_report_no').val(data.test_report_number);
                            $('#test_report_date').val(data.test_report_date);
                            $('#document_no').val(data.doc_number);
							$('#lot_id').val(data.id);
							$('#prepared_by option[value="'+data.prepared_by+'"]').attr("selected", "selected");;
							$('#approved_by').val(data.approved_by);
        });
                        //alert(invoice_item_id);
    });
  });
</script>


@stop