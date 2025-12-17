<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    
    <title>SAI_{{$sai['sai_number']}}_{{$sai['sai_date']}}</title
</head>
<body>

    <style>
        .col1,.col3{
            float:left;
            width:25%;
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
           /* width:25%;*/
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
    <div class="row1" style="height:130px;">
        <div class="col1">
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
            
    <div style="display:block;height: 8px;width:90%; border-bottom: solid black;margin-bottom:20px;">
        <span style="float:right;font-weight:bold;font-size: 24px; background-color: #f4f5f8; padding: 0 4px;margin-top:-12px;position: absolute;margin-right:-80px">
        Stock Adjustment - Increase (SAI) <!--Padding is optional-->
        </span>
    </div>
    <br/>
    <div class="row2">
        <div class="col21" style="float:left;">
            <table>
                <tr>
                    <td>Doc Number</td>
                    <td>: {{$sai['sai_number']}} </td>
                </tr>
                <tr>
                    <td> Doc Date</td>
                    <td>: {{date('d-m-Y', strtotime($sai['sai_date']))}}</td>
                </tr>
            </table>
        </div>
        <div class="col22" style="margin-left:450px;">
            <table>
                
            </table>
        </div>
        <div class="col23" style="float:right;">
            <table>
                <tr>
                    <td>Location(Increase)</td>
                    <td>:&nbsp;{{$sai['location_name']}} </td>
                </tr>
            </table>
        </div>
        <div class="col24">
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
                <th>SL.NO</th>
                <th style="">
                    HSN CODE  
                </th>
                <th>SKU CODE</th>
                <th width='40%'>ITEM DESCRIPTION</th>
                <th width='6%'>Date of Mfg.</th>
                <th>Date of Expiry</th>
                <th>Batch No</th>
                <!-- <th rowspan="2">VALUE</th> -->
                <th>Qty</th>
                <th>UOM</th>
                <th>Rate</th>
                <th>TOTAL AMOUNT</th>
            </tr>
            <?php $i=1;
            $total = 0;
            $qsum = 0;
            $totalsum = 0;
            $total_amount = 0;
             ?>
            @foreach($items as $item)
            <tr>
                <td style="text-align:center;">{{$i++}}</td>
                <td>{{$item['hsn_code']}}</td>
                <td>{{$item['sku_code']}}</td>
                <td>{{$item['discription']}}</td>
                <td>
    {{ ($item['manufacturing_date'] === '1970-01-01' || $item['manufacturing_date'] === '0000-00-00') ? 'N.A.' : date('d-m-Y', strtotime($item['manufacturing_date'])) }}
</td>
                <td style="text-align:center;">@if($item['expiry_date']!='0000-00-00') {{date('d-m-Y', strtotime($item['expiry_date']))}} @else NA  @endif</td>
                <td style="text-align:center;">{{$item['batch_no']}}</td>
                <td style="text-align:center;">{{$item['quantity']}}</td>
                <td style="text-align:center;">Nos</td>
                <td style="text-align:right;">{{number_format((float)$item['rate'], 2, '.', '')}}</td>
                <td style="text-align:right;">{{number_format((float)($item['rate']*$item['quantity']), 2, '.', '')}}</td>
               
                <?php 
                 $total_amount =$total+ $item['rate']* $item['quantity'];
                 $qsum = $qsum+$item['quantity'];
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
                <th style="text-align:center;">{{  $qsum }}</th>
                <th style="text-align:center;">Nos</th>
                <th style="text-align:right;font-weight:bold;"></th>
                <th style="text-align:right;font-weight:bold;">{{number_format((float)($totalsum), 2, '.', '') }}</th>
            </tr>  
        
        </table>
    </div>
    <br/>
    <div class="row4" style="height:170px;">
        <div class="col41">
            
            <div class="remarks" style="">
                <strong>Remarks:  </strong><br/>
                @if($sai['remarks'])
                <?php echo nl2br($sai['remarks']); ?>
                @endif
            </div>
            
        </div>
        <div class="col42">
            <div class="" style="height:50px;float:right">
               
            </div>
        </div> 
        <div class="col43">
            <div class="" style="height:50px;float:right">
                For Adler Healthcare Pvt. Ltd.
            </div><br/><br/><br/><br/><br/><br/>
            <div class="" style="height:50px;float:right">
                Authorised Signatory
            </div>
        </div> 
    </div>
   
    <!-- <div style="border-top:solid 1.5px black; margin-top:5px;font-size:10px;">
    <br/><br/>
        
    </div> -->
    
     
   
</body>
</html>