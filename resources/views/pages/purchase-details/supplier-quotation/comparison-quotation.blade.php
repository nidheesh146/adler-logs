@extends('layouts.default')
@section('content')
@inject('fn', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"><span><a href="{{ url('inventory/supplier-quotation') }}">SUPPLIER QUOTATION </a></span> <span>Comparison of quotation</span> </div>
			<h4 class="az-content-title" style="font-size: 20px;">Comparison of quotation <span>( {{$rq_number}} )</span>
            </h4>
			<div class="alert alert-success success" style="width: 100%;display:none;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fa fa-check"></i> Quotation selected successfully..
            </div>
                   
            <div class="alert alert-danger danger"  role="alert" style="width: 100%;display:none;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                Quotation selection failed
            </div>
                 
            <style>
                input[type="radio"]{
                    appearance: none;
                    border: 1px solid #d3d3d3;
                    width: 30px;
                    height: 30px;
                    content: none;
                    outline: none;
                    margin: 0;
                    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
                    background-color: #fff;
                }

                input[type="radio"]:checked {
                appearance: none;
                outline: none;
                padding: 0;
                content: none;
                border: none;
                }

                input[type="radio"]:checked::before{
                position: relative;
                color: green !important;
                content: "\00A0\2713\00A0" !important;
                border: 1px solid #d3d3d3;
                font-weight: bolder;
                font-size: 21px;
                }

               th, td {
                border-color: black;
                color:#1c273c;
                }
                thead th{
                    color:red;
                }
                
            </style>
            @if($supplier_data)
			<div class="table-responsive" style=" border-color:black;width:1000px; overflow-x: scroll;">
				<table class="table table-bordered " id="example1" class="table1">
                <colgroup>
                <?php
                    function bgcolor(){return dechex(rand(0,10000000));}
                ?>
                    <col span="2">
                    @if(!empty($suppliers))
                    <?php $i=0; ?>
                    @foreach($suppliers as $supplier)
                    <col span="5" style="border-color:black; background-color:#<?php echo bgcolor(); ?>">
                    <?php $i++; ?>
                    @endforeach
                    @endif
                </colgroup>
					<thead>
						<tr>
							<th  rowspan="2" style="color:#1c273c;">Item </th>
							<!-- <th  rowspan="2" style="color:#1c273c;">Item Code</th> -->
							<th  rowspan="2" style="color:#1c273c;">Item HSN</th>
                            @if(!empty($suppliers))
				            @foreach($suppliers as $supplier)
							<th colspan="5" style="color:black; font-size:15px;">
                                <center>{{$supplier['vendor_name']}}</center>
                                
                                <!-- <div style="font-size:10px;text-align:center;margin-top:-10px;">(Delivery Date :{{date('d-m-Y',strtotime($supplier['commited_delivery_date']))}})</div> -->
                            </th>
                            @endforeach
                            @endif
						</tr>
                        <tr>
                        @if(!empty($suppliers))
				            @foreach($suppliers as $supplier)
                            <th></th>
                            <th width="5%" style="color:#1c273c;">Rate</th>
                            <th style="color:#1c273c;">Qty</th>
                            <th style="color:#1c273c;">Discount(%)</th>
                            <th style="color:#1c273c;">Total</th>
                        @endforeach
                            @endif
                        </tr>
                        
					</thead>
					<tbody >
                    @if(!empty($supplier_data))
						@foreach($supplier_data as $item) 
                        <tr>
                            <?php $i=1;?>
                            <td >{{$item['item_name']}}</td>
                            <!-- <td>{{$item['item_code']}}</td> -->
                            <td>{{$item['hsn_code']}}</td>
                            @foreach($item['price_data'] as $data)
                            <?php $j=1;?>
                            <td>
                            <input type="radio" class="item-select-radio" name="{{$data['radio_name']}}" value="{{$data['itemId']}}" data-quotation="{{$rq_no}}"  data-supplier="{{$data['supplier_id']}}" @if($data["selected_item"]==1) checked @endif></td>
                            <td class="supplier_rate" >@if($data['rate']==NULL) 0 @else {{ $data['rate'] }} {{$data['currency_code']}} @endif</td>
                            <td class="quantity" >{{ $data['quantity'] }} {{$item['unit_name']}}</td>
                            <td class="quantity" >{{ $data['discount'] }}</td>
                            <td class="total{{$i++}}">{{ $data['total'] }} <span class="currency">{{$data['currency_code']}}</span></td>
                            @endforeach
                            
						</tr>                 
                        @endforeach
        
                    @endif
                    <tr>
                    <td colspan="2"></td>
                    @if(!empty($suppliers))
				    @foreach($suppliers as $supplier)
                        <td colspan="4">
                            <span style="float:right">Total :</span>
                        </td>
                        <td class="grant_total"><span class="tot"></span><span class="currency_coe">{{$fn->getCurrency_code($rq_no,$supplier['id'])}}</span></td>
                        
                    @endforeach
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        @foreach($suppliers as $supplier)
                            <td colspan="5"><strong>Remarks:</strong>{{$fn->getRemarks($rq_no,$supplier['id'])}}</td>
                        @endforeach
                    </tr>
                    @endif
					</tbody>
				</table>
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
            @else
            <div class="row">
            <div class="alert alert-success success" style="width: 100%;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fa fa-check"></i> It is the fixed rate item, No need to Compare..
            </div>
            </div>
            @endif
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
<script type="text/javascript">
   var getSum = function (colNumber) {
    var sum = 0;
    var selector = '.total' + colNumber;
    
    $('#example1').find(selector).each(function (index, element) {
        sum += parseInt($(element).text());
    });  
    return sum;        
};

$('#example1').find('.tot').each(function (index, element) {
    $(this).text(  getSum(index + 1)); 
    var currency = $('#example1').find('.currency:first').text();
    //alert(currency);
    $(this).next('.currency_code').text(currency);
});
$('.item-select-radio').on('change', function() {
    let item_id = $(this).val();
    let quotation_id = $(this).data('quotation');
    let supplier = $(this).data('supplier');
    $.ajax({
           type:'POST',
           url:"{{ url('inventory/select-quotation-items') }}",
           data:{ "_token": "{{ csrf_token() }}",quotation_id:quotation_id, item_id:item_id, supplier:supplier},
           success:function(data){
            
            //location.reload();
            //   if(data == 1)
            //   { 
            //     $(".success").show();
            //     //alert('Quotation Selected successfuly');
            //   }
            //   else 
            //   {
            //     $(".danger").show();
            //     //alert('Quotation Selection failed');
            //   }
           }
    });
});

// $(".select-button").on("click", function(){
//     var quotation_id = $(this).data('quotation');
//     var supplier = $(this).data('supplier');
//     $(".danger").hide();
//     $(".success").hide();
//     //alert(supplier);
//     $.ajax({
//            type:'POST',
//            url:"{{ url('inventory/select-quotation') }}",
//            data:{ "_token": "{{ csrf_token() }}",quotation_id:quotation_id, supplier:supplier},
//            success:function(data){
//             location.reload();
//               if(data == 1)
//               {
                
//                 $(".success").show();
//                 //alert('Quotation Selected successfuly');
//               }
//               else 
//               {
//                 $(".danger").show();
//                 //alert('Quotation Selection failed');
//               }
//            }
//     });
// });

</script>

@stop