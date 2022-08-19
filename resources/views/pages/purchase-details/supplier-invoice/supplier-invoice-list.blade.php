@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
<div class="az-content az-content-dashboard">
  <br>
  <div class="container">
	<div class="az-content-body">
		<div class="az-content-breadcrumb"> 
	
			 <span><a href="">Supplier Invoice</a></span>
			 </div>
		<h4 class="az-content-title" style="font-size: 20px;">Supplier Invoice list
		  <div class="right-button">
			  <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
				  <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
			  <div class="dropdown-menu">
			  <a href="#" class="dropdown-item">Excel</a>

			  </div> -->
		  <div>  
		  </div>
		<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/supplier-invoice-add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Supplier Invoice</button> 
	  </div>
	</h4>
		<div class="az-dashboard-nav">
			<nav class="nav"> </nav>	
		</div>

  
		
	   


		<div class="table-responsive">
			<table class="table table-bordered mg-b-0" id="example1">
				<thead>
					<tr>
					
						<th style="width:120px;">PO number :</th>
						<th>Invoice number:</th>
						<th>Invoice date</th>
						<th>Supplier</th>
						<th>Created Date</th>
						<th>Created By</th>
						<th>Action</th>
					
					</tr>
				</thead>
				<tbody>
											 <tr>
						<td>RQ-22080008</td>
						<td>PO-22080001</td>
						<td>15-08-2022</td>
						<td>VDR033 - Darshan Surgical</td>
						<td>15-08-2022</td>
						<td>Admin Admin</td>
						<td>
							<a class="badge badge-info" style="font-size: 13px;" href="http://localhost/adler/public/inventory/final-purchase-add/3"><i class="fas fa-edit"></i> Edit</a>
							<a class="badge badge-danger" style="font-size: 13px;" href="http://localhost/adler/public/inventory/final-purchase-delete/3" onclick="return confirm('Are you sure you want to delete this ?');"><i class="fa fa-trash"></i> Delete</a>
							
						</td>
					</tr>
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


<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>

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