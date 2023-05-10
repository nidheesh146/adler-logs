@extends('layouts.default')
@section('content')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				<span><a href="" style="color: #596881;">Customer-Supplier Master </a></span> 
                <span><a href="" style="color: #596881;">
                Customer-Supplier List
                </a></span>
			</div> 
			<h4 class="az-content-title" style="font-size: 20px;">Customer-Supplier List
			<button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/customer-supplier/add')}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> Customer-Supplier</button>
			 <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/customer-supplier/excel-export').'?'.http_build_query(array_merge(request()->all()))}}'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button>
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
										<form autocomplete="off" >
											<th scope="row">
												<div class="row filter_search" style="margin-left: 0px;">
													<div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 row">
														<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label>ENTITY NAME:</label>
															<input type="text" value="{{request()->get('firm_name')}}" name="firm_name"  id="firm_name" class="form-control" placeholder="ENTITY NAME">
														</div><!-- form-group -->
									
														<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label  style="font-size: 12px;">CONTACT PERSON</label>
															<input type="text" value="{{request()->get('contact_person')}}" id="contact_person" class="form-control" name="contact_person" placeholder="CONTACT PERSON">
														</div> 
														<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label  style="font-size: 12px;">SALES TYPE</label>
															<input type="text" value="{{request()->get('sales_type')}}"  class="form-control " name="sales_type" id="sales_type" placeholder="SALES TYPE" >
														</div> 
														<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
															<label  style="font-size: 12px;">STATE</label>
															 <select name="state" id="state" class="form-control">
                                    <option value="">-- Select one ---</option>
                                 @foreach ($states as $item)
                                        <option value="{{$item->state_id}}">{{$item->state_name}}</option>
                                    @endforeach
                                </select>
															


															<!-- <input type="text" value="{{request()->get('state')}}"  class="form-control " name="state" placeholder="STATE" > -->
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
				<div class="tab-pane tab-pane active  show" id="purchase">
					
					<div class="table-responsive">
						<table class="table table-bordered mg-b-0" >
							<thead>
								<tr>
									<th>Firm/Entity Name</th>
									<th>Contact Person </th>
									<th>Contact Number </th>
									<th>Email </th>
                                    <th>Sales Type</th>
                                    <th>GST Number</th>
									<th>PAN</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody  id="prbody1">
							@foreach($customers as $customer)
								<tr>
									<td>{{$customer['firm_name']}}</td>
									<td>{{$customer['contact_person']}}</td>
									<td>{{$customer['contact_number']}}</td>
									<td>{{$customer['email']}}</td>
									<td>{{$customer['sales_type']}}</td>
									<td>{{$customer['gst_number']}}</td>
									<td>{{$customer['pan_number']}}</td>
									<td>
									@if($customer['is_active']==1)
										<button data-toggle="dropdown" style="width: 64px;" class="badge badge-success"> Active <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
										<div class="dropdown-menu">
											<a href="{{url('fgs/customer-supplier/add?id='.$customer["id"])}}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> 
											<a href="{{url('fgs/customer-supplier/delete?id='.$customer["id"])}}" onclick="return confirm('Are you sure you want to delete this ?');" class="dropdown-item"><i class="fas fa-trash-alt"></i>  Delete</a> 
										</div>
									@endif
									</td>
									
								</tr>
								@endforeach
							</tbody>
						</table>
						<div class="box-footer clearfix">
						{{ $customers->appends(request()->input())->links() }}
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
		var firm_name = $('#firm_name').val();
		var contact_person = $('#contact_person').val();
		var sales_type = $('#sales_type').val();
		var state = $('#state').val();
		if(!firm_name & !contact_person & !sales_type & !state)
		{
			e.preventDefault();
		}
	});

</script>


@stop