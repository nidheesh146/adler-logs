@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				 <span>Supplier Quotation</span>
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
							
							{{-- <th>Item count</th> --}}
						
							<th>Action</th>
						
						</tr>
					</thead>
					<tbody >
						@foreach($data['quotation'] as $item)
                        <tr>
							{{-- <td>{{$i++}}</td> --}}
                            <td>{{$item['rq_no']}}</td>
							<td>{{$item['date'] ? date('d-m-Y',strtotime($item['date'])) : '-'}}</td>
							<td>{{$item['delivery_schedule'] ? date('d-m-Y',strtotime($item['delivery_schedule'])) : '-'}}</td>
							<td>
								<?php
							    	$supp = $SupplierQuotation->get_supplier($item['quotation_id']);
									echo $supp['supplier'];
								?>
							</td>
							<td>
								<a class="badge badge-info" style="font-size: 13px;" href="{{url('inventory/view-supplier-quotation-items/'.$item['quotation_id'].'/'.$supp['supplier_id'])}}"  class="dropdown-item"><i class="fas fa-eye"></i> View</a>
								<a class="badge badge-primary" style="font-size: 13px;" href="{{url('inventory/comparison-quotation/'.$item['quotation_id']) }}"  class="dropdown-item"><i class="fa fa-balance-scale"></i> Comparison</a>
							</td>
						</tr>
						
						@endforeach 
				
					</tbody>
				</table>
				<div class="box-footer clearfix">
					{{ $data['quotation']->appends(request()->input())->links() }}
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

    $('#prbody').show();
  });
</script>


@stop