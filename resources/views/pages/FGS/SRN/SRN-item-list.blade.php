@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				 <span>Sales Return Note(SRN)</span>
				 <span><a href="">
				 	SRN Item List
				</a></span>
				 </div>
			<h4 class="az-content-title" style="font-size: 20px;">
            SRN Item List @if($srn_number)({{$srn_number}}) @endif
              <div class="right-button">
                
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
			
			
					<div class="tab-pane  active  show " id="purchase"> 
					
					
					{{-- <div style="width:50%;float:left;font-size:14px;font-weight:bold;">DNI/EXI Number : {{$item['dni_number']}}</div>
					<div style="width:50%;float:right;font-size:14px;font-weight:bold; text-align:right;">DNI/EXI Date : {{date('d-m-Y', strtotime($item['dni_date']))}}</div> --}}
					<div class="table-responsive">
                        <table class="table table-bordered mg-b-0" >
							<thead>
								<tr>
									<th></th>
                                    <th>Product</th>
									<th>Description</th>
									<th>HSN Code</th>
									<th>Batchcard</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <th>Discount</th>
                                    <th>Net Value</th>
									<th></th>
								</tr>
							</thead>
							<tbody id="prbody1">
                            @php $i=1; @endphp
                            @foreach($srn_items as $item)
                                <tr>
									<td>{{$i++}}</td>
									<td>{{$item['sku_code']}}</td>
									<td>{{$item['discription']}}</td>	
									<td>{{$item['hsn_code']}}</td>
									<td>{{$item['batch_no']}}</td>
                                    <td>{{$item['quantity']}}Nos</td>
                                    <td>{{$item['rate']}}</td>
                                    <td>{{$item['discount']}}%</td>
                                    <td>{{($item['rate']*$item['quantity'])-(($item['quantity']*$item['discount']*$item['rate'])/100)}}</td>
									<td>
										<a class="badge badge-info" style="font-size: 13px;" href="{{ url('fgs/SRN/item-edit/' . $srn_id . '/' . $item['srn_item_id']) }}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a>
									</td>
								</tr>
                                @endforeach
							</tbody>
						</table>
						<div class="box-footer clearfix">
						{{ $srn_items->appends(request()->input())->links() }}
						</div>
					</div>
					<br/>
					
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
  	$('#purchase_tab').on('click',function(){
		$('#pr_no').val(" ");
		$('#department').val("");
		$('#from').val(" ");
	});
	$('#service_tab').on('click',function(){
		$('#pr_no').val(" ");
		$('#department').val("");
		$('#from').val(" ");
	});
	$('.search-btn').on( "click", function(e)  {
		var product = $('#product').val();
		var batch_no = $('#batch_no').val();
		var from = $('#from').val();
		if(!product  & !batch_no & !from)
		{
			e.preventDefault();
		}
	});
</script>


@stop