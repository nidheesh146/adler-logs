@extends('layouts.default')
@section('content')
@inject('fn', 'App\Http\Controllers\Web\FGS\PIController')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				 <span>Proforma Invoice(PI)</span>
				 <span><a href="">
				 	PI  List
				</a></span>
				 </div> 
			<h4 class="az-content-title" style="font-size: 20px;">
                PI List 
              <div class="right-button">
                <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/PI-add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> 
						PI
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
												<div class="col-sm-10 col-md- col-lg-10 col-xl-10 row">
								
												<div class="form-group col-sm-12 col-md-3 col-lg- col-xl-4">
														<label>PI No :</label>
														<input type="text" value="{{request()->get('pi_number')}}" name="pi_number" id="pi_number" class="form-control" placeholder="PI NO">
													</div><!-- form-group -->
													
													
													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label for="exampleInputEmail1" style="font-size: 12px;">Customer</label>
														<input type="text" value="{{request()->get('customer')}}" name="customer" id="customer" class="form-control" placeholder="CUSTOMER">
													</div>
													
													<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
														<label  style="font-size: 12px;">PI Month</label>
														<input type="text" value="{{request()->get('from')}}" id="from" class="form-control datepicker" name="from" placeholder="Month(MM-YYYY)">
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
					<a class="badge badge-success" style="float:right;font-size: 13px; color:white;border:solid black;border-width:thin;margin-top:2px;"><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;Email</a><span style="float:right;">&nbsp;&nbsp;Sent Mail : </span>
					&nbsp;&nbsp;
					<a class="badge badge-default" style="float:right;font-size: 13px; color:white;background:blue;border:solid black;border-width:thin;margin-top:2px;"><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;Email</a><span style="float:right;">Not Sent Mail : </span>

					<div class="table-responsive">
						<table class="table table-bordered mg-b-0" >
							<thead>
								<tr>
									<th>PI Number</th>
                                    <th>PI Date</th>
									<th>GRS Number</th>
                                    <th>GRS Date</th>
									<th>Busines category</th>
									<th>Product category</th>
									<th>OEF Number</th>
                                    <th>OEF Date</th>
									<th>Order Number</th>
                                    <th>Order Date</th>
									<th>Customer</th>
									<th>Merged PI Number</th>
                                    <th>Action</th>
								</tr>
							</thead>
							<tbody id="prbody1">
							@foreach($pi as $item)
                                <tr>
									
									<td>{{$item['pi_number']}}</td>
									<td>{{date('d-m-Y', strtotime($item['pi_date']))}}</td>
									<?php $grs_info = $fn->getGRSInfo($item['id']); ?>	
									<td>
										@foreach($grs_info as $grs)
										{{$grs['grs_number']}}<br/>
										@endforeach
									</td>
									<td>@foreach($grs_info as $grs)
										{{date('d-m-Y', strtotime($grs['grs_date']))}}<br/>
										@endforeach
									</td>
									<td>@foreach($grs_info as $grs)
										{{ $grs['category_name'] }}<br/>
										@endforeach
									</td>
									<td>@foreach($grs_info as $grs)
										{{ $grs['new_category_name'] }}<br/>
										@endforeach
									</td>
									<?php $oef_info = $fn->getOEFInfo($item['id']); ?>	
									<td>@foreach($oef_info as $oef)
										{{$oef['oef_number']}}<br/>
										@endforeach
									</td>
									<td>
										@foreach($oef_info as $oef)
										{{date('d-m-Y', strtotime($oef['oef_date']))}}<br/>
										@endforeach
									</td>
									<td>
										@foreach($oef_info as $oef)
										{{$oef['order_number']}}<br/>
										@endforeach
									</td>
									<td>
										@foreach($oef_info as $oef)
										{{date('d-m-Y', strtotime($oef['order_date']))}}<br/>
										@endforeach
									</td>
									<td>{{$item['firm_name']}}<br/>
										Contact Person:{{$item['contact_person']}}<br/>
										Contact Number:{{$item['contact_number']}}<br/>
									</td>
									<td>{{$item['merged_pi_name']}}</td>
                                    <td>
										<a class="badge badge-info" style="font-size: 13px;" href="{{url('fgs/PI/item-list/'.$item["id"])}}"  class="dropdown-item"><i class="fas fa-eye"></i> Item</a> 
										<a class="badge badge-default" style="font-size: 13px; color:black;border:solid black;border-width:thin;margin-top:2px;" href="{{url('fgs/PI/pdf/'.$item["id"])}}" target="_blank"><i class="fas fa-file-pdf" style='color:red'></i>&nbsp;PDF</a>	
										<a class="badge badge-default" style="font-size: 13px; color:black;border:solid black;border-width:thin;margin-top:2px;" href="{{url('fgs/PI/payment-pdf/'.$item["id"])}}" target="_blank"><i class="fas fa-file-pdf" style='color:red'></i>&nbsp;Payment</a>
										@if($item['is_mail_sent']==1)
										<a class="badge badge-success" style="font-size: 13px;color:white;border:solid black;border-width:thin;margin-top:2px;" href="{{url('fgs/PI/payment-mail/'.$item["id"])}}"><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;Email</a>
										@else
										<a class="badge badge-default" style="font-size: 13px;background:blue; color:white;border:solid black;border-width:thin;margin-top:2px;" href="{{url('fgs/PI/payment-mail/'.$item["id"])}}"><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;Email</a>
										@endif	
										<?php $is_exist_in_dni = $fn->piExistInDNI($item['id']);?>
										@if($is_exist_in_dni==1)
										<a class="badge badge-primary" style="font-size: 13px;" onclick="return confirm('Cannot edit PI . It moved to next step!');"><i class="fa fa-edit"></i> Edit</a>
										<a class="badge badge-danger" style="font-size: 13px;" onclick="return confirm('Cannot delete PI . It moved to next step!');"><i class="fa fa-trash"></i> Delete</a>
										@else
										<a class="badge badge-primary" style="font-size: 13px;" href="{{url('fgs/PI-edit/'.$item['id'])}}"><i class="fa fa-edit"></i> Edit</a>
										<a class="badge badge-danger" style="font-size: 13px;"  href="{{url('fgs/PI-delete/'.$item['id'])}}"><i class="fa fa-trash"></i> Delete</a>
										@endif
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						<div class="box-footer clearfix">
                        {{ $pi->appends(request()->input())->links() }}
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
	$('#prbody1').show();
	$('#prbody2').show();
  });
	$('.search-btn').on( "click", function(e)  {
		var pi_number = $('#pi_number').val();
		var customer = $('#customer').val();
		var from = $('#from').val();
		if(!pi_number   & !customer & !from)
		{
			e.preventDefault();
		}
	});
</script>


@stop