@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> <span>Purchase Requisition</span> <span>Requisition Approval</span> </div>
			<h4 class="az-content-title" style="font-size: 20px;">Requisition Approval
              <div class="right-button">
                
                  <!-- <button data-toggle="dropdown" style="float: right; margin-left: 9px;font-size: 14px;" class="badge badge-pill badge-info ">
                      <i class="fa fa-download" aria-hidden="true"></i> Download <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>
                  <div class="dropdown-menu">
                  <a href="" class="dropdown-item">Excel</a>
          
                  </div> -->
              <div>  
              </div>
          </div>
        </h4>
        @foreach ($errors->all() as $errorr)
            <div class="alert alert-danger "  role="alert" style="width: 100%;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ $errorr }}
            </div>
        @endforeach 
		   
		@if (Session::get('success'))
		   <div class="alert alert-success " style="width: 100%;">
			   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			   <i class="icon fa fa-check"></i> {{ Session::get('success') }}
		   </div>
		@endif
        @include('includes.purchase-details.pr-sr-tab')
		<div class="tab-content">
            <div class="tab-pane active show" id="purchase">
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
                                    <form autocomplete="off">
                                        <th scope="row">
                                            <div class="row filter_search" style="margin-left: 0px;">
                                            <div class="col-sm-10 col-md- col-lg-10 col-xl-10 row">
                                
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label for="exampleInputEmail1" style="font-size: 12px;">PR No</label>
                                                        <input type="text" value="{{request()->get('pr_no')}}" name="pr_no" class="form-control" placeholder="PR NO">
                                                    </div><!-- form-group -->
                                                    
                                                    
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                    <label for="exampleInputEmail1" style="font-size: 12px;">Item Code</label>
                                                        <input type="text" value="{{request()->get('item_code')}}" name="item_code" id="item_code" class="form-control" placeholder="ITEM CODE">
                                                    
                                                    </div><!-- form-group -->
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label for="exampleInputEmail1" style="font-size: 12px;">Supplier</label>
                                                        <input type="text" value="{{request()->get('supplier')}}" name="supplier" id="supplier" class="form-control" placeholder="SUPPLIER">
                                                        
                                                    </div>
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label  style="font-size: 12px;">Status</label>
                                                        <select name="status" id="status" class="form-control">
                                                            <option value=""> --Select One-- </option>
                                                            <option value="1" {{(request()->get('status') == 1) ? 'selected' : ''}}> Active </option>
                                                            <option value="4" {{(request()->get('status') == 4) ? 'selected' : ''}}> Pending</option>
                                                            <option value="5"{{(request()->get('status') == 5) ? 'selected' : ''}}>On hold</option>
                                                        </select>
                                                    </div> 
                                                                        
                                                </div>
                                                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
                                                        <label style="width: 100%;">&nbsp;</label>
                                                        <button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                        @if(count(request()->all('')) > 1)
                                                            <a href="{{url()->current();}}" class="badge badge-pill badge-warning"
                                                            style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
                                                        @endif
                                                    </div> 
                                                </div>
                                            </div>
                                        </th>
                                    </form>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mg-b-0" id="example1">
                        <thead>
                            <tr>
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
                    
                            @foreach($data['inv_purchase'] as $item)
                            @if($item['PR_SR']=="PR")
                            <tr style="	@if($item['status'] == 5)
                                    background: #ffc1074f;
                                    @endif">
                                <td>{{$item['pr_no']}}</td>
                                <td>{{$item['item_code']}}</td>
                                <td><span>{{$item['vendor_id']}}</span></td>
                                <td>{{$item['actual_order_qty']}}</td>
                                <td>{{$item['rate']}}</td>
                                <td>{{$item['discount_percent']}}</td>
                                <td>{{$item['gst']}}</td>
                                <td>{{$item['currency_code']}}</td>
                                <td>{{$item['net_value']}}</td>
                                {{-- <td><span class="badge badge-pill badge-info ">waiting for Action<span></td> --}}
                                <td>
                                <a href="#" data-toggle="modal" value="{{$item['requisition_item_id']}}" rel="{{$item['vendor_id']}}" orderqty="{{$item['actual_order_qty']}}" type="Purchase" data-target="#myModal" id="change-status" style="width: 64px;" 
                                data-html="true" data-placement="top" 
                                class="badge 
                                @if($item['status'] == 4)
                                    badge-info
                                    @elseif($item['status'] == 5)
                                    badge-warning
                                    @endif
                                ">
                                @if($item['status'] ==4)
                                    Pending
                                @elseif($item['status'] == 5)
                                    On hold
                                @endif
                                </a></td>
                            </tr>	
                            @endif
                            @endforeach
                        
                        </tbody>
                    </table>

                    <div class="box-footer clearfix">
                        {{ $data['inv_purchase']->appends(request()->input())->links() }}
                    </div>
            
                </div>
            </div>
            <div class="tab-pane" id="service">
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
                                    <form autocomplete="off">
                                        <th scope="row">
                                            <div class="row filter_search" style="margin-left: 0px;">
                                            <div class="col-sm-10 col-md- col-lg-10 col-xl-10 row">
                                
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label for="exampleInputEmail1" style="font-size: 12px;">SR No</label>
                                                        <input type="text" value="{{request()->get('pr_no')}}" name="pr_no" class="form-control" placeholder="SR NO">
                                                    </div><!-- form-group -->
                                                    
                                                    
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                    <label for="exampleInputEmail1" style="font-size: 12px;">Item Code</label>
                                                        <input type="text" value="{{request()->get('item_code')}}" name="item_code" id="item_code" class="form-control" placeholder="ITEM CODE">
                                                    
                                                    </div><!-- form-group -->
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label for="exampleInputEmail1" style="font-size: 12px;">Supplier</label>
                                                        <input type="text" value="{{request()->get('supplier')}}" name="supplier" id="supplier" class="form-control" placeholder="SUPPLIER">
                                                        
                                                    </div>
                                                    <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                        <label  style="font-size: 12px;">Status</label>
                                                        <select name="status" id="status" class="form-control">
                                                            <option value=""> --Select One-- </option>
                                                            <option value="1" {{(request()->get('status') == 1) ? 'selected' : ''}}> Active </option>
                                                            <option value="4" {{(request()->get('status') == 4) ? 'selected' : ''}}> Pending</option>
                                                            <option value="5"{{(request()->get('status') == 5) ? 'selected' : ''}}>On hold</option>
                                                        </select>
                                                    </div> 
                                                                        
                                                </div>
                                                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
                                                        <label style="width: 100%;">&nbsp;</label>
                                                        <button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                        @if(count(request()->all('')) > 1)
                                                            <a href="{{url()->current();}}" class="badge badge-pill badge-warning"
                                                            style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
                                                        @endif
                                                    </div> 
                                                </div>
                                            </div>
                                        </th>
                                    </form>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mg-b-0" id="example1">
                        <thead>
                            <tr>
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
                    
                            @foreach($data['inv_purchase'] as $item)
                            @if($item['PR_SR']=="SR")
                            <tr style="	@if($item['status'] == 5)
                                    background: #ffc1074f;
                                    @endif">
                                <td>{{$item['pr_no']}}</td>
                                <td>{{$item['item_code']}}</td>
                                <td><span>{{$item['vendor_id']}}</span></td>
                                <td>{{$item['actual_order_qty']}}</td>
                                <td>{{$item['rate']}}</td>
                                <td>{{$item['discount_percent']}}</td>
                                <td>{{$item['gst']}}</td>
                                <td>{{$item['currency_code']}}</td>
                                <td>{{$item['net_value']}}</td>
                                {{-- <td><span class="badge badge-pill badge-info ">waiting for Action<span></td> --}}
                                <td>
                                <a href="#" data-toggle="modal" value="{{$item['requisition_item_id']}}" rel="{{$item['vendor_id']}}" orderqty="{{$item['actual_order_qty']}}" data-target="#myModal" type="Service" id="change-status" style="width: 64px;" 
                                data-html="true" data-placement="top" 
                                class="badge 
                                @if($item['status'] == 4)
                                    badge-info
                                    @elseif($item['status'] == 5)
                                    badge-warning
                                    @endif
                                ">
                                @if($item['status'] ==4)
                                    Pending
                                @elseif($item['status'] == 5)
                                    On hold
                                @endif
                                </a></td>
                            </tr>	
                            @endif
                            @endforeach
                        
                        </tbody>
                    </table>

                    <div class="box-footer clearfix">
                        {{ $data['inv_purchase']->appends(request()->input())->links() }}
                    </div>
            
                </div>
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
                            <h4 class="modal-title">#Approve <span id="type"></span> Requisition <span class="item-codes"></span></h4>
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
									<option value="5">On hold</option>
                                    <option value="0">Reject</option>
                                </select>
                            </div>
							<div class="form-group">
                                <label for="inputAddress">Approved Qty *</label>
                                <input type="text" name="approved_qty"  class="form-control approved_qty" id="approved_qty" placeholder="Approved Qty">
                            </div> 
                            <div class="form-group">
                                <label for="inputAddress">Remarks *</label>
                                <textarea style="min-height: 100px;" name="reason" type="text" class="form-control" id="reason" placeholder="Remarks"></textarea>
                            </div> 
                            
                        </div>
                        <div class="modal-footer">
                            <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
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
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>

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

	$("#status-change-form").validate({
            rules: {
                status: {
                    required: true,
                },
                approved_qty: {
                    // required: true,
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
  
 

 $('.search-btn').on( "click", function(e)  {
		var supplier = $('#supplier').val();
		var item_code = $('#item_code').val();
		var pr_no = $('#pr_no').val();
		var status = $('#status').val();
		if(!supplier & !item_code & !pr_no & !status )
		{
			e.preventDefault();
		}
	});

</script>
<script>
$(document).ready(function() {
        $('body').on('click', '#change-status', function (event) {
            event.preventDefault();
			$(".item-codes").text('') ;
            $('.approved_qty').val('');
            var orderqty = $(this).attr('orderqty');
            var type = $(this).attr('type');
            $('#type').html(type);
			let purchaseRequisitionItemId = $(this).attr('value');
			$('#purchaseRequisitionItemId').val(purchaseRequisitionItemId);
			$(".item-codes").text('( '+ $(this).attr('rel') + ')');
            $('.approved_qty').val(orderqty);
        });
        
    });

    </script>


@stop