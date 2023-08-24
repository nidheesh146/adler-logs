@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				 <span>Material Receipt Note(MRN)</span>
				 <span><a href="">
				 	MRN Item List
				</a></span>
				 </div>
			<h4 class="az-content-title" style="font-size: 20px;">
            MRN Item List 
              <div class="right-button">
				@if($product_cat->product_category==3)
                <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/MRN/add-item-trade/'.$mrn_id)}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> 
						MRN Item
				</button>
				@else
				<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/MRN/add-item/'.$mrn_id)}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> 
						MRN Item
				</button>
				@endif
				<button style="float: right;font-size: 14px;" class="badge badge-pill badge-info item-upload" style="font-size: 13px;" href="#" mrnid="{{$mrn_id}}" data-toggle="modal" data-target="#uploadModal"><i class="fas fa-plus"></i> Upload</a>
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
												<div class="col-sm-10 col-md- col-lg-10 col-xl-10 row">
								
													<div class="form-group col-sm-12 col-md-3 col-lg- col-xl-4">
														<label>Product :</label>
														<input type="text" value="{{request()->get('product')}}" name="product" id="product" class="form-control" placeholder="PRODUCT">
													</div><!-- form-group -->
													
													
													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label for="exampleInputEmail1" style="font-size: 12px;">Batch No</label>
														<input type="text" value="{{request()->get('batchnumber')}}" name="batchnumber" id="batchnumber" class="form-control" placeholder="BATCH NO">
													</div>
													
													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label  style="font-size: 12px;">Manufacturing Month</label>
														<input type="text" value="{{request()->get('manufaturing_from')}}" id="manufaturing_from" class="form-control datepicker" name="manufaturing_from" placeholder="Month(MM-YYYY)">
													</div>
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
									<th>Batch No.</th>
									<th>Qty.</th>
									<th>UOM</th>
                                    <th>Date of Mfg.</th>
                                    <th>Date of Expiry</th>
									<th>Action</th>
								</tr>
							</thead>
							
							<tbody id="prbody1">
								<?php $qty=0; ?>
								@foreach($items as $item)
                                <tr>
									<?php $qty=$qty+$item['quantity'];
									 ?>
									 
									<td>{{$item['sku_code']}}</td>
                                    <td>{{$item['hsn_code']}}</td>
									<td>{{$item['discription']}}</td>
									<td>{{$item['batch_no']}}</td>
									<td>{{$item['quantity']}}</td>
									<td>Nos</td>
                                    <td width="10%">{{date('d-m-Y', strtotime($item['manufacturing_date']))}}</td>
                                    <td width="10%">@if($item['expiry_date']!='0000-00-00') {{date('d-m-Y', strtotime($item['expiry_date']))}} @else NA  @endif</td>
									<td>
									{{--<a class="badge badge-info" style="font-size: 13px;" href="{{url('fgs/MRN-item-edit/'.$item['id'])}}"  class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> --}}
                            		<a class="badge badge-danger" style="font-size: 13px;" href="{{url('fgs/MRN-item-delete/'.$item['id'])}}" onclick="return confirm('Are you sure you want to delete this ?');"><i class="fa fa-trash"></i> Delete</a>
									</td>
								</tr>
								@endforeach
								
							</tbody>
						</table>
						<div class="box-footer clearfix">
						{{ $items->appends(request()->input())->links() }}
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
					<h4 class="modal-title">Upload  MRN Items<span id="mrn_number"></span></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
							<form method="POST" id="commentForm" action="{{url('fgs/MRN/item-upload')}}" novalidate="novalidate" enctype='multipart/form-data'>
								{{ csrf_field() }}
								<div class="row">
									<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<label for="exampleInputEmail1">Product Category</label>
										<input type="text" required class="form-control" name="product_category" id="product_category">
										<input type="hidden" name="mrn_id" id="mrn_id" value="">
									</div>
									<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<label for="exampleInputEmail1">Stock Location</label>
										<input type="text" required class="form-control" name="stock_location" id="stock_location">
									</div>
									<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<label for="exampleInputEmail1">Select File*</label>
										<input type="file" required class="form-control file" name="file" id="file">
										<a href="{{ asset('uploads/FGS_mrn_item_sample.xlsx') }}" target="_blank" style="float: right; font-size: 10px;"> Download Template</a>
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
	</div> <!-- model -->
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
		var mrn_number = $(this).data('MRNNumber');
		var mrn_id = $(this).attr('mrnid');
		$('#mrn_id').val(mrn_id);
		$.get("{{ url('fgs/fetchMRNInfo') }}?mrn_id="+mrn_id,function(data)
        {
			$('#mrn_number').html(' (' + data.mrn_number + ')');
			$('#product_category').val(data.category_name);
			$('#stock_location').val(data.location_name);
		});
	});
</script>


@stop