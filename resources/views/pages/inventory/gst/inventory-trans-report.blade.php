@extends('layouts.default')
@section('content')


@php
use App\Http\Controllers\Web\PurchaseDetails\InventoryreportController;
$obj_inv=new InventoryreportController();
@endphp

<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">
            <div class="az-content-breadcrumb">
                <span><a href="">Inventory Transaction Report</a></span>
            </div>
            <!-- <h4 class="az-content-title" style="font-size: 20px;">Inventory Transaction Report
                <div class="right-button">
                    <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/MIQ/quarantine-excel-export').'?'.http_build_query(array_merge(request()->all()))}}'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button>
                </div>
            </h4> -->
            <div class="az-dashboard-nav">
                <nav class="nav"> </nav>
            </div>
            @if (Session::get('success'))
            <div class="alert alert-success " style="width: 100%;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fa fa-check"></i> {{ Session::get('success') }}
            </div>
            @endif
            <div class="row row-sm mg-b-20 mg-lg-b-0">
                <div class="table-responsive" style="margin-bottom: 13px;">
                    <table class="table table-bordered mg-b-0">
                        <tbody>
                            <tr>
                                <style>
                                    .select2-container .select2-selection--single {
                                        height: 26px;
                                        width: 122px;
                                    }

                                    .select2-selection__rendered {
                                        font-size: 12px;
                                    }
                                </style>
                                <!-- <form autocomplete="off">
                                    <th scope="row">
                                        <div class="row filter_search" style="margin-left: 0px;">
                                            <div class="col-sm-10 col-md- col-lg-10 col-xl-12 row">
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label>MIQ No:</label>
                                                    <input type="text" value="{{request()->get('miq_no')}}" name="miq_no" id="miq_no" class="form-control" placeholder="MIQ NO">

                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                    <label>Item Code:</label>
                                                    <input type="text" value="{{request()->get('item_code')}}" name="item_code" id="item_code" class="form-control" placeholder="Item Code">

                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                                    <label for="exampleInputEmail1" style="font-size: 12px;">Supplier</label>
                                                    <input type="text" value="{{request()->get('supplier')}}" name="supplier" id="supplier" class="form-control" placeholder="SUPPLIER">

                                                </div>
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                    <label for="exampleInputEmail1" style="font-size: 12px;">Month</label>
                                                    <input type="text" value="{{request()->get('from')}}" id="from" class="form-control datepicker" name="from" placeholder="Month(MM-YYYY)">

                                                </div>
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="padding: 0 0 0px 6px;">
                                                    <label style="width: 100%;">&nbsp;</label>
                                                    <button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                    @if(count(request()->all('')) > 1)
                                                    <a href="{{url()->current();}}" class="badge badge-pill badge-warning" style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                               
                                            </div>
                                        </div>
                                    </th>
                                </form> -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered mg-b-0" id="example1">
                    <thead>
                        <tr>
                            <!-- <th>Cr. year</th>
                            <th>Month</th> -->
                            <th>Code (ITEM+PO/WO)</th>
                            <!-- <th>Txn_Entry_Dt</th>
                            <th>Txn_Dc_Typ.</th> -->
                            <th>Txn_Doc_No.</th>
                            <th>Basic_Doc_No</th>
                            <!-- <th>Doc_ Ref_No</th>
                            <th>Doc_Date</th>
                            <th> Doc_Qty</th>
                            <th>Doc_ Inward_Dt</th> -->
                            <th>Work_Centre</th>
                            <th>Supplier_Name</th>
                            <!-- <th>Supplier Code</th>
                            <th>PO / WO Number</th> -->
                            <th>Item_Code</th>
                            <th>Item_Description</th>
                            <th>Lot_Number</th>
                            <th> Quantity</th>
                            <!-- <th>Stk_Kpng_Unt</th>
                            <th> Unit_Rate </th>
                            <th>Value in INR</th>
                            <th>Item_Group</th>
                            <th>Rejection_Reson_If_Any</th>
                            <th>Expiry Control Required Y/N</th>
                            <th>Prapare By</th>
                            <th>REMARK RM STORE</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item_details as $item_detail)
                        <tr>
                            
                            <td>{{$item_detail->item_name}}</td>
                            @if(!empty($obj_inv->get_sip($item_detail->id)))
                            <td>{{$obj_inv->get_sip($item_detail->id)->sip_number}}</td>
                            @else
                            <td>{{$obj_inv->get_sip($item_detail->id)}}</td>
                            @endif
                            @if(!empty($obj_inv->get_sip($item_detail->id)))
                            <td>{{$obj_inv->get_sip($item_detail->id)->sip_number}}</td>
                            @if(!empty($obj_inv->get_workcenter($obj_inv->get_sip($item_detail->id)->work_centre)))
                            <td>{{$obj_inv->get_workcenter($obj_inv->get_sip($item_detail->id)->work_centre)}}</td>
                            @else
                            <td>{{ $obj_inv->get_workcenter($obj_inv->get_sip($item_detail->id)) }}</td>
                            @endif
                            @else
                            <td>{{$obj_inv->get_sip($item_detail->id)}}</td>
                            <td></td>
                            @endif
                            @if(!empty($obj_inv->get_suplier($obj_inv->get_lot_no($item_detail->lot_id))))
                            <td>{{$obj_inv->get_suplier($obj_inv->get_lot_no($item_detail->lot_id)->supplier_id)}}</td>
                            @else
                            <td>{{$obj_inv->get_suplier($obj_inv->get_lot_no($item_detail->lot_id))}}</td>
                            @endif
                            
                            <td>{{$item_detail->item_code}}</td>
                            <td>{{$item_detail->discription}}</td>
                            @if(!empty($obj_inv->get_lot_no($item_detail->lot_id)->lot_number))
                            <td>{{$obj_inv->get_lot_no($item_detail->lot_id)->lot_number}}</td>
                            @else
                            <td>{{$obj_inv->get_lot_no($item_detail->lot_id)}}</td>
                            @endif
                            <td>{{$item_detail->order_qty}}</td>
                            



                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- az-content-body -->
    <!-- Modal content-->




    <script src="<?= url(''); ?>/js/azia.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"> </script>
    <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
    <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
    <script src="<?= url('') ?>/js/jquery.validate.js"></script>
    <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?= url('') ?>/js/additional-methods.js"></script>
    <script>
        $(".datepicker").datepicker({
            format: "mm-yyyy",
            viewMode: "months",
            minViewMode: "months",
            // startDate: date,
            autoclose: true
        });
        $('.search-btn').on("click", function(e) {
            var miq_no = $('#miq_no').val();
            var item_code = $('#item_code').val();
            var from = $('#from').val();
            var supplier = $('#supplier').val();
            var prepared = $('#prepared').val();
            if (!miq_no & !item_code & !from & !supplier & !prepared) {
                e.preventDefault();
            }
        });
    </script>
    @stop