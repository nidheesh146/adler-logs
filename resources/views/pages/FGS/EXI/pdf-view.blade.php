<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    
    <title>EXI_{{$dni['firm_name']}}_{{$dni['dni_number']}}</title>
</head>
<body>

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
            height:120px;
            border-bottom:solid 0.5px black;
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
   
    <!-- <div class="row1" style="height:150px;border-bottom:solid 2px black;"> -->
    <div class="row1" style="height:200px;">
        <div class="col1">
            To<br/>
            <strong>{{$dni['firm_name']}}</strong>
            <p>{{$dni['billing_address']}}<br/>
            {{$dni['city']}}, {{$dni['state_name']}}<br/>
            Cell No : {{ $dni['contact_number'] }}<br/>
            <span style="font-size:10px;  overflow-wrap: break-word;">Email:{{$dni['email']}}<br/><span>
           </p>
            Shipping Address :
           <p>{{$dni['shipping_address']}}<br/>
           {{$dni['city']}},  {{$dni['state_name']}}
           </p> 
            <p>GST Details : {{$dni['gst_number']}}<br/>
            D.L. Details: {{$dni['dl_number1']}}, {{$dni['dl_number2']}}, {{$dni['dl_number3']}} <p>

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
            <img src="{{asset('/img/logo.png')}}"  style="width:80px;">
        </div>
    </div><br/>
            
    <div style="display:block;height: 8px;width:90%; border-bottom: solid black;margin-bottom:40px;">
        <span style="float:right;font-weight:bold;font-size: 24px; background-color: #f4f5f8; padding: 0 4px;margin-top:-12px;position: absolute;margin-right:-80px">
        Export Invoice(EXI)<!--Padding is optional-->
        </span>
    </div>
    <br/>
    <div class="row2">
        <div class="col21">
            <table>
                <tr>
                    <td>GRS No.</td>
                    <td>: 
                       
                        @foreach($dni_items as $dni_item)
                        @foreach($dni_item['pi_item'] as $item)
                            <?php $ar[]=$item['grs_number']; ?>
                            {{--$item['grs_number']--}} 
                        @endforeach
                        @endforeach
                        
                        <?php
                        $arr=array_filter(array_unique($ar));
                        for($x = 0; $x <count($arr); $x++) {
                        echo $arr[$x];
                         echo ",";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>GRS Date</td>
                    <td>: 
                        @foreach($dni_items as $dni_item)
                        @foreach($dni_item['pi_item'] as $item)
                            <?php $grs_date[]=date('d-m-Y', strtotime($item['grs_date'])); ?>
                            {{--date('d-m-Y', strtotime($item['grs_date']))--}} 
                        @endforeach
                        @endforeach
                        <?php
                        $arr_date=array_filter(array_unique($grs_date));
                        for($x = 0; $x <count($arr_date); $x++) 
                        {
                         echo $arr_date[$x]; 
                         echo ",";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>OEF No.</td>
                    <td>:
                        @foreach($dni_items as $dni_item)
                        @foreach($dni_item['pi_item'] as $item)
                            <?php $oef[]=$item['oef_number'] ?>
                        @endforeach
                        @endforeach 
                        <?php
                        $oef_arr=array_filter(array_unique($oef));
                        for($x = 0; $x <count($oef_arr); $x++) 
                        {
                         echo $oef_arr[$x]; 
                         echo ",";
                        }
                        ?>
                    </td>
                   
                </tr>
                <tr>
                    <td>OEF Date</td>
                    <td>: 
                        @foreach($dni_items as $dni_item)
                        @foreach($dni_item['pi_item'] as $item)
                            <?php $oef_date[]=date('d-m-Y', strtotime($item['oef_date'])); ?>
                            <!-- {{date('d-m-Y', strtotime($item['oef_date']))}} ,  -->
                        @endforeach
                        @endforeach
                        <?php
                        $oef_date_arr=array_filter(array_unique($oef_date));
                        for($x = 0; $x <count($oef_date_arr); $x++) 
                        {
                         echo $oef_date_arr[$x]; 
                         echo ",";
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col22">
            <table style="float:left;">
                <tr>
                    <td>Zone</td>
                    <td>: {{$dni['zone_name']}} </td>
                </tr>
                <tr>
                    <td>Trnsctn Type</td>
                    <td>:  
                        @foreach($dni_items as $dni_item)
                        @foreach($dni_item['pi_item'] as $item)
                            <?php $trnsctn[]=$item['transaction_name']; ?>
                            <!-- {{date('d-m-Y', strtotime($item['oef_date']))}} ,  -->
                        @endforeach
                        @endforeach
                        <?php
                        $trnsctn_arr=array_filter(array_unique($trnsctn));
                        for($x = 0; $x <count($trnsctn_arr); $x++) 
                        {
                         echo $trnsctn_arr[$x]; 
                         echo ",";
                        }
                        ?>
                    </td>
                </tr>
                
                <tr>
                    <td> Sales Type</td>
                    <td>: {{$dni['sales_type']}}</td>
                </tr>
                <tr>
                    <td>Order Fulfil</td>
                    <td>:
                        @foreach($dni_items as $dni_item)
                        @foreach($dni_item['pi_item'] as $item)
                            <?php $fulfil_type[]=$item['order_fulfil_type']; ?>
                            <!-- {{date('d-m-Y', strtotime($item['oef_date']))}} ,  -->
                        @endforeach
                        @endforeach 
                        <?php
                        $fulfil_type_arr=array_filter(array_unique($fulfil_type));
                        for($x = 0; $x <count($fulfil_type_arr); $x++) 
                        {
                         echo $fulfil_type_arr[$x]; 
                         echo ",";
                        }
                        ?>
                    </td>
                </tr>
                
            </table>
        </div>
        <div class="col23">
            <table style="float:left;">
                <tr>
                    <td>PI No.</td>
                    <td>:
                    @foreach($dni_items as $dni_item)
                        <!-- {{$dni_item['pi_number']}} , -->
                        <?php $pi[]=$dni_item['pi_number']; ?>
                    @endforeach
                    <?php
                        $pi_arr=array_filter(array_unique($pi));
                        for($x = 0; $x <count($pi_arr); $x++) 
                        {
                         echo $pi_arr[$x]; 
                         echo ",";
                        }
                    ?>
                    </td>
                </tr>
                <tr>
                    <td> PI  Date</td>
                    <td>:  
                        @foreach($dni_items as $dni_item)
                        <!-- {{date('d-m-Y', strtotime($dni_item['pi_date']))}} , -->
                        <?php $pi_date[]=date('d-m-Y', strtotime($dni_item['pi_date'])); ?>
                        @endforeach
                        <?php
                            $pi_date_arr=array_filter(array_unique($pi_date));
                            for($x = 0; $x <count($pi_date_arr); $x++) 
                            {
                            echo $pi_date_arr[$x]; 
                            echo ",";
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Order No.</td>
                    <td>:
                    @foreach($dni_items as $dni_item)
                        @foreach($dni_item['pi_item'] as $item)
                            <?php $order_no[]=$item['order_number']; ?>
                            <!-- {{$item['order_number']}} , -->
                        @endforeach
                    @endforeach
                    <?php
                            $order_arr=array_filter(array_unique($order_no));
                            for($x = 0; $x <count($order_arr); $x++) 
                            {
                            echo $order_arr[$x]; 
                            echo ",";
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Order Date</td>
                    <td>: 
                    @foreach($dni_items as $dni_item)
                        @foreach($dni_item['pi_item'] as $item)
                        <?php $order_date[]=date('d-m-Y', strtotime($item['order_date'])); ?>
                        @endforeach
                    @endforeach
                    <?php
                            $order_date_arr=array_filter(array_unique($order_date));
                            for($x = 0; $x <count($order_date_arr); $x++) 
                            {
                            echo $order_date_arr[$x]; 
                            echo ",";
                            }
                        ?>
                    </td> 
                </tr>
            </table>
        </div>
        <div class="col24">
            <table style="float:right;">
                <tr>
                    <td>Doc No.</td>
                    <td>: {{$dni['dni_number']}}</td>
                </tr>
                <tr>
                    <td> Doc  Date</td>
                    <td>: {{date('d-m-Y', strtotime($dni['dni_date']))}}</td>
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
                <th rowspan="2" width='35%'>ITEM DESCRIPTION</th>
                <th rowspan="2" style="width:10%;">DATE Of MFG.</th>
                <th rowspan="2" style="width:10%;">DATE Of EXPIRY</th>
                <th rowspan="2">BATCHCARD</th>
                <th rowspan="2">QTY</th>
                <th rowspan="2">UNIT</th>
                <th rowspan="2">RATE</th>
               
                <th colspan="2">DISC</th>
                <th rowspan="2">TAXABLE VALUE</th>
                <th colspan="2">CGST</th>
                <th colspan="2">SGST/UTGST  </th>
                <th colspan="2">IGST</th>
                <th rowspan="2">TOTAL AMOUNT</th>
            </tr>
            <tr>
                <th>%</th>
                <th>Value</th>
                <th>%</th>
                <th>Value</th>
                <th>%</th>
                <th>Value  </th>
                <th>%</th>
                <th>Value</th>
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
            @foreach($dni_items as $dni_item)
            @foreach($dni_item['pi_item'] as $item)
            <tr>
                <td>{{$i++}}</td>
                <td>{{$item['hsn_code']}}</td>
                <td>{{$item['sku_code']}}</td>
                <td>{{$item['discription']}}</td>
                <td>{{date('d-m-Y', strtotime($item['manufacturing_date']))}}</td>
                <td>@if($item['expiry_date']!='0000-00-00') {{date('d-m-Y', strtotime($item['expiry_date']))}} @else NA @endif</td>
                <td>{{$item['batch_no']}}</td>
                <td>{{$item['quantity']}}</td>
                <td>Nos</td>
                <td>{{number_format((float)$item['rate'], 2, '.', '')}}</td>
               
                <td>{{$item['discount']}}</td>
                <?php $discount_value = ($item['rate']* $item['quantity'])-(($item['rate']* $item['quantity']*$item['discount'])/100);?>
                <td>{{number_format((float)(($item['rate']* $item['quantity']*$item['discount'])/100), 2, '.', '')}}</td>
                <td>{{$discount_value}}</td>
                <td>{{$item['cgst']}}</td>
                <td>{{number_format((float)(($discount_value*$item['cgst'])/100), 2, '.', '')}}</td>
                <td >{{$item['sgst']}}</td>
                <td width="5%">{{number_format((float)(($discount_value*$item['sgst'])/100), 2, '.', '')}}</td>
                <td>{{$item['igst']}}</td>
                <td>{{number_format((float)(($discount_value*$item['igst'])/100), 2, '.', '')}}</td>
                <?php $total_amount =$discount_value+(($discount_value*$item['cgst'])/100)+ (($discount_value*$item['cgst'])/100)+ (($discount_value*$item['igst'])/100);  ?>
                <td>{{number_format((float)($total_amount), 2, '.', '')}}</td>
                <?php 
                $total =$total+ $item['rate']* $item['quantity'];
                $total_discount = $total_discount+($item['rate']* $item['quantity']*$item['discount'])/100;
                $total_igst = $total_igst+($discount_value*$item['igst'])/100;
                $total_sgst = $total_sgst+($discount_value*$item['sgst'])/100;
                $total_cgst = $total_cgst+($discount_value* $item['quantity']*$item['cgst'])/100;
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
            @endforeach   
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{  $qsum }}</td>
                <td></td>
                <td> {{ $rsum }}</td>
                <td></td>
                <td></td>
                <td>{{ $tsum }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $totalsum }}</td>
            </tr>              
        </table>
    </div>
    <br/>
    <div class="row4" style="height:170px;">
        <div class="col41">
            <div class="remarks" style="">
                <strong>Value in words: </strong><br/>
                <div></div><br/><br/>
                <table style="float:left;">
                    <tr>
                        <td>ML No.</td>
                        <td>: MFG/MD/2021/000369</td>
                        <td width="5%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>GSTIN No.</td>
                        <td>: 27AAJCB3689C1ZJ</td>
                    </tr>
                    <tr>
                        <td> PAN No.</td>
                        <td>: AAJCB3689C</td>
                        <td width="5%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>Sate Code</td>
                        <td>: Maharashtra (27)</td>
                    </tr>
                </table>
                <!-- <br/>
                <br/>
                 ML No. : MFG/MD/2021/000369<br/>
                PAN No. : AAJCB3689C<br/> -->
            </div>
            
        </div>
        <div class="col42">
            <div class="" style="height:50px;margin-left:50px;">
               <strong> Company's Bank Details:-</strong><br/>
                Bank Name:- The Federal Bank Limited<br/>
                Branch:- Kanjikode (Kl), Palakkad<br/>
                Account No.: 15245500003334<br/>
                IFSC Code: FDRL0001524
            </div>
        </div>
        <div class="col43">
            <table style="height:130px;">
                <tr>
                    <td style="width:160px">Sum of Taxable Value</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)($total-$total_discount), 2, '.', '')}}</td>
                </tr>
                
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
                <tr>
                    <td style="width:160px">Other Charges</td> 
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;"></td>
                </tr>
                 <tr>
                    <td style="width:160px">Round Off</td>
                    <td style="width:30px;">:</td>
                     <?php 
                    $t = number_format((float)($total-$total_discount+$total_igst+$total_sgst+$total_sgst), 2, '.', '');
                    $round = round($t);
                    $roundoff = number_format((float)($round-$t), 2, '.', '');
                    ?>
                    <td style="text-align:right;">{{ $roundoff }}</td>
                </tr>
                
            </table>
            <table style="border-bottom:solid 1px black;width:100%;border-top:solid 1px black;width:100%;">
                <tr>
                    <th style="width:148px; text-align: left;">GRAND TOTAL</th>
                    <th style="width:30px;">:</th>
                     <?php
                     $grand = 0;
                     $grandt = 0;
                    $grand = round(number_format((float)($total-$total_discount+$total_igst+$total_sgst+$total_sgst), 2, '.', ''))
                   
                    ?>
                    <th class="grand_total_value" style="text-align:right;">{{ $grand }} {{$dni['currency_code']}}</th>
                </tr> 
            </table>
        </div>
       
    </div>
    <br/>
    <div class="row5" style="border-bottom:solid 1px black;height:170px;">
        <div class="col51" style="width:70%">
        <strong> Declaration:- </strong><br>
        <p>
        1. We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.
        </p>
        </div>
        <div class="col52">
            <p>For Adler Healthcare Pvt. Ltd.</p>
            <br/><br/><br/>
            <p>Authorised Signatory</p>
            <br/><br/><br/>
        </div>
    </div>
   
    <div style="border-top:solid 1.5px black; margin-top:5px;font-size:10px;">
    
    </div>
    
     
   
</body>
</html>