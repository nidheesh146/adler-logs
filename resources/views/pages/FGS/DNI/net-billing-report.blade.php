@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				<span><a href="" style="color: #596881;">Net Billing Report </a></span> 
                <span><a href="" style="color: #596881;">
               
                </a></span>
			</div> 
			<h4 class="az-content-title" style="font-size: 20px;">Net Billing Report
			<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/net-billing-report/excel-export').'?'.http_build_query(array_merge(request()->all()))}}'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button>
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
												font-size:12px;
											}
										</style>
										<form autocomplete="off" id="formfilter">
											<th scope="row">
												<div class="row filter_search" style="margin-left: 0px;">
													<div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row">
                                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label  style="font-size: 12px;">Type</label>
															<select class="form-control" name="type" id="type">
                                                                <option value="all" @if(request()->get('type')=='all') selected @endif>All</option>
                                                                <option value="DNI" @if(request()->get('type')=='DNI') selected @endif >DNI</option>
                                                                <option value="EXI" @if(request()->get('type')=='EXI') selected @endif >EXI</option>
                                                            </select>
														</div> 
                                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label  style="font-size: 12px;">DNI/EXI Number</label>
															<input type="text" value="{{request()->get('dni_number')}}" id="dni_number" class="form-control " name="dni_number" placeholder="DNI/EXI NUMBER" >
														</div> 
														<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label>SKU Code</label>
															<input type="text" value="{{request()->get('sku_code')}}" name="sku_code"  id="sku_code" class="form-control" placeholder="SKU CODE">
														</div><!-- form-group -->
														
                                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="padding: 0 0 0px 6px;">
                                                            <label style="width: 100%;">&nbsp;</label>
                                                            <button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                            @if(count(request()->all('')) > 2)
                                                                <a href="{{url()->current()}}" class="badge badge-pill badge-warning"
                                                                style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
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
									<th>DNI/EXI Number</th>
									<th>SKU Code </th>
									<th  width='35%'>Description </th>
									<th>HSN Code</th>
                                    <th>BatchCard</th>
                                    <th>QTY</th>
                                    <th>Rate</th>
                                    <th>Discount</th>
                                    <th>GST</th>
									<th>DNI/EXI Date </th>
                                    <th>Customer</th>
                                    <th>Zone</th>
								</tr>
							</thead>
							<tbody id="prbody1">
                                @foreach($dni_items as $item)
                                <tr>
                                    <td>{{$item['dni_number']}}</td>
                                    <td>{{$item['sku_code']}}</td>
                                    <td >{{$item['discription']}}</td>
                                    <td>{{$item['hsn_code']}}</td>
                                    <td>{{$item['batch_no']}}</td>
                                    <td>{{$item['remaining_qty_after_cancel']}}Nos</td>
                                    <td>{{$item['rate']}}</td>
                                    <td>{{$item['discount']}}%</td>
                                    <td>IGST:{{$item['igst']}}%<br/>SGST:{{$item['sgst']}}%<br/>CGST:{{$item['cgst']}}%</td>
                                    <td>{{$item['dni_date']}}</td>
                                    <td>{{$item['firm_name']}}</td>
                                    <td>{{$item['zone_name']}}</td>
                                </tr>
                                @endforeach
							</tbody>
						</table>
						<div class="box-footer clearfix">
                        {{ $dni_items->appends(request()->input())->links() }}               
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

    $('#prbody').show();
  });
  
	$('.search-btn').on( "click", function(e)  {
		var sku_code = $('#sku_code').val();
		var type = $('#type').val();
		var dni_number = $('#dni_number').val();
		if(!sku_code & !type & !dni_number)
		{
			e.preventDefault();
		}
	});

</script>


@stop