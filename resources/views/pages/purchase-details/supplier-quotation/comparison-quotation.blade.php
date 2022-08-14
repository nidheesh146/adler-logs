@extends('layouts.default')
@section('content')
@inject('fn', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> <span>Supplier Quotation</span> <span>Comparison of quotation</span> </div>
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
               th, td {
                border-color: black;
                color:#1c273c;
                }
                thead th{
                    color:red;
                }
                
            </style>
			<div class="table-responsive" style=" border-color:black;width:1000px; overflow-x: scroll;">
				<table class="table table-bordered " id="example1" class="table1">
                <colgroup>
                <?php
                    function bgcolor(){return dechex(rand(0,10000000));}
                ?>
                    <col span="3">
                    @if(!empty($suppliers))
                    <?php $i=0; ?>
                    @foreach($suppliers as $supplier)
                    <col span="4" style="border-color:black; background-color:#<?php echo bgcolor(); ?>">
                    <?php $i++; ?>
                    @endforeach
                    @endif
                </colgroup>
					<thead>
						<tr>
							<th  rowspan="2" style="color:#1c273c;">Item </th>
							<th  rowspan="2" style="color:#1c273c;">Item Code</th>
							<th  rowspan="2" style="color:#1c273c;">Item HSN</th>
                            @if(!empty($suppliers))
				            @foreach($suppliers as $supplier)
							<th colspan="4" style="color:black; font-size:15px;"><center>{{$supplier['vendor_name']}}</center></th>
                            @endforeach
                            @endif
						</tr>
                        <tr>
                        @if(!empty($suppliers))
				            @foreach($suppliers as $supplier)
                            <th width="5%" style="color:#1c273c;">Rate</th>
                            <th style="color:#1c273c;">Qty</th>
                            <th style="color:#1c273c;">Discount</th>
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
                            <td>{{$item['item_code']}}</td>
                            <td>{{$item['hsn_code']}}</td>
                            @foreach($item['price_data'] as $data)
                            <td class="supplier_rate" >@if($data['rate']==NULL) 0 @else {{ $data['rate'] }} {{$item['currency_code']}} @endif</td>
                            <td class="quantity" >{{ $data['quantity'] }} {{$item['unit_name']}}</td>
                            <td class="quantity" >{{ $data['discount'] }}</td>
                            <td class="total{{$i++}}">{{ $data['total'] }} {{$item['currency_code']}}</td>
                            @endforeach
						</tr>
                        
                    @endforeach
                    @endif
                    <tr>
                    <td colspan="3"></td>
                    @if(!empty($suppliers))
				    @foreach($suppliers as $supplier)
                        <td colspan="3">
                            <?php $check = $fn->checkSelectedQuotation($rq_no,$supplier['id']) ?>
                            <button style="margin-left: 9px;font-size: 14px;" class="button badge badge-pill badge-warning select-button" data-quotation="{{$rq_no}}" data-supplier="{{$supplier['id']}}" @if($check==1) disabled @endif>
                            <span class="text">@if($check==1)Selected @else Select @endif</span> 
                            <i class="fas fa-arrow-alt-circle-right" aria-hidden="true"></i>
                            </button>
                            <span style="float:right">Total :</span>
                        </td>
                        <td class="grant_total"><span class="tot"></span> {{$item['currency_code']}}</td>
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
});

$(".select-button").on("click", function(){
    var quotation_id = $(this).data('quotation');
    var supplier = $(this).data('supplier');
    $(".danger").hide();
    $(".success").hide();
    //alert(supplier);
    $.ajax({
           type:'POST',
           url:"{{ url('inventory/select-quotation') }}",
           data:{ "_token": "{{ csrf_token() }}",quotation_id:quotation_id, supplier:supplier},
           success:function(data){
            location.reload();
              if(data == 1)
              {
                
                $(".success").show();
                //alert('Quotation Selected successfuly');
              }
              else 
              {
                $(".danger").show();
                //alert('Quotation Selection failed');
              }
           }
    });
});

</script>

@stop