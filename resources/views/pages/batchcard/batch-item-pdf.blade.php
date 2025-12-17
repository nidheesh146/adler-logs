<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

    <title>ITEM REPORT</title>
</head>

<body>
    @php
    use App\Http\Controllers\Web\BatchCardController;
    $fn= new BatchCardController;
    @endphp
    <style>
        table,
        tr,
        th,
        td {
            border: 1px solid black;
            /* padding: 8px; */
            text-align: left;
            border-collapse: collapse;
            width: 100%;
        }

        .col1 {
            float: left;
            width: 60%;
        
        }

        .col2 {
            width: 40%;
            float: right;
        }
    </style>
    <div style="margin: 10px;  padding: 20px;">
        <div class="row1">

            <div class="col10">
                <span style="color:#1434A4;font-weight:bold;font-size: 20px;text-align: right; float: right !important; "><strong>ADLER HEALTHCARE PVT. LTD.</strong></span>
            </div>
            <br>
            <div style="display:block;height: 8px;width:100%; border-bottom: solid black;margin-bottom:40px;margin-top:50px;">
                <span style="float:right;font-weight:bold;font-size: 22px; background-color: #f4f5f8; padding: 0 4px;margin-top:-12px;position: absolute;margin-right:-20px">
                    BatchCard - Item Info<!--Padding is optional-->
                </span>
            </div>
        </div>
        <div class="row" style="font-size:17px;">
            <table border="0" cellpading="0" cellspacing="0" style="border: none;">
                <tr>
                    <td style="font-weight:bold;">Item Code: </td>
                    <td style="font-weight:bold;">Batch No.: </td>
                </tr>
                <tr>
                    <td>{{$data['item']['item_code']}}</td>
                    <td>{{$data['item']['batch_no']}}</td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">HSN Code:</td>
                    <td style="font-weight:bold;">Batch Quantity: </td>
                </tr>
                <tr>
                    <td>{{$data['item']['hsn_code']}}</td>
                    <td>{{$data['item']['batchcard_qty']}} Nos</td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Description: </td>
                    <td style="font-weight:bold;">Batch Created By: </td>
                </tr>
                <tr>
                    <td>{{$data['item']['discription']}}</td>
                    <td>@if($data['item']['batchcared_created_by']) {{$fn->user($data['item']['batchcared_created_by'])->f_name}}  {{$fn->user($data['item']['batchcared_created_by'])->l_name}} @endif</td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Type:</td>
                    <td style="font-weight:bold;"> Product:</td>
                </tr>
                <tr>
                    <td>{{$data['item']['type_name']}}</td>
                    <td>{{$data['item']['sku_code']}}</td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Type2:</td>
                    <td style="font-weight:bold;"> Process Sheet No.:</td>
                </tr>
                <tr>
                    <td>{{$data['item']['type2_name']}}</td>
                    <td>{{$data['item']['process_sheet_no']}}</td>
                </tr>
            </table>
            <!-- <div class="col1" style="display:inline;">
                <span ></span>
                <p></p>
                <span style="font-weight:bold;"> </span>
                <p></p>
                <span style="font-weight:bold;"></span>
                <p></p>
                <span style="font-weight:bold;"></span>
                <p></p>
                <span style="font-weight:bold;">:</span>
                <p></p>
            </div><br/>
            <div class="col2" style="display:inline;">
                <span style="font-weight:bold;"></span>
                <p></p>
                <span style="font-weight:bold;"></span>
                <p></p>
                <span style="font-weight:bold;"></span>
                <p></p>
                <span style="font-weight:bold;"></span>
                <p></p>
                <span style="font-weight:bold;"></span>
                <p></p>
               
            </div><br/> -->
        </div>
        <br> 
        <div class="row"> 
            @if($data['item']['pr_no'])
            <h4>PURCHASE REQUISITION <i class="fas fa-hand-point-right"></i></h4>
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

                        <td>{{$data['item']['f_name']}} {{$data['item']['l_name']}}</td>
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

                <h4>QUOTATION INFO <i class="fas fa-hand-point-right"></i></h4>

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

                        <td>{{$fn->user($data['item']['created_user'])->f_name}}  {{$fn->user($data['item']['created_user'])->l_name}}</td>
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
                        <td style="font-weight: bold;">QUOTATION SUPPLIERS:</td>
                        <td>
                            @foreach($fn->get_sup_list($data['item']['quotation_id']) as $supl)
                            @if($fn->suplier($supl['supplier_id'])){{$fn->suplier($supl['supplier_id'])->vendor_name}}@endif,
                            @endforeach

                        </td>
                        <td style="font-weight: bold;">SELECTED SUPPLIER:</td>
                        <td>@if($fn->suplier($data['item']['rqsupplier'])){{$fn->suplier($data['item']['rqsupplier'])->vendor_name}}@endif</td>


                    </tr>


                </table>
                @endif
                <br>
                @if($data['item']['po_number'])

                <h4>PURCHASE ORDER <i class="fas fa-hand-point-right"></i></h4>

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
                        <td>@if($fn->suplier($data['item']['supplier_id'])){{$fn->suplier($data['item']['supplier_id'])->vendor_name}}@endif</td>
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

                <h4>INVOICE INFO <i class="fas fa-hand-point-right"></i></h4>

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
                @if($data['item']['lot_number'])
                <br>
                <h4>LOT INFO <i class="fas fa-hand-point-right"></i></h4>

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
                @if($data['item']['miq_number'])
                <br>
                <h4>MIQ INFO <i class="fas fa-hand-point-right"></i></h4>

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

                        <td>{{$fn->user($data['item']['miqcreated'])->f_name}} {{$fn->user($data['item']['miqcreated'])->l_name}}</td>
                    </tr>

                </table>
                <br>
                @endif
                
                <br>
                @if($data['item']['mac_number'])

                <h4>ACCEPTANCE/REJECTION INFO <i class="fas fa-hand-point-right"></i></h4>

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
                        <td>@if($fn->user($data['item']['mrrcreated'])){{$fn->user($data['item']['mrrcreated'])->f_name}}  {{$fn->user($data['item']['mrrcreated'])->l_name}} @endif</td>
                    </tr>
                    @endif
                </table>
                @endif
                <br>
                @if($data['item']['mac_number'])

                <h4>STOCK INFO <i class="fas fa-hand-point-right"></i></h4>

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
               
                <h4>FINISHED GOODS INFO <i class="fas fa-hand-point-right"></i></h4>

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
                </table>
                <?php $dni_info = $fn->getDNIInfo($data['item']['batch_id']); ?>
                @if($dni_info)
                <h5>DNI Info:</h5>
                <table>
                    @foreach($dni_info as $dni)        
                    <tr>
                        <td>{{$dni->dni_number}}</td>
                        <td>{{$dni->quantity}} Nos</td>
                        <td>{{$dni->firm_name}}</td>
                    </tr>
                    @endforeach
                </table>
                @endif
                <br>
                <h4>PRODUCTION, QUALITY, PACKING <i class="fas fa-hand-point-right"></i></h4>
                <table>
                    <tr>
                        <td style="font-weight: bold;"> PRODUCTION DONE BY</td>
                        <td>Shriniwas Atmaram Bandagale</td>
                        <td style="font-weight: bold;"> PRODUCTION DATE</td>
                        <td>30-11-2023</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;"> QUALITY CHECKED BY</td>
                        <td>Shriniwas Atmaram Bandagale</td>
                        <td style="font-weight: bold;">  QUALITY CHECK DATE</td>
                        <td>05-12-2023</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;"> PACKING DONE BY</td>
                        <td>Shriniwas Atmaram Bandagale</td>
                        <td style="font-weight: bold;"> PACKING DATE</td>
                        <td>12-12-2023</td>
                    </tr>
                </table>

        </div>
    </div>
    <script type="text/php">



        <!-- if (isset($pdf)) {
    $xPage = 545; // X-axis for "Page", positioned on the right side
    $yPage = 810; // Y-axis horizontal position

    $textPage = "Page {PAGE_NUM} of {PAGE_COUNT}"; // "Page" message

    $font = $fontMetrics->get_font("helvetica");
    $size = 7;
    $color = array(0, 0, 0);


    $pdf->page_text($xPage, $yPage, $textPage, $font, $size, $color); // "Page" on the right
    $pageNumber = $pdf->get_page_number();
    // Check if it's not the first page
    if (var_dump($pageNumber) != 1) {
        $xDoc = 530;  // X-axis for "Doc", positioned on the left side
        $yDoc = 15; // Y-axis horizontal position
        $textDoc = "{{$data['item']['batch_no']}}"; // "Doc" message
        $pdf->page_text($xDoc, $yDoc, $textDoc, $font, $size, $color); // "Doc" on the left
    }
} -->
</script>

</body>

</html>