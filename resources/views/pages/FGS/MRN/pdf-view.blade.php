<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    
    <title>MRN_{{$mrn['firm_name']}}_{{$mrn['mrn_number']}}</title
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
            width:50%;
        }
        .col22{
            margin-top:-25px;
            float:left;
            width:50%;
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
            <span style="color:#1434A4;font-weight:bold;font-size: 18px;text-align: right; float: right !important; ">
                <strong>ADLER HEALTHCARE PVT. LTD.</strong></span>
        </div>
        
    </div>
            
    <div style="display:block;height: 8px;width:90%; border-bottom: solid black;margin-bottom:60px;margin-top:-50px;">
        <span style="float:right;font-weight:bold;font-size: 22px; background-color: #f4f5f8; padding: 0 4px;margin-top:-12px;position: absolute;margin-right:-80px">
        Material Receipt Notes (MRN)<!--Padding is optional-->
        </span>
    </div>
 
    <div class="row2">
        <div class="col21">
            <table>
                <tr>
                    <td>Supplier Doc No.</td>
                    <td>:  {{$mrn['supplier_doc_number']}}</td>
                   
                </tr>
                <tr>
                    <td>Supplier Doc Date</td>
                    <td>: {{date('d-m-Y', strtotime($mrn['supplier_doc_date']))}}</td>
                </tr>
                @if($mrn['supplier']==1)
                <tr>
                    <td>Supplier</td>
                    <td>: ADLER HEALTHCARE PVT. LTD.</td>
                </tr>
                <tr>
                    <td>Supplier Address</td>
                    <td>: MIDC Sadavali, Devrukh, Tal. Sangameshwar – 415 804</td>
                </tr>
                @elseif($mrn['supplier']==2)
                <tr>
                    <td>Supplier</td>
                    <td>: SMITH & NEPHEW HEALTHCARE PVT. LTD.</td>
                </tr>
                <tr>
                    <td>Supplier Address</td>
                    <td>: Andheri (East), Mumbai – 400 059</td>
                </tr>
                @endif
            </table>
        </div>
        
        <div class="col22">
            <table style="float:left;">
                <tr>
                    <td>Doc  No</td>
                    <td>: {{$mrn['mrn_number']}}</td>
                </tr>
                <tr>
                    <td> Doc  Date</td>
                    <td>: {{date('d-m-Y', strtotime($mrn['mrn_date']))}}</td>
                </tr>
                <tr>
                    <td> Product Category</td>
                    <td>: {{$mrn['category_name']}}</td>
                </tr>
                <tr>
                    <td>Stock Location</td>
                    <td>: {{$mrn['location_name1']}}</td> 
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
            <?php $qty=0; ?>
            @foreach($items as $item)
            <tr style="text-align:left;">
                <?php $qty=$qty+$item['quantity'];
									 ?>
                <td style="text-align:center;">{{$i++}}</td>
                <td>{{$item['hsn_code']}}</td>
                <td>{{$item['sku_code']}}</td>
                <td>{{$item['discription']}}</td>
                <td>{{$item['batch_no']}}</td>
                <td style="text-align:center;">{{$item['quantity']}}</td> 
                <td>Nos</td> 
                <td style="text-align:center;">{{date('d-m-Y', strtotime($item['manufacturing_date']))}}</td>
                <td style="text-align:center;">@if($item['expiry_date']!='0000-00-00') {{date('d-m-Y', strtotime($item['expiry_date']))}} @else NA  @endif</td>
               
            </tr>
            @endforeach
            <tr>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th style="text-align:center;font-weight:bold;">{{$qty}}</th>
				<th style="font-weight:bold;">Nos</th>
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
                {{$mrn['remarks']}}

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