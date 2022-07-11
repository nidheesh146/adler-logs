@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> <span>Purchase Requisition</span> <span>Purchase Requisition Approval</span> </div>
			<h4 class="az-content-title" style="font-size: 20px;">Purchase Requisition Approval
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
			@if($data['error'])
			<div class="alert alert-danger "  role="alert" style="width: 100%;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				{{$data['error'] }}
			</div>
	       @endif
		   
		   @if (Session::get('success'))
		   <div class="alert alert-success " style="width: 100%;">
			   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			   <i class="icon fa fa-check"></i> {{ Session::get('success') }}
		   </div>
		   @endif
			<div class="table-responsive">
				<table class="table table-bordered mg-b-0" id="example1">
					<thead>
						<tr>
							<th style="display:none;"></th>
						    <th>PR NO:</th>
							<th>Item code </th>
							<th>Supplier</th>
							<th>Actual order Qty</th>
							<th>Rate</th>
							<th>Discount %</th>
							<th>GST %</th>
							<th>Currency</th>
							<th>Net value </th>
							{{-- <th>Status</th> --}}
							<th>Status</th>
						</tr>
					</thead>
					<tbody >
						@if(!empty($data['response']['purchase_requisition'][0]))
						@foreach($data['response']['purchase_requisition'] as $item)
						
						<tr>
							<td style="display:none;">{{ $item['id']}}</td>
							<td>{{!empty($item['purchase_reqisition']) ? $item['purchase_reqisition']['pr_no'] : '-'}}</td>
							<td>{{$item['item_code']['item_code']}}</td>
							<td>{{$item['supplier']['vendor_name']}}</td>
							<td>{{$item['actual_order_qty']}}</td>
						    <td>{{$item['rate']}}</td>
							<td>{{$item['discount_percent']}}</td>
							<td>{{$item['gst']}}</td>
							<td>{{$item['currency']}}</td>
							<td>{{$item['net_value']}}</td>
							{{-- <td><span class="badge badge-pill badge-info ">waiting for Action<span></td> --}}
							<td>
							<a href="#" data-toggle="modal" value="{{$item['id']}}" rel="{{$item['item_code']['item_code']}}" data-target="#myModal" id="change-status" style="width: 64px;" 
							data-html="true" data-placement="top" 
							class="badge 
							@if($item['status'] == 0)
							    badge-info
								@elseif($item['status'] == 2)
								badge-warning
								@endif
							">
							@if($item['status'] == 0)
							    Pending
							@elseif($item['status'] == 2)
								On hold
							@endif
							</a></td>
						</tr>	
					
						@endforeach
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

	<div id="myModal" class="modal">
                <div class="modal-dialog modal-md" role="document">
                    <form id="status-change-form" method="post" action="{{ url('inventory/purchase-reqisition/approval')}}">
                    {{ csrf_field() }} 
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">#Approve Purchase Requisition <span class="item-codes"></span></h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="inputAddress2">Status *</label><br>
                                {{-- <input type="text" name="purchaseRequisitionMasterId" id ="purchaseRequisitionMasterId" value=" "> --}}
								<input type="hidden" name="purchaseRequisitionItemId" id ="purchaseRequisitionItemId" value="">
                                <select class="form-control" name="status" id="status">
									<option value=""> --Select One-- </option>
									{{-- <option value="0"> Pending </option> --}}
									<option value="1"> Approve</option>
									<option value="2">On hold</option>
                                </select>
                            </div>
							<div class="form-group">
                                <label for="inputAddress">Approved Qty *</label>
                                <input type="text" name="approved_qty"  class="form-control" id="approved_qty" placeholder="Approved Qty">
                            </div> 
                            <div class="form-group">
                                <label for="inputAddress">Remarks *</label>
                                <textarea style="min-height: 100px;" name="reason" type="text" class="form-control" id="reason" placeholder="Remarks"></textarea>
                            </div> 
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="save"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
								role="status" aria-hidden="true"></span> <i class="fas fa-save"></i> Submit</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div><!-- modal-dialog -->
	<!-- az-content-body -->
</div>



<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>


<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>

<script>
  $(function(){
    'use strict'

    $('#example1').DataTable({
      language: {
        searchPlaceholder: 'Search...',
        sSearch: '',
        lengthMenu: '_MENU_ items/page',
      },
	  order: [[1, 'desc']],
    });
	$("#status-change-form").validate({
            rules: {
                status: {
                    required: true,
                },
                approved_qty: {
                    required: true,
					number: true,
                },
                reason: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });
    
  });
</script>
<script>
$(document).ready(function() {
        $('body').on('click', '#change-status', function (event) {
            event.preventDefault();
			$(".item-codes").text('') ;
			let purchaseRequisitionItemId = $(this).attr('value');
			$('#purchaseRequisitionItemId').val(purchaseRequisitionItemId);

			$(".item-codes").text('( '+ $(this).attr('rel') + ')');
        });
        
    });

    </script>


@stop