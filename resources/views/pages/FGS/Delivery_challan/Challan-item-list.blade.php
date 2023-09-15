@extends('layouts.default')
@section('content')
@inject('fn', 'App\Http\Controllers\Web\FGS\GRSController')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				 <span>Delivery Challan Item List</span>
				 <span><a href="">
                 Delivery Challan Item List
				</a></span>
				 </div>
			<h4 class="az-content-title" style="font-size: 20px;">
            Delivery Challan Item List
              <div class="right-button">
              <div>  
            </h4>	
            <div class="form-devider"></div>
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
										
									</form>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
			
			
					<div class="tab-pane  active  show " id="purchase"> 
					
						<div class="table-responsive">
							<label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
							<i class='fas fa-hand-point-right'></i> Delivery Challan Items
							</label>
							<table class="table table-bordered mg-b-0" >
                            	<thead>
                                	<tr>
											<th>Product</th>
                                            <th>Description</th>
                                            <th>HSN Code</th>
                                            <th>Batchcard</th>
                                            <th>Batch Quantity </th>
                                            <th>Manufacturing Date</th>
                                            <th>Expiry Date</th>
                                            <th>Status</th>
                                        </tr>                                                
                                </thead>
                                <tbody id="prbody1">
                                @if(count($dc_items)>0)
                                    @foreach($dc_items as $item)
                                        <tr>
                                            <input type="hidden" id="dc_id" value="{{$dc_id}}" name="dc_id">
                                                    <!-- <td><input type="checkbox" name="oef_item_id[]" value="{{$item['id']}}"></td> -->
                                            <td>{{$item['sku_code']}}</td>
                                            <td>{{$item['discription']}}</td>
                                            <td>{{$item['hsn_code']}} </td>
                                            <td>{{$item['batch_no']}} </td>
                                            <td>{{$item['batch_quantity']}} Nos</td>
                                            <td>{{$item['manufacturing_date']}}</td>
                                            <td>@if($item['expiry_date']=='0000-00-00') NA @else {{$item['expiry_date']}} @endif</td>
                                            <td>
												@if($item['cdc_status']==0)
												<span class="badge badge-primary" style="width:60px;">Active</span>
												@else
												<span class="badge badge-danger" style="width:60px;">Cancelled</span> 
												@endif
											</td> 
											{{--<td>
												<?php $is_exist_in_pi = $fn->grsItemExistInPI($item['id']);?>
												@if($is_exist_in_pi==1)
												<a class="badge badge-primary" style="font-size: 13px;" onclick="return confirm('Cannot edit GRS Item. It moved to next step!');"><i class="fa fa-edit"></i> Edit</a>
												<a class="badge badge-danger" style="font-size: 13px;" onclick="return confirm('Cannot delete GRS Item. It moved to next step!');"><i class="fa fa-trash"></i> Delete</a>
												@else
												<a class="badge badge-primary" style="font-size: 13px;" href="{{url('fgs/GRS-item-edit/'.$item['id'])}}"><i class="fa fa-edit"></i> Edit</a>
												<a class="badge badge-danger" style="font-size: 13px;"  href="{{url('fgs/GRS-item-delete/'.$item['id'])}}"><i class="fa fa-trash"></i> Delete</a>
												@endif
											</td>--}}
                                        </tr>
                                    @endforeach
									@else
									<tr>
										<td colspan="9" ><center>No data Found...</center></td>
                                    </tr>
									@endif
                                
                                </tbody>
                            </table>
                            <div class="box-footer clearfix">
                                 {{$dc_items->appends(request()->input())->links()}} 
                            </div>
                        </div>
						<br/>
						<div class="form-devider"></div>
						<div class="table-responsive">
							<label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
							<i class='fas fa-hand-point-right'></i> Unreserved OEF Items
							</label>
							<table class="table table-bordered mg-b-0" >
                            	<thead>
                                	<tr>
                                            <th>Product</th>
                                            <th>Description</th>
                                            <th>Actual Quantity</th>
                                            <th>Unreserved Quantity</th>
                                            <th>Rate</th>
                                            <th>Discount</th>
                                            <th>GST</th>
											<th>Action</th>
                                        </tr>                                                
                                </thead>
                                <tbody id="prbody1">
									@if(count($oef_items)>0)
                                    @foreach($oef_items as $item)
                                        <tr>
                                            <td>{{$item['sku_code']}}</td>
                                            <td>{{$item['discription']}}</td>
                                            <td>{{$item['quantity']}} Nos</td>
                                            <td>{{$item['quantity_to_allocate']}} Nos</td>
                                            <td>{{$item['rate']}}</td>
                                            <td>{{$item['discount']}}%</td>
                                            <td>IGST:{{$item['igst']}}%<br/>
                                                        CGST:{{$item['cgst']}}%<br/>
                                                        SGST:{{$item['sgst']}}%
                                            </td>
											<td><a class="badge badge-info" style="font-size: 13px;" href="{{url('fgs/Delivery_challan/'.$dc_id.'/add-item/'.$item["id"])}}"  class="dropdown-item"><i class="fas fa-plus"></i>  Challan Item</a></td>
                                        </tr>
                                    @endforeach
									@endif
                                </tbody>
                            </table>
                            <div class="box-footer clearfix">
                                {{ $oef_items->appends(request()->input())->links() }}
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
		var supplier_doc_number = $('#supplier_doc_number').val();
		var mrn_no = $('#mrn_no').val();
		var from = $('#from').val();
		if(!mrn_no  & !supplier_doc_number & !from)
		{
			e.preventDefault();
		}
	});
</script>


@stop