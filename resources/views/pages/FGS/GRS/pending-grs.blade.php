@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
	<br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb">
				<span>Goods Reservation Slip(GRS)</span>
				<span><a href="">
						GRS - Back Order Report
					</a></span>
			</div>
			@include('includes.fgs.back-order-tab')
			<br><br>
			<h4 class="az-content-title" style="font-size: 20px;">
				GRS - Back Order Report
				<div class="right-button">
					<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/GRS/pending-GRS-export').'?'.http_build_query(array_merge(request()->all()))}}'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button>
				</div>
			</h4>
			@if(Session::get('error'))
			<div class="alert alert-danger " role="alert" style="width: 100%;">
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
											font-size: 12px;
										}
									</style>
									<form autocomplete="off">
										<th scope="row">
											<div class="row filter_search" style="margin-left: 0px;">
												<div class="col-sm-10 col-md- col-lg-10 col-xl-10 row">

													<div class="form-group col-sm-12 col-md-3 col-lg- col-xl-4">
														<label>GRS No :</label>
														<input type="text" value="{{request()->get('grs_no')}}" name="grs_no" id="grs_no" class="form-control" placeholder="GRS NO">
													</div><!-- form-group -->


													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label for="exampleInputEmail1" style="font-size: 12px;">Order No</label>
														<input type="text" value="{{request()->get('order_no')}}" name="order_no" id="order_no" class="form-control" placeholder="Order NO">
													</div>

													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label style="font-size: 12px;">GRS Month</label>
														<input type="text" value="{{request()->get('from')}}" id="from" class="form-control datepicker" name="from" placeholder="Month(MM-YYYY)">
													</div>
												</div>
												<div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
													<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
														<label style="width: 100%;">&nbsp;</label>
														<button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
														@if(count(request()->all('')) > 2)
														<a href="{{url()->current()}}" class="badge badge-pill badge-warning" style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
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
						<table class="table table-bordered mg-b-0">
							<thead>
								<tr>
									<th>Doc Date</th>
									<th>Doc No</th>
									<th>Customer</th>
									<th>Order No</th>
									<th>Order Date</th>
									<th>SKU Code</th>
									<th>Description</th>
									<th>Category</th>
									<th>Pending Qty</th>
									<th>Pending Value</th>
								</tr>
							</thead>
							<tbody id="prbody1">
								@foreach($grs_items as $item)
								<?php
								if($item['expiry_date']=='0000-00-00') 
								$expiry = 'NA'; 
								else 
								$expiry = date('d-m-Y',strtotime($item['expiry_date']));
								if($item->rate)
								{
									$total_rate = $item['remaining_qty_after_cancel']*$item['rate'];
									$discount_value = $total_rate*$item['discount']/100;
									$discounted_value = $total_rate-$discount_value;
									$igst_value = $total_rate*$item['igst']/100;
									$sgst_value = $total_rate*$item['sgst']/100;
									$cgst_value = $total_rate*$item['cgst']/100;
									$total_value = $discounted_value+$igst_value+$cgst_value+$sgst_value;
									
								}
								else
									{
										$total_rate =0;
										$total_value =0;
									}
								?>
								<tr>
									<td>{{date('d-m-Y', strtotime($item['grs_date']))}}</td>
									<td>{{$item['grs_number']}}</td>
									<td>{{$item['firm_name']}}</td>
									<td>{{$item['order_number']}}</td>
									<td>{{date('d-m-Y', strtotime($item['order_date']))}}</td>
									<td>{{$item['sku_code']}}</td>
									<td>{{$item['discription']}}</td>
									<td>{{$item['category_name']}}</td>
									<td>{{$item['remaining_qty_after_cancel']}} Nos</td>
									<td>{{(number_format((float)($total_rate), 2, '.', ''))}}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						<div class="box-footer clearfix">
							{{ $grs_items->appends(request()->input())->links() }}
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
<script src="<?= url(''); ?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"> </script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>

<script>
	$(function() {
		'use strict'
		var date = new Date();
		date.setDate(date.getDate());
		$(".datepicker").datepicker({
			format: "mm-yyyy",
			viewMode: "months",
			minViewMode: "months",
			// startDate: date,
			autoclose: true
		});
		$('#prbody1').show();
		$('#prbody2').show();
	});
	$('.search-btn').on("click", function(e) {
		var grs_no = $('#grs_no').val();
		var order_no = $('#order_no').val();
		var from = $('#from').val();
		if (!grs_no & !order_no & !from) {
			e.preventDefault();
		}
	});
</script>


@stop