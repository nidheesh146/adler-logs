@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="{{url('inventory/get-purchase-reqisition')}}" style="color: #596881;">PURCHASE DETAILS</a></span> 
                <span><a href="{{url('inventory/get-purchase-reqisition')}}" style="color: #596881;">PURCHASE REQISITION</a></span>
                <span><a href="">List purchase reqisition item </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;">List Purchase reqisition item
                <div class="right-button">
                    <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                        <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                    <div class="dropdown-menu">
                    <a href="#" class="dropdown-item">Excel</a>
  
                    </div>
                <div>  
                </div>
              <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/add-purchase-reqisition-item?pr_id='.request()->pr_id)}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Purchase reqisition item</button>
            </div>
          </h4>


            <div class="az-dashboard-nav">
                <nav class="nav">
                    <a class="nav-link" href="{{url('inventory/edit-purchase-reqisition?pr_id='.request()->pr_id)}}">Purchase reqisition master </a>
                    <a class="nav-link active "  href=""  >  Purchase reqisition item </a>
                     <a class="nav-link" href=""> </a>
                </nav>
           
            </div>

            <div class="table-responsive">
				@if(!empty($data['error']))
				<div class="alert alert-danger "  role="alert" style="width: 100%;">
				  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				 {{ $data['error'] }}
			   </div>
			  @endif 
			    		   
		   @if (Session::get('success'))
		   <div class="alert alert-success " style="width: 100%;">
			   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			   <i class="icon fa fa-check"></i> {{ Session::get('success') }}
		   </div>
		   @endif
				<table class="table table-bordered mg-b-0" id="example1">
					<thead>
						<tr>
							<th>Item code </th>
							<th>Supplier</th>
							<th>Actual order Qty</th>
							<th>Rate</th>
							<th>Discount %</th>
							<th>GST %</th>
							<th>Currency</th>
							<th>Net value </th>
							<th>#</th>
						</tr>
					</thead>
					<tbody >

					
						@if(!empty($data['response']['purchase_requisition'][0]))
						@foreach($data['response']['purchase_requisition'] as $item)
						<tr>
							<th>{{$item['item_code']['item_code']}}</th>
							<th>{{$item['supplier']['vendor_name']}}</th>
							<td>{{$item['actual_order_qty']}}</td>
							<td>{{$item['rate']}}</td>
							<td>{{$item['discount_percent']}}</td>
							<td>{{$item['gst']}}</td>
							<td>{{$item['currency']}}</td>
							<th>{{$item['net_value']}}</th>
							<td>
								<button data-toggle="dropdown" style="width: 64px;" class="badge badge-success"> Active <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
								<div class="dropdown-menu"> 
							   <a href="{{url('inventory/edit-purchase-reqisition-item?pr_id='.request()->pr_id.'&item='.$item['id'])}}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> 
							   <a href="{{url('inventory/delete-purchase-reqisition-item?pr_id='.request()->pr_id).'&'.'item_id='.$item['id']}}" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i>  Delete</a> </div>
							</td>
						</tr>
						@endforeach
						@endif
					
					</tbody>
				</table>
				<div class="box-footer clearfix">
					<style>
					.pagination-nav {
						width: 100%;
					}
					
					.pagination {
						float: right;
						margin: 0px;
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

    $('#example1').DataTable({
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
        lengthMenu: '_MENU_ items/page',
      }
    });

    
  });
</script>


@stop