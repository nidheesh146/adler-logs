@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				 <span>Supplier Quotation</span>
				 <span><a href="">Supplier Quotation Items </a></span>
			</div>
			<h4 class="az-content-title" style="font-size: 20px;">Supplier Quotation Items  <span>({{$rq_no}})</span>			 
			<div class="form-group col-sm-4 col-md-4 col-lg-4 col-xl-4" style="float: right;">
				<form id="Supplier_form">
					<label>Supplier *</label>
					<select class=" Supplier"  name="supplier">
						@if(!empty($Res['response']['response1']))
						@foreach($Res['response']['response1']['quotation'][0]['supplier'] as $supplier)
						   <option value="{{$supplier['id']}}"
						   @if(!empty($Res['response']['response0']))
						   @if($Res['response']['response0']['supplier_quotation'][0]['supplier']['id'] == $supplier['id'])
							  selected
						   @endif
						   @endif
						   >{{$supplier['vendor_name']}}</option>
						@endforeach
						@endif
					</select>   	
				</form>   
			</div>
			
			{{-- <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
				<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
					<label style="    width: 100%;">&nbsp;</label>
					<button type="submit" class="button btn-primary" style="margin-top:8px;"><i class="fas fa-search"></i> Change</button>
				</div>
				</div> --}}
			
        </h4>

      
			@if($Res['error'])
			<div class="alert alert-danger "  role="alert" style="width: 100%;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				{{$Res['error'] }}
			</div>
	        @endif
	   
		   
		   @if(Session::get('error'))
		   <div class="alert alert-danger"  role="alert" style="width: 100%;">
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
	        
		<div class="row">
		    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
		    	<label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
				<i class="fas fa-address-card"></i> Basic details                               
				</label>
				<div class="form-devider"></div>
			</div>
		</div>
		<form method="post" action="{{url('inventory/supplierQuotationUpdate/'.$rq_no.'/'.$supp_id)}}"  id="supplierquotationform">
		{{ csrf_field() }}
			<div class="row">
				<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
					<label>Supplier quotation NO: *</label>
					<input type="text"  class="form-control" id="supplier_quotation_no" name="supplier_quotation_no" placeholder="Supplier quotation NO" @if(!empty($Res['response']['response0']['supplier_quotation'][0]['supplier_quotation_no']) && !empty($Res['response']['response0']['supplier_quotation'][0]['quotation_date'])) value="{{$Res['response']['response0']['supplier_quotation'][0]['supplier_quotation_no']}}" @endif>
				</div>       
				<input type="hidden" name="quotation_id" @if(!empty($Res['response']['response0']['supplier_quotation'][0]['id'])) value="{{$Res['response']['response0']['supplier_quotation'][0]['id']}}" @endif>
				<div class="form-group col-sm-12 col-md-4 col-lg-6 col-xl-6">
					<label for="exampleInputEmail1">Commited delivery date *</label>
					<input type="text" class="form-control datepicker date-picker" name="commited_delivery_date" @if(!empty($Res['response']['response0']['supplier_quotation'][0]['deliver_date'])) 
					value="{{date('d-m-Y',strtotime($Res['response']['response0']['supplier_quotation'][0]['deliver_date']))}}" @endif>
				</div>
				<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
					<label>Quotation date *</label>
					<input type="text" name="quotation_date" class="form-control datepicker date-picker1" 
					@if(!empty($Res['response']['response0']['supplier_quotation'][0]['quotation_date'])) 
					value="{{date('d-m-Y',strtotime($Res['response']['response0']['supplier_quotation'][0]['quotation_date']))}}" @endif>
				</div>

				<!-- form-group -->
				<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
					<label>Contact</label>
					<input type="text" name="contact" class="form-control" placeholder="Contact Number" @if(!empty($Res['response']['response0']['supplier_quotation'][0]['contact'])) value="{{$Res['response']['response0']['supplier_quotation'][0]['contact']}}" @endif>
				</div>
			</div>
			<div class="row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;">
					<span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                        @if(!empty($Res['response']['response0']['supplier_quotation'][0]))
                            Update
                        @else 
                            Save & Next
                        @endif
                    </button>
                </div>
            </div>
            <div class="form-devider"></div>
		</form> 


<div class="row">
	<div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
		<label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
		<i class="fas fa-address-card"></i> Basic details                               
		</label>
		<div class="form-devider"></div>
	</div>
</div>
			

			<div class="table-responsive">
				<table class="table table-bordered mg-b-0" id="example1">
					<thead>
						<tr>
							<th>PR NO.</th>
							<th>Item Code:</th>
							<th>HSN</th>
							<th>Delivery schedule</th>
							<th>Requested Qty</th>
							<th>Supplier Qty</th>
							<th>Supplier Rate</th>
                            <th>Supplier Discount %</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody id="prbody">
						@if(!empty($Res['response']['response0']['supplier_quotation'][0]))
						@foreach($Res['response']['response0']['supplier_quotation'] as $item)
                        <tr>
                            {{-- <th>1</th> --}}
                            <th>{{$item['purchase_reqisition_approval']['purchase_reqisition_list'][0]['purchase_reqisition']['pr_no']}}</th>
                            <th>{{$item['purchase_reqisition_approval']['purchase_reqisition_list'][0]['item_code']['item_code']}}</th>
							<th>{{$item['purchase_reqisition_approval']['purchase_reqisition_list'][0]['item_code']['hsn_code']}}</th>
                            <th>{{date('d-m-Y',strtotime($item['quotation']['deliver_schedule']))}}</th>
							<th>{{$item['purchase_reqisition_approval']['quantity']}}</td>
							<th>{{$item['quantity']}}</td>
							<th>{{$item['supplier_rate']}}</td>
							<th>{{$item['supplier_discount']}}</td>
                            <td><a href="{{url('inventory/edit-supplier-quotation-item/'.$rq_no.'/'.$supp_id.'/'.$item['id'])}}?name={{$item['supplier']['vendor_name']}}" class="badge badge-info"><i class="fas fa-edit"></i> Update</a>
							</td>
						</tr>    
						@endforeach
						@endif
					</tbody>
				</table>
				{{-- @if(!empty($data['response']))
				@include('includes.pagination',['data'=>$data['response']])
			 	@endif --}}
				<div class="box-footer clearfix">
					<style>
					.pagination-nav {
						width: 100%;
					}
					
					.pagination {
						float: right;
						margin: 0px;
						margin-top: -16px;
					}
					</style>
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
                contact: {
                    required: true
                },
            },
            submitHandler: function(form) {
              //  $('.spinner-button').show();
                form.submit();
            }
        });


  });

</script>


@stop