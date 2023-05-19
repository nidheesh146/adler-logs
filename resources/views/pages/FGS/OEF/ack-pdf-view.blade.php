<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    
    <title>OEF_{{$oef['firm_name']}}_{{$oef['oef_number']}}</title>
</head>
<body>
@inject('fn', 'App\Http\Controllers\Web\FGS\OEFController')
    <style>
        .col1,.col3{
            float:left;
            width:23%;
            font-size:11px;
        }
        .col2{
            width:45%;
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
          
            float:left;
            width:25%;
        }
        .col24{
            margin-top:-25px;
            float:right;
            width:25%;
        }
         .row2{
            display:block;
            font-size:11px;
            height:35px;
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
        .remarks, .adler {
            height:50px;
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
        .col6{
            width: 50%;
        }
    </style>
   
 
            
    <div class="row">
        <span style="font-weight:bold;font-size: 24px; background-color: #f4f5f8; padding: 0 4px;position: absolute;">
       Order Acknowledgement<!--Padding is optional-->
        </span>
    </div>
    <br/> <br/> <br/> 
    <div class="row">
        
            <table style="font-weight:bold;font-size: 14px;">
                <tr>
                    <td>Reference No</td>
                    <td>: {{$oef['oef_number']}} </td>
                   
                </tr>
                
            </table>
       
    </div>
   <br>
    <div class="row3" style="height:100px;">
        <div class="col23">
            <table>
                <tr>
                    <td> <strong><font size="12px">{{$oef['firm_name']}} </font></strong>
            <p>{{$oef['billing_address']}}<br/>
            {{$oef['city']}}, {{$oef['state_name']}}
           
           </p></td>
                   
                </tr>
                
            </table>
        </div>
        <div class="col23">
            <table style="padding-left: 10px;">
                <tr>
                    <td style="font-weight:bold;font-size: 12px;">Order No.</td>
                    <td>: {{$oef['order_number']}} </td>
                </tr>
                <tr>
                    <td style="font-weight:bold;font-size: 12px;"> Order Date</td>
                    <td>: {{date('d-m-Y', strtotime($oef['order_date']))}}</td>
                </tr>
                 <tr>
                    <td style="font-weight:bold;font-size: 12px;">Our Ref. No</td>
                    <td>: {{$oef['oef_number']}}</td>
                </tr>
                <tr>
                    <td style="font-weight:bold;font-size: 12px;"> Doc Ref. Date</td>
                    <td>: {{date('d-m-Y', strtotime($oef['oef_date']))}}</td>
                </tr>
                
            </table>
        </div>
     
    </div>
    <div  style="font-size:12px; padding-top: 30px;">
        <span></span>
        <span style="padding-left:0px;" >Dear Sir / Madam,</span><br>
        <span>This is to acknowledge that we are in receipt of your order having following details:</span>
    </div><br>
    <style>
        th{
            text-align:center;
        }
    </style>
    <div class="row3" style="border-bottom:solid 0.5px black;">
        <table >
            <tr style="font-weight:bold;font-size: 10px;">
                <th rowspan="2">S.NO</th>
                <th rowspan="2">ITEM NO.</th>
                <th rowspan="2" width='40%'>ITEM DESCRIPTION</th>
                <th rowspan="2">QTY</th>
                <th rowspan="2">UNIT</th>
                <th rowspan="2">RATE</th>
                <th colspan="2">DISC</th>
                <th rowspan="2">TAXABLE VALUE</th>
                <th colspan="2">CGST</th>
                <th colspan="2">SGST/UTGST</th>
                <th colspan="2">IGST</th>
                <th rowspan="2">TOTAL AMOUNT</th>
            </tr>
            <tr>
                <th>%</th>
                <th>Value</th>
                <th>%</th>
                <th>Value</th>
                <th>%</th>
                <th>Value</th>
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
            @foreach($items as $item)
            <tr style="text-align: center;">
                <td>{{$i++}}</td>
                <td>{{$item['sku_code']}}</td>
                <td>{{$item['discription']}}</td>
                <td>{{$item['quantity']}}</td>
                <td>Nos</td>
                <td>{{number_format((float)$item['rate'], 2, '.', '')}}</td>
                <td>{{$item['discount']}}</td>
                <?php $discount_value = ($item['rate']* $item['quantity'])-(($item['rate']* $item['quantity']*$item['discount'])/100);?>
                <td>{{number_format((float)(($item['rate']* $item['quantity']*$item['discount'])/100), 2, '.', '')}}</td>
                <td>{{$discount_value}}</td>
                <td>{{$item['cgst']}}</td>
                <td>{{number_format((float)(($discount_value*$item['cgst'])/100), 2, '.', '')}}</td>
                <td>{{$item['sgst']}}</td>
                <td>{{number_format((float)(($discount_value*$item['sgst'])/100), 2, '.', '')}}</td>
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
            <tr>
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
        
        </table><br>
    </div>
    <br/>
    <div class="row4" style="height:100px">
        <div class="col41">
            <div class="valuewords">
                <strong>Amount in Words</strong><br/>
              
                
                <span class="value_in_words"></span>
            </div>
           
            
        </div>
        <div class="col42">
            <div class="" style="height:50px;">
            </div>
        </div>
        <div class="col43">
            <table style="height:100px;">
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
                    <td style="width:160px">Rounf Off</td>
                    <td style="width:30px;">:</td>
                     <?php 
                    $t = number_format((float)($total-$total_discount+$total_igst+$total_sgst+$total_sgst), 2, '.', '');
                    $round = round($t);
                    $roundoff = number_format((float)($round-$t), 2, '.', '');
                    ?>
                    <td style="text-align:right;">{{ $roundoff }}</td>
                </tr>
                
            </table>
            <table style="width:100%;">
                <tr>
                   <th style="width:148px; text-align: left;">GRAND TOTAL</th>
                    <th style="width:30px;">:</th>
                     <?php
                     $grand = 0;
                     $grandt = 0;
                    $grand = round(number_format((float)($total-$total_discount+$total_igst+$total_sgst+$total_sgst), 2, '.', ''))
                   
                    ?>
                    <th class="grand_total_value" style="text-align:right;">{{ $grand }} </th>
                </tr> 
            </table>
        </div>
    </div>
   <br><br>
    <div class="row3" style="border-bottom:solid 0.5px black;font-size:12px; ">
       
        <span>We also request you to make a note of the Order Acceptance Number and Date mentioned above. 
Kindly mention this reference number and date in all future correspondence you have with us 
concerning this order.</span><br>
  <span>Kindly note that this order has been booked only for items which were clearly specified (with correct 
numbers). You may receive a separate query from us relating to items from this order for which the 
specifications were not clear or complete. When you receive this query, kindly send us a fresh order with 
complete and clear specifications (code numbers) for items mentioned in our query letter.</span><br>
  <span>Please Note : This is a computer generated e-mail intimating the booking of your order. If you have query 
related to your orders, please communicate with our Commercial/Logistics department.</span><br><br>
    </div><br>
  <div class="row3">  
    
    <span style="color:#1434A4; font-size: 12px;"><strong>ADLER HEALTHCARE PVT. LTD</strong></span>
            <p style="font-size: 8px;"> Plot No-A1 MIDC, Sadavali(Devrukh), <br/>
             Tal- Sangmeshwar, Dist -Ratnagiri , <br/> PIN-415804, Maharashtra, India<br/>
             Contact No: 8055136000, 8055146000<br/>
             E-Mail:adler-customer.care@adler-healthcare.com<br>
            CIN :U33125PN2020PTC195161 <br/>
           </p> 
 </div>    
 
</body>
</html>