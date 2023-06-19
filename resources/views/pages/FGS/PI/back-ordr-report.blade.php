@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
	<br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb">
				<span>Proforma Invoice(PI)</span>
				<span><a href="">
						All - Back Order Report
					</a></span>
			</div>
			@include('includes.fgs.back-order-tab')
			<br><br>
			<h4 class="az-content-title" style="font-size: 20px;">
				Back Order Report - All
				<div class="right-button">
					<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/all/export').'?'.http_build_query(array_merge(request()->all()))}}'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button>
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
														<label>OEF No :</label>
														<input type="text" value="{{request()->get('oef_no')}}" name="oef_no" id="oef_no" class="form-control" placeholder="OEF NO">
													</div><!-- form-group -->


													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label for="exampleInputEmail1" style="font-size: 12px;">GRS No:</label>
														<input type="text" value="{{request()->get('grs_no')}}" name="grs_no" id="grs_no" class="form-control" placeholder="GRS NO">
													</div>

													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label style="font-size: 12px;">PI No:</label>
														<input type="text" value="{{request()->get('pi_no')}}" id="pi_no" class="form-control" name="pi_no" placeholder="PI NO">
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
								<tr style="text-align:center;">
									<th colspan="4">OEF</th>
									<th colspan="5">GRS</th>
									<th colspan="2">PI </th>

								</tr>
								<tr>
									<th>OEF NUMBER</th>
									<th>OEF DATE</th>
									<th>ORDER NUMBER</th>
									<th>CUSTOMER INFO</th>

									<th>GRS NUMBER</th>
									<th>GRS DATE</th>
									<th>PRODUCT CATEGORY</th>
									<th>STOCK LOCATION(DECREASE)</th>
									<th>STOCK LOCATION(INCREASE)</th>

									<th>PI NUMBER</th>
									<th>PI DATE</th>

									
								</tr>
							</thead>
							<tbody id="prbody1">
								@foreach($data as $info)
								<tr>
									<td>{{$info['oef_number']}}</td>
									<td>@if($info['oef_date']) {{date('d-m-Y', strtotime($info['oef_date']))}} @endif</td>
									<td>{{$info['order_number']}}</td>
									<td>{{$info['firm_name']}}</td>
									<td>{{$info['grs_number']}}</td>
									<td>@if($info['grs_date']) {{date('d-m-Y', strtotime($info['grs_date']))}} @endif</td>
									<td>{{$info['category_name']}}</td>
									<td>{{$info['location_name1']}}</td>
									<td>{{$info['location_name2']}}</td>
									<td>{{$info['pi_number']}}</td>
									<td>@if($info['pi_date'])  {{date('d-m-Y', strtotime($info['pi_date']))}} @endif</td>
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
		var oef_no = $('#oef_no').val();
		var grs_no = $('#grs_no').val();
		var pi_no = $('#pi_no').val();
		if (!oef_no & !grs_no & !pi_no) {
			e.preventDefault();
		}
	});
</script>


@stop