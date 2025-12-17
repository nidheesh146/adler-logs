@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				 <span>Manual Cancellation Delivery Challan(CDC)</span>
				 <span><a href="">
                 Manual CDC Item List
				</a></span>
				 </div>
			<h4 class="az-content-title" style="font-size: 20px;">
                Manual CDC Item List 
              <div class="right-button">
                    <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('fgs/manual-CDC-item-add/'.$id)}}'" class="badge badge-pill badge-dark "><i class="fas fa-plus"></i> 
                    Manual CDC Item
                    </button>
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
					
					<div class="table-responsive">
                        <table class="table table-bordered mg-b-0" >
							<thead>
								<tr>
									
                                    <th>Product</th>
									<th>Description</th>
									<th>HSN Code</th>
									<th>Batchcard</th>
                                    <th>Quantity</th>
									<th></th>

								</tr>
							</thead>
							<tbody id="prbody1"> 
							@foreach($items as $item)
                                <tr>
									
									
									<td>{{$item['sku_code']}}</td>
									<td>{{$item['discription']}}</td>	
									<td>{{$item['hsn_code']}}</td>
									<td>{{$item['batch_no']}}</td>
                                    <td>{{$item['quantity']}}Nos</td>
									<td><a class="badge badge-info" style="font-size: 13px;" href="{{url('fgs/CDC/item-edit/'.$item['id'])}}"  class="dropdown-item"><i class="fas fa-edit"></i> Edit</a> </td>

                                </tr>
								@endforeach
							</tbody>
						</table>
						<div class="box-footer clearfix">
                        {{ $items->appends(request()->input())->links() }}
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
	
</script>


@stop