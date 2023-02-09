@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				 <span>Purchase details</span>
				 <span><a href="">
				 	@if(request()->get('prsr')=="sr")
					Service Requisition
					@else
					Purchase Requisition
					@endif
				</a></span>
				 </div>
			<h4 class="az-content-title" style="font-size: 20px;">
				@if(request()->get('prsr')=="sr")
					Service Requisition
				@else
					Purchase Requisition
				@endif 
              <div class="right-button">
                  {{-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                      <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                  <div class="dropdown-menu">
                  <a href="#" class="dropdown-item">Excel</a>

                  </div> --}}
              <div>  
				
              </div>
			  
					@if(request()->get('prsr')=="sr")
					<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/add-purchase-reqisition')}}?prsr=sr'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> 
						Service Requisition
					</button>
					@else
					<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/add-purchase-reqisition')}}?prsr=pr'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> 
						Purchase Requisition
					</button>
					@endif
			
			
          </div>
        </h4>
        @include('includes.purchase-details.pr-sr-tab')
	
		   @if(Session::get('error'))
		   <div class="alert alert-danger "  role="alert" style="width: 100%;">
			   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			   {{Session::get('error')}}
		   </div>
	       @endif
		   @if (Session::get('success'))
		   <div class="alert alert-success " style="width: 100%;">
			   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			   <i class="icon fa fa-check"></i> {{ Session::get('success') }}
		   </div>
		   @endif
		   
			<div class="tab-content"> 
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
									<form autocomplete="off">
										<th scope="row">
											<div class="row filter_search" style="margin-left: 0px;">
												<div class="col-sm-10 col-md- col-lg-10 col-xl-10 row">
								
													<div class="form-group col-sm-12 col-md-3 col-lg- col-xl-4">
														<label>@if(request()->get('prsr')=="sr")SR @else PR @endif No:</label>
														<input type="text" value="{{request()->get('pr_no')}}" name="pr_no" id="pr_no" class="form-control" placeholder="@if(request()->get('prsr')=='sr')SR @else PR @endif NO">
													</div><!-- form-group -->
													
													
													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label for="exampleInputEmail1" style="font-size: 12px;">Department</label>
														<input type="text" value="{{request()->get('department')}}" name="department" id="department" class="form-control" placeholder="DEPARTMENT">
													</div>
													<!-- <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
														<label for="exampleInputEmail1" style="font-size: 12px;">PR/SR</label>
														
														<select name="pr_sr" id="pr_sr" class="form-control">
															<option value="">PR/SR</option>
															<option value="PR" {{(request()->get('pr_sr') == 'PR') ? 'selected' : ''}}>PR</option>
															<option value="SR" {{(request()->get('pr_sr') == 'SR') ? 'selected' : ''}}>SR</option>
														</select>
													</div> -->
													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label  style="font-size: 12px;">Month</label>
														<input type="text" value="{{request()->get('from')}}" id="from" class="form-control datepicker" name="from" placeholder="Month(MM-YYYY)">
													</div>
													
														<input type="hidden" value="{{request()->get('prsr')}}" id="prsr"  name="prsr">
																		
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
													<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
														<label style="width: 100%;">&nbsp;</label>
														<button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
														@if(count(request()->all('')) > 2)
															<a href="{{url()->current()}}" class="badge badge-pill badge-warning"
															style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
														@endif
													</div> 
												</div>
											</div>
										</th>
									</form>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
			
			
					<div class="tab-pane  active  show " id="purchase"> 
					
					<div class="table-responsive">
						<table class="table table-bordered mg-b-0" >
							<thead>
								<tr>
									<th>@if(request()->get('prsr')=="sr")SR @else PR @endif NO:</th>
									<th>requestor</th>
									<th>date</th>
									<th>department</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody id="prbody1">
									
							@foreach($data['master'] as $item)
							
								<tr>
									<th>{{$item['pr_no']}} </th>
									<th>{{$item['f_name'].' '.$item['l_name']}}</th>
									<td>{{date('d-m-Y',strtotime($item['date']))}}</td>
									<td>{{$item['dept_name']}}</td>
									<td >
										<span style="width: 133px;">
										<button data-toggle="dropdown" style="width: 64px;" class="badge badge-success"> Active <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
										<div class="dropdown-menu">
											@if($item['prsr_type']=="PR")
												@if(in_array('purchase_details.requisition_edit',config('permission')))
												<a href="{{url('inventory/edit-purchase-reqisition?pr_id='.$item["master_id"])}}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> 
												@endif
												@if(in_array('purchase_details.requisition_item_list',config('permission')))
												<a href="{{url('inventory/add-purchase-reqisition-item?pr_id='.$item["master_id"])}}" class="dropdown-item"><i class="fas fa-plus"></i> Item</a> 
												@endif
												{{-- @if(in_array('purchase_details.requisition_delete',config('permission')))
												<a href="{{url('inventory/delete-purchase-reqisition?pr_id='.$item["master_id"])}}" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i>  Delete</a> 
												@endif --}}
											@else
												@if(in_array('purchase_details.requisition_edit',config('permission')))
												<a href="{{url('inventory/edit-service-reqisition?sr_id='.$item["master_id"])}}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> 
												@endif
												@if(in_array('purchase_details.requisition_item_list',config('permission')))
												<a href="{{url('inventory/add-purchase-reqisition-item?sr_id='.$item["master_id"])}}" class="dropdown-item"><i class="fas fa-plus"></i> Item</a> 
												@endif
												{{-- @if(in_array('purchase_details.requisition_delete',config('permission')))
												<a href="{{url('inventory/delete-service-reqisition?sr_id='.$item["master_id"])}}" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i>  Delete</a> 
												@endif --}}
											@endif
										</div>
										@if(in_array('purchase_details.requisition_item_list',config('permission')))
											@if($item['prsr_type']=="PR")
											<a class="badge badge-info" style="font-size: 13px;" href="{{url('inventory/get-purchase-reqisition-item?pr_id='.$item["master_id"])}}"  class="dropdown-item"><i class="fas fa-eye"></i> Item</a> 	
											@else
											<a class="badge badge-info" style="font-size: 13px;" href="{{url('inventory/get-service-reqisition-item?sr_id='.$item["master_id"])}}"  class="dropdown-item"><i class="fas fa-eye"></i> Item</a> 	
											@endif
										@endif
									</span>
									</td>
								</tr>
							
							@endforeach
					
							</tbody>
						</table>
						<div class="box-footer clearfix">
							{{ $data['master']->appends(request()->input())->links() }}
						</div>
					</div>
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
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>

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
	$('#prbody1').show();
	$('#prbody2').show();
  });
  	$('#purchase_tab').on('click',function(){
		$('#pr_no').val(" ");
		$('#department').val("");
		$('#from').val(" ");
	});
	$('#service_tab').on('click',function(){
		$('#pr_no').val(" ");
		$('#department').val("");
		$('#from').val(" ");
	});
	$('.search-btn').on( "click", function(e)  {
		var pr_no = $('#pr_no').val();
		var department = $('#department').val();
		var from = $('#from').val();
		if(!pr_no  & !department & !from)
		{
			e.preventDefault();
		}
	});
</script>


@stop