@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				<span><a href="" style="color: #596881;">ROW MATERIAL</a></span> 
                <span><a href="" style="color: #596881;">
                FIXED RATE ROW MATERIAL 
                </a></span>
			</div>
			<h4 class="az-content-title" style="font-size: 20px;">Fixed Rate Row Materials
			<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('row-material/add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Row Material</button>
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
															<label>Item Code:</label>
															<input type="text" value="{{request()->get('item_code')}}" name="item_code"  id="item_code" class="form-control" placeholder="ITEM CODE">
														</div><!-- form-group -->
									
														<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label  style="font-size: 12px;">Type1</label>
															<input type="text" value="{{request()->get('type1')}}" id="type1" class="form-control" name="type1" placeholder="TYPE1">
														</div> 
														<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label  style="font-size: 12px;">Type2</label>
															<input type="text" value="{{request()->get('type2')}}"  class="form-control " name="type2" placeholder="TYPE2" >
														</div> 
														<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label  style="font-size: 12px;">Origin</label>
															<input type="text" value="{{request()->get('origin')}}"  class="form-control " name="origin" placeholder="ORIGIN" >
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
									<th>Item Code </th>
									<th>Supplier </th>
									<th>Rate </th>
									<th>Rate Expiry Start Date</th>
									<th>Rate Expiry End Date</th>
									<th>GST</th>
									<th>Discount</th>
                                    <th>Delivery Within</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody >
							@foreach($data['items'] as $item)
                        <tr>
                            <td>{{$item['item_code']}}</td>
                            <td>{{$item['vendor_name']}}</td>
                            <td>{{$item['rate']}}</td>
                            <td>{{$item['rate_expiry_startdate'] ? date('d-m-Y',strtotime($item['rate_expiry_startdate'])) : '-'}}</td>
                            <td>{{$item['rate_expiry_enddate'] ? date('d-m-Y',strtotime($item['rate_expiry_enddate'])) : '-'}}</td>
                            <td>@if($item['gst']==NULL)
                                -
                                @else
                                    @if($item['igst']!=0)
                                    IGST:{{$item['igst']}}%
                                    &nbsp;
                                    @endif
                                    
                                    @if($item['sgst']!=0)
                                    SGST:{{$item['sgst']}}%,
                                    &nbsp;
                                    @endif
                                    
                                    @if($item['sgst']!=0)
                                    CGST:{{$item['sgst']}}%
                                    @endif
                                @endif
                            </td>
                            <td>{{$item['discount'] ? $item['discount'] : '-'}}</td>
                            <td>{{$item['delivery_within']}} Days</td>
							<td>
                                <a href="{{url('row-material/edit?id='.$item["id"])}}" class="badge badge-success"><i class="fas fa-edit"></i> Edit</a> 
							</td>
                        </tr>
                        @endforeach
							</tbody>
						</table>
						<div class="box-footer clearfix">
							{{ $data['items']->appends(request()->input())->links() }}
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