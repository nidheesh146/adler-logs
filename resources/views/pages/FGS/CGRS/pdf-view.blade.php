<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    
    <title>CGRS_{{$cgrs['firm_name']}}_{{$cgrs['cgrs_number']}}</title
</head>
<body>
@inject('fn', 'App\Http\Controllers\Web\PurchaseDetails\PurchaseController')
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
            width:30%;
        }
        .col22{
            margin-top:-25px;
            float:left;
            width:30%;
        }
        .col23{
            margin-left:120px;
            margin-top:-25px;
            float:left;
            width:40%;
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
            <strong>{{$cgrs['firm_name']}}</strong>
            <p>{{$cgrs['billing_address']}}<br/>
            {{$cgrs['city']}}, {{$cgrs['state_name']}}<br/>
            Cell No : {{ $cgrs['contact_number'] }}<br/>
            <span style="font-size:10px;  overflow-wrap: break-word;">Email:{{$cgrs['email']}}<br/><span>
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
       Cancellation Goods Reservation Slip(CGRS)<!--Padding is optional-->
        </span>
    </div>
    <br/>
    <div class="row2">
        <div class="col21">
            <table>
                <tr>
                    <td>OEF No.</td>
                    <td>: {{$cgrs['oef_number']}} </td>
                   
                </tr>
                <tr>
                    <td>OEF Date</td>
                    <td>: {{date('d-m-Y', strtotime($cgrs['oef_date']))}}</td>
                </tr>
                <tr>
                    <td>Order No.</td>
                    <td>: {{$cgrs['order_number']}} </td>
                </tr>
                <tr>
                    <td>Order Date</td>
                    <td>: {{date('d-m-Y', strtotime($cgrs['order_date']))}}</td>
                </tr>
            </table>
        </div>
        <div class="col22">
            <table style="float:left;">
                <tr>
                    <td>Zone</td>
                    <td>: {{$cgrs['zone_name']}} </td>
                </tr>
                <tr>
                    <td>Order Fulfil</td>
                    <td>: {{$cgrs['order_fulfil_type']}} </td>
                </tr>
                <tr>
                    <td> Sales Type</td>
                    <td>: {{$cgrs['sales_type']}}</td>
                </tr>
                <tr>
                    <td>Trnsctn Type</td>
                    <td>: {{$cgrs['transaction_name']}} </td>
                </tr>
            </table>
        </div>
        <div class="col23">
            <table style="float:left;">
                <tr>
                    <td>Doc  No</td>
                    <td>: {{$cgrs['cgrs_number']}}</td>
                </tr>
                <tr>
                    <td> Doc  Date</td>
                    <td>: {{$cgrs['cgrs_date']}}</td>
                </tr>
                <tr>
                    <td> Product Category</td>
                    <td>:{{$cgrs['category_name']}}</td>
                </tr>
                <tr>
                    <td>Stck Lctn (Decrease)</td>
                    <td>: {{$cgrs['location_name1']}}</td> 
                </tr>
                <tr>
                    <td>Stck Lctn (Increase)</td>
                    <td>: {{$cgrs['location_name2']}}</td> 
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
                <th>S.NO</th>
                <th style="width:10%;">
                    HSN CODE  
                </th>
                <th>SKU CODE</th>
                <th width='40%'> DESCRIPTION</th>
                <th>BATCH NO</th>
                <th>BATCH QTY</th>
                <th>UOM</th>
                <th style="width:9%;">DATE OF MFG.</th>
                <th style="width:9%;">DATE OF EXPIRY</th>
                
            </tr>
            <?php $i=1; ?>
            @foreach($items as $item)
            <tr>
                <td style="text-align:center;">{{$i++}}</td>
                <td>{{$item['hsn_code']}}</td>
                <td>{{$item['sku_code']}}</td>
                <td>{{$item['discription']}}</td>
                <td>{{$item['batch_no']}}</td>
                <td style="text-align:center;">{{$item['batch_quantity']}}</td> 
                <td>Nos</td> 
                <td>{{date('d-m-Y', strtotime($item['manufacturing_date']))}}</td>
                <td>@if($item['expiry_date']!='0000-00-00') {{date('d-m-Y', strtotime($item['expiry_date']))}} @else N.A @endif</td>
            </tr>
            @endforeach
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th style="text-align:center;">{{$qsum}}</th>
                <th>Nos</th>
                <th></th>
                <th></th>
            </tr>
        
        </table>
    </div>
    <br/>
    <div class="row4" style="border-bottom:solid 1px black;height:170px;">
        <div class="col41">
            <div class="remarks" style="">
                <strong>Remarks/Notes </strong><br/>
                @if($cgrs['remarks'])
                {{$cgrs['remarks']}}
                @endif
            </div>
            
        </div>
        <div class="col42">
            <div class="" style="height:50px;">
            </div>
        </div>
       
    </div>
   
    <div style="border-top:solid 1.5px black; margin-top:5px;font-size:10px;">
    
    </div>
    
     
   
</body>
</html>