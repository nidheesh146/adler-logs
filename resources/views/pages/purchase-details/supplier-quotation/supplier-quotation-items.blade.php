@extends('layouts.default')
@section('content')
@inject('fn', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				 <span><a href="{{url('inventory/supplier-quotation')}}">Supplier Quotation</a></span>
				 <span>Supplier Quotation Items </span>
			</div>
			<h4 class="az-content-title" style="font-size: 20px;">Supplier Quotation Items  <span>({{$data['quotation']->rq_no}})</span>			 
			
        </h4>
	   

		   @if (Session::get('success'))
		   <div class="alert alert-success " style="width: 100%;">
			   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			   <i class="icon fa fa-check"></i> {{ Session::get('success') }}
		   </div>
		   @endif
		   @foreach ($errors->all() as $errorr)
		   <div class="alert alert-danger "  role="alert" style="width: 100%;">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			 {{ $errorr }}
		   </div>
		  @endforeach
		  
		<div class="row">
		    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
		    	<label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
				<i class="fas fa-address-card"></i> Basic details   ( {{$data['supplier_single']->vendor_id}} - {{$data['supplier_single']->vendor_name}} )                           
				</label>
				<div class="form-devider"></div>
			</div>
		</div>

		<div class="row">
			

				<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
					<label>Supplier *</label>
					<form id="Supplier_form">
					<select class="form-control Supplier" name="Supplier">
						@foreach( $data['supplier'] as $supplier)
					   <option value="{{$supplier['id']}}"
					   @if($supp_id == $supplier['id'])
						  selected
					   @endif
					   >{{$supplier['vendor_name']}}</option>
					@endforeach
				
					</select>
				</form> 
				</div>




  
		</div>

		<div class="row">
		    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
		    	<label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
				<i class="fas fa-address-card"></i> Supplier Quotation master 
				</label>
				<div class="form-devider"></div>
			</div>
		</div>


		<form method="post" action="{{url('inventory/supplierQuotationUpdate/'.$rq_no.'/'.$supp_id)}}"  id="supplierquotationform" autocomplete="off">
		{{ csrf_field() }}
			<div class="row">
				<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
					<label>Supplier quotation NO: *</label>
					<input type="text"  class="form-control" id="supplier_quotation_no" name="supplier_quotation_no" 
					placeholder="Supplier quotation NO" 
					@if( !empty($data['supplier_single']->supplier_quotation_num)) value="{{$data['supplier_single']->supplier_quotation_num}}" @endif>
				</div>       
				
				{{--<div class="form-group col-sm-12 col-md-4 col-lg-6 col-xl-6">
					<label for="exampleInputEmail1">Commited delivery date *</label>
					<input type="text" class="form-control datepicker date-picker" name="commited_delivery_date" 
					@if(!empty($data['supplier_single']->commited_delivery_date)) 
				    	value="{{date('d-m-Y',strtotime($data['supplier_single']->commited_delivery_date))}}" 
					@endif
					>
				</div>--}}
				<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
					<label>Quotation date *</label>
					<input type="text" name="quotation_date" class="form-control datepicker date-picker1" 
					@if(!empty($data['supplier_single']->quotation_date)) 
					value="{{date('d-m-Y',strtotime($data['supplier_single']->quotation_date))}}" @endif>
				</div>

				{{--<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label> Currency *</label>
                    <select class="form-control currency" name="currency">
                        <option value="">--- select one ---</option>
                        @foreach ($data["currency"] as $item)
                        <option value="{{ $item['currency_id']}}" >{{ $item['currency_code'] }}</option>
                        @endforeach
                    </select>

                </div>--}}
				<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
					<label>Transportation & Freight Charge</label>
					<input type="text" name="freight_charge" class="form-control" placeholder="Transportation & Freight Charge" 
					@if(!empty($data['supplier_single']->freight_charge))
					    value="{{$data['supplier_single']->freight_charge}}" 
					@endif>
				</div>
			</div>
			<div class="row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;">
					<span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                        @if(!empty($data['supplier_single']))
                            Update
                        @else 
                            Save 
                        @endif
                    </button>
                </div>
            </div>
            <div class="form-devider"></div>
		</form> 


<div class="row">
	<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
		<label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
		<i class="fas fa-address-card"></i> Supplier Quotation Items                               
		</label>
		<div class="form-devider"></div>
	</div>
</div>
			

			<div class="table-responsive">
				<table class="table table-bordered mg-b-0" id="example1">
					<thead>
						<tr>
							<th>PR/SR NO.</th>
							<th>Item Code:</th>
							<th>Delivery schedule</th>
							<th>Requested Qty</th>
							<th>Supplier Qty</th>
							<th>Rate</th>
                            <th>Discount %</th>
							<th>GST %</th>
							<th>Currency</th>
							<th>Committed Delivery Date</th>
							<th>Action</th>
						</tr>
					</thead>
					
					<tbody id="prbody">
						@foreach($data['inv_purchase_req'] as $item)
							@php $fixed_items= $fn->getFixedRateItems($rq_no,$item['item_id']);
					 		@endphp
                        <tr>
                            {{-- <th>1</th> --}}
                            <td>{{$item['pr_no']}}</td>
                            <td>{{$item['item_code']}}</td>
							<!-- <td>{{$item['hsn_code']}}</td> -->
                            <td>{{date('d-m-Y',strtotime($item['delivery_schedule']))}}</td>
							<td>{{$item['actual_order_qty']}} {{$item['unit_name']}}</td>
							
	
							{{--@if($fixed_item[0] && $fn->get_Fixeditem($fixed_item[0])==$item['item_code'] && $fn->get_fixed_supplier($fixed_item[0])!=$item['supplier_id']) --}}
							@if($fixed_items==1)
							<td colspan="7" style="vertical-align: middle;text-align:center;">
								<div class="alert alert-success success" style="width: 100%;">
								This is the fixed rate item, No need to add these informations
								</div>
							</td>
							
						
							@else
							<td>@if($item['quantity']) {{$item['quantity']}} {{$item['unit_name']}} @endif</td>
							<td>@php $rate=$fn->get_rate($item['supplier_id'], $item['itemId']) @endphp
								 @if($item['rate']!=NULL) 
								 {{$item['rate']}}
								 @elseif($rate!=0)
								 {{$rate}}
								 @endif
							</td>
							<td>{{$item['discount']}}</td>
							<td>@if($item['igst']!=0)
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
							</td>
							<th>{{$item['currency_code']}}</td>
							<td>@if($item['committed_delivery_date']!=NULL) {{date('d-m-Y',strtotime($item['committed_delivery_date']))}} @endif</td>
                            <td><a href="{{url('inventory/edit-supplier-quotation-item/'.$rq_no.'/'.$supp_id.'/'.$item['inv_item_id'])}}" class="badge badge-info"><i class="fas fa-edit"></i> Update</a>
							</td>
							@endif 
							
						</tr>    
						@endforeach
				
					</tbody>
				</table>
			
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
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>

<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>

<script>
  $(function(){
    'use strict'

    $('#prbody').show();
	

    $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true
    });


	$('.datepicker').mask('99-99-9999'); 

$('.Supplier').change(function(){
   $('#Supplier_form').submit();
})
$('#supplierquotationform').validate({
            rules: {
                supplier_quotation_no: {
                    required: true
                },
                commited_delivery_date: {
                    required: true
                },
                quotation_date: {
                    required: true
                },
                // contact: {
                //     required: true
                // },
            },
            submitHandler: function(form) {
              //  $('.spinner-button').show();
                form.submit();
            }
        });


  });

</script>


@stop