@extends('layouts.default')
@section('content')
@inject('fn', 'App\Http\Controllers\Web\PurchaseDetails\InventoryController')
@php
use App\Http\Controllers\Web\PurchaseDetails\InventoryController;
$obj_req= new InventoryController;
@endphp
<div class="az-content az-content-dashboard">
	<br>
	<div class="container">
		<div class="az-content-body">

			<div class="az-content-breadcrumb">
				<span><a href="{{url('inventory/get-purchase-reqisition')}}" style="color: #596881;">PURCHASE DETAILS</a></span>
				<span><a href="{{url('inventory/get-purchase-reqisition')}}" style="color: #596881;">{{ (request()->pr_id)?  "PURCHASE": "SERVICE"}} REQUISITION</a></span>
				<span><a href="">{{ (request()->pr_id)?  "Purchase": "Service"}} Requisition Details ( {{$data["master"]['pr_no']}} )</a></span>
			</div>

			<h4 class="az-content-title" style="font-size: 20px;">{{ (request()->pr_id)?  "Purchase": "Service"}} Requisition Details ( {{$data["master"]['pr_no']}} )
				<div class="right-button">
					<!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                        <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                    <div class="dropdown-menu">
                    <a href="#" class="dropdown-item">Excel</a>
  
                    </div> -->
					<div>
					</div>
					@if(in_array('purchase_details.requisition_item_add',config('permission')))
					@if(request()->pr_id)
						<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/add-purchase-reqisition-item?pr_id='.request()->pr_id)}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Purchase Requisition Details</button>
						<button style="float: right;font-size: 14px;" class="badge badge-pill badge-info item-upload" style="font-size: 13px;" href="#" data-prId="{{request()->pr_id}}" data-type="Purchase" data-master="{{$data["master"]['pr_no']}}" data-toggle="modal" data-target="#uploadModal"><i class="fas fa-plus"></i> Upload</a>
					@else
						<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/add-purchase-reqisition-item?sr_id='.request()->sr_id)}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Service Requisition Details</button>
						<button style="float: right;font-size: 14px;" class="badge badge-pill badge-info item-upload" style="font-size: 13px;" href="#" data-prId="{{request()->sr_id}}" data-type="Service" data-master="{{$data["master"]['pr_no']}}" data-toggle="modal" data-target="#uploadModal"><i class="fas fa-plus"></i> Upload</a>
					@endif
					@endif
						<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/purchase-reqisition-item/excel-export?pr_id='.request()->pr_id)}}'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button>
				</div>
			</h4>
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
                                    <form autocomplete="off" id="formfilter" method="GET">
                                        <th scope="row">
                                            <div class="row filter_search" style="margin-left: 0px;">
                                
                                                    <input type="hidden" value="{{request()->pr_id}}" id="pr_id"  name="pr_id">
													<input type="hidden" value="{{request()->sr_id}}" id="sr_id"  name="sr_id">
													<input type="hidden" value="{{request()->get('prsr')}}" id="prsr"  name="prsr">
                                                    
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                    <label for="exampleInputEmail1" style="font-size: 12px;">Item Code</label>
                                                        <input type="text" value="{{request()->get('item_code')}}" name="item_code" id="item_code" class="form-control" placeholder="ITEM CODE">
                                                    
                                                    </div><!-- form-group -->
                                                    
                                
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label  style="font-size: 12px;">Status</label>
                                                        <select name="status" class="form-control">
                                                            <option value=""> --Select One-- </option>
                                                            <option value="1" {{(request()->get('status') == 1) ? 'selected' : ''}}> Approved</option>
                                                            <option value="4" {{(request()->get('status') == 4) ? 'selected' : ''}}> Pending</option>
                                                            <option value="5"{{(request()->get('status') == 5) ? 'selected' : ''}}>On hold</option>
                                                            <option value="0"{{(request()->get('status') == '0') ? 'selected' : ''}}>Rejected</option>


                                                        </select>
                                                    </div> 
													<div class="form-group col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
													
															<label style="width: 100%;">&nbsp;</label>
															<button type="submit" class="badge badge-pill badge-primary search-btn" onclick="document.getElementById('formfilter').submit();" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
															@if(count(request()->all('')) > 2)
																{{--<a href="{{url()->current();}}" class="badge badge-pill badge-warning"
																style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>--}}
															@endif
														
													</div>
                                            </div>
                                        </th>
                                    </form>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

			<div class="az-dashboard-nav">
				<nav class="nav">
					@if(request()->pr_id)
					<a class="nav-link" href="{{url('inventory/edit-purchase-reqisition?pr_id='.request()->pr_id)}}">Purchase Requestor Details </a>
					<a class="nav-link active " href=""> Purchase Requisition Details </a>
					<a class="nav-link" href=""> </a>
					@else
					<a class="nav-link" href="{{url('inventory/edit-purchase-reqisition?sr_id='.request()->sr_id)}}">Service Requestor Details </a>
					<a class="nav-link active " href=""> Service Requisition Details </a>
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
							<th>Description </th>
							<th>Item Type </th>
							<th>Order Qty</th>
							<th>Unit</th>
							<th>#</th>

						</tr>
					</thead>
					<tbody>

						@foreach($data['item'] as $item)
						<tr>
							<td>{{$item['item_code']}}</td>
							<td>{{$item['short_description']}}</td>
							<td>{{$item['type_name']}}</td>
							<td>{{$item['actual_order_qty']}}</td>
							<td> {{$item['unit_name']}}</td>
							<td>
								
								@if(!empty($obj_req->get_purchase($item['requisition_item_id'])))
								<a class="badge badge-info" style="font-size: 13px;" class="dropdown-item">Purchase </a>
								@elseif(!empty($obj_req->get_suplier_qtn($item['requisition_item_id'])))
								<a class="badge badge-info" style="font-size: 13px;" class="dropdown-item">supplier Qtn</a>
								@elseif(!empty($obj_req->get_requisition_qtn($item['requisition_item_id'])))
								<a class="badge badge-info" style="font-size: 13px;" class="dropdown-item">Requisition </a>
							
								@endif
								
							
							</td>
							<td>
								@php $status =$fn->getStatus($item['requisition_item_id']); @endphp
								<button data-toggle="dropdown" style="width: 70px;" class="badge @if($status == 1) badge-success @elseif($status== 4) badge-info  @elseif($status==5 ) badge-warning   @elseif($item['status'] == 0) badge-danger @endif ">
									@if($status ==4)
									Pending
									@elseif($status == 5)
									On hold
									@elseif($status == 1)
									Approved
									@else
									Rejected
									@endif
									<i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i>
								</button>
								@php $qtn =$fn->get_qtn($item['requisition_item_id']); @endphp
								<div class="dropdown-menu">
									@if(!empty($qtn))
									<a  onclick="return confirm('Not possible to delete.It is under quotation!');" class="dropdown-item"><i class="fas fa-trash-alt"></i> Delete</a>
									@else
									{{--<a href="{{url('inventory/delete-purchase-reqisition-item?pr_id='.request()->pr_id).'&'.'item_id='.$item['requisition_item_id']}}" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i> Delete</a>--}}
									<a style="" class="dropdown-item item-delete" style="font-size: 13px;" href="#" item="{{$item['item_code']}}" qty="{{$item['actual_order_qty']}}" unit="{{$item['unit_name']}}" itemId="{{$item['requisition_item_id']}}" type="Purchase" master="{{$data["master"]['pr_no']}}" description="{{$item['short_description']}}" data-toggle="modal" data-target="#deleteModal"><i class="fas fa-trash-alt"></i> Delete</a>
									@endif
								</div>
								@if(in_array('purchase_details.requisition_item_edit',config('permission')))
								@if($status ==4)
								<div class="dropdown-menu">
									@if(request()->pr_id)
									@if($status ==4)
									<a href="{{url('inventory/edit-purchase-reqisition-item?pr_id='.request()->pr_id.'&item='.$item['requisition_item_id'])}}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a>
									<a href="{{url('inventory/delete-purchase-reqisition-item?pr_id='.request()->pr_id).'&'.'item_id='.$item['requisition_item_id']}}" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i> Delete</a>
									@endif
									@else
									@if($status ==4)
									<a href="{{url('inventory/edit-purchase-reqisition-item?sr_id='.request()->sr_id.'&item='.$item['requisition_item_id'])}}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a>
									<a href="{{url('inventory/delete-purchase-reqisition-item?sr_id='.request()->sr_id).'&'.'item_id='.$item['requisition_item_id']}}" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i> Delete</a>
									@endif
									@endif
								</div>
								@endif
								@endif
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
	<div class="modal fade" id="uploadModal" role="dialog">
		<div class="modal-dialog modal-xs">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header" style="display: block;">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Upload <span id="type"></span> Requisition Details<span id="pr_master"></span></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
							<form method="POST" id="commentForm" action="{{url('inventory/purchase-reqisition-item-upload')}}" novalidate="novalidate" enctype='multipart/form-data'>
								{{ csrf_field() }}
								<div class="row">

									<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<label for="exampleInputEmail1">Select File*</label>
										<input type="file" required class="form-control file" name="file" id="file">
										<a href="{{ asset('uploads/purchase_requisition_items_sample.xlsx') }}" target="_blank" style="float: right; font-size: 10px;"> Download Template</a>
										<input type="hidden" name="pr_id" id="pr_id" value="">
									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
											Save
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="deleteModal" role="dialog">
		<div class="modal-dialog modal-xs">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header" style="display: block;">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Delete <span id="type"></span> Requisition Details<span id="pr_master_no"></span></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
							<form method="POST" id="commentForm" action="{{url('inventory/delete-purchase-reqisition-item')}}" novalidate="novalidate" enctype='multipart/form-data'>
								{{ csrf_field() }}
								<div class="row">
									<table class="table table-bordered mg-b-0">
										<tr>
											<td>Item Code</td>
											<td id="itemCode"></td>
											<input type="hidden" name="pr_item_id" id="pr_item_id" value="">
										</tr>
										<tr>
											<td>Description</td>
											<td id="itemdescription"></td>
										</tr>
										<tr>
											<td>Quantity</td>
											<td id="itemQty"></td>
										</tr>
										<tr>
											<td>Reason for Deletion</td>
											<td><textarea class="form-control" name="reason"></textarea></td>
										</tr>
									</table>
								</div><br/>
								<div class="row">
									<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
											Delete
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>



<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>


<script src="<?= url(''); ?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"> </script>

<script>
	$(function() {
		'use strict'

		// $('#example1').DataTable({
		//   language: {
		//     searchPlaceholder: 'Search...',
		//     sSearch: '',
		//     lengthMenu: '_MENU_ items/page',
		//   }
		// });
		$("#commentForm").validate({
			rules: {
				file: {
					required: true,
				},
			},
			submitHandler: function(form) {
				form.submit();
			}
		});


	});
	$(".item-upload").on("click", function() {
		var type = $(this).data('type');
		$('#type').html(type);
		var pr_master = $(this).data('master');
		$('#pr_master').html(' (' + pr_master + ')');
		var pr_id = $(this).data('prid');
		$('#pr_id').val(pr_id);
	});
	$(".item-delete").on("click", function() {
		var type = $(this).attr('type');
		$('#type').html(type);
		var pr_master = $(this).attr('master');
		$('#pr_master_no').html(' (' + pr_master + ')');
		var pr_item_id = $(this).attr('itemId');
		$('#pr_item_id').val(pr_item_id);
		var qty = $(this).attr('qty');
		var unit = $(this).attr('unit');
		$('#itemQty').html(qty+' '+unit);
		var item = $(this).attr('item');
		$('#itemCode').html(item);
		$('#pr_item_id').val(pr_item_id);
		var description = $(this).attr('description');
		$('#itemdescription').html(description);
	});
</script>


@stop