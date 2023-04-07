@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				 <span>Tax Invoice Cum Delivery Note(DNI)</span>
				 <span><a href="">
				 	DNI Item List
				</a></span>
				 </div>
			<h4 class="az-content-title" style="font-size: 20px;">
                DNI Item List 
              <div class="right-button">
                
              <div>  
				
              </div>
          </div>
        </h4>	
		  
		   
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
									
								</tr>
								</tbody>
							</table>
						</div>
					</div>
			
			
					<div class="tab-pane  active  show " id="purchase"> 
					@foreach($dni_items as $dni_item)
					<div style="width:50%;float:left;font-size:14px;font-weight:bold;">PI Number : {{$dni_item['pi_number']}}</div>
					<div style="width:50%;float:right;font-size:14px;font-weight:bold; text-align:right;">PI Date : {{date('d-m-Y', strtotime($dni_item['pi_date']))}}</div>
					<div class="table-responsive">
                        <table class="table table-bordered mg-b-0" >
							<thead>
								<tr>
									<th></th>
									<th>GRS Number</th>
                                    <th>Product</th>
									<th>Description</th>
									<th>HSN Code</th>
									<th>Batchcard</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <th>Discount</th>
                                    <th>Net Value</th>
								</tr>
							</thead>
							<tbody id="prbody1">
							@php $i=1; @endphp
							@foreach($dni_item['pi_item'] as $item)
                                <tr>
									<td>{{$i++}}</td>
									<td>{{$item['grs_number']}}</td>
									<td>{{$item['sku_code']}}</td>
									<td>{{$item['discription']}}</td>	
									<td>{{$item['hsn_code']}}</td>
									<td>{{$item['batch_no']}}</td>
                                    <td>{{$item['batch_quantity']}}Nos</td>
                                    <td>{{$item['rate']}} {{$item['currency_code']}}</td>
                                    <td>{{$item['discount']}}%</td>
                                    <td>{{($item['rate']*$item['batch_quantity'])-(($item['batch_quantity']*$item['discount']*$item['rate'])/100)}} {{$item['currency_code']}}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						<div class="box-footer clearfix">
                        
						</div>
					</div>
					<br/>
					@endforeach
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
		var ref_number = $('#ref_number').val();
		var min_no = $('#min_no').val();
		var from = $('#from').val();
		if(!min_no   & !ref_number & !from)
		{
			e.preventDefault();
		}
	});
</script>


@stop