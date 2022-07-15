@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> <span>Supplier Quotation</span> <span>Comparison of quotation</span> </div>
			<h4 class="az-content-title" style="font-size: 20px;">Comparison of quotation
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
	
			<div class="table-responsive">
				<table class="table table-bordered mg-b-0" id="example1">
                <colgroup>
                    <col span="3" style="background-color:red">
                    <col span="3" style="background-color:yellow">
                </colgroup>
					<thead>
						<tr>
							<th>Item </th>
							<th>Item Code</th>
							<th>Item HSN</th>
							<th rowspan="2" colspan="3"><center>Supplier 1<br>(QR NO)</center></th>
                            <th rowspan="2" colspan="3"><center>Supplier 2<br>(QR NO)</center></th>
                            <th rowspan="2" colspan="3"><center>Supplier 3<br>(QR NO)</center></th>
						</tr>
					</thead>
					<tbody >
						<tr>
							<td> ITEM 1</a> </td>
							<td>A5001</td>
							<td>YUI</td>
							<td >price1</td>
                            <td>gst1</td>
                            <td>total1</td>
                            <td >price1</td>
                            <td>gst1</td>
                            <td>total1</td>
                            <td >price1</td>
                            <td>gst1</td>
                            <td>total1</td>
							
						</tr>
						<tr>
                            <td> ITEM 2</a> </td>
							<td>A5001</td>
							<td>YUI</td>
							<td >price2</td>
                            <td>gst2</td>
                            <td>total2</td>
                            <td >price2</td>
                            <td>gst2</td>
                            <td>total2</td>
                            <td >price2</td>
                            <td>gst2</td>
                            <td>total2</td>	
                            
						</tr>
						
						<tr>
                            <td> ITEM 3</a> </td>
							<td>A5001</td>
							<td>YUI</td>
							<td >price3</td>
                            <td>gst3</td>
                            <td>total3</td>
                            <td >price3</td>
                            <td>gst3</td>
                            <td>total3</td>
                            <td >price3</td>
                            <td>gst3</td>
                            <td>total3</td>
							
						</tr>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">Total :</td>
                            <td> 456</td>
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