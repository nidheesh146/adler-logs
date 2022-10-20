@extends('layouts.default')
@section('content')
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
			<div class="az-content-breadcrumb">  <span>Purchase details</span>
                <span>@if(request()->get('prsr')=='sr') Service @else Purchase @endif Requisition</span> 
                <span>@if(request()->get('prsr')=='sr') Service @else Purchase @endif Requisition Approval</span> 
            </div>
			<h4 class="az-content-title" style="font-size: 20px;">
                @if(request()->get('prsr')=='sr') Service @else Purchase @endif Requisition Approval
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
                                    <form autocomplete="off" id="formfilter" method="GET">
                                        <th scope="row">
                                            <div class="row filter_search" style="margin-left: 0px;">
                                            <div class="col-sm-10 col-md- col-lg-10 col-xl-10 row">
                                
                                                    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                        <label for="exampleInputEmail1" style="font-size: 12px;">@if(request()->get('prsr')!='sr') PR No @else SR No @endif</label>
                                                        <input type="text" value="{{request()->get('pr_no')}}" name="pr_no" class="form-control" placeholder="@if(request()->get('prsr')!='sr') PR NO @else SR NO @endif">
                                                    </div><!-- form-group -->
                                                    <input type="hidden" value="{{request()->get('prsr')}}" id="prsr"  name="prsr">
                                                    
                                                    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label for="exampleInputEmail1" style="font-size: 12px;">Item Code</label>
                                                        <input type="text" value="{{request()->get('item_code')}}" name="item_code" id="item_code" class="form-control" placeholder="ITEM CODE">
                                                    
                                                    </div><!-- form-group -->
                                
                                                    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                        <label  style="font-size: 12px;">Status</label>
                                                        <select name="status" class="form-control">
                                                            <option value=""> --Select One-- </option>
                                                            <option value="1" {{(request()->get('status') == 1) ? 'selected' : ''}}> Approved</option>
                                                            <option value="4" {{(request()->get('status') == 4) ? 'selected' : ''}}> Pending</option>
                                                            <option value="5"{{(request()->get('status') == 5) ? 'selected' : ''}}>On hold</option>
                                                            <option value="0"{{(request()->get('status') == '0') ? 'selected' : ''}}>Rejected</option>


                                                        </select>
                                                    </div> 
                                                                        
                                                </div>
                                                <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0 0 0px 6px;">
                                                        <label style="width: 100%;">&nbsp;</label>
                                                        <button type="submit" class="badge badge-pill badge-primary search-btn" onclick="document.getElementById('formfilter').submit();" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
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
            <div class="tab-pane active  show">
                <form autocomplete="off" action="{{ url('inventory/purchase-reqisition/approval') }}?prsr={{request()->get('prsr')}}" method="POST" id="approve-form">
                {{ csrf_field() }}  
                    <div class="table-responsive">
                        <div style="float:right;">
                        <input type="checkbox" class="item-select-radio check-approve bg-success text-white"  style="color:green;width:20px;height:20px;">
                        <span style="vertical-align: middle;"><label  style="font-size: 12px;">Approve</label></span>
                        <input type="checkbox" class="item-select-radio check-hold bg-warning text-dark"   style="color:yellow;width:20px;height:20px;">
                        <span style="vertical-align: middle;"><label  style="font-size: 12px;"><span>On Hold</span></label></span>
                        <input type="checkbox" class="item-select-radio check-reject bg-danger text-white"  style="color:red;width:20px;height:20px;">
                        <span style="vertical-align: middle;"><label  style="font-size: 12px;"><span>Reject</span></label></span>
                        </div>
                        <table class="table table-bordered mg-b-0" id="example1">
                            <thead>
                                <tr>
                                    <th>@if(request()->get('prsr')!='sr') PR No @else SR No @endif</th>
                                    <th>Item code </th>
                                    <th>Description</th>
                                    <th>Qrder Qty</th>
                                    <th style="width:15%;">Processed info</th>
                                    <th>Status</th>
                                    @if(request()->get('status')==4 || request()->get('status')==5 || !request()->get('status'))
                                    <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody >
                        
                                @foreach($data['inv_purchase'] as $item)
                            
                                <tr style="	@if($item['status'] == 5)
                                        background: #ffc1074f;
                                        @endif @if($item['status'] == 0) background: #ffc1074f;
                                        @endif"
                                        >
                                    <td>{{$item['pr_no']}}</td>
                                    <td>{{$item['item_code']}}</td>
                                    <td>{{$item['short_description']}}</td>
                                    <td>{{$item['actual_order_qty']}} {{$item['unit_name']}}</td>
                                    <td>@if($item['updated_at']!=NULL) on: {{date( 'd-m-Y' , strtotime($item['updated_at']))}} @endif<br/>
                                        @if($item['f_name'])By:{{$item['f_name']}} {{$item['l_name']}} @endif</td>
                                    <!-- <td>@if($item['updated_at']!=NULL) {{date( 'd-m-Y' , strtotime($item['updated_at']))}} @endif</td> -->
                                    {{-- <td><span class="badge badge-pill badge-info ">waiting for Action<span></td> --}}
                                    <td>
                                        <a href="#"   id="change-status" style="width: 64px;" class="badge 
                                        @if($item['status'] == 4)
                                            badge-info
                                            @elseif($item['status'] == 5)
                                            badge-warning
                                            @elseif($item['status'] == 0)
                                            badge-danger
                                            @elseif($item['status'] == 1)
                                            badge-primary
                                            @endif
                                        ">
                                        @if($item['status'] ==4)
                                            Pending
                                        @elseif($item['status'] == 5)
                                            On hold
                                        @elseif($item['status'] == 0)
                                            Rejected
                                        @elseif($item['status'] == 1)
                                            Approved
                                        @endif

                                        </a>
                                    </td>
                                    @if(request()->get('status')==4 || request()->get('status')==5 || !request()->get('status'))
                                    <td style="width:12%;" class="checkbox-group">
                                        @if($item['status']==4 || $item['status']==5 )
                                        <input type="checkbox" class="item-select-radio check-approve bg-success text-white" id="check-approve" name="check_approve[]" value="{{$item['requisition_item_id']}}" style="color:green;">
                                        <input type="checkbox" class="item-select-radio check-hold bg-warning text-dark"  id="check-hold" @if($item['status'] == 5)  checked @endif name="check_hold[]" value="{{$item['requisition_item_id']}}" style="color:yellow;">
                                        <input type="checkbox" class="item-select-radio check-reject bg-danger text-white" id="check-reject" name="check_reject[]" value="{{$item['requisition_item_id']}}" style="color:red;">
                                        @endif
                                    </td>
                                   @endif
                                  
                                    
                                </tr>	
                                
                                @endforeach
                            
                            </tbody>
                        </table>

                        <div class="box-footer clearfix">
                            {{ $data['inv_purchase']->appends(request()->input())->links() }}
                        </div><br/>
                        @if($data['inv_purchase'])
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

	<div id="myModal" class="modal">
                <div class="modal-dialog modal-md" role="document">
                    <form id="status-change-form" method="post" action="{{ url('inventory/purchase-reqisition/approval')}}">
                    {{ csrf_field() }} 
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">#Approve <span id="type"></span> Requisition Item <span  class="item-codes"></span></h4>
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
							<div class="form-group qty-div">
                                <label for="inputAddress">Approved Qty *</label>
                                <input type="text" name="approved_qty"  class="form-control approved_qty" id="approved_qty" placeholder="Approved Qty">
                            </div> 

                            <div class="form-group">
                                <label for="inputAddress">Approved By *</label><br/>
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
                            <input type="hidden" value="" id="prsr"  name="prsr" class="prsr">
                            <div class="form-group">
                                <label for="inputAddress">Remarks </label>
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
                // reason: {
                //     required: true,
                // },
                approved_by:{
                    required: true,
                }
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });
    
  });
    
  
  $('.approved_by').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
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
    $('.btn-submit').on( "click", function()  {
		var approve = $('.check-approve').val();
		var hold = $('.check-hold').val();
		var reject = $('.check-reject').val();
		if(!approve || !hold || !reject)
		{
			$('.btn-submit').removeAttr('disabled');
		}
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
<script>
$(document).ready(function() {
        $('body').on('click', '#change-status', function (event) {
            event.preventDefault();
			$(".item-codes").text('') ;
            $('.approved_qty').val('');
            $('.prsr').val('');
            var orderqty = $(this).attr('orderqty');
            var type = $(this).attr('type');
            $('#type').html(type);
			let purchaseRequisitionItemId = $(this).attr('value');
			$('#purchaseRequisitionItemId').val(purchaseRequisitionItemId);
			//$(".item-codes").text('( '+ $(this).attr('rel') + ')');
            $('.approved_qty').val(orderqty);
            if(type=="Service"){
                let prsr ='sr';
                $('.prsr').val(prsr);
                // $('.qty-div').append('<input type="hidden" value="sr" id="prsr"  name="prsr">');
            }else {
                let prsr ='pr';
                $('.prsr').val(prsr);
                // $('.qty-div').append('<input type="hidden" value="pr" id="prsr"  name="prsr">');
            }
        });
        
    });

    </script>


@stop