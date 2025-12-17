@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
	<br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb">
				<span><a href="" style="color: #596881;">Net booking-Billing Report </a></span>
				<span><a href="" style="color: #596881;">

					</a></span>
			</div>

			@include('includes.fgs.net-bk-billing-tab')
			<br><br>
			<h4 class="az-content-title" style="font-size: 20px;">Net billing Report
				<button style="float: right; font-size: 14px;" onclick="document.location.href='{{url('fgs/net-all-billing-report/export').'?'.http_build_query(array_merge(request()->all()))}}'" class="badge badge-pill badge-info">
					<i class="fas fa-file-excel"></i> Report
				</button>
			</h4>

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
									<form autocomplete="off" id="formfilter">
										<th scope="row">
											<div class="row filter_search" style="margin-left: 0px;">
												<div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row">

													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label>SKU Code</label>
														<input type="text" value="{{request()->get('sku_code')}}" name="sku_code" id="sku_code" class="form-control" placeholder="SKU CODE">
													</div><!-- form-group -->
													<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
														<label style="font-size: 12px;">Product Category</label>
														<select name="category_name" id="category_name" class="form-control">
															<option value="">-- Select one ---</option>
															@foreach ($fgs_product_category as $item)
															<option value="{{$item->id}}">{{$item->category_name}}</option>
															@endforeach
														</select>
													</div>
													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label style="font-size: 12px;">Month</label>
														<input type="text" value="{{request()->get('from')}}" id="from" class="form-control datepicker" name="from" placeholder="Month(MM-YYYY)">
													</div>
													<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="padding: 0 0 0px 6px;">
														<label style="width: 100%;">&nbsp;</label>
														<button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
														@if(count(request()->all('')) > 2)
														<a href="{{url()->current()}}" class="badge badge-pill badge-warning" style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
														@endif
													</div>


												</div>
												<div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">

												</div>
											</div>
										</th>
									</form>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="tab-pane tab-pane active  show" id="purchase">

					<div class="table-responsive">
						<table class="table table-bordered mg-b-0" id="example1">
							<thead>
								<tr>
									<th>Doc number</th>
									<th>SKU Code </th>
									<th width='35%'>Description </th>
									<th>HSN Code</th>
									<th>BatchCard</th>
									<th>QTY</th>
									{{--<th>Rate</th>
									<th>Discount</th>
									<th>GST</th>--}}
									<th>Date </th>
									<th>Customer</th>
									<th>Zone</th>
								</tr>
							</thead>
							<tbody id="prbody1">
								@foreach($oef_items as $oef)
								<tr>
									<td>{{$oef->oef_number}}</td>
									<td>{{$oef->sku_code}}</td>
									<td>{{$oef->discription}}</td>
									<td>{{$oef->hsn_code}}</td>
									<td></td>
									<td>{{$oef->quantity}}</td>
									{{--<td>{{$oef->rate}}</td>
									<td>{{$oef['discount']}}%</td>
									<td>IGST:{{$oef['igst']}}%<br />SGST:{{$oef['sgst']}}%<br />CGST:{{$oef['cgst']}}%</td>--}}
									<td>{{date('d-m-Y',strtotime($oef->oef_date))}}</td>
									<td>{{$oef->firm_name}}</td>
									<td>{{$oef->zone_name}}</td>

								</tr>
								@endforeach
								@foreach($cgrs_items as $cgrs)
								<tr>
									<td>{{$cgrs->cgrs_number}}</td>
									<td>{{$cgrs->sku_code}}</td>
									<td>{{$cgrs->discription}}</td>
									<td>{{$cgrs->hsn_code}}</td>
									<td>{{$cgrs->batch_no}}</td>
									<td>{{$cgrs->batch_quantity}}</td>
									{{--<td>{{$cgrs->rate}}</td>
									<td>{{$cgrs['discount']}}%</td>
									<td>IGST:{{$cgrs['igst']}}%<br />SGST:{{$cgrs['sgst']}}%<br />CGST:{{$cgrs['cgst']}}%</td>--}}
									<td>{{date('d-m-Y',strtotime($cgrs->cgrs_date))}}</td>
									<td>{{$cgrs->firm_name}}</td>
									<td>{{$cgrs->zone_name}}</td>

								</tr>
								@endforeach
								@foreach($cpi_items as $cpi)
								<tr>
									<td>{{$cpi->cpi_number}}</td>
									<td>{{$cpi->sku_code}}</td>
									<td>{{$cpi->discription}}</td>
									<td>{{$cpi->hsn_code}}</td>
									<td>{{$cpi->batch_no}}</td>
									<td>{{$cpi->batch_quantity}}</td>
									{{--<td>{{$cpi->rate}}</td>
									<td>{{$cpi['discount']}}%</td>
									<td>IGST:{{$cpi['igst']}}%<br />SGST:{{$cpi['sgst']}}%<br />CGST:{{$cpi['cgst']}}%</td>--}}
									<td>{{date('d-m-Y',strtotime($cpi->cpi_date))}}</td>
									<td>{{$cpi->firm_name}}</td>
									<td>{{$cpi->zone_name}}</td>

								</tr>
								@endforeach
								@foreach($dni_items as $dni)
								<tr>
									<td>{{$dni->dni_number}}</td>
									<td>{{$dni->sku_code}}</td>
									<td>{{$dni->discription}}</td>
									<td>{{$dni->hsn_code}}</td>
									<td></td>
									<td>{{$dni->quantity}}</td>
									{{--<td>{{$dni->rate}}</td>
									<td>{{$dni['discount']}}%</td>
									<td>IGST:{{$dni['igst']}}%<br />SGST:{{$dni['sgst']}}%<br />CGST:{{$dni['cgst']}}%</td>--}}
									<td>{{date('d-m-Y',strtotime($dni->dni_date))}}</td>
									<td>{{$dni->firm_name}}</td>
									<td>{{$dni->zone_name}}</td>

								</tr>
								@endforeach
								@foreach($exi_items as $exi)
								<tr>
									<td>{{$exi->dni_number}}</td>
									<td>{{$exi->sku_code}}</td>
									<td>{{$exi->discription}}</td>
									<td>{{$exi->hsn_code}}</td>
									<td></td>
									<td>{{$exi->quantity}}</td>
									{{--<td>{{$exi->rate}}</td>
									<td>{{$exi['discount']}}%</td>
									<td>IGST:{{$exi['igst']}}%<br />SGST:{{$exi['sgst']}}%<br />CGST:{{$exi['cgst']}}%</td>--}}
									<td>{{date('d-m-Y',strtotime($exi->dni_date))}}</td>
									<td>{{$exi->firm_name}}</td>
									<td>{{$exi->zone_name}}</td>

								</tr>
								@endforeach
								@foreach($srn_items as $srn)
								<tr>
									<td>{{$srn->srn_number}}</td>
									<td>{{$srn->sku_code}}</td>
									<td>{{$srn->discription}}</td>
									<td>{{$srn->hsn_code}}</td>
									<td></td>
									<td>{{$srn->quantity}}</td>
									{{--<td>{{$srn->rate}}</td>
									<td>{{$srn['discount']}}%</td>
									<td>IGST:{{$srn['igst']}}%<br />SGST:{{$srn['sgst']}}%<br />CGST:{{$srn['cgst']}}%</td>--}}
									<td>{{date('d-m-Y',strtotime($srn->srn_date))}}</td>
									<td>{{$srn->firm_name}}</td>
									<td>{{$srn->zone_name}}</td>

								</tr>
								@endforeach
							</tbody>
						</table>
						<div class="box-footer clearfix">
							{{-- $dni_items->appends(request()->input())->links() --}}
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

		$('#prbody').show();
	});

	$('.search-btn').on("click", function(e) {
		var sku_code = $('#sku_code').val();
		var from = $('#from').val();
		var category_name = $('#category_name').val();
		if (!sku_code & !from & !category_name) {
			e.preventDefault();
		}
	});
</script>


@stop