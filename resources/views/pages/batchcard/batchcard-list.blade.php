@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				<span><a href="" style="color: #596881;">BATCHCARD</a></span> 
                <span><a href="" style="color: #596881;">
                BATCHCARD LIST
                </a></span>
			</div>
			<h4 class="az-content-title" style="font-size: 20px;">Batchcard List
			<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('batchcard/batchcard-add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Batchcatd</button>
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
														<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
															<label>Batch No:</label>
															<input type="text" value="{{request()->get('batch_no')}}" name="batch_no"  id="batch_no" class="form-control" placeholder="BATCH NO">
														</div><!-- form-group -->
									
														<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
															<label  style="font-size: 12px;">SKU Code</label>
															<input type="text" value="{{request()->get('sku_code')}}" id="sku_code" class="form-control" name="sku_code" placeholder="SKU Code">
														</div> 
														<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
															<label  style="font-size: 12px;">Process Sheet</label>
															<input type="text" value="{{request()->get('process_sheet')}}"  class="form-control " name="process_sheet" placeholder="Process Sheet" >
														</div> 		
																			
													</div>
													<div class="col-sm-2 col-md-2 col-lg-2 col-xl-2" style="padding: 0 0 0px 6px;">
														<!-- <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;"> -->
															<label style="width: 100%;">&nbsp;</label>
															<button type="submit" class="badge badge-pill badge-primary search-btn" 
															onclick="document.getElementById('formfilter').submit();"
															style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
															@if(count(request()->all('')) > 1)
																<a href="{{url()->current();}}" class="badge badge-pill badge-warning"
																style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
															@endif
														<!-- </div>  -->
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
									<th>Batch No</th>
									<th>Product SKU Code </th>
									<th>SKU Quantity</th>
									<th>Start Date</th>
									<th>End Date </th>
									<th>Process Sheet No</th>
									<th>Input Material & Quantity</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody >
							@foreach($data['batchcards'] as $card)
                        <tr>
                            <td>{{$card['batch_no']}}</td>
                            <td>{{$card['sku_code']}}</td>
                            <td>{{$card['quantity']}}</td>
                            <td>{{$card['start_date']}}</td>
                            <td>{{$card['target_date']}}</td>
                            <td>{{$card['process_sheet_id']}}</td>
                            <td>@foreach($card['material'] as $material)
                                <span>{{$material['item_code']}}</span> -
                                <span>{{$material['quantity']}}{{$material['unit_name']}}</span><br/>
                                @endforeach
                            </td>
							<td>
								@if($card['is_active']==1)
								<button data-toggle="dropdown" style="width: 64px;" class="badge badge-success"> Active <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
								<div class="dropdown-menu">
									<a href="{{url('batchcard/edit?id='.$card["id"])}}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> 
									<a href="{{url('batchcard/delete?id='.$card["id"])}}" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i>  Delete</a> 
								</div>
								@endif
								<a class="badge badge-default" style="font-size: 13px; color:black;border:solid black;border-width:thin;margin-top:2px;" href="{{url('batchcard/batchcard-list/'.$card['id'].'/report')}}" target="_blank"><i class="fas fa-file-pdf" style='color:red'></i>&nbsp;PDF</a>
							</td>
                        </tr>
                        @endforeach
							</tbody>
						</table>
						<div class="box-footer clearfix">
							{{ $data['batchcards']->appends(request()->input())->links() }}
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

    //$('#prbody').show();
  });
  
	$('.search-btn').on( "click", function(e)  {
		//var supplier = $('#supplier').val();
		var rq_no = $('#rq_no').val();
		var po_no = $('#po_no').val();
		var from = $('#from').val();
		if(!rq_no & !po_no & !from)
		{
			e.preventDefault();
		}
	});

</script>


@stop