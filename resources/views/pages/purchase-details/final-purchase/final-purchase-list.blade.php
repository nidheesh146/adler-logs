@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
		
				 <span><a href="">Final Purchase Order</a></span>
				 </div>
			<h4 class="az-content-title" style="font-size: 20px;">Final Purchase Order list
              <div class="right-button">
                  <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                      <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                  <div class="dropdown-menu">
                  <a href="#" class="dropdown-item">Excel</a>

                  </div> -->
              <div>  
              </div>
			 <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/final-purchase-add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Final Purchase Order </button> 
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
							<th>PO NO:</th>
							<th>PO date</th>
							<th>Supplier</th>
							<th>Created Date</th>
							<th>Created By</th>
							<th>Action</th>
						
						</tr>
					</thead>
					<tbody>
						@foreach($data['po_data'] as $po_data)
						 <tr>
                            <td>{{$po_data->rq_no}}</td>
							<td>{{$po_data->po_number}}</td>
							<td>{{date('d-m-Y',strtotime($po_data->po_date))}}</td>
							<td>{{$po_data->vendor_id}} - {{$po_data->vendor_name}}</td>
							<td>{{date('d-m-Y',strtotime($po_data->created_at))}}</td>
							<td>{{$po_data->f_name}} {{$po_data->l_name}}</td>
							<td>
								<a class="badge badge-info" style="font-size: 13px;" href="{{url('inventory/final-purchase-add/'.$po_data->id)}}"><i class="fas fa-edit"></i> Edit</a>
								
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<div class="box-footer clearfix">
					{{ $data['po_data']->appends(request()->input())->links() }}
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