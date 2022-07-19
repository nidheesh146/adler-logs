@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> <span>Supplier Quotation</span> <span>Comparison of quotation</span> </div>
			<h4 class="az-content-title" style="font-size: 20px;">Comparison of quotation <span>( {{$rq_no}} )</span>
              <div class="right-button">
                
                  <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                      <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                  <div class="dropdown-menu">
                  <a href="" class="dropdown-item">Excel</a>
          
                  </div>
              <div>  
              </div>
          </div>
        </h4>
			<div class="az-dashboard-nav">
				<nav class="nav"> </nav>
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
			<div class="table-responsive" style="overflow-y: hidden;overflow-x: visible; border-color:black;">
				<table class="table table-bordered " id="example1">
                <colgroup>
                <?php
                    function bgcolor(){return dechex(rand(0,10000000));}
                ?>
                    <col span="3">
                    @if(!empty($Res['response']['response1']))
                    <?php $i=0; ?>
                    @foreach($Res['response']['response1']['quotation'][0]['supplier'] as $supplier)
                    <col span="3" style="background-color:#<?php echo bgcolor(); ?>">
                    <?php $i++; ?>
                    @endforeach
                    @endif
                </colgroup>
					<thead>
						<tr>
							<th  rowspan="2" style="color:#1c273c;">Item </th>
							<th  rowspan="2" style="color:#1c273c;">Item Code</th>
							<th  rowspan="2" style="color:#1c273c;">Item HSN</th>
                            @if(!empty($Res['response']['response1']))
				            @foreach($Res['response']['response1']['quotation'][0]['supplier'] as $supplier)
							<th colspan="3" style="color:black; font-size:15px;"><center>{{$supplier['vendor_name']}}</center></th>
                            @endforeach
                            @endif
						</tr>
                        <tr>
                        @if(!empty($Res['response']['response1']))
				            @foreach($Res['response']['response1']['quotation'][0]['supplier'] as $supplier)
                            <th style="color:#1c273c;">Rate</th>
                            <th style="color:#1c273c;">Qty</th>
                            <th style="color:#1c273c;">Total</th>
                        @endforeach
                            @endif
                        </tr>
                        
					</thead>
					<tbody >
                    @if(!empty($Res['response']['response0']['supplier_quotation'][0]))
						@foreach($supplier_values['supplier_items'] as $item)
                        <tr>
                            {{-- <th>1</th> --}}
                            <td >{{$item['item_name']}}</td>
                            <td>{{$item['item_code']}}</td>
                            <td>{{$item['hsn']}}</td>
                            @foreach($item['price_data'] as $data)
                            <td class="supplier_rate" >@if($data['supplier_rate']==NULL) 0 @else {{ $data['supplier_rate'] }} @endif</td>
                            <td class="quantity" >{{ $data['quantity'] }}</td>
                            <td class="total" >{{ $data['total'] }}</td>
                            @endforeach
                            
						</tr>
                    @endforeach
                    @endif
                    <tr>
                    <td colspan="3"></td>
                    @if(!empty($Res['response']['response1']))
				    @foreach($supplier_values['grant_total_supplier'] as $item)
                            <td colspan="2">Total :</td>
                            <td class="grant_total"> {{$item}}</td>
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


@stop