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
		  <div>  
		  </div>
		<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/lot-allocation-add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> LOT Allocation</button> 
	  </div>
	</h4>
		<div class="az-dashboard-nav">
			<nav class="nav"> </nav>	
		</div>

		<div class="table-responsive">
			<table class="table table-bordered mg-b-0" id="example1">
				<thead>
					<tr>
						<th>Lot No:</th>
						<th>PO number :</th>
						<th>Invoice No.</th>
						<th>Invoice Qty</th>
						<th>Supplier</th>
						<th>Received Qty</th>
						<th>Accepted Qty</th>
						<th>Rejected Qty</th>
						<th>Transporter</th>
						<th>Vehicle No</th>
						<th>Action</th>
					
					</tr>
				</thead>
				<tbody>
					@foreach($lot_data as $data)
					<tr>
						<td>{{$data['lot_number']}}</td>
						<td>{{$data['po_id']}}</td>
						<td>{{$data['invoice_number']}}</td>
						<td>{{$data['invoice_qty']}}</td>
						<td>VDR033-Darshan Surgical</td>
						<td>{{$data['qty_received']}}</td>
						<td>{{$data['qty_accepted']}}</td>
						<td>{{$data['qty_rejected']}}</td>
						<td>{{$data['transporter_name']}}</td>
						<td>{{$data['vehicle_number']}}</td>
						<td>
							<a class="badge badge-info" style="font-size: 13px;" href="http://localhost/adler/public/inventory/final-purchase-add/3"><i class="fas fa-edit"></i> Edit</a>
							
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
    // $('#example1').DataTable({
    //   language: {
    //     searchPlaceholder: 'Search...',
    //     sSearch: '',
    //     lengthMenu: '_MENU_ items/page',
    //   },
	//   order: [[1, 'desc']],
    // });

  });
</script>


@stop