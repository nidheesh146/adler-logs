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
	
			<div class="table-responsive" style="overflow-y: hidden;overflow-x: visible;">
				<table class="table table-bordered mg-b-0" id="example1">
                <colgroup>
                    <col span="3" style="background-color:#D1F2EB">
                    <col span="3" style="background-color:yellow">
                </colgroup>
					<thead>
						<tr>
							<th  rowspan="3">Item </th>
							<th  rowspan="3">Item Code</th>
							<th  rowspan="3">Item HSN</th>
                            @if(!empty($Res['response']['response1']))
				            @foreach($Res['response']['response1']['quotation'][0]['supplier'] as $supplier)
							<th rowspan="2" colspan="3"><center>Supplier 1<br>(QR NO)</center></th>
                            @endforeach
                            @endif
						</tr>
					</thead>
					<tbody >
                    @if(!empty($Res['response']['response0']['supplier_quotation'][0]))
						@foreach($item_by_supplier as $item)
                        <tr>
                            {{-- <th>1</th> --}}
                            <td >{{$item['item_name']}}</td>
                            <td>{{$item['item_code']}}</td>
                            <td>{{$item['hsn']}}</td>
						</tr>
                    @endforeach
                    @endif
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">Total :</td>
                            <td> 456</td>
                            <td colspan="2">Total :</td>
                            <td> 456</td>
                        </tr>
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