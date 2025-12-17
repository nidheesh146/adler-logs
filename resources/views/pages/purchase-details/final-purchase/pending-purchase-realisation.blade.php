@extends('layouts.default')
@section('content')

@inject('SupplierQuotation', 'App\Http\Controllers\Web\PurchaseDetails\SupplierQuotationController')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">
			<div class="az-content-breadcrumb"> 
				<span><a href="">R02-Pending Purchase Realisation</a></span>
			</div>
			<h4 class="az-content-title" style="font-size: 20px;">R02-Pending Purchase Realisation
               
                <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/pending-purchase-realisation/excel-export').'?'.http_build_query(array_merge(request()->all()))}}'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button> 
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
                                                            <label>@if(request()->get('order_type')=='wo') WO @else PO @endif No:</label>
                                                            <input type="text" value="{{request()->get('po_no')}}" name="po_no" id="po_no" class="form-control" placeholder="@if(request()->get('order_type')=='wo') WO NO @else PO NO @endif">
                                                            
                                                        </div><!-- form-group -->
                                                        <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                            <label>@if(request()->get('order_type')=="wo") SR @else PR @endif  No:</label>
                                                            <input type="text" value="{{request()->get('pr_no')}}" name="pr_no" id="pr_no" class="form-control" placeholder="@if(request()->get('order_type')=="wo") SR @else PR @endif  NO"> 
                                                            <input type="hidden" value="{{request()->get('order_type')}}" id="order_type"  name="order_type">
                                                        </div><!-- form-group -->
                                                        <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                            <label for="exampleInputEmail1" style="font-size: 12px;">Item Code</label>
                                                            <input type="text" value="{{request()->get('item_code')}}" name="item_code" id="item_code" class="form-control" placeholder="ITEMCODE">
                                                            
                                                        </div>
                                                        <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                            <label for="exampleInputEmail1" style="font-size: 12px;">Supplier</label>
                                                            <input type="text" value="{{request()->get('supplier')}}" name="supplier" id="supplier" class="form-control" placeholder="SUPPLIER">
                                                            
                                                        </div>
                                                       
                                                        <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                            <label  style="font-size: 12px;">@if(request()->get('order_type')=='wo') WO @else PO @endif Date (From)</label>
                                                            <input type="text" value="{{request()->get('po_from')}}" id="po_from" class="form-control datepicker" name="po_from" placeholder="DD-MM-YYYY">
                                                        </div>
                                                        <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                            <label  style="font-size: 12px;">@if(request()->get('order_type')=='wo') WO @else PO @endif Date(To) </label>
                                                            <input type="text" value="{{request()->get('po_to')}}" id="po_to" class="form-control datepicker" name="po_to" placeholder="DD-MM-YYYY">
                                                        </div>
                                                                        
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
                    <style>
                        tbody tr{
                            font-size:12.9px;
                        }
                    </style>
                    <div class="table-responsive">
                        <table class="table table-bordered mg-b-0" id="example1">
                            <thead>
                                <tr>
                                    <th>@if(request()->get('order_type')=="wo") SR @else PR @endif Number</th>
                                    <th style="width:120px;">@if(request()->get('order_type')=="wo") WO @else PO @endif number </th>
                                    <th>Item Code</th>
                                    <th>Type</th>
                                    <th>Order Qty</th>
                                    <th>Pending Qty</th>
                                    <th>RATE</th>
                                    <th>DISCOUNT</th>
                                    <th>GST</th>
                                    <th>Supplier</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $item)
    @if(abs(floatval($item['qty_to_invoice'])) > 0.0001)
        <tr>
            <td>{{$item['pr_number']}}</td>
            <td>{{$item['po_number']}}</td>
            <td>
                <a href="#" style="color:#3b4863;" data-toggle="tooltip" data-placement="top" title="{{$item['short_description']}}">
                    {{$item['item_code']}}
                </a>
            </td>
            <td>{{$item['type']}}</td>
            <td>{{$item['order_qty']}} {{$item['unit_name']}}</td>
            <td>{{ number_format($item['qty_to_invoice'], 2) }} {{$item['unit_name']}}</td>
            <td>{{$item['rate']}}</td>
            <td>{{$item['discount']}}</td>
            <td>
                @if($item['igst'] != 0)
                    IGST: {{$item['igst']}}%<br/>
                @endif
                @if($item['cgst'] != 0)
                    CGST: {{$item['cgst']}}%<br/>
                @endif
                @if($item['sgst'] != 0)
                    SGST: {{$item['sgst']}}%
                @endif
            </td>
            <td>{{$item['vendor_name']}}</td>
        </tr>
    @endif
@endforeach
      

                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                        {{ $data->appends(request()->input())->links() }}
                        </div> 
                
                
                    </div>
                </div>

            </div>
		</div>
	</div>
	
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
        format: "dd-mm-yyyy",
        // viewMode: "months",
        // minViewMode: "months",
         endDate: date,
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
		var pr_no = $('#pr_no').val();
        var item_code = $('#item_code').val();
		var po_no = $('#po_no').val();
		var po_from = $('#po_from').val();
        var po_to = $('#po_to').val();
        //var processed_from = $('#processed_from').val();
        var status = $('#status').val();
		if(!supplier & !rq_no & !po_no & !po_from & !po_to & !status & !item_code)
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