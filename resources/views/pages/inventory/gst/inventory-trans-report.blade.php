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
            <h4 class="az-content-title" style="font-size: 20px;">Inventory Transaction Report
                <div class="right-button">
                    <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('inventory/inventory-trans-export').'?'.http_build_query(array_merge(request()->all()))}}'" class="badge badge-pill badge-info"><i class="fas fa-file-excel"></i> Report</button>
                </div>
            </h4>
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
                                <form autocomplete="off">
                                    <th scope="row">
                                        <div class="row filter_search" style="margin-left: 0px;">
                                            <div class="col-sm-10 col-md-6 col-lg-6 col-xl-6 row">
                                                
                                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label>Item Code:</label>
                                                    <input type="text" value="{{request()->get('item_code')}}" name="item_code" id="item_code" class="form-control" placeholder="Item Code">

                                                </div>
                                                
                                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                    <label for="exampleInputEmail1" style="font-size: 12px;">Month</label>
                                                    <input type="text" value="{{request()->get('from')}}" id="from" class="form-control datepicker" name="from" placeholder="Month(MM-YYYY)">

                                                </div>
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="padding: 0 0 0px 6px;">
                                                    <label style="width: 100%;">&nbsp;</label>
                                                    <button type="submit" class="badge badge-pill badge-primary search-btn" style="margin-top:-2px;"><i class="fas fa-search"></i> Search</button>
                                                    @if(count(request()->all('')) > 0)
                                                    <a href="{{url()->current();}}" class="badge badge-pill badge-warning" style="margin-top:-2px;"><i class="fas fa-sync"></i> Reset</a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2 row">
                                               
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
                            <th>Cr. year</th>
                            <th>Month</th> 
                            <th>Code (ITEM+PO/WO)</th>
                            <th>Txn_Entry_Dt</th>
                            <th>Item Code</th>
                            <th>Item Description</th>
                            <th>Item Group</th>
                            <th>Supplier Name</th>
                            <th>Supplier Code</th>
                            {{--<th>PO / WO Number</th> 
                            <th>Stk_Kpng_Unt</th>
                            <th>Invoice No.</th> 
                            <th>Invoice Qty.</th> 
                            <th>Invoice Created</th>
                            <th>Lot Number</th>
                            <th>Lot Created</th>
                            <th>MIQ No.</th> 
                            <th>MIQ Date.</th>
                            <th> Unit_Rate </th>
                            <th>Value in INR</th> 
                            <th>Expiry Control Required Y/N</th>
                            <th>MIQ Created</th>
                            <th>MAC No.</th> 
                            <th>MAC Date.</th>
                            <th>Accepted Qty.</th>
                            <th>MAC Created</th>
                            <th>MRD No.</th> 
                            <th>MRD Date.</th>
                            <th>Rejected Qty.</th>
                            <th>Rejection Reason.</th>
                            <th>MRD Created</th>
                            <th>MRR No</th>
                            <th>MRR Date</th>
                            <th> MRR created</th>
                            <th>SIP Number</th>
                            <th>SIP Date</th>
                            <th>SIP Qty.</th>
                            <th>SIP Created</th>
                            <th>Work Center</th>
                            <th>SIR Number</th>
                            <th>SIR Date</th>
                            <th>SIR Qty.</th>
                            <th>SIR Created</th>
                            <th>STO Number</th>
                            <th>STO Date</th>
                            <th>STO Qty.</th>
                            <th>STO Created</th>--}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item_details as $item_detail)
                        <tr>

                            <td>{{date('Y', strtotime($item_detail->invoice_date))}}</td>
                            <td>{{date('m/y', strtotime($item_detail->invoice_date))}}</td>
                            <td>{{$item_detail->item_code}}</td>
                            <td>{{date('d-m-Y', strtotime($item_detail->transaction_date))}}</td>
                            <td>{{$item_detail->item_code}}</td>
                            <td>{{$item_detail->discription}}</td>
                            <td>{{$item_detail->type_name}}</td>
                            <td>{{$item_detail->vendor_name}}</td>
                            <td>{{$item_detail->vendor_id}}</td>
                            {{--<td>{{$item_detail->po_number}}</td>
                            <td>{{$item_detail->unit_name}}</td>
                            <td>{{$item_detail->invoice_number}}</td>
                            <td>{{$item_detail->invoice_qty}}</td>
                            <td>{{$obj_inv->get_user($item_detail->invoice_created_by)}}</td>
                            <td>{{$item_detail->lot_number}}</td>
                            <td>{{$obj_inv->get_user($item_detail->lot_created_by)}}</td>
                            <td>{{$item_detail->miq_number}}</td>
                            <td>@if($item_detail->miq_number) {{date('d-m-Y', strtotime($item_detail->miq_date))}} @endif</td>
                            <td>{{$item_detail->basic_rate}}</td>
                            <td>{{$item_detail->value_inr}}</td>
                            <td>@if($item_detail->expiry_control =='1') Yes 
                                    @elseif($item_detail->expiry_control=='0') No 
                                    @else {{$item_detail->expiry_control}}
                                    @endif</td>
                            <td>{{$obj_inv->get_user($item_detail->miq_created_by)}}</td>
                            <td>{{$item_detail->mac_number}}</td>
                            <td>@if($item_detail->mac_number) {{date('d-m-Y', strtotime($item_detail->mac_date))}} @endif</td>
                            <td>{{$item_detail->accepted_quantity}}</td>
                            <td>{{$obj_inv->get_user($item_detail->mac_created_by)}}</td>

                            <td>{{$item_detail->mrd_number}}</td>
                            <td>@if($item_detail->mrd_number) {{date('d-m-Y', strtotime($item_detail->mrd_date))}} @endif</td>
                            <td>{{$item_detail->rejected_quantity}}</td>
                            <td>{{$item_detail->remarks}}</td>
                            <td>{{$obj_inv->get_user($item_detail->mrd_created_by)}}</td>
                           
                            
                            
                            <td>{{$item_detail->mrr_number}} </td>
                            <td>@if($item_detail->mrr_number) {{date('d-m-Y', strtotime($item_detail->mrr_date))}} @endif</td>
                            <td>{{$obj_inv->get_user($item_detail->mrr_created_by)}}</td>
                            
                            <td>{{$item_detail->sip_number}}</td>
                            <td>@if($item_detail->sip_number) {{date('d-m-Y', strtotime($item_detail->sip_date))}} @endif</td>
                            <td>{{$item_detail->qty_to_production}}</td>
                            <td>{{$obj_inv->get_user($item_detail->sip_created_by)}}</td> 
                            <td>{{$item_detail->centre_code}}</td>
                            <td>{{$item_detail->sir_number}}</td>
                            <td>@if($item_detail->sir_number) {{date('d-m-Y', strtotime($item_detail->sir_date))}} @endif</td>
                            <td>{{$item_detail->qty_to_return}}</td> 
                            <td></td>

                           <td>{{$item_detail->sto_number}}</td>
                            <td>@if($item_detail->sto_number) {{date('d-m-Y', strtotime($item_detail->sto_date))}} @endif</td>
                            <td>{{$item_detail->transfer_qty}}</td> 
                            <td>{{$obj_inv->get_user($item_detail->sto_created_by)}}</td>--}}
                          

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="box-footer clearfix">
                    {{ $item_details->appends(request()->input())->links() }}
                        
                </div> 
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
            var item_code = $('#item_code').val();
            var from = $('#from').val();

            if (!item_code & !from) {
                e.preventDefault();
            }
        });
    </script>
    @stop