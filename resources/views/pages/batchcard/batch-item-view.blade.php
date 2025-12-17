@extends('layouts.default')
@section('content')
@php
use App\Http\Controllers\Web\BatchCardController;

$fn= new BatchCardController;
@endphp
<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">
            <div class="az-content-breadcrumb">
                <span><a href="" style="color: #596881;">Batch</a></span>
                <span><a href="" style="color: #596881;">
                        Batch Item
                    </a></span>
            </div>
            <h4 class="az-content-title" style="font-size: 20px;">ITEM REPORT
                <button style="float: right;font-size: 14px;" onclick="document.location.href='{{url('batchcard/batch-item-pdf/'.$id).'?'.http_build_query(array_merge(request()->all()))}}'" class="badge badge-pill badge-danger" target="_blank"><i class="fas fa-file-pdf"></i> Report</button>

            </h4>

            @if (Session::get('success'))
            <div class="alert alert-success " style="width: 100%;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fa fa-check"></i> {{ Session::get('success') }}
            </div>
            @endif
            <div class="row row-sm mg-b-20 mg-lg-b-0">
                <div class="col-md-6">
                    <h5>Item Code: </h5>
                    <p>{{$data['item']['item_code']}}</p>
                    <h5>Description: </h5>
                    <p>{{$data['item']['discription']}}</p>
                    <h5>Type:</h5>
                    <p>{{$data['item']['type_name']}}</p>
                </div>
                <div class="col-md-6">
                    <h5>Batch No.:</h5>
                    <p>{{$data['item']['batch_no']}}</p>
                    <h5>HSN Code: </h5>
                    <p>{{$data['item']['hsn_code']}}</p>
                    <h5> </h5>
                    <p></p>

                </div>
            </div>
            <div class="table-responsive">
                @if($data['item']['pr_no'])
                <h6>PURCHASE REQUISITION <i class="fas fa-hand-point-right"></i></h6>

                <table class="table table-bordered mg-b-0" id="example1">
                    <tr>
                        <td style="font-weight: bold;">PR NO.:</td>
                        <td>{{$data['item']['pr_no']}}</td>

                        <td style="font-weight: bold;">DATE:</td>
                        <td>{{date('d-m-Y',strtotime($data['item']['req_date']))}}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">QTY:</td>

                        <td>
                            {{$data['item']['actual_order_qty']}} @if($data['item']['unit_name']) {{$data['item']['unit_name']}} @endif
                        </td>
                        <td style="font-weight: bold;">REQUESTOR:</td>

                        <td>{{$data['item']['f_name']}}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">REQUISITION APPROVED BY:</td>
                        <td>
                        @if($data['item']['processed_by']) {{$fn->user($data['item']['req_approved_user'])->f_name}}  {{$fn->user($data['item']['req_approved_user'])->l_name}} @endif
                        </td>
                        <td style="font-weight: bold;">APPROVED DATE:</td>

                        <td>{{date('d-m-Y',strtotime($data['item']['req_approved_date']))}}</td>
                    </tr>
                </table>
                @endif
                <br>
                @if($data['item']['rq_no'])

                <h6>QUOTATION INFO <i class="fas fa-hand-point-right"></i></h6>

                <table class="table table-bordered mg-b-0" id="example1">
                    <tr>
                        <td style="font-weight: bold;">RQ No.:</td>
                        <td>{{$data['item']['rq_no']}}</td>

                        <td style="font-weight: bold;">DATE:</td>
                        <td>{{date('d-m-Y',strtotime($data['item']['rq_date']))}}</td>


                    </tr>
                    <tr>
                        <td style="font-weight: bold;">QTY:</td>

                        <td>
                            @if($data['item']['rq_qty']) {{$data['item']['rq_qty']}}@else 0 @endif @if($data['item']['unit_name']) {{$data['item']['unit_name']}} @endif
                        </td>
                        <td style="font-weight: bold;">CREATED BY:</td>

                        <td>{{$fn->user($data['item']['created_user'])->f_name}}</td>
                    </tr>
                    <tr>

                        <td style="font-weight: bold;">RATE:</td>
                        <td>@if($data['item']['rate']){{$data['item']['rate']}}@else 0 @endif</td>
                        <td style="font-weight: bold;">DISCOUNT:</td>
                        <td>@if($data['item']['discount']){{$data['item']['discount']}} @else 0 @endif%</td>

                    </tr>
                    <tr>
                        <td style="font-weight: bold;">GST:</td>
                        <td>@if($data['item']['igst']!=0) IGST:{{$data['item']['igst']}}% @else SGST:{{$data['item']['sgst']}}% - CGST:{{$data['item']['cgst']}}% @endif</td>

                        <td style="font-weight: bold;">CURRENCY:</td>
                        <td>{{$data['item']['currency_code']}}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">QUATATION SUPPLIERS:</td>
                        <td>
                            @foreach($fn->get_sup_list($data['item']['quotation_id']) as $supl)
                            @if($fn->suplier($supl['supplier_id'])){{$fn->suplier($supl['supplier_id'])->firm_name}}@endif,
                            @endforeach

                        </td>
                        <td style="font-weight: bold;">SELECTED SUPPLIER:</td>
                        <td>@if($fn->suplier($data['item']['rqsupplier'])){{$fn->suplier($data['item']['rqsupplier'])->firm_name}}@endif</td>


                    </tr>


                </table>
                @endif
                <br>
                @if($data['item']['po_number'])

                <h6>PURCHASE ORDER <i class="fas fa-hand-point-right"></i></h6>

                <table class="table table-bordered mg-b-0" id="example1">
                    <tr>
                        <td style="font-weight: bold;">PO NO.:</td>
                        <td>{{$data['item']['po_number']}}</td>

                        <td style="font-weight: bold;">DATE:</td>
                        <td>{{date('d-m-Y',strtotime($data['item']['po_date']))}}</td>
                    </tr>
                    <tr>

                        <td style="font-weight: bold;">QTY:</td>
                        <td>
                            {{$data['item']['po_qty']}} @if($data['item']['unit_name']) {{$data['item']['unit_name']}} @endif
                        </td>
                        <td style="font-weight: bold;">RATE:</td>
                        <td>@if($data['item']['rate']){{$data['item']['rate']}}@else 0 @endif</td>



                    </tr>
                    <tr>
                        <td style="font-weight: bold;">DISCOUNT:</td>
                        <td>@if($data['item']['podisc']){{$data['item']['podisc']}} @else 0 @endif%</td>
                        <td style="font-weight: bold;">GST:</td>

                        <td>@if($data['item']['gst']!=0 || $data['item']['gst'])
                            @if($fn->gst($data['item']['gst'])->igst!=0) IGST:{{$fn->gst($data['item']['gst'])->igst}}% @else SGST:{{$fn->gst($data['item']['gst'])->sgst}}% - CGST:{{$fn->gst($data['item']['gst'])->cgst}}% @endif
                        @endif</td>
                    </tr>
                    

                    <tr>
                        <td style="font-weight: bold;">CREATED BY:</td>

                        <td>{{$data['item']['f_name']}}</td>
                        <td style="font-weight: bold;">SUPPLIER:</td>
                        <td>@if($fn->suplier($data['item']['supplier_id'])){{$fn->suplier($data['item']['supplier_id'])->firm_name}}@endif</td>
                        </tr>
                    <tr>
                        <td style="font-weight: bold;">PROCESSED BY:</td>
                        <td>@if($data['item']['processed_by']){{ $fn->user($data['item']['processed_by'])->f_name}} {{$fn->user($data['item']['processed_by'])->l_name}} @endif</td>
                    
                        <td style="font-weight: bold;">PROCESSED DATE:</td>

                        <td>{{date('d-m-Y',strtotime($data['item']['processed_date']))}}</td>

                    </tr>
                </table>
                @endif
                <br>
                @if($data['item']['invoice_number'])

                <h6>INVOICE INFO <i class="fas fa-hand-point-right"></i></h6>

                <table class="table table-bordered mg-b-0" id="example1">
                    <tr>
                        <td style="font-weight: bold;">INVOICE NO.:</td>
                        <td>{{$data['item']['invoice_number']}}</td>
                        <td style="font-weight: bold;">DATE:</td>
                        <td>{{date('d-m-Y',strtotime($data['item']['invoice_date']))}}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">QTY:</td>
                        <td>
                            {{$data['item']['inv_qty']}} @if($data['item']['unit_name']) {{$data['item']['unit_name']}} @endif
                        </td>
                        <td style="font-weight: bold;">CREATED BY:</td>
                        <td>{{$fn->user($data['item']['invoice_created'])->f_name}}  {{$fn->user($data['item']['invoice_created'])->l_name}}</td>
                    </tr>

                </table>
                @endif
                @if($data['item']['miq_number'])
                <br>
                <h6>MIQ INFO <i class="fas fa-hand-point-right"></i></h6>

                <table class="table table-bordered mg-b-0" id="example1">
                    <tr>
                        <td style="font-weight: bold;">MIQ NO.:</td>
                        <td>{{$data['item']['miq_number']}}</td>

                        <td style="font-weight: bold;">DATE:</td>
                        <td>{{date('d-m-Y',strtotime($data['item']['miq_date']))}}</td>


                    </tr>
                    <tr>
                        <td style="font-weight: bold;">QTY:</td>

                        <td>
                            {{$data['item']['inv_qty']}} @if($data['item']['unit_name']) {{$data['item']['unit_name']}} @endif
                        </td>
                        <td style="font-weight: bold;">CREATED BY:</td>

                        <td>{{$fn->user($data['item']['miqcreated'])->f_name}}</td>
                    </tr>

                </table>
                <br>
                @endif
                @if($data['item']['lot_number'])
                <br>
                <h6>LOT INFO <i class="fas fa-hand-point-right"></i></h6>

                <table class="table table-bordered mg-b-0" id="example1">
                    <tr>
                        <td style="font-weight: bold;">LOT NO.:</td>
                        <td>{{$data['item']['lot_number']}}</td>
                        <td style="font-weight: bold;">QTY:</td>

                        <td>
                            {{$data['item']['lotqty']}} @if($data['item']['unit_name']) {{$data['item']['unit_name']}} @endif
                        </td>


                    </tr>
                    <tr>
                        <td style="font-weight: bold;">TRANSPORTER NAME:</td>
                        <td>{{$data['item']['transporter_name']}}</td>

                        <td style="font-weight: bold;">VEHICLE:</td>

                        <td>{{$data['item']['vehicle_number']}}</td>
                    </tr>

                </table>
                <br>
                @endif
                <br>
                @if($data['item']['mac_number'])

                <h6>ACCEPTANCE/REJECTION INFO <i class="fas fa-hand-point-right"></i></h6>

                <table class="table table-bordered mg-b-0" id="example1">
                    <tr>
                        <td style="font-weight: bold;">MAC NO.:</td>
                        <td>{{$data['item']['mac_number']}}</td>
                        <td style="font-weight: bold;">DATE:</td>
                        <td>{{date('d-m-Y',strtotime($data['item']['mac_date']))}}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">ACCEPTED QTY:</td>
                        <td>
                            {{$data['item']['mac_qty']}} @if($data['item']['unit_name']) {{$data['item']['unit_name']}} @endif
                        </td>
                        <td style="font-weight: bold;">MAC CREATED BY:</td>
                        <td>@if($fn->user($data['item']['maccreated'])) {{$fn->user($data['item']['maccreated'])->f_name}}  {{$fn->user($data['item']['maccreated'])->l_name}} @endif</td>
                    </tr>
                    @if($data['item']['mrd_number'])
                    <tr>
                        <td style="font-weight: bold;">MRD NO.:</td>
                        <td>{{$data['item']['mrd_number']}}</td>
                        <td style="font-weight: bold;">MRD DATE:</td>
                        <td>{{date('d-m-Y',strtotime($data['item']['mrd_date']))}}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">REJECTED QTY:</td>
                        <td>
                            {{$data['item']['mrd_qty']}} @if($data['item']['unit_name']) {{$data['item']['unit_name']}} @endif
                        </td>
                        <td style="font-weight: bold;">MRD CREATED BY:</td>
                        <td>@if($fn->user($data['item']['mrdcreated'])) {{$fn->user($data['item']['mrdcreated'])->f_name}}  {{$fn->user($data['item']['mrdcreated'])->l_name}} @endif</td>
                    </tr>
                    @endif
                    @if($data['item']['mrr_number'])
                    <tr>
                        <td style="font-weight: bold;">MRR NO.:</td>
                        <td>{{$data['item']['mrr_number']}}</td>
                        <td style="font-weight: bold;">MRR DATE:</td>
                        <td>{{date('d-m-Y',strtotime($data['item']['mrr_date']))}}</td>
                    </tr>

                    <tr>
                        <td style="font-weight: bold;">QTY:</td>
                        <td>
                            {{$data['item']['mac_qty']}} @if($data['item']['unit_name']) {{$data['item']['unit_name']}} @endif
                        </td>
                        <td style="font-weight: bold;">CREATED BY:</td>
                        <td>@if($fn->user($data['item']['mrrcreated'])){{$fn->user($data['item']['mrrcreated'])->f_name}}@endif</td>
                    </tr>
                    @endif
                </table>
                @endif
                <br>
                @if($data['item']['mac_number'])

                <h6>STOCK INFO <i class="fas fa-hand-point-right"></i></h6>

                <table class="table table-bordered mg-b-0" id="example1">
                    <tr>
                        <td style="font-weight: bold;">SIP NO.:</td>
                        <td>{{$data['item']['sip_number']}}</td>

                        <td style="font-weight: bold;">SIP DATE:</td>
                        <td>{{date('d-m-Y',strtotime($data['item']['sip_date']))}}</td>


                    </tr>
                    <tr>
                        <td style="font-weight: bold;">SIP QTY:</td>
                        <td>
                            {{$data['item']['sip_qty']}} @if($data['item']['unit_name']) {{$data['item']['unit_name']}} @endif
                        </td>
                        <td style="font-weight: bold;">STOCK QTY:</td>

                        <td>{{ $data['item']['stock_qty']}}</td>
                    </tr>
                    @if($data['item']['sir_number'])
                    <tr>
                        <td style="font-weight: bold;">SIR NO.:</td>
                        <td>{{$data['item']['sir_number']}}</td>
                        <td style="font-weight: bold;">SIR DATE:</td>
                        <td>{{date('d-m-Y',strtotime($data['item']['sirdate']))}}</td>
                    </tr>

                    <tr>
                        <td style="font-weight: bold;">SIR QTY:</td>
                        <td>
                            {{$data['item']['sirqty']}} @if($data['item']['unit_name']) {{$data['item']['unit_name']}} @endif
                        </td>
                    </tr>
                    @endif
                    @if($data['item']['sto_number'])
                    <tr>
                        <td style="font-weight: bold;">STO NO.:</td>
                        <td>{{$data['item']['sto_number']}}</td>
                        <td style="font-weight: bold;">STO DATE:</td>
                        <td>{{date('d-m-Y',strtotime($data['item']['stodate']))}}</td>
                    </tr>

                    <tr>
                        <td style="font-weight: bold;">STO QTY:</td>
                        <td>
                            {{$data['item']['stoqty']}} @if($data['item']['unit_name']) {{$data['item']['unit_name']}} @endif
                        </td>
                        <td style="font-weight: bold;">STO REASON:</td>
                        <td>{{date('d-m-Y',strtotime($data['item']['transfer_reason']))}}</td>
                    </tr>
                    @endif
                </table>
                @endif
                <br>
                @if($data['item']['grs_number'])

                <h6>FINISHED GOODS INFO <i class="fas fa-hand-point-right"></i></h6>

                <table class="table table-bordered mg-b-0" id="example1">
                    <tr>
                        <td style="font-weight: bold;">PRODUCT SKU CODE:</td>
                        <td>{{$data['item']['sku_code']}}</td>
                        <td style="font-weight: bold;">PRODUCT HSN CODE:</td>
                        <td>{{$data['item']['prdcthsn']}}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">PRODUCT CATEGORY:</td>
                        <td>{{$data['item']['category_name']}}</td>
                        <td style="font-weight: bold;">PRODUCT GROUP:</td>
                        <td>{{$data['item']['group1_name']}}</td>

                    </tr>
                    <tr>
                        <td style="font-weight: bold;">QTY:</td>

                        <td>
                            {{$data['item']['grsqty']}} @if($data['item']['unit_name']) {{$data['item']['unit_name']}} @endif
                        </td>
                        <td style="font-weight: bold;">CUSTOMER:</td>
                        <?php $customers = $fn->getCustomers($data['item']['batch_id']); ?>
                        <td>@if($customers)
                                    @foreach($customers as $customer)
                                    {{$customer->firm_name}}
                                    <br/>
                                    @endforeach
                                    @endif
                        </td>
                    </tr>

                </table>
                @endif
                <br>
            </div>
        </div>
    </div>


</div>



<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>


<script src="<?= url(''); ?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"> </script>

<script>
    $(document).ready(function() {
        $('form').submit(function() {
            $(this).find(':submit').prop('disabled', true);
        });
    });
    $(function() {
        'use strict'

        // $('#example1').DataTable({
        //   language: {
        //     searchPlaceholder: 'Search...',
        //     sSearch: '',
        //     lengthMenu: '_MENU_ items/page',
        //   }
        // });
        $("#commentForm").validate({
            rules: {
                file: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });


    });
</script>

@stop