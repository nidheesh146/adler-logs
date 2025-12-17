<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

</head>

<body>
    @php
    use App\Http\Controllers\Web\FGS\DeliveryNoteController;
    $obj_challan=new DeliveryNoteController;
    @endphp
    @inject('fn', 'App\Http\Controllers\Web\FGS\OEFController')
    <style>
        table,
        td,
        th {
            
    page-break-inside: avoid; /* Avoid page breaks inside the table */

            border: 1px solid black;
            border-collapse: collapse;

        }

        div {
            font-size: 12px;
            page-break-inside: avoid; /* Avoid page breaks inside the table */

        }

        #table2 th {
            background-color: #B6D0E2;
            

        }
        #table2{
            outline: none;
            page-break-inside: avoid; /* Avoid page breaks inside the table */
 

        }
    </style>

    <!-- <div class="row1" style="height:150px;border-bottom:solid 2px black;"> -->




    <div class="row" style="margin-left: 10px; margin-right: 10px; ">
        <table style="width: 100%;" id="table">
            <tr>
                <td colspan="7">
                    <table style="border: none;">
                        <tr>

                            <td style="border: none;"> <img src="data:img/logo.png;base64,<?php echo base64_encode(file_get_contents('img/logo.png')); ?>"style="width:80px;" />

                            </td>
                            <td colspan="6" style="border: none;">
                                <span style="font-weight: bold; font-size: 25px;margin-left: 50px;">Adler Healthcare Pvt. Ltd.</span> <span style="margin-left: 30px;">CIN : U33125PN2020PTC195161</span> <br>
                                <p style="margin-left: 80px;">Plot No. A-1, MIDC Sadavali, Tal. : Sangameshwar, Dist. Ratnagiri, PIN - 415 804.<br> Contact No.: 8055136000 / 8055146000</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="7">
                    <h2 style="text-align: center;">DELIVERY CHALLAN</h2>
                </td>
            </tr>
            <tr>
                <td colspan="7" >
                    <table id="table1" style="width: 100%;">
                        <tr>

                            <td colspan="3" style="font-weight: bold;">To:
                                <span style=" font-weight: bold;">{{$dc->firm_name}}</span>
                            </td>
                            <td colspan="2" rowspan="2" style="width: 20%; font-size: bold;">Ref. No.: {{$dc->ref_no}} <br>
                                Ref. Date: {{date('d-m-Y',strtotime($dc->ref_date))}}
                            </td>
                            <td style="width: 15%;" style="font-weight: bold;">Doc No. :</td>
                            <td style="width: 15%;">{{$dc->doc_no}}</td>
                        </tr>
                        <tr>
                            
                            <td colspan="3" rowspan="2">@if($dc->dummy_shipping_address){{$dc->dummy_shipping_address}} @else{{$dc->shipping_address}}@endif</td>
                            <td style="font-weight: bold;">Doc Date :</td>
                            <td>{{date('d-m-Y',strtotime($dc->doc_date))}}</td>
                        </tr>
                        <tr>
                           
                            <td colspan="2">OEF No.: {{$dc->oef_number}}<br/>
                                OEF. Date: {{date('d-m-Y',strtotime($dc->oef_date))}}
                            </td>
                            <td style="font-weight: bold;">Trnsctn Type:</td>
                            <td>{{$dc->transaction_name}}</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-weight: bold;">Zone: <span>{{$dc->zone_name}}</span></td>
                            <td colspan="2"></td>
                            <td style="font-weight: bold;">Trnsctn Cndtn:</td>
                            <td>@if($dc->transaction_condition==1)
                                Returnable
                                @else
                                Non Returnable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-weight: bold;">Stock Location (Decrease): <span>{{$dc->location_decrease}}</span></td>
                            <td colspan="2">Sub division : {{$dc->location_decrease}}</td>
                            <td style="font-weight: bold;">Business Category:</td>
                            <td>{{$dc->category_name}}</td>
                            <!-- <td style="font-weight: bold;">Product Category:</td>
                            <td>{{$dc->new_category_name}}</td> -->
                        </tr>
                        <tr>
                            <td colspan="2" style="font-weight: bold;">Stock Location (Increase): <span>@if($dc->location_increase) {{$dc->location_increase}} @else N.A @endif</span></td>
                            <td colspan="3">Sub division:{{$dc->location_increase}}</td>
                            <td style="font-weight: bold;">Product Category:</td>
                            <td>{{$dc->new_category_name}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            </table>
                    <table id="table2" style="width: 100%;">
                        <tr>
                            <th style="font-weight: bold;">Sr. No.</th>
                            <th style="font-weight: bold;">Item Code</th>
                            <th style="font-weight: bold;">Item Description</th>
                            <th style="font-weight: bold;">Batch No.</th>
                            <th style="font-weight: bold;">Qty.</th>
                            <th style="font-weight: bold;width:80px;">Date of Mfg.</th>
                            <th style="font-weight: bold;">Date of Expiry</th>
                        </tr>
                        @php
                        $s=1;
                        $totalQuantity = 0;
                        @endphp
                        @foreach($items as $item)
                        <tr>
                            <td style="height: 15px;text-align:center;">{{$s++}}</td>
                            <td>{{$item->sku_code}}</td>
                            <td>{{$item->discription}}</td>
                            <td>{{$item->batch_no}}</td>
                            <td style="text-align:center;">{{$item->batch_qty}}</td>
                            <?php $totalQuantity = $totalQuantity+$item->batch_qty; ?>
                            <td>{{date('d-m-Y',strtotime($item->manufacturing_date))}}</td>
                            <td>@if($item->expiry_date=='0000-00-00' ||$item->expiry_date=='1970-01-01'|| $item->expiry_date==NULL ) NA  @else {{date('d-m-Y',strtotime($item->expiry_date))}} @endif</td>
                        </tr>
                        @endforeach
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="text-align:center;">{{  $totalQuantity }}</th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr id="test" >
                           
                                    <td colspan="3" style="height: 120px;" valign="top">
                                Remarks:<br/>
                                <?= nl2br($dc->oef_remark);?></td>
                               
                            
                            <td colspan="4"  ><p style="margin-top: 5px;" >For Adler Healthcare Pvt. Ltd. </p>
                                <p style="margin-top: 80px;">Authorised signatory</p>
                            </td>
                        </tr>
                    </table>
                <!-- </td>
            </tr>
        </table> -->

    </div>

    <script type="text/php">
   
    if (isset($pdf)) {
    $xPage = 535; // X-axis for "Page", positioned on the right side
    $yPage = 810; // Y-axis horizontal position

    $textPage = "Page {PAGE_NUM} of {PAGE_COUNT}"; // "Page" message

    $font = $fontMetrics->get_font("helvetica");
    $size = 7;
    $color = "#808080";

    $pdf->page_text($xPage, $yPage, $textPage, $font, $size, $color); // "Page" on the right
    $pageNumber = $pdf->get_page_number();
    // Check if it's not the first page
    if (var_dump($pageNumber) != 1) {
        $xDoc = 530;  // X-axis for "Doc", positioned on the left side
        $yDoc = 15; // Y-axis horizontal position
        $textDoc = "{{$dc->doc_no}}"; // "Doc" message
        $pdf->page_text($xDoc, $yDoc, $textDoc, $font, $size, $color); // "Doc" on the left
    }
}
   
   </script>
</body>

</html>