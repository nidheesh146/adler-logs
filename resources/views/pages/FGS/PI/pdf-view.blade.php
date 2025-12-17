<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    
    <title>PI_{{$pi['firm_name']}}_{{$pi['pi_number']}}</title>
</head>
<body>
@inject('fn', 'App\Http\Controllers\Web\FGS\OEFController')
@inject('function', 'App\Http\Controllers\Web\FGS\PIController')
    <style>
        .col1,.col3{
            float:left;
            width:33%;
            font-size:11px;
        }
        .col2{
            width:28%;
            float:left;
        }
        .attn {
            margin-top:32px;
            font-weight:bold;
           font-size:10px; 
           color:#1434A4;
        }
        .main-head{
            margin-top:10px;
            font-size:24px;
            font-weight:bold;
            font-style:Italic;
        }
        .col21{
            margin-top:-25px;
            float:left; 
            width:25%;
        }
        .col22{
            margin-top:-25px;
            float:left;
            width:25%;
        }
        .col23{
            margin-left:120px;
            margin-top:-25px;
            float:left;
            width:25%;
        }
        .col24{
            margin-top:-25px;
            float:right;
            width:25%;
        }
        .col25{
            margin-top:-25px;
            float:right;
            width:20%;
        }
         .row2{
            display:block;
            font-size:11px;
            height:50px;
            /* border-bottom:solid 0.5px black; */
        }
        .row3, .row4{
            display:block;
        }
        .intro{
            font-size:11px;
            font-style:italic;
            padding:10px;
        }
        .row3 table{
            width:100%;
            font-size:10px;
            border-collapse: collapse;

        }
        .row4{
            font-size:10px;
        }
        .col41, .col42{
            width:35%;
            float:left;
        }
        .col43{
            font-size:11px;
            float:right;
        }
        .remarks, .adler {
            height:50px;
        }
        .row3 table th{
            background-color:#B6D0E2;
        }
        .col51, .col52{
            font-size:11px;
            width:33%;
            float:left;
        }
        .col52{
            text-align:center;
        }
        .col53{
            font-size:11px;
            text-align:right;
            float:right;
        }
    </style>
   
    <!-- <div class="row1" style="height:150px;border-bottom:solid 2px black;"> -->
    <div class="row1" style="height:140px;">
        <div class="col1">
            To<br/>
            <strong>{{$pi['firm_name']}}</strong>
            <p>@if($pi['billing_address']){{$pi['billing_address']}} @else {{$pi['dummy_billing_address']}} @endif<br/>
            {{$pi['city']}}, {{$pi['state_name']}}<br/>
            Cell No : {{ $pi['contact_number'] }}<br/>
            <span style="font-size:10px;  overflow-wrap: break-word;">Email:{{$pi['email']}}<br/><span>
           </p>
           <!-- Shipping Address :
           <p>{{$pi['shipping_address']}}<br/>
           {{$pi['city']}},  {{$pi['state_name']}}
           </p> -->

        </div>
        <div class="col2" style="text-align:center;">
           
        </div>
        <div class="col3">
            <span style="color:#1434A4;"><strong>ADLER HEALTHCARE PVT. LTD</strong></span>
            <p> Plot No-A1 MIDC, Sadavali(Devrukh), <br/>
             Tal- Sangmeshwar, Dist -Ratnagiri , <br/> PIN-415804, Maharashtra, India<br/>
             Contact No: 8055136000, 8055146000<br/>
             E-Mail:adler-customer.care@adler-healthcare.com<br>
            CIN :U33125PN2020PTC195161 <br/>
           </p>
        </div>
        <div class="col4" style="float:right;">
        <img src="data:img/logo.png;base64,<?php echo base64_encode(file_get_contents('img/logo.png')); ?>"style="width:80px;" />
        </div>
    </div><br/>
            
    <div style="display:block;height: 8px;width:90%; border-bottom: solid black;margin-bottom:40px;">
        <span style="float:right;font-weight:bold;font-size: 24px; background-color: #f4f5f8; padding: 0 4px;margin-top:-12px;position: absolute;margin-right:-80px">
        Proforma Invoice(PI)<!--Padding is optional-->
        </span>
    </div>
   
    <div class="row2">
        <div class="col21">
            <table>
                <tr>
                    <td>Zone</td>
                    <td>: {{$pi['zone_name']}} </td>
                   
                </tr>
                <tr>
                    
                    <td >GRS No.</td>
                    <td>: 
                        @foreach($items as $item)
                            <?php $ar[]=$item['grs_number']; ?>
                            {{--$item['grs_number']--}} 
                        @endforeach
                        
                        <?php
                        $arr_grs=array_values(array_unique($ar));
                        for($x = 0; $x <count($arr_grs); $x++) {
                        echo $arr_grs[$x];
                        echo " ";
                        }
                        ?>
                    </td>
                   
                </tr>
                <tr>
                    <td>GRS Date</td>
                    <td>: 
                        @foreach($items as $item)
                            <?php $grs_date[]=date('d-m-Y', strtotime($item['grs_date'])); ?>
                            {{--date('d-m-Y', strtotime($item['grs_date']))--}} 
                        @endforeach
        
                        <?php
                        $arr_date=array_values(array_unique($grs_date));
                        for($x = 0; $x <count($arr_date); $x++) 
                        {
                         echo $arr_date[$x]; 
                         echo "  ";
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col22">
            <table style="float:left;">
                <tr>
                    <td>OEF No.</td>
                    <td>: 
                        @foreach($items as $item)
                            <?php $ar_oef[]=$item['oef_number']; ?>
                        @endforeach
                        
                        <?php
                        $arr_oef=array_values(array_unique($ar_oef));
                        for($x = 0; $x <count($arr_oef); $x++) {
                        echo $arr_oef[$x];
                        echo " ";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td> OEF Date</td>
                    <td>: 
                        @foreach($items as $item)
                            <?php $oef_date[]=date('d-m-Y', strtotime($item['oef_date'])); ?>
                            {{--date('d-m-Y', strtotime($item['grs_date']))--}} 
                        @endforeach
        
                        <?php
                        $oef_date=array_values(array_unique($oef_date));
                        for($x = 0; $x <count($oef_date); $x++) 
                        {
                         echo $oef_date[$x]; 
                         echo "  ";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td> Business Category</td>
                    <td>: {{$pi['category_name']}}</td>
                </tr>
                <tr>
                    <td> Product Category</td>
                    <td>: {{$pi['new_category_name']}}</td>
                </tr>
            </table>
        </div>
        <div class="col23">
            <table style="float:left;">
                <tr>
                    <td>Order No.</td>
                    <td>: 
                        @foreach($items as $item)
                            <?php $ar_order[]=$item['order_number']; ?>
                        @endforeach
                        
                        <?php
                        $arr_order=array_values(array_unique($ar_order));
                        for($x = 0; $x <count($arr_order); $x++) {
                        echo $arr_order[$x];
                        echo " ";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td> Order Date</td>
                    <td>: 
                        @foreach($items as $item)
                            <?php $order_date[]=date('d-m-Y', strtotime($item['order_date'])); ?>
                            {{--date('d-m-Y', strtotime($item['grs_date']))--}} 
                        @endforeach
        
                        <?php
                        $orderdate=array_values(array_unique($order_date));
                        for($x = 0; $x <count($orderdate); $x++) 
                        {
                         echo $orderdate[$x]; 
                         echo "  ";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Order Fulfil</td>
                    <td>: {{$pi['order_fulfil_type']}} </td>
                </tr>
            </table>
        </div>
        <div class="col24">
            <table style="float:right;">
            <tr>
                    <td>Doc Number</td>
                    <td>:{{$pi['pi_number']}} </td>
                </tr>
                <tr>
                    <td> Doc Date:</td>
                    <td>:{{date('d-m-Y', strtotime($pi['pi_date']))}}</td>
                </tr>
                <tr>
                    <td>Trnsctn Type</td>
                    <td>:{{$pi['transaction_name']}} </td>
                </tr>
                <tr>
                    <td> Sales Type:</td>
                    <td>:{{$pi['sales_type']}}</td>
                </tr>
                <tr>
                    <!-- <td>Department</td>
                    <td>: </td> -->
                </tr>
            </table>
        </div>
        
    <br/>    
    </div>
    
    <style>
        th{
            text-align:center;
        }
    </style>
    <div class="row3">
        <table border="1">
            <tr>
                <th rowspan="2">S.NO</th> 
                <th rowspan="2">
                    HSN CODE  
                </th>
                <th rowspan="2">SKU CODE</th>
                <th rowspan="2" width='25%'>ITEM DESCRIPTION</th>
                <th rowspan="2" style="width:6% !important;">DATE Of MFG.</th>
                <th rowspan="2" style="width:8% !important;">DATE Of EXPIRY</th>
                <th rowspan="2">BATCH NO</th>
                <th rowspan="2">QTY</th>
                <th rowspan="2">UNIT</th>
                <th rowspan="2">RATE</th>
                
                <th colspan="2">DISC</th>
                <th rowspan="2">TAXABLE VALUE</th>
                @if($pi['zone_name']!='Export')
                    @if($pi['state_name']=='Maharashtra')
                    <th colspan="2">CGST</th>
                    <th colspan="2">SGST/UTGST  </th>
                    @else
                    <th colspan="2">IGST</th>
                    @endif
                @endif
                <th rowspan="2">TOTAL AMOUNT</th>
            </tr>
            <tr>
                <th>%</th>
                <th>Value</th>
                @if($pi['zone_name']!='Export')
                    @if($pi['state_name']=='Maharashtra')
                    <th>%</th>
                    <th>Value</th>
                    <th>%</th>
                    <th>Value  </th>
                    @else
                    <th>%</th>
                    <th>Value</th>
                    @endif
                @endif
            </tr>
            <?php $i=1;
            $total = 0;
            $total_discount = 0;
            $total_igst = 0;
            $total_cgst = 0;
            $total_sgst = 0;
            $qsum = 0;
            $rsum = 0;
            $tsum = 0;
            $isum = 0;
            $totalsum = 0;
             ?> 
            @foreach($items as $item)
            <tr style="border-bottom: solid black;">
                <td style="text-align:center;">{{$i++}}</td>
                <td>{{$item['hsn_code']}}</td>
                <td>{{$item['sku_code']}}</td>
                <td>{{$item['discription']}}</td>
                <td>
    @if($item['manufacturing_date'] != '0000-00-00' && $item['manufacturing_date'] > '1990-01-01')
        {{ date('d-m-Y', strtotime($item['manufacturing_date'])) }}
    @else
        NA
    @endif
</td>                <td>
    @if($item['expiry_date'] != '0000-00-00' && $item['expiry_date'] != '1970-01-01' && strtotime($item['expiry_date']) >= strtotime('1990-01-01'))
        {{ date('d-m-Y', strtotime($item['expiry_date'])) }}
    @else
        NA
    @endif
</td>

                <td>{{$item['batch_no']}}</td>
                <td style="text-align:center;">{{$item['remaining_qty_after_cancel']}}</td>
                <td>Nos</td>
                <td style="text-align:right;">{{number_format((float)$item['rate'], 2, '.', '')}}</td>
               
                <td style="text-align:center;">{{$item['discount']}}</td>
                <?php $discount_value = ($item['rate']* $item['remaining_qty_after_cancel'])-(($item['rate']* $item['remaining_qty_after_cancel']*$item['discount'])/100);?>
                <td style="text-align:right;">{{number_format((float)(($item['rate']* $item['remaining_qty_after_cancel']*$item['discount'])/100), 2, '.', '')}}</td>
                <td style="text-align:right;">{{number_format((float)$discount_value, 2, '.', '')}}</td>
                @if($pi['zone_name']!='Export')
                    @if($pi['state_name']=='Maharashtra')
                    <td style="text-align:center;">{{$item['cgst']}}</td>
                    <td style="text-align:right;">{{number_format((float)(($discount_value*$item['cgst'])/100), 2, '.', '')}}</td>
                    <td style="text-align:center;">{{$item['sgst']}}</td>
                    <td width="5%" style="text-align:right;">{{number_format((float)(($discount_value*$item['sgst'])/100), 2, '.', '')}}</td>
                    @else
                    <td style="text-align:center;">{{$item['igst']}}</td>
                    <td style="text-align:right;">{{number_format((float)(($discount_value*$item['igst'])/100), 2, '.', '')}}</td>
                    @endif
                @endif
                <?php // $total_amount =$discount_value+(($discount_value*$item['cgst'])/100)+ (($discount_value*$item['cgst'])/100)+ (($discount_value*$item['igst'])/100);  ?>
                <?php
                if($pi['zone_name']!='Export')
                {
                $total_amount =$discount_value+(($discount_value*$item['cgst'])/100)+ (($discount_value*$item['cgst'])/100)+ (($discount_value*$item['igst'])/100); 
                }
                else
                {
                    $total_amount =$discount_value; 
                }
                ?>
                <td style="text-align:right;">{{number_format((float)($total_amount), 2, '.', '')}}</td>
                <?php 
                $total =$total+ $item['rate']* $item['remaining_qty_after_cancel'];
                $total_discount = $total_discount+($item['rate']* $item['remaining_qty_after_cancel']*$item['discount'])/100;
                $total_igst = $total_igst+($discount_value*$item['igst'])/100;
                $total_sgst = $total_sgst+($discount_value*$item['sgst'])/100;
                $total_cgst = $total_cgst+($discount_value* $item['remaining_qty_after_cancel']*$item['cgst'])/100;
                ?>
                <?php 
                 $qsum = $qsum+$item['remaining_qty_after_cancel'];
                 $rsum = $rsum+$item['rate'];
                 $tsum = $tsum+$discount_value;
                 $isum = $isum+$total_igst;
                 $totalsum = $totalsum+$total_amount;
                 ?>
            </tr>
            @endforeach
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th style="text-align:center;font-weight:bold;">{{  $qsum }}</th>
                <th style="font-weight:bold;text-align:left;">Nos</th>
                <th style="text-align:center;font-weight:bold;">{{-- number_format((float)($rsum), 2, '.', '') --}}</th>
                <th></th>
                <th style="text-align:right;font-weight:bold;">{{number_format((float)($total_discount), 2, '.', '') }}</th>
                <th style="text-align:right;font-weight:bold;">{{number_format((float)($tsum), 2, '.', '') }}</th>
                @if($pi['zone_name']!='Export')
                    @if($pi['state_name']=='Maharashtra')
                    <th></th>
                    <th style="text-align:right;font-weight:bold;">{{number_format((float)($total_sgst), 2, '.', '') }}</th>
                    <th></th>
                    <th style="text-align:right;font-weight:bold;">{{number_format((float)($total_cgst), 2, '.', '') }}</th>
                    @else
                    <th></th> 
                    <th style="text-align:right;font-weight:bold;">{{number_format((float)($total_igst), 2, '.', '') }}</th>
                    @endif
                @endif
                <th style="text-align:right;font-weight:bold;">{{number_format((float)($totalsum), 2, '.', '') }}</th>
            </tr>  
        </table>
    </div>
    
    <div class="row4" style="height:170px;">
        <div class="col41">
            <div class="valuewords">
                <strong>Value in Words</strong><br/>
                <?php 
                    if($pi['zone_name']!='Export')
                    $amnt = $total-$total_discount+$total_igst+$total_sgst+$total_sgst;
                    else
                    $amnt = $total-$total_discount;
                ?>
                <span class="value_in_words"> <?php echo( $fn->getIndianCurrencyInt(round(number_format((float)($amnt), 2, '.', '')))) ?> Only</span>
            </div>
            <div class="remarks" style="">
                <strong>Remarks/Notes </strong><br/>
                @if($pi['oef_remarks'])                
                <?= nl2br($pi['oef_remarks']); ?><br/>
                @endif
                @if($pi['remarks'])
                <?= nl2br($pi['remarks']); ?>
                @endif
            </div>
            
        </div>
        <div class="col42">
            <div class="" style="height:50px;">
            @if($pi['payment_terms'])
            <div class="row6" style="font-size:10px;display:block; font-weight:bold" >
                <?= nl2br($pi['payment_terms']);?>
            </div>
            @endif
            </div>
        </div>
        <div class="col43">
            <table style="height:130px;">
                <tr>
                    <td style="width:160px">Sum of Taxable value</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)($total-$total_discount), 2, '.', '')}}</td>
                </tr>
                @if($pi['zone_name']!='Export')
                <tr>
                    <td style="width:160px">Sum of CGST</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)($total_sgst), 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td style="width:160px">Sum of SGST/UTGST</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)($total_sgst), 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td style="width:160px">Sum of IGST</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)($total_igst), 2, '.', '')}}</td>
                </tr>
                @endif
                <tr>
                    <td style="width:160px">Other Charges</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;"></td>
                </tr>
                 <tr>
                    <td style="width:160px">Rounf Off</td>
                    <td style="width:30px;">:</td>
                     <?php 
                    if($pi['zone_name']!='Export') 
                        $t = number_format((float)($total-$total_discount+$total_igst+$total_sgst+$total_sgst), 2, '.', '');
                    else
                        $t = number_format((float)($total-$total_discount), 2, '.', '');

                    $round = round($t);
                    $roundoff = number_format((float)($round-$t), 2, '.', '');
                    ?>
                    <td style="text-align:right;">{{ $roundoff }}</td>
                </tr>
            </table>
            <table style="border-bottom:solid 1px black;width:100%;border-top:solid 1px black;width:100%;">
                <tr>
                      <th style="width:148px; text-align: left;color:#1434A4;">GRAND TOTAL</th>
                    <th style="width:30px;color:#1434A4;">:</th>
                     <?php
                     $grand = 0;
                     $grandt = 0;
                     if($pi['zone_name']!='Export') 
                        $grand = round(number_format((float)($total-$total_discount+$total_igst+$total_sgst+$total_sgst), 2, '.', ''));
                    else
                        $grand = round(number_format((float)($total-$total_discount), 2, '.', ''))
                    ?>
                    <th class="grand_total_value" style="text-align:right;color:#1434A4;">{{ number_format((float)$grand,2,'.','') }}</th>
                </tr> 
            </table>
        </div>
    </div>
   
    
    <script type="text/php">

    if (isset($pdf)) {
    $xPage = 790; // X-axis for "Page", positioned on the right side
    $yPage = 573; // Y-axis horizontal position

    $textPage = "Page {PAGE_NUM} of {PAGE_COUNT}"; // "Page" message

    $font = $fontMetrics->get_font("helvetica");
    $size = 7;
    $color = array(0, 0, 0);


    $pdf->page_text($xPage, $yPage, $textPage, $font, $size, $color); // "Page" on the right
    $pageNumber = $pdf->get_page_number();
    // Check if it's not the first page
    if (var_dump($pageNumber) != 1) {
        $xDoc = 780;  // X-axis for "Doc", positioned on the left side
        $yDoc = 15; // Y-axis horizontal position
        $textDoc = "{{$pi['pi_number']}}"; // "Doc" message
        $pdf->page_text($xDoc, $yDoc, $textDoc, $font, $size, $color); // "Doc" on the left
    }
}
</script> 
     
   
</body>
</html>