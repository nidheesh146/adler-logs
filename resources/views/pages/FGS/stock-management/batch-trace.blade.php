@extends('layouts.default')
@section('content')
@inject('fn', 'App\Http\Controllers\Web\FGS\StockManagementController')
<div class="az-content az-content-dashboard">
	<br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb">
				<span>BATCH TRACE REPORT</span>
				<span><a href="">
						
					</a></span>
			</div>
			
			<br><br>
			<h4 class="az-content-title" style="font-size: 20px;">
				Batch Trace Report
				<div class="right-button">
					<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/batch-trace-report/export').'?'.http_build_query(array_merge(request()->all()))}}'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button>
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

													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label>SKU Code :</label>
														<input type="text" value="{{request()->get('sku_code')}}" name="sku_code" id="sku_code" class="form-control" placeholder="SKU CODE">
													</div><!-- form-group -->


													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label for="exampleInputEmail1" style="font-size: 12px;">Batch No:</label>
														<input type="text" value="{{request()->get('batch_no')}}" name="batch_no" id="batch_no" class="form-control" placeholder="BATCH NO">
													</div>
													<div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 row">
														<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
															<label style="width: 100%;">&nbsp;</label>
															<button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
															@if(count(request()->all('')) > 1)
															<a href="{{url()->current()}}" class="badge badge-pill badge-warning" style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
															@endif
														</div>
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
									<th colspan="6"></th>
									<th colspan="2">MRN</th>
									<th colspan="2">GRS </th>
                                    <th colspan="2">PI </th>
                                    <th colspan="2">DNI/EXI </th>
								</tr>
								<tr>
									<th>SKU CODE</th>
									<th>HSN CODE</th>
									<th>DESCRIPTION</th>
									<th>BATCH NO</th>
                                    <th>DATE OF MFG.</th>
                                    <th>DATE OF EXPIRY</th>
									<th>MRN NUMBER</th>
									<th>MRN QTY</th>
									<th>GRS NUMBER</th>
									<th>GRS QTY</th>
									<th>PI NUMBER</th>
									<th>PI QTY</th>
									<th>DNI/EXI NUMBER</th>
                                    <th>DNI/EXI QTY</th>
								</tr>
							</thead>
							<tbody id="prbody1">
								@foreach($mrn_item as $item)
								<tr>
									<td>{{$item['sku_code']}}</td>
									<td>{{$item['hsn_code']}}</td>
									<td>{{$item['discription']}}</td>
									<td>{{$item['batch_no']}}</td>
									<td>{{date('d-m-Y', strtotime($item['manufacturing_date']))}}</td>
                                    <td>@if($item['expiry_date']!='0000-00-00') {{date('d-m-Y', strtotime($item['expiry_date']))}} @else NA  @endif</td>
                                    <td>{{$item['mrn_number']}}</td>
									<td>{{$item['quantity']}}Nos</td>
									<td>{{$item['grs_number']}}</td>
									<td>@if($item['grs_number']) {{$item['grs_qty']}}Nos @endif</td>
									<td>{{$item['pi_number']}}</td>
									<td>@if($item['pi_number']) {{$item['pi_qty']}}Nos @endif</td>
									<td>{{$item['dni_number']}}</td>
									<td>@if($item['dni_number']) {{$item['pi_qty']}}Nos @endif</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						<div class="box-footer clearfix">
						{{ $mrn_item->appends(request()->input())->links() }}
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
		var batch_no = $('#batch_no').val();
		var sku_code = $('#sku_code').val();
		if (!oef_no & !grs_no & !pi_no) {
			e.preventDefault();
		}
	});
</script>


@stop