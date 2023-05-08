<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    
    <title>CPI_{{$cpi['firm_name']}}_{{$cpi['cpi_number']}}</title
</head>
<body>
@inject('fn', 'App\Http\Controllers\Web\PurchaseDetails\PurchaseController')
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
    <div class="row1" style="height:170px;">
        <div class="col1">
            To<br/>
            <strong>{{$cpi['firm_name']}}</strong>
            <p>{{$cpi['billing_address']}}<br/>
            {{$cpi['city']}}, {{$cpi['state_name']}}<br/>
            Cell No : {{ $cpi['contact_number'] }}<br/>
            <span style="font-size:10px;  overflow-wrap: break-word;">Email:{{$cpi['email']}}<br/><span>
           </p>
          

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
        Cancellation Proforma Invoice(CPI)<!--Padding is optional-->
        </span>
    </div>
    <br/>
    <div class="row2">
        <div class="col21">
            <table>
                <tr>
                    <td>Zone</td>
                    <td>: {{$cpi['zone_name']}} </td>
                   
                </tr>
                <tr>
                    
                    <td >GRS No.</td>
                    <td>: {{$cpi['grs_number']}}</td>
                   
                </tr>
                <tr>
                    <td>GRS Date</td>
                    <td>: {{date('d-m-Y', strtotime($cpi['grs_date']))}} </td>
                </tr>
            </table>
        </div>
        <div class="col22">
            <table style="float:left;">
                <tr>
                    <td>OEF No.</td>
                    <td>: {{$cpi['oef_number']}} </td>
                </tr>
                <tr>
                    <td> OEF Date</td>
                    <td>: {{date('d-m-Y', strtotime($cpi['oef_date']))}}</td>
                </tr>
            </table>
        </div>
        <div class="col23">
            <table style="float:left;">
                <tr>
                    <td>Order No.</td>
                    <td>: {{$cpi['order_number']}} </td>
                </tr>
                <tr>
                    <td> Order Date</td>
                    <td>: {{date('d-m-Y', strtotime($cpi['order_date']))}}</td>
                </tr>
                <tr>
                    <td>Order Fulfil</td>
                    <td>: {{$cpi['order_fulfil_type']}} </td>
                </tr>
            </table>
        </div>
        <div class="col24">
            <table style="float:left;">
                <tr>
                    <td>Trnsctn Type</td>
                    <td>:{{$cpi['transaction_name']}} </td>
                </tr>
                <tr>
                    <td> Sales Type:</td>
                    <td>:{{$cpi['sales_type']}}</td>
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
                <th rowspan="2" width='35%'>ITEM DESCRIPTION</th>
                <th rowspan="2" style="width:10%;">DATE Of MFG.</th>
                <th rowspan="2" style="width:10%;">DATE Of EXPIRY</th>
                <th rowspan="2">BATCHCARD</th>
                <th rowspan="2">QTY</th>
                <th rowspan="2">UNIT</th>
                <th rowspan="2">RATE</th>
                <th rowspan="2">VALUE</th>
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
             ?>
            @foreach($items as $item)
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
                <td>{{number_format((float)($item['rate']* $item['quantity']), 2, '.', '') }}</td>
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
            </tr>
            @endforeach
        
        </table>
    </div>
    <br/>
    <div class="row4" style="border-bottom:solid 1px black;height:170px;">
        <div class="col41">
            <div class="valuewords">
                <strong>Value in Words</strong><br/>
                <span class="value_in_words"></span>
            </div>
            <div class="remarks" style="">
                <strong>Remarks/Notes </strong><br/>
                @if($cpi['remarks'])
                {{$cpi['remarks']}}
                @endif
            </div>
            
        </div>
        <div class="col42">
            <div class="" style="height:50px;">
            </div>
        </div>
        <div class="col43">
            <table style="height:130px;">
                <tr>
                    <td style="width:160px">Sum of Net Amount</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)($total-$total_discount), 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td style="width:160px">Total Discount</td>
                    <td style="width:30px;">:</td>
                    <td style="text-align:right;">{{number_format((float)$total_discount, 2, '.', '')}}</td>
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
                
            </table>
            <table style="border-bottom:solid 1px black;width:100%;border-top:solid 1px black;width:100%;">
                <tr>
                    <th style="width:130px">GRAND TOTAL</th>
                    <th style="width:30px;">:</th>
                    <th class="grand_total_value" style="text-align:right;">{{number_format((float)($total-$total_discount+$total_igst+$total_sgst+$total_sgst), 2, '.', '')}} {{$cpi['currency_code']}}</th>
                </tr> 
            </table>
        </div>
    </div>
   
    <div style="border-top:solid 1.5px black; margin-top:5px;font-size:10px;">
    
    </div>
    
     
   
</body>
</html>