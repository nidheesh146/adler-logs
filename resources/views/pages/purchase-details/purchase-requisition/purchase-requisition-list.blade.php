@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				 <span>Purchase details</span>
				 <span><a href="">Purchase Reqisition</a></span>
				 </div>
			<h4 class="az-content-title" style="font-size: 20px;">Purchase Reqisition 
              <div class="right-button">
                  <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                      <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                  <div class="dropdown-menu">
                  <a href="#" class="dropdown-item">Excel</a>

                  </div>
              <div>  
              </div>
			<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/add-purchase-reqisition')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Purchase Reqisition   </button>
          </div>
        </h4>
			<div class="az-dashboard-nav">
				<nav class="nav"> </nav>
			</div>
	
      
			@if($data['error'])
				<div class="alert alert-danger "  role="alert" style="width: 100%;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					{{$data['error'] }}
				</div>
		   @endif
		   
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
							<th>PR NO:</th>
							<th>requestor</th>
							<th>date</th>
							<th>department</th>
							<th>prcsr</th>
				
							<th>Action</th>
						
						</tr>
					</thead>
					<tbody id="prbody" style="display:none;">
					@if(!empty($data['response']['purchase_requisition']))
					@foreach ($data['response']['purchase_requisition'] as $item)
						<tr>
							<th>{{$item['pr_no']}} </th>
							<th>{{$item['requestor']}}</th>
							<td>{{$item['date']}}</td>
							<td>{{$item['department']}}</td>
							<td>{{$item['prcsr']}}</td>
						
							<td style="width: 133px;">
								<span style="width: 133px;">
								<button data-toggle="dropdown" style="width: 64px;" class="badge badge-success"> Active <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
								<div class="dropdown-menu">
									<a href="{{url('inventory/edit-purchase-reqisition?pr_id='.$item["id"])}}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> 
									<a href="{{url('add-purchase-reqisition-item?pr_id='.$item["id"])}}" class="dropdown-item"><i class="fas fa-plus"></i> Item</a> 
									<a href="{{url('inventory/delete-purchase-reqisition?pr_id='.$item["id"])}}" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i>  Delete</a> 
								
								</div>
								<a class="badge badge-info" style="font-size: 13px;" href="{{url('inventory/get-purchase-reqisition-item?pr_id='.$item["id"])}}"  class="dropdown-item"><i class="fas fa-eye"></i> Item</a> 
								
							</span>
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

    $('#prbody').show();
  });
</script>


@stop