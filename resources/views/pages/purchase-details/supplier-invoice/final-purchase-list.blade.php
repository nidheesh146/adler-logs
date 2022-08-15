@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
		
				 <span><a href="">Supplier Quotation</a></span>
				 </div>
			<h4 class="az-content-title" style="font-size: 20px;">Supplier Quotation
              <div class="right-button">
                  <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                      <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                  <div class="dropdown-menu">
                  <a href="#" class="dropdown-item">Excel</a>

                  </div> -->
              <div>  
              </div>
			<!-- <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/add-supplier-quotation')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Supplier Quotation   </button> -->
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



			<div class="table-responsive">
                <table class="table table-bordered mg-b-0" id="example1">
					<thead>
						<tr>
						
							<th style="width:120px;">RQ NO:</th>
							<th>Date</th>
							<th>delivery Schedule </th>
							<th>Suppliers</th>
							
							
						
							<th>Action</th>
						
						</tr>
					</thead>
					<tbody>
						                        <tr>
							
                            <td>RQ-22080008</td>
							<td>08-08-2022</td>
							<td>08-08-2022</td>
							<td>
								<span>VIN056</span> , <span>VDR033</span> , <span>VDR055</span>							</td>
							<td>
								<a class="badge badge-info" style="font-size: 13px;" href="http://localhost/adler/public/inventory/view-supplier-quotation-items/9/56"><i class="fas fa-eye"></i> View</a>
								<a class="badge badge-primary" style="font-size: 13px;" href="http://localhost/adler/public/inventory/comparison-quotation/9"><i class="fa fa-balance-scale"></i> Comparison</a>
							</td>
						</tr>
						
						                    
						
						                      
						


						 
				
					</tbody>
				</table>
				{{-- <div class="box-footer clearfix">
					{{ $data['quotation']->appends(request()->input())->links() }}
			   </div>  --}}
		
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