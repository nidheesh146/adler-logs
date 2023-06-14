<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    
    <title>mtq_{{$mtq['firm_name']}}_{{$mtq['mtq_number']}}</title
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
    <div class="row1" style="height:110px;width:100%;">
    
        <div class="col10">
            <span style="color:#1434A4;font-weight:bold;font-size: 18px;text-align: right; float: right !important; "><strong>ADLER HEALTHCARE PVT. LTD.</strong></span>
        </div>
        
    </div>
            
    <div style="display:block;height: 8px;width:90%; border-bottom: solid black;margin-bottom:40px;margin-top:-50px;">
        <span style="float:right;font-weight:bold;font-size: 22px; background-color: #f4f5f8; padding: 0 4px;margin-top:-12px;position: absolute;margin-right:-80px">
         Material Transferred To Qurantine(MTQ)<!--Padding is optional-->
        </span>
    </div>
 
    <div class="row2">
        <div class="col21">
            <table>
                <tr>
                    <td>Reference No.</td>
                    <td>:  {{$mtq['ref_number']}}</td>
                   
                </tr>
                <tr>
                    <td>Reference Date</td>
                    <td>: {{date('d-m-Y', strtotime($mtq['ref_date']))}}</td>
                </tr>
            </table>
        </div>
        <div class="col22">
        </div>
        <div class="col23">
            <table style="float:left;">
                <tr>
                    <td>Doc  No</td>
                    <td>: {{$mtq['mtq_number']}}</td>
                </tr>
                <tr>
                    <td> Doc  Date</td>
                    <td>: {{date('d-m-Y', strtotime($mtq['mtq_date']))}}</td>
                </tr>
                <tr>
                    <td> Product Category</td>
                    <td>: {{$mtq['category_name']}}</td>
                </tr>
                <tr>
                    <td>Stock Location</td>
                    <td>: {{$mtq['location_name1']}}</td> 
                </tr>
            </table>
        </div>   
    </div>
    
    <style>
        th{
            text-align:center;
        }
    </style>
    <div class="row3" >
    <table border="1" style="margin-top: -35px ;">
            <tr>
                <th>Sr.NO</th>
                <th style="width:10%;">
                    HSN CODE  
                </th>
                <th>ITEM CODE</th>
                <th width='35%'> DESCRIPTION</th>
                <th>BATCH NO</th>
                <th>BATCH QTY</th>
                <th>UOM</th>
                <th width='10%'>DATE OF MFG.</th>
                <th width='10%'>DATE OF EXPIRY.</th>
                
            </tr>
            <?php $i=1; ?>
            @foreach($items as $item)
            <tr style="text-align:left;">
                <td style="text-align:center;">{{$i++}}</td>
                <td>{{$item['hsn_code']}}</td>
                <td>{{$item['sku_code']}}</td>
                <td>{{$item['discription']}}</td>
                <td>{{$item['batch_no']}}</td>
                <td style="text-align:center;">{{$item['quantity']}}</td> 
                <td>Nos</td> 
                <td>{{date('d-m-Y', strtotime($item['manufacturing_date']))}}</td>
                <td>{{date('d-m-Y', strtotime($item['manufacturing_date']))}}</td>
               
            </tr>
            @endforeach
        
        </table>
    </div>
    <br/>
    <div class="row4" style="border-bottom:solid 1px black;height:170px;">
        <div class="col41">
            <div class="remarks" style="">
                <strong>Remarks/Notes </strong><br/>
              

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