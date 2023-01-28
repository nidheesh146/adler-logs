@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				<span><a href="" style="color: #596881;">BATCHCARD</a></span> 
                <span><a href="" style="color: #596881;">
                Quantity Update Request List
                </a></span>
			</div>
			<h4 class="az-content-title" style="font-size: 20px;">Quantity Update Request List
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
															<label>Batch No:</label>
															<input type="text" value="{{request()->get('batch_no')}}" name="batch_no"  id="batch_no" class="form-control" placeholder="BATCH NO">
														</div><!-- form-group -->
									
														<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label  style="font-size: 12px;">SKU Code</label>
															<input type="text" value="{{request()->get('sku_code')}}" id="sku_code" class="form-control" name="sku_code" placeholder="SKU Code">
														</div> 
														<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label  style="font-size: 12px;">Item Code</label>
															<input type="text" value="{{request()->get('item_code')}}"  class="form-control " name="item_code" placeholder="Item Code" >
														</div>
                                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                            <label  style="font-size: 12px;">Status</label>
                                                            <!-- <input type="text" value="{{request()->get('from')}}" id="from" class="form-control datepicker" name="from" placeholder="@if(request()->get('order_type')=='work-order') WO @else PO @endif DATE (MM-YYYY)"> -->
                                                            <select name="status" id="status" class="form-control">
                                                                <option value=""> --Select One-- </option>
                                                                <!-- <option value="1" {{(request()->get('status') == 1) ? 'selected' : ''}}> Active </option> -->
                                                                <option value="2" {{(request()->get('status') == 2) ? 'selected' : ''}}> Pending</option>
                                                                <option value="1"{{(request()->get('status') == 1) ? 'selected' : ''}}>Approved</option>
                                                                <option value="reject" {{(request()->get('status') == 'reject') ? 'selected' : ''}}> Rejected </option>
                                                            </select>
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
									<th width="13%">Batch No</th>
									<th>Product SKU Code </th>
									<th>Actual SKU Quantity</th>
                                    <th>Requested SKU Quantity</th>
									<th>Item Code</th>
									<th>Actual Quantity</th>
									<th>Requested Actual Quantity</th>
                                    <th width="12%">Request Date</th>
                                    <th>Status</th>
                                    @if(request()->get('status') != 'reject' && request()->get('status') != 1)
									<th> Action</th>
                                    @endif
								</tr>
							</thead>
							<tbody >
							@foreach($data['requests'] as $request)
                        <tr>
                            <td>{{$request['batch_no']}} 
                                @php $today = strtotime('now'); @endphp
                                @if($request['status']==2 && date('d-m-Y', $today) == date('d-m-Y', strtotime($request['created_at'])))
                                <span class="badge badge-warning">New</span>
                                @endif
                            </td>
                            <td>{{$request['sku_code']}}</td>
                            <td>{{$request['sku_qty']}}</td>
                            <td>{{$request['sku_qty_to_be_update']}}</td>
                            <td>{{$request['item_code']}}</td>
                            <td>{{$request['material_qty']}} {{$request['unit_name']}}</td>
                            <td>{{$request['material_qty_to_be_update']}} {{$request['unit_name']}}</td>
                            <td>{{date('d-m-Y', strtotime($request['created_at']))}}</td>
                            <td>
                                @if($request['status'] == 0)
                                <span class="badge badge-danger">Rejected</span>
                                @elseif($request['status'] == 1)
                                <span class="badge badge-primary">Approved</span>
                                @elseif($request['status'] == 2)
                                <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            @if(request()->get('status') != 'reject' && request()->get('status') != 1)
							<td>
								@if($request['status']==2)
									<a href="{{url('batchcard/quantity-update/approve?id='.$request["request_id"])}}" onclick="return confirm('Are you sure you want to approve this request ?');" class="badge badge-success"><i class="fa fa-check"></i>  Approve</a> 
                                    <a href="{{url('batchcard/quantity-update/reject?id='.$request["request_id"])}}" onclick="return confirm('Are you sure you want to reject this request ?');" class="badge badge-danger"><i class="fas fa-trash-alt"></i>  Reject</a> 
								@endif
							</td>
                            @endif
                        </tr>
                        @endforeach
							</tbody>
						</table>
						<div class="box-footer clearfix">
							{{ $data['requests']->appends(request()->input())->links() }}
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