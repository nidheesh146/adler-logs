@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="{{url('inventory/get-purchase-reqisition')}}" style="color: #596881;">PURCHASE DETAILS</a></span> 
                <span><a href="{{url('inventory/get-purchase-reqisition')}}" style="color: #596881;">{{ (request()->pr_id)?  "PURCHASE": "SERVICE"}} REQUISITION</a></span>
                <span><a href="">{{ (request()->pr_id)?  "Purchase": "Service"}}  Requisition Details ( {{$data["master"]['pr_no']}}  )</a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;">{{ (request()->pr_id)?  "Purchase": "Service"}} Requisition Details ( {{$data["master"]['pr_no']}}  )
                <div class="right-button">
                    <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                        <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                    <div class="dropdown-menu">
                    <a href="#" class="dropdown-item">Excel</a>
  
                    </div> -->
                <div>  
            	</div>
				@if(request()->pr_id)
              	<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/add-purchase-reqisition-item?pr_id='.request()->pr_id)}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Purchase Requisition Details</button>
				@else
				<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/add-purchase-reqisition-item?sr_id='.request()->sr_id)}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Service Requisition Details</button>
				@endif
            </div>
          </h4>


            <div class="az-dashboard-nav">
                <nav class="nav">
					@if(request()->pr_id)
                    <a class="nav-link" href="{{url('inventory/edit-purchase-reqisition?pr_id='.request()->pr_id)}}">Purchase Requestor Details </a>
                    <a class="nav-link active "  href=""  >  Purchase Requisition Details </a>
                     <a class="nav-link" href=""> </a>
					 @else
					 <a class="nav-link" href="{{url('inventory/edit-purchase-reqisition?sr_id='.request()->sr_id)}}">Purchase Requestor Details </a>
                    <a class="nav-link active "  href=""  >  Service Requisition Details </a>
                     <a class="nav-link" href=""> </a>
					 @endif
                </nav>
           
            </div>

            <div class="table-responsive">
	
			    		   
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

						@foreach($data['item'] as $item)
						<tr>
							<th>{{$item['item_code']}}</th>
							<th>{{$item['vendor_id']}}</th>
							<td>{{$item['actual_order_qty']}}</td>
							<td>{{$item['rate']}}</td>
							<td>{{$item['discount_percent']}}</td>
							<td>@if($item['igst']!=0)
                                    IGST:{{$item['igst']}}%
                                    &nbsp;
                                    @endif
                                    
                                    @if($item['sgst']!=0)
                                    SGST:{{$item['sgst']}}%,
                                    &nbsp;
                                    @endif
                                    
                                    @if($item['sgst']!=0)
                                    CGST:{{$item['sgst']}}%
                                    @endif
							</td>
							<td>{{$item['currency_code']}}</td>
							<th>{{$item['net_value']}}</th>
							<td>
								<button data-toggle="dropdown" style="width: 64px;" class="badge badge-success"> Active <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
								<div class="dropdown-menu"> 
								@if(request()->pr_id)
									<a href="{{url('inventory/edit-purchase-reqisition-item?pr_id='.request()->pr_id.'&item='.$item['requisition_item_id'])}}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> 
									<a href="{{url('inventory/delete-purchase-reqisition-item?pr_id='.request()->pr_id).'&'.'item_id='.$item['requisition_item_id']}}" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i>  Delete</a> 
								@else
								<a href="{{url('inventory/edit-purchase-reqisition-item?sr_id='.request()->sr_id.'&item='.$item['requisition_item_id'])}}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> 
									<a href="{{url('inventory/delete-purchase-reqisition-item?sr_id='.request()->sr_id).'&'.'item_id='.$item['requisition_item_id']}}" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i>  Delete</a> 
								@endif
								</div>
							</td>
						</tr>
						@endforeach
				
					
					</tbody>
				</table>
				<div class="box-footer clearfix">
					{{ $data['item']->appends(request()->input())->links() }}
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
    //   }
    // });

    
  });
</script>


@stop