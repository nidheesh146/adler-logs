<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    
<title>SRN_{{$srn['firm_name']}}_{{$srn['srn_number']}}</title>
</head>
<body>
@inject('fn', 'App\Http\Controllers\Web\FGS\OEFController')
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
            margin-left:150px;
            margin-top:-25px;
            float:left;
            width:25%;
        }
        .col24{
            margin-left:180px;
            margin-top:-25px;
            float:left;
            width:25%;
        }
        
         .row2{
            display:block;
            font-size:11px;
            height:65px;
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
        .col51{
            width:80%;
            float:left;
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
   <div class="row1" style="height:180px;">
        <div class="col1">
            To<br/>
            <strong>{{$srn['firm_name']}}</strong>
            <p>{{$srn['billing_address']}}<br/>
            {{$srn['city']}}, {{$srn['state_name']}}<br/>
            Cell No : {{ $srn['contact_number'] }}<br/>
            <span style="font-size:10px;  overflow-wrap: break-word;">Email:{{$srn['email']}}<span>
           <!--/p>
            < Shipping Address :
           <p>{{$srn['shipping_address']}}<br/>
           {{$srn['city']}},  {{$srn['state_name']}}
           </p --> 
            <table style="margin-top:-17px;">
                <tr>
                    <td>GST Details :</td><td> {{$srn['gst_number']}}</td>
                </tr>
                <tr>
                    <td>DL Number Details:</td><td> 20B: {{$srn['dl_number1']}}</td>
                </tr>
                @if($srn['dl_number2'])
                <tr>
                    <td></td><td> 21B : {{$srn['dl_number2']}} </td>
                </tr> 
                @endif
                @if($srn['dl_number3'])
                <tr> 
                    <td></td><td>Others if any : {{$srn['dl_number3']}} </td> 
                </tr>
                @endif
            
            </table>
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
    </div>
            
    <div style="display:block;height: 8px;width:90%; border-bottom: solid black;margin-bottom:30px;">
        <span style="float:right;font-weight:bold;font-size: 24px; background-color: #f4f5f8; padding: 0 4px;margin-top:-8px;position: absolute;margin-right:-80px">
        Sales Return Note(SRN)<!--Padding is optional-->
        </span>
    </div>
    <br/>
    <div class="row2">
        <div class="col21">
            <table>
                <tr>
                    <td>GRS No.</td>
                    <td>: 
                    @foreach($srn_items as $item)
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
                        @foreach($srn_items as $item)
                            <?php $grs_date[]=date('d-m-Y', strtotime($item['grs_date'])); ?>
                        @endforeach
                        <?php
                        $arr_date=array_filter(array_unique($grs_date));
                        for($x = 0; $x <count($arr_date); $x++) 
                        {
                         echo $arr_date[0]; 
                         echo "";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>OEF No.</td>
                    <td>:
                        @foreach($srn_items as $item)
                            <?php $oef[]=$item['oef_number'] ?>
                        @endforeach 
                        <?php
                        $oef_arr=array_filter(array_unique($oef));
                        for($x = 0; $x <count($oef_arr); $x++) 
                        {
                         echo $oef_arr[$x]; 
                         echo "  ";
                        }
                        ?>
                    </td>
                   
                </tr>
                <tr>
                    <td>OEF Date</td>
                    <td>: 
                        @foreach($srn_items as $item)
                            <?php $oef_date[]=date('d-m-Y', strtotime($item['oef_date'])); ?>
                            <!-- {{date('d-m-Y', strtotime($item['oef_date']))}} ,  -->
                        @endforeach
                        <?php
                        $oef_date_arr=array_filter(array_unique($oef_date));
                        for($x = 0; $x <count($oef_date_arr); $x++) 
                        {
                         echo $oef_date_arr[$x]; 
                         echo "  ";
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col21">
            <table style="float:left;">
                <tr>
                    <td>Zone</td>
                    <td>: {{$srn['zone_name']}} </td>
                </tr>
                <tr>
                    <td>Trnsctn Type</td>
                    <td>:  
                        @foreach($srn_items as $item)
                            <?php $trnsctn[]=$item['transaction_name']; ?>
                            <!-- {{date('d-m-Y', strtotime($item['oef_date']))}} ,  -->
                        @endforeach
                        <?php
                        $trnsctn_arr=array_filter(array_unique($trnsctn));
                        for($x = 0; $x <count($trnsctn_arr); $x++) 
                        {
                         echo $trnsctn_arr[$x]; 
                         echo "  ";
                        }
                        ?>
                    </td>
                </tr>
                
                <tr>
                    <td> Sales Type</td>
                    <td>: {{$srn['sales_type']}}</td>
                </tr>
                <tr>
                    <td>Order Fulfil</td>
                    <td>:
                        @foreach($srn_items as $item)
                            <?php $fulfil_type[]=$item['order_fulfil_type']; ?>
                            <!-- {{date('d-m-Y', strtotime($item['oef_date']))}} ,  -->
                        @endforeach 
                        <?php
                        $fulfil_type_arr=array_filter(array_unique($fulfil_type));
                        for($x = 0; $x <count($fulfil_type_arr); $x++) 
                        {
                         echo $fulfil_type_arr[$x]; 
                         echo "  ";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
    <td>Business Category</td>
    <td>: 
        @if($srn['product_category'] == 2)
            OEM
        @elseif($srn['product_category'] == 1)
            OBM
        @elseif($srn['product_category'] == 3)
            TRADE
        @else
            {{ $srn['product_category'] }}  <!-- Fallback if none of the conditions are met -->
        @endif
    </td>
</tr>
            </table>
        </div>
        <div class="col22"style="margin-left:70px;">
            <table style="float:left;">
                <tr>
                    <td>PI No.</td>
                    <td>:
                        <?php $pi=[];
                        $pi_arr=[];
                        ?>
                    @foreach($srn_items as $item)
                        <?php $pi[]=$item['pi_number']; ?>
                        
                    @endforeach
                    <?php
                        $pi_arr=array_values(array_filter(array_unique($pi)));
                        //print_r($pi_arr);
                        for($i = 0; $i <count($pi_arr); $i++) 
                        {
                          echo $pi_arr[$i]; 
                          echo "  ";
                        }
                        // $x=0;
                        // while($x <count($pi_arr))
                        // {
                        //     echo $pi_arr[$x]. ' ';
                        //     $xz
                        // }
                        
                    ?>
                    </td>
                </tr>
                <tr>
                    <td> PI  Date</td>
                    <td>:  
                        @foreach($srn_items as $item)
                        <?php $pi_date[]=date('d-m-Y', strtotime($item['pi_date'])); ?>
                        @endforeach
                        <?php
                            $pi_date_arr=array_values(array_filter(array_unique($pi_date)));
                            for($x = 0; $x <count($pi_date_arr); $x++) 
                            {
                            echo $pi_date_arr[$x]; 
                            echo "  ";
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Order No.</td>
                    <td>:
                    @foreach($srn_items as $item)
                            <?php $order_no[]=$item['order_number']; ?>
                            <!-- {{$item['order_number']}} , -->
                    @endforeach
                    <?php
                            $order_arr=array_filter(array_unique($order_no));
                            for($x = 0; $x <count($order_arr); $x++) 
                            {
                            echo $order_arr[$x]; 
                            echo "  ";
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Order Date</td>
                    <td>: 
                    @foreach($srn_items as $item)
                        <?php $order_date[]=date('d-m-Y', strtotime($item['order_date'])); ?>
                    @endforeach
                    <?php
                            $order_date_arr=array_filter(array_unique($order_date));
                            for($x = 0; $x <count($order_date_arr); $x++) 
                            {
                            echo $order_date_arr[$x]; 
                            echo "  ";
                            }
                        ?>
                    </td> 
                </tr>
                <tr>
    <td>Product Category</td>
    <td>: 
        @if($srn['new_product_category'] == 2)
            AWM
        @elseif($srn['new_product_category'] == 1)
            ASD
        
        @else
            {{ $srn['new_product_category'] }}  <!-- Fallback if none of the conditions are met -->
        @endif
    </td>
</tr>
            </table>
        </div>
        <div class="col22" style="margin-left:60px;">
            <table style="float:right;">
                <tr>
                    <td>Doc No.</td>
                    <td>: {{$srn['srn_number']}}</td>
                </tr>
                <tr>
                    <td> Doc  Date</td>
                    <td>: {{date('d-m-Y', strtotime($srn['srn_date']))}}</td>
                </tr>
                <tr>
                    <td>DNI/EXI No.</td>
                    <td>: {{$srn['dni_number']}}</td>
                </tr>
                <tr>
                    <td> DNI/EXI  Date</td>
                    <td>: {{date('d-m-Y', strtotime($srn['dni_date']))}}</td>
                </tr>
                <tr>
                    <td>Stock location Increase</td>
                    <td>: {{$srn['location_name']}}</td>
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
                <th rowspan="2" width='28%'>ITEM DESCRIPTION</th>
                <th rowspan="2" style="width:6% !important;">DATE Of MFG.</th>
                <th rowspan="2" style="width:6% !important;">DATE Of EXPIRY</th>
                <th rowspan="2">BATCH NO</th>
                <th rowspan="2">QTY</th>
                <th rowspan="2">UNIT</th>
                <th rowspan="2">RATE</th>
               
                <th colspan="2">DISC</th>
                <th rowspan="2">TAXABLE VALUE</th>
                @if($srn['zone_name']!='Export')
                @if($srn['state_name']=='Maharashtra')
                <th colspan="2">CGST</th>
                <th colspan="2">SGST/UTGST  </th>
                @else
                <th colspan="2">IGST</th>
                @endif
                @endif
                <th rowspan="2">TOTAL AMOUNT</th>
            </tr>
            <tr>
                <th >%</th>
                <th >Value</th>
                @if($srn['zone_name']!='Export')
                @if($srn['state_name']=='Maharashtra')
                <th >%</th>
                <th>Value</th>
                <th >%</th>
                <th>Value  </th>
                @else
                <th >%</th>
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
            @foreach($srn_items as $item)
            <tr>
                <td style="text-align:center;">{{$i++}}</td>
                <td>{{$item['hsn_code']}}</td>
                <td>{{$item['sku_code']}}</td>
                <td>{{$item['discription']}}</td>
                <td>{{ date('d-m-Y', strtotime($item['manufacturing_date'])) }}</td>
<td>
    @if($item['expiry_date'] != '0000-00-00' && $item['expiry_date'] != '01-01-1970' && strtotime($item['expiry_date']) >= strtotime('1999-01-01'))
        {{ date('d-m-Y', strtotime($item['expiry_date'])) }}
    @else
        NA
    @endif
</td>

                <td >{{$item['batch_no']}}</td>
                <td style="text-align:center;">{{$item['quantity']}}</td>
                <td style="text-align:left;">Nos</td>
                <td style="text-align:right;">{{number_format((float)$item['rate'], 2, '.', '')}}</td>                
                <td style="text-align:center;">{{$item['discount']}}</td>
                <?php $discount_value = ($item['rate']* $item['quantity'])-(($item['rate']* $item['quantity']*$item['discount'])/100);?>
                <td style="text-align:right;">{{number_format((float)(($item['rate']* $item['quantity']*$item['discount'])/100), 2, '.', '')}}</td>
                <td style="text-align:right;">{{number_format((float)$discount_value, 2, '.', '')}}</td>
                @if($srn['zone_name']!='Export')
                @if($srn['state_name']=='Maharashtra')
                <td style="text-align:center;">{{$item['cgst']}}</td>
                <td style="text-align:right;">{{number_format((float)(($discount_value*$item['cgst'])/100), 2, '.', '')}}</td>
                <td style="text-align:center;">{{$item['sgst']}}</td>
                <td width="5%" style="text-align:right;">{{number_format((float)(($discount_value*$item['sgst'])/100), 2, '.', '')}}</td>
                @else
                <td style="text-align:center;">{{$item['igst']}}</td>
                <td style="text-align:right;">{{number_format((float)(($discount_value*$item['igst'])/100), 2, '.', '')}}</td>
                @endif
                @endif
                <?php $total_amount =$discount_value+(($discount_value*$item['cgst'])/100)+ (($discount_value*$item['cgst'])/100)+ (($discount_value*$item['igst'])/100);  ?>
               ? <td style="text-align:right;">{{number_format((float)($total_amount), 2, '.', '')}}</td>
                <?php 
                $total =$total+ $item['rate']* $item['quantity'];
                $total_discount = $total_discount+($item['rate']* $item['quantity']*$item['discount'])/100;
                if($srn['zone_name']!='Export')
                {
                $total_igst = $total_igst+($discount_value*$item['igst'])/100;
                $total_sgst = $total_sgst+($discount_value*$item['sgst'])/100;
                $total_cgst = $total_cgst+($discount_value* $item['quantity']*$item['cgst'])/100;
                }
                else
                {
                $total_igst = 0;
                $total_sgst = 0;
                $total_cgst = 0;
                }

                ?>
                 <?php 
                 $qsum = $qsum+$item['quantity'];
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
                @if($srn['zone_name']!='Export')
                @if($srn['state_name']=='Maharashtra')
                <th></th>
                <th style="text-align:right;font-weight:bold;">{{number_format((float)($total_sgst), 2, '.', '') }}</th>
                <th></th>
                <th style="text-align:right;font-weight:bold;">{{number_format((float)($total_sgst), 2, '.', '') }}</th>
                @else
                <th></th> 
                <th style="text-align:right;font-weight:bold;">{{number_format((float)($total_igst), 2, '.', '') }}</th>
                @endif
                @endif
                <th style="text-align:right;font-weight:bold;">{{number_format((float)($totalsum), 2, '.', '') }}</th>
            </tr>            
        </table>
    </div>
    <br/>
    <div class="row4" style="height:140px;">
        <div class="col41">
            <div class="remarks" style="">
                <strong>Value in words: </strong>
                <br/>
                <?php
    // Calculate Other Charges as a percentage
    $otherChargesPercentage = $srn->calc_unit == '1' 
        ? ($srn->other_charges / 100) * ($total - $total_discount + $total_cgst + $total_igst + $total_sgst) 
        : $srn->other_charges;

    // Calculate the initial Grand Total
    $grand = ($total - $total_discount + $total_igst + $total_sgst + $total_sgst - $otherChargesPercentage);

    // Rounding logic
    $fractionalPart = $grand - floor($grand);
    if ($fractionalPart > 0.49) {
        $grand = ceil($grand); // Round up if fractional part > 0.49
    } else {
        $grand = floor($grand); // Round down if fractional part <= 0.49
    }
?>

                <span class="value_in_words">
    <?php echo( $fn->getIndianCurrencyInt($grand) ); ?> Only
</span>
                <div></div><br/><br/>
                
                <!-- <div class="col51" style="width:65%;margin-top:50px;">
                <p>For Adler Healthcare Pvt. Ltd.</p>
                <br/>
                </div> -->
            </div>
            
        </div>
        <div class="col42">
            <div class="" style="height:50px;margin-left:50px;">
            <strong>Remarks: </strong><br/>
                <div><?= nl2br($srn['remarks']); ?></div><br/>
            </div>
            <!-- <div class="col52" style="margin-top:50px;">
                Authorised Signatory
            </div> -->
        </div>
        <div class="col43">
        <table style="width:100%; height:auto; border-collapse: collapse;">
    <tr>
        <td style="text-align:left; width:50%; padding-right:10px;">Sum of Taxable Value</td>
        <td style="text-align:right;">{{ number_format((float)($total - $total_discount), 2, '.', '') }}</td>
    </tr>
    
    <tr>
        <td style="text-align:left; padding-right:10px;">Sum of CGST</td>
        <td style="text-align:right;">{{ number_format((float)($total_sgst), 2, '.', '') }}</td>
    </tr>

    <tr>
        <td style="text-align:left; padding-right:10px;">Sum of SGST/UTGST</td>
        <td style="text-align:right;">{{ number_format((float)($total_sgst), 2, '.', '') }}</td>
    </tr>

    <tr>
        <td style="text-align:left; padding-right:10px;">Sum of IGST</td>
        <td style="text-align:right;">{{ number_format((float)($total_igst), 2, '.', '') }}</td>
    </tr>

    <!-- Other Charges -->
    <tr>
    <td style="text-align:left; padding-right:10px;">Less Other Charges</td>
    <td style="text-align:right;">
        {{ number_format($srn->other_charges) }} 
        @if($srn->calc_unit == '1') 
            % 
        @endif
    </td>
</tr>


    <!-- Charge Type -->
    <!-- <tr>
        <td style="text-align:left; padding-right:10px;">Charge Type</td>
        <td style="text-align:right;">
            @if($srn->calc_unit == '1')
%
            @elseif($srn->calc_unit == '2')
                Lump Sum
            @else
                N/A
            @endif
        </td>
    </tr> -->

    <!-- Round Off -->
    <tr>
        <td style="text-align:left; padding-right:10px;">Round Off</td>
        <?php
            $t = number_format((float)($total - $total_discount + $total_igst + $total_sgst + $total_sgst), 2, '.', '');
            $round = round($t);
            $roundoff = number_format((float)($round - $t), 2, '.', '');
        ?>
        <td style="text-align:right;">{{ $roundoff }}</td>
    </tr>
</table>


    <table style="border-bottom:solid 1px black;width:100%;border-top:solid 1px black;width:100%;">
        <tr>
            <th style="width:148px; text-align: left;">GRAND TOTAL</th>
            @if($srn->calc_unit == '1')
            <th style="width:30px;">:</th>
            <?php
        // Calculate Other Charges as a percentage
        $otherChargesPercentage = $srn->calc_unit == '1' ? ($srn->other_charges / 100) * ($total - $total_discount+$total_cgst+$total_igst+$total_sgst) : $srn->other_charges;
                echo $otherChargesPercentage;
        // Calculate the Grand Total
        // Calculate Other Charges as a percentage
        $otherChargesPercentage = $srn->calc_unit == '1' 
            ? ($srn->other_charges / 100) * ($total - $total_discount + $total_cgst + $total_igst + $total_sgst) 
            : $srn->other_charges;
    
       // echo "Other Charges Percentage: " . $otherChargesPercentage;
    
        // Calculate the initial Grand Total
        $grand = ($total - $total_discount + $total_igst + $total_sgst + $total_sgst - $otherChargesPercentage);
    
        // Check the fractional part
        $fractionalPart = $grand - floor($grand); // Extract fractional part
        if ($fractionalPart > 0.49) {
            $grand = ceil($grand); // Round up if fractional part > 0.49
        } else {
            $grand = floor($grand); // Round down if fractional part <= 0.49
        }
    
        // If there's a negative sign, keep it untouched and subtract $roundoff
        if ($roundoff < 0) {
            $roundoff -= abs($roundoff); // Ensure correct adjustment for negative values
        } else {
            $roundoff -= $roundoff; // For positive values, subtract the roundoff
        }
    
    ?>
                    @else
                    <th style="width:30px;">:</th>
                    <?php
$grand = round(($total - $total_discount + $total_igst + $total_sgst + $total_sgst - $srn->other_charges), 0);
$decimal = fmod(($total - $total_discount + $total_igst + $total_sgst + $total_sgst - $srn->other_charges), 1);
$decimalPart = (int) ($decimal * 100);
if ($decimalPart > 49) {
    $grand += $roundoff-$roundoff;
} else {
    $grand -= $roundoff-$roundoff;
}                    ?>
            @endif
            <th class="grand_total_value" style="text-align:right;">
                {{ number_format((float)($grand), 2, '.', '') }} {{--$srn['currency_code']--}}
            </th>
        </tr>
    </table>
</div>
<div class="row5" style="float:right; margin-right:-130px; margin-bottom:0px; margin-top:150px; font-size:12px;">
    <div>
        <p>For Adler Healthcare Pvt. Ltd.</p>
        <br/>
    </div>
    <div class="">
        <p>Authorised Signatory</p>
        <br/>
    </div>
</div>

    <script type="text/php">
    if (isset($pdf)) {
    $xPage = 780; // X-axis for "Page", positioned on the right side
    $yPage = 560; // Y-axis horizontal position

    $textPage = "Page {PAGE_NUM} of {PAGE_COUNT}"; // "Page" message

    $font = $fontMetrics->get_font("helvetica");
    $size = 7;
    $color = array(0, 0, 0);


    $pdf->page_text($xPage, $yPage, $textPage, $font, $size, $color); // "Page" on the right
    $pageNumber = $pdf->get_page_number();
    // Check if it's not the first page
    if (var_dump($pageNumber) != 1) {
        $xDoc = 750;  // X-axis for "Doc", positioned on the left side
        $yDoc = 15; // Y-axis horizontal position
        $textDoc = "{{$srn['srn_number']}}"; // "Doc" message
        $pdf->page_text($xDoc, $yDoc, $textDoc, $font, $size, $color); // "Doc" on the left
    }
}
</script>
   
</body>
</html>