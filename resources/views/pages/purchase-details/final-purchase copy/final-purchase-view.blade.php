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
                                <th>@if($data['master']['processed_date']!=NULL) {{date('d-m-Y ',strtotime($data['master']['processed_date']))}} @endif</th>
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
                                        <th>HSN Code</th>
                                        <th>Quantity</th>
                                        <th>Rate</th>
                                        <th>Discount</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <?php $i=1; ?>
                                    @foreach($data['items'] as $item)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$item['item_code']}}</td>
                                        <td>{{$item['hsn_code']}}</td>
                                        <td>{{$item['order_qty']}}{{$item['unit_name']}}</td>
                                        <td>{{$item['rate']}}</td>
                                        <td>{{$item['discount']}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- az-content-body -->
    </div>

    <script src="<?=url('');?>/js/azia.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
    <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
    <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
    <script src="<?= url('') ?>/js/jquery.validate.js"></script>
    <script src="<?= url('') ?>/js/additional-methods.js"></script>

@stop
