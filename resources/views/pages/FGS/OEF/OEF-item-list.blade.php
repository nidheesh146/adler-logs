@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				 <span>Order Execution Form(OEF)</span>
				 <span><a href="">
				 	OEF Item List
				</a></span>
				 </div>
			<h4 class="az-content-title" style="font-size: 20px;">
            OEF Item List 
			
              <div class="right-button">
			  <button style="float: right;font-size: 14px;" class="badge badge-pill badge-info item-upload" style="font-size: 13px;" href="#" data-prId="{{$oef_id}}" data-type="Purchase"  data-toggle="modal" data-target="#uploadModal"><i class="fas fa-plus"></i> Upload</a>

                <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/OEF/add-item/'.$oef_id)}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> 
                OEF Item
				</button>
				
              <div>  
				
              </div>
          </div>
        </h4>	
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
												<div class="col-sm-5 col-md-5 col-lg-5 col-xl-5 row">
								
													<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
														<label>Product :</label>
														<input type="text" value="{{request()->get('product')}}" name="product" id="product" class="form-control" placeholder="PRODUCT">
													</div><!-- form-group -->
													
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
									<th>Product</th>
                                    <th>HSN Code</th>
									<th>Description</th>
									<th>Quantity</th>
									<th>OutStanding Quantity</th>
                                    <th>Rate</th>
                                    <th>Discount</th>
                                    <th>GST</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody id="prbody1">
								@foreach($items as $item)
								<tr>
									<td>{{$item['sku_code']}}</td>
									<td>{{$item['hsn_code']}}</td>
									<td>{{$item['discription']}}</td>
									<td>{{$item['quantity']}} Nos</td>
									<td>{{$item['remaining_qty_after_cancel']}} Nos</td>
									<td>{{$item['rate']}}</td>
									<td>{{$item['discount']}}%</td>
									<td>IGST:{{$item['igst']}}%<br/>
										CGST:{{$item['cgst']}}%<br/>
										SGST:{{$item['sgst']}}%
									</td>
									<td>
										@if($item['coef_status']==0)
										<span class="badge badge-primary" style="width:60px;">Active</span>
										@else
										<span class="badge badge-danger" style="width:60px;">Cancelled</span> 
										@endif
									</td>
								</tr> 
								@endforeach
							</tbody>
						</table>
						<div class="box-footer clearfix">
							
						</div>
					</div>
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
					<h4 class="modal-title">Upload <span id="type"></span> OEF ITEM<span id="pr_master"></span></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
							<form method="POST" id="commentForm" action="{{url('fgs/OEF/item-upload/'.$oef_id)}}" novalidate="novalidate" enctype='multipart/form-data'>
								{{ csrf_field() }}
								<div class="row">

									<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<label for="exampleInputEmail1">Select File*</label>
										<input type="file" required class="form-control file" name="file" id="file">
										<a href="{{ asset('uploads/oef.xlsx') }}" target="_blank" style="float: right; font-size: 10px;"> Download Template</a>
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
	
  });
  
	$('.search-btn').on( "click", function(e)  {
		var product = $('#pr_no').val();
		var batchnumber = $('#batchnumber').val();
		var manufaturing_from = $('#manufaturing_from').val();
		if(!pr_no  & !department & !from)
		{
			e.preventDefault();
		}
	});
	$(".item-upload").on("click", function() {
		// var type = $(this).data('type');
		// $('#type').html(type);
		// var pr_master = $(this).data('master');
		// $('#pr_master').html(' (' + pr_master + ')');
		var pr_id = $(this).data('prid');
		$('#pr_id').val(pr_id);
	});
</script>


@stop