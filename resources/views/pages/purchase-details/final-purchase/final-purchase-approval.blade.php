@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')
<style>
    input[type="checkbox"]{
        appearance: none;
        width: 25px;
        height: 25px;
        content: none;
        outline: none;
        margin: 0;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }
    

    input[type="checkbox"]:checked {
        appearance: none;
        outline: none;
        padding: 0;
        content: none;
        border: none;
    }

    input[type="checkbox"]:checked::before{
        position: absolute;
        color: white !important;
        content: "\00A0\2713\00A0" !important;
        border: 1px solid #d3d3d3;
        font-weight: bolder;
        font-size: 18px;
    }

               
                
</style>
<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				<span><a href="">Final @if(request()->get('order_type')=='wo') Work @else Purchase @endif Order</a></span>
                <span><a href="">@if(request()->get('order_type')=='wo') Work @else Purchase @endif Order Approval</a></span>
			</div>
			<h4 class="az-content-title" style="font-size: 20px;">@if(request()->get('order_type')=='wo') Work @else Purchase @endif Order Approval
            <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/final-purchase/excel-export').'?'.http_build_query(array_merge(request()->all()))}}'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button>  
                
            </h4><br/>
			
		   @if (Session::get('success'))
		   <div class="alert alert-success " style="width: 100%;">
			   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			   <i class="icon fa fa-check"></i> {{ Session::get('success') }}
		   </div>
		   @endif
           
            @include('includes.purchase-details.purchase-work-order-tab')
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
                                        <form autocomplete="off">
                                            <th scope="row">
                                                <div class="row filter_search" style="margin-left: 0px;">
                                                    <div class="col-sm-10 col-md- col-lg-10 col-xl-10 row">
                                
                                                        <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                            <label>RQ No:</label>
                                                            <input type="text" value="{{request()->get('rq_no')}}" name="rq_no" id="rq_no" class="form-control" placeholder="RQ NO"> 
                                                            <input type="hidden" value="{{request()->get('order_type')}}" id="order_type"  name="order_type">
                                                        </div><!-- form-group -->
                                                        <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                            <label>@if(request()->get('order_type')=='wo') WO @else PO @endif No:</label>
                                                            <input type="text" value="{{request()->get('po_no')}}" name="po_no" id="po_no" class="form-control" placeholder="@if(request()->get('order_type')=='wo') WO NO @else PO NO @endif">
                                                            
                                                        </div><!-- form-group -->
                                                        
                                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                            <label for="exampleInputEmail1" style="font-size: 12px;">Supplier</label>
                                                            <input type="text" value="{{request()->get('supplier')}}" name="supplier" id="supplier" class="form-control" placeholder="SUPPLIER">
                                                            
                                                        </div>
                                                        <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                            <label  style="font-size: 12px;">@if(request()->get('order_type')=='wo') WO @else PO @endif Date </label>
                                                            <input type="text" value="{{request()->get('po_from')}}" id="po_from" class="form-control datepicker" name="po_from" placeholder="MM-YYYY">
                                                        </div>
                                                        <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                            <label  style="font-size: 12px;">Status</label>
                                                            <!-- <input type="text" value="{{request()->get('from')}}" id="from" class="form-control datepicker" name="from" placeholder="@if(request()->get('order_type')=='work-order') WO @else PO @endif DATE (MM-YYYY)"> -->
                                                            <select name="status" id="status" class="form-control">
                                                                <option value=""> --Select One-- </option>
                                                                <!-- <option value="1" {{(request()->get('status') == 1) ? 'selected' : ''}}> Active </option> -->
                                                                <option value="4" {{(request()->get('status') == 4) ? 'selected' : ''}}> Pending</option>
                                                                <option value="5"{{(request()->get('status') == 5) ? 'selected' : ''}}>On hold</option>
                                                                <option value="1"{{(request()->get('status') == 1) ? 'selected' : ''}}>Approved</option>
                                                                <option value="reject" {{(request()->get('status') == 'reject') ? 'selected' : ''}}> Cancelled </option>
                                                            </select>
                                                        </div> 
                                                        {{-- <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                            <label  style="font-size: 12px;">Processed Date</label>
                                                            <input type="text" value="{{request()->get('processed_from')}}" id="processed_from" class="form-control datepicker" name="processed_from" placeholder="MM-YYYY">
                                                        </div>  --}}
                                                                        
                                                    </div>
                                                    <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
                                                            <label style="width: 100%;">&nbsp;</label>
                                                            <button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                            @if(count(request()->all('')) > 2)
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
                <div class="tab-pane active show " id="purchase">
                        <form autocomplete="off" action="{{ url('inventory/final-purchase/approval') }}?order_type={{request()->get('order_type')}}" method="POST" id="approve-form">
                        {{ csrf_field() }} 
                        <style>
                            tbody tr{
                                font-size:12.9px;
                            }
                        </style>
                        <div class="table-responsive">
                            <div style="float:right;">
                                <input type="checkbox" class="item-select-radio check-approve bg-success text-white check-all-approve"  style="color:green;width:20px;height:20px;">
                                <span style="vertical-align: middle;"><label  style="font-size: 12px;">Approve All</label></span>
                                <input type="checkbox" class="item-select-radio check-hold bg-warning text-dark check-all-hold"   style="color:yellow;width:20px;height:20px;">
                                <span style="vertical-align: middle;"><label  style="font-size: 12px;"><span>On Hold All</span></label></span>
                                <input type="checkbox" class="item-select-radio check-reject bg-danger text-white check-all-reject"  style="color:red;width:20px;height:20px;">
                                <span style="vertical-align: middle;"><label  style="font-size: 12px;"><span>Reject All</span></label></span>
                            </div>
                            <table class="table table-bordered mg-b-0" id="example1">
                                <thead>
                                    <tr>
                                    
                                        <th style="width:120px;">RQ NO:</th>
                                        <th>@if(request()->get('order_type')=="wo") WO @else PO @endif No</th>
                                        <th>PO date</th>
                                        <th>Supplier</th>
                                        <th>Created Date</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                        @if(request()->get('status')==4 || request()->get('status')==5 || !request()->get('status'))
                                        <th>Status Change</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['po_data'] as $po_data)
                                    <tr style="	@if($po_data->status == 5)
                                        background: #ffc1074f;
                                        @endif @if($po_data->status == 0) background: #ffc1074f;
                                        @endif"
                                        >
                                        <td>{{$po_data->rq_no}}</td>
                                        <td>{{$po_data->po_number}}</td>
                                        <td>{{date('d-m-Y',strtotime($po_data->po_date))}}</td>
                                        <td>{{$po_data->vendor_id}} - {{$po_data->vendor_name}}</td>
                                        <td>{{date('d-m-Y',strtotime($po_data->created_at))}}</td>
                                        <td>{{$po_data->f_name}} {{$po_data->l_name}}</td>
                                        <td>
                                        
                                        <button data-toggle="dropdown" style="width: 68px;" class="badge 
                                            @if($po_data->status==1) badge-success @elseif($po_data->status==4)  badge-warning @elseif($po_data->status==5)  badge-warning @elseif($po_data->status==0) badge-danger @endif"> 
                                            @if($po_data->status==1) 
                                                Approved 
                                            @elseif($po_data->status==4)  
                                                pending
                                            @elseif($po_data->status==5)  
                                                On hold
                                            @elseif($po_data->status==0)  
                                                Cancelled
                                            @endif
                                            <i class="icon ion-ios-arrow-down tx-11 mg-l-3"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            @if($po_data->status!=0 && $po_data->status!=1)
                                            <a href="#" data-toggle="modal"  po="{{$po_data->po_number}}" status="{{$po_data->status}}" orderqty="" value="{{$po_data->po_id}}" data-target="#approveModal" id="approve-model" class="approve-model dropdown-item "><i class="fa fa-check-circle"></i> Approve</a>
                                            @endif
                                            @if(in_array('order.view',config('permission')))
                                            <a href="{{url('inventory/final-purchase-view/'.$po_data->id)}}" class="dropdown-item" style="padding:2px 15px;"><i class="fas fa-eye"></i> View</a>
                                            @endif
                                            @if(in_array('order.delete',config('permission')))
                                            <a href="{{url('inventory/final-purchase-delete/'.$po_data->id)}}" class="dropdown-item"onclick="return confirm('Are you sure you want to delete this ?');"><i class="fa fa-trash"></i> Delete</a>
                                            @endif
                                        </div>
                                           {{-- @if($po_data->status!=0 && $po_data->status!=1)
                                            <a href="#" data-toggle="modal"  po="{{$po_data->po_number}}" status="{{$po_data->status}}" orderqty="" value="{{$po_data->po_id}}" data-target="#approveModal" id="approve-model" class="approve-model badge badge-success" style="color: #141c2b;text-decoration:none;padding:6px;color:white;">
                                                <i class="fa fa-check-circle"></i> Approve
                                            </a>
                                            @endif  --}}
                                            
                                           {{-- @if($po_data->status==1) --}}
                                            <a class="badge badge-default" style="font-size: 13px; color:black;border:solid black;border-width:thin;" href="{{url('inventory/final-purchase/pdf/'.$po_data->id)}}" target="_blank"><i class="fas fa-file-pdf" style='color:red'></i>&nbsp;PDF</a>
                                            {{-- @endif --}}
                                        </td>
                                        @if(request()->get('status')==4 || request()->get('status')==5 || !request()->get('status'))
                                        <td style="width:12%;" class="checkbox-group">
                                            @if($po_data['status']==4 || $po_data['status']==5 )
                                            <input type="checkbox" class="item-select-radio check-approve bg-success text-white approve" id="check-approve" name="check_approve[]" value="{{$po_data['id']}}" style="color:green;">
                                            <input type="checkbox" class="item-select-radio check-hold bg-warning text-dark hold"  id="check-hold" @if($po_data['status'] == 5)  checked @endif name="check_hold[]" value="{{$po_data['id']}}" style="color:yellow;">
                                            <input type="checkbox" class="item-select-radio check-reject bg-danger text-white reject" id="check-reject" name="check_reject[]" value="{{$po_data['id']}}" style="color:red;">
                                            @endif
                                        </td>
                                    @endif
                                    </tr>
                                    
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="box-footer clearfix">
                                {{ $data['po_data']->appends(request()->input())->links() }}
                            </div> 
                            <br/>
                            @if(count($data['po_data'])>0)
                                <div class="row">
                                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <button type="submit" class="btn btn-primary btn-rounded btn-submit" style="float: right;" >
                                            <span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  
                                            <i class="fas fa-save"></i>
                                            Save 
                                        </button>
                                    </div>
                                </div>
                            @endif
                    
                        </div>
                    </form>
                </div>

            </div>
		</div>
	</div>
	<!-- az-content-body -->
    <div id="approveModal" class="modal">
        <div class="modal-dialog modal-md" role="document">
            <form id="status-change-form" method="post" action="{{ url('inventory/final-purchase/change/status')}}">
                {{ csrf_field() }} 
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"># @if(request()->get('order_type')=="wo") Work Order @else Purchase Order @endif Approval<span class="po_number"></span></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="inputAddress2">Status *</label><br>
                            {{-- <input type="text" name="purchaseRequisitionMasterId" id ="purchaseRequisitionMasterId" value=" "> --}}
							<input type="hidden" name="po_id" id ="po_id" >
                            <input type="hidden" name="poa" value="poa">
                            <input type="hidden" value="{{request()->get('order_type')}}" id="order_type"  name="order_type">
                            <select class="form-control" name="status" id="status">
								<option value="1"> Approve</option>
                            </select>
                        </div> 
                        <div class="form-group">
                            <label>Date *</label>
                            <input type="text" 
                                value="{{date('d-m-Y')}}" class="form-control datepicker2" name="date" placeholder="Date">
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Processed By *</label><br/>
                            <style>
                                    .select2-container .select2-selection--single {
                                        height: 38px;
                                        width: 450px;
                                    }
                                    .select2-container--default .select2-selection--single .select2-selection__arrow b{
                                        margin-left: 242px;
                                        margin-top: 2px;
                                    }
                                    .select2-container--open .select2-dropdown--above{
                                        width:445px;
                                    }
                                    .select2-container--default .select2-results>.select2-results__options{
                                        width: 433px;
                                    }
                            </style>
                            <select class="form-control select2 approved_by" name="approved_by">
                                <option value="">--- select one ---</option>
                                @foreach($data['users'] as $user)
                                <option value="{{$user['user_id']}}">{{$user['f_name']}} {{$user['l_name']}}</option>
                                @endforeach
                            </select>
                        </div> 
                        <div class="form-group">
                            <label for="inputAddress">Remarks</label>
                            <textarea style="min-height: 100px;" name="remarks" type="text" class="form-control" id="remarks" placeholder="Remarks"></textarea>
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
    $(".datepicker2").datepicker({
        format: " dd-mm-yyyy",
        autoclose:true
    });
    $("#status-change-form").validate({
            rules: {
                status: {
                    required: true,
                },
                date: {
                    required: true,
                },
                remarks: {
                    required: true,
                },
                approved_by:{
                    required: true,
                }
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                //form.submit();
            }
    });

  });
    

  
	$('.search-btn').on( "click", function(e)  {
		var supplier = $('#supplier').val();
		var rq_no = $('#rq_no').val();
		var po_no = $('#po_no').val();
		var po_from = $('#from').val();
        var processed_from = $('#processed_from').val();
        var status = $('#status').val();
		if(!supplier & !rq_no & !po_no & !from & !processed_from & !status)
		{
			e.preventDefault();
		}
	});

    $(document).ready(function() {
        $('body').on('click', '#approve-model', function (event) {
            event.preventDefault();
            var po = $(this).attr('po');
            // alert(po);
            $('.po_number').html('('+po+')');
			let po_id = $(this).attr('value');
			$('#po_id').val(po_id);
            let status = $(this).attr('status');
            $('#status option').each(function(){
                if (this.value == status) {
                  $(this).remove();
                }
            });
            //$('#status')

        });   
        
    });
    $(".check-all-approve").click(function () {
     $('.approve').not(this).prop('checked', this.checked);
    });
    $(".check-all-hold").click(function () {
     $('.hold').not(this).prop('checked', this.checked);
    });
     $(".check-all-reject").click(function () {
     $('.reject').not(this).prop('checked', this.checked);
    });
    $(".checkbox-group").each(function (i, li) {
        var currentgrp = $(li);
        $(currentgrp).find(".check-approve").on('change', function () {
            $(currentgrp).find(".check-hold").not(this).prop('checked',false);
             $(currentgrp).find(".check-reject").not(this).prop('checked',false);
        });

        $(currentgrp).find(".check-hold").on('change', function () {
            $(currentgrp).find(".check-approve").not(this).prop('checked', false);
            $(currentgrp).find(".check-reject").not(this).prop('checked',false);
        });
         $(currentgrp).find(".check-reject").on('change', function () {
            $(currentgrp).find(".check-approve").not(this).prop('checked', false);
            $(currentgrp).find(".check-hold").not(this).prop('checked',false);
        });
    });

	
</script>


@stop