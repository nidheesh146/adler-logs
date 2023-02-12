@extends('layouts.default')
@section('content')
@inject('fn', 'App\Http\Controllers\Web\PurchaseDetails\PurchaseController')
    <div class="az-content az-content-dashboard">
        <br>
        <div class="container" data-select2-id="9">
            <div class="az-content-body" data-select2-id="8">
                <div class="az-content-breadcrumb">
                    <span><a href="">@if ($data['master']['type'] == 'PO')FINAL PURCHASE @else WORK @endif ORDER</a></span>
                    <span>@if ($data['master']['type'] == 'PO') FINAL PURCHASE @else WORK @endif VIEW</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">@if ($data['master']['type'] == 'PO') FINAL PURCHASE @else WORK @endif VIEW

                </h4>
                @if (Session::get('success'))
                <div class="alert alert-success " style="width: 100%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                </div>
                @endif

                <!-- <div class="row">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                            Supplier Invoice :
                        </label>
                        <div class="form-devider"></div>
                    </div>
                </div> -->
                
                <div class="data-bindings">
                @if($data['master'])
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                            <i class="fas fa-hand-point-right"></i>
                            Final @if ($data['master']['type'] == 'PO') Purchase @else Work @endif Order ({{$data['master']['po_number']}})
                                </label>
                            <div class="form-devider"></div>
                        </div>
                    </div>
                    <table class="table table-bordered mg-b-0">    
                        <tbody>
                            <tr>
                                <th>@if (str_starts_with($data['master']['po_number'], 'PO')) PO @else WO @endif No</th>
                                <th>{{$data['master']['po_number']}}</th>
                                <th>@if (str_starts_with($data['master']['po_number'], 'PO')) PO @else WO @endif Date</th>
                                <th>{{date('d-m-Y',strtotime($data['master']['po_date']))}}</th>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <th>@if($data['master']['status']==1)
                                    <span class="badge badge-success">Approved</span>
                                    @elseif($data['master']['status']==4)
                                    <span class="badge badge-warning">Pending</span>
                                    @elseif($data['master']['status']==5)
                                    <span class="badge badge-warning">On Hold</span>
                                    @elseif($data['master']['status']==0)
                                    <span class="badge badge-danger">Cancelled</span>
                                    @endif
                                </th>
                                <th>Supplier</th>
                                <th>{{$data['master']['vendor_id']}}-{{$data['master']['vendor_name']}}</th>
                            </tr>
                            <tr>
                                <th>Created By</th>
                                <th>@if($data['master']['order_created_by'])<?php $user = $fn->find_user($data['master']['order_created_by']); ?>
                                    {{$user['f_name']}} {{$user['l_name']}}
                                    @endif</th>
                                <th>Created at</th>
                                <th>{{date('d-m-Y h:i:sa',strtotime($data['master']['created_at']))}}</th>
                            </tr>
                            <tr>
                                <th>Processed By</th>
                                <th>@if($data['master']['processed_by'])<?php $user = $fn->find_user($data['master']['processed_by']); ?>
                                    {{$user['f_name']}} {{$user['l_name']}}
                                    @endif
                                </th>
            
                                <th>Processed at</th>
                                <th>{{date('d-m-Y h:i:sa',strtotime($data['master']['processed_at']))}}</th>
                            </tr>
                            <tr>
                                <th>Remarks</th>
                                <th colspan="3">{{$data['master']['remarks']}}</th> 
                            </tr>
                        </tbody> 
                        <!-- <tbody>
                            <tr>
                                <td>{{date('d-m-Y',strtotime('18-09-2022'))}}</td>
                                <td>{{date('d-m-Y',strtotime('19-09-2022'))}}</td>
                            </tr>
                        </tbody> -->
                    </table>
                   
                    <br/>
                    <div class="form-devider"></div>
                    
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                            <i class="fas fa-hand-point-right"></i>
                                Quotation Details({{$data['master']['rq_no']}})
                                </label>
                            <div class="form-devider"></div>
                        </div>
                    </div>
                    <table class="table table-bordered mg-b-0">     
                        <thead>
                            <tr>
                                <th>Quotation No</th>
                                <th>{{$data['master']['rq_no']}}</th>
                                <th>Qotation Date</th>
                                <th>{{date('d-m-Y',strtotime($data['master']['rq_date']))}}</th>
                            </tr>
                            <tr>
                                <td>Delivery Schedule</td>
                                <td>{{date('d-m-Y',strtotime($data['master']['delivery_schedule']))}}</td>
                                <td>Created By</td>
                                <td><?php $user = $fn->find_user($data['master']['rq_created_user']); ?>
                                    {{$user['f_name']}} {{$user['l_name']}}
                                </td>
                            </tr>
                        </thead>
                    </table><br>
                    
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                            @if (str_starts_with($data['master']['po_number'], 'PO')) Purchase @else Work @endif Order items</label>
                            <div class="form-devider"></div>
                        </div>
                    </div>
                    @endif
                    <div class="table-responsive">
                            <table class="table table-bordered mg-b-0" id="example1">
                                <thead>
                                    <tr>
                                        <th>Sl No.</th>
                                        <th>Item Code</th>
                                        <th>Quantity</th>
                                        <th>Rate</th>
                                        <th>Discount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <?php $i=1; ?>
                                    @foreach($data['items'] as $item)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$item['item_code']}}</td>
                                        <td>{{$item['order_qty']}}{{$item['unit_name']}}</td>
                                        <td>{{$item['rate']}}</td>
                                        <td>{{$item['discount']}}</td>
                                        <td><a href="" data-toggle="modal"  data-target="#excessOrderModal" class="excess-order-model badge badge-primary"   id="excess-order-model" po="{{$data['master']['po_number']}}"
                                         item="{{$item['item_code']}}" orderQty="{{$item['order_qty']}}" unit="{{$item['unit_name']}}" rq="{{$data['master']['rq_no']}}" supplier="{{$data['master']['vendor_name']}}" 
                                         purchaseItem="{{$item['purchase_item_id']}}" poId="{{$data['master']['id']}}" style="width:92px;padding:6px;margin-top:2px;background-color:#FF5733 ;color:white;">
                                            <i class="fa fa-window-close"></i> Excess Order
                                        </a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- az-content-body -->
        <div id="excessOrderModal" class="modal">
        <div class="modal-dialog modal-lg" role="document">
            <form id="excess-order-form" method="post" action="{{url('inventory/final-purchase/excess-qty-order')}}">
                {{ csrf_field() }} 
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">#Excess Order Quantity-@if(request()->get('order_type')=="wo") Work Order @else Purchase Order @endif <span class="po_number"></span></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                    <div class="row">
                            <div class="col-sm-8 col-md-8 col-lg-8 col-xl-8">
                               
                                <div class="po">
                                    <label>
                                        Supplier:
                                    </label>
                                    <span class="supplier"></span>
                                </div>
                                <label>
                                    RQ  Number:
                                </label>
                                <span class="rq-number"></span>
                            </div>
                            <div class="form-devider"></div>
                            <table class="table table-bordered ">
                            <tr>
                                <td class="item" style="vertical-align: middle;"></td>
                                <td>
                                    <label>Actual Order Quantity </label> 
                                    <div class="input-group">
                                        <input type="text" class="order-qty " id="order-qty" name="qty" disabled aria-describedby="unit-div">
                                        <div class="input-group-append">
                                            <span class="input-group-text unit-div" id="unit-div"></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <label>Excess Quantity</label> 
                                        <div class="input-group">
                                        <input type="text" min="0" class="excess-qty" id="excess-qty" name="excess_qty" required aria-describedby="unit-div1">
                                        <div class="input-group-append">
                                            <span class="input-group-text unit-div" id="unit-div1"></span>
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{request()->get('order_type')}}" id="order_type"  name="order_type">
                                    <input type="hidden" name="purchase_item_id" id="purchase_item" value="">
                                    <input type="hidden" name="po_id" id="po_id" value="">
                                </td>
                            <tr>
                            </table>
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

    <script src="<?=url('');?>/js/azia.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
    <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
    <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
    <script src="<?= url('') ?>/js/jquery.validate.js"></script>
    <script src="<?= url('') ?>/js/additional-methods.js"></script>
    <script>
        $(document).ready(function() {
            $('body').on('click', '#excess-order-model', function (event) {
                //$(".binding").empty();
                $('#excess-qty').val('');
                event.preventDefault();
                var po = $(this).attr('po');
                $('.po-number').html(po);
                $('.po_number').html('('+po+')');
                let po_id = $(this).attr('poId');
                $('#po_id').val(po_id);
                var rq = $(this).attr('rq');
                $('.rq-number').html(rq);
                var supplier = $(this).attr('supplier');
                $('.supplier').html(supplier);
                var po_date = $(this).attr('podate');
                $('.po-date').html(po_date);
                var item = $(this).attr('item');
                $('.item').html(item);
                var orderQty = $(this).attr('orderQty');
                $('#order-qty').val(orderQty);
                var unit = $(this).attr('unit');
                $('#unit-div1').html(unit);
                $('#unit-div').html(unit);
                var purchaseItem = $(this).attr('purchaseItem');
                $('#purchase_item').val(purchaseItem);


            });
        });
    </script>

@stop
