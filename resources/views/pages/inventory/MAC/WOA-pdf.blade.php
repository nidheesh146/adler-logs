<!DOCTYPE html>
@inject('fn', 'App\Http\Controllers\Web\PurchaseDetails\MACController')
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <title>{{$mac['mac_number']}}</title>
</head>
<style>
    .main-heading{
        font-weight:500;
        font-size:20px;
        text-align:center;
        
    }
    .mrr_info{
        width:25%;
        font-size:12px;
        float:right;
        text-align:left;
        margin-top:40px;
    }
    .adler-address{
        width:30%;
        font-size:12px;
        float:left;
    }
    .row1{
        border-bottom: double;
    }
    .rmrn-info{
        width:50%;
        font-size:12px;
        float:left;
    }
    .mrd-info{
        width:50%;
        font-size:12px;
        float:right;
        text-align:right;

    }
    .supplier-info{
        float:left;
        width:25%;
        font-size:12px;
    }
    .reference{
        float:right;
        width:26%;
        font-size:12px;
        text-align:left;
    }
</style>
<body>
<?php  
            if (!function_exists('SplitPhone'))
            {
            function SplitPhone($data)
            {
                $a = "";
                $arr = explode(",",ltrim(rtrim($data,']'),'['));
                $len = count($arr);
                echo trim($arr[0],' " ');
                
            }
            }
            ?>
    <div class="row1" style="height:13%;">
        
        <div class="adler-address">
            <strong>ADLER HEALTHCARE PVT. LTD</strong>
            <p> Plot No-A1 MIDC, Sadavali(Devrukh), <br/>
             Tal- Sangmeshwar, Dist -Ratnagiri ,<br/>
               PIN-415804, Maharashtra, India<br/>
                CIN :U33125PN2020PTC195161 <br/>
                Company GSTIN :27AAJCB3689C1ZJ</p>
        </div>
        <div style="width:30%"></div>
        <div class="mrr_info">
            <strong>REPORT</strong>
            <table style="border-top:solid;">
                <tr>
                    <td>Date</td>
                    <td>: {{date('d-m-Y g:i a',strtotime($mac['created_at']))}}</td>
                </tr>
                <tr>
                    <td>WOA No</td>
                    <td>: {{$mac['mac_number']}}</td>
                </tr>
            </table>         
        </div>      
    </div><br/>
    <div class="main-heading">
        <div style="margin-top:-10px;">Work Order Acceptance</div>
    </div><br/>
    <div class="" style=" display:block;">
        <table  border="1" style="font-size:12px;width:100%;">
            <tr>
                <td>&nbsp; WOA No</td>
                <td>&nbsp; {{$mac['mac_number']}}</td>
                <td>&nbsp; WOA Date</td>
                <td>&nbsp; {{date('d-m-Y',strtotime($mac['mac_date']))}}</td>
            </tr>
            <tr>
                <td>&nbsp; Invoice No</td>
                <td>&nbsp; {{$mac['invoice_number']}}</td>
                <td>&nbsp; Created By</td>
                <td>&nbsp; {{$mac['f_name']}} {{$mac['l_name']}}</td>
            </tr>
            <tr>
                <td>&nbsp; Supplier Id</td>
                <td>&nbsp; {{$mac['vendor_id']}}</td>
                <td>&nbsp; Supplier Name</td>
                <td>&nbsp; {{$mac['vendor_name']}}</td>
            </tr>
        </table>
        <br>
    </div>
    <label style="font-size:14px;">WOA Items :</label>
    <table border="1" style="font-size:12px;width:100%;">
        <tr class="head" style="font-size:12px;">
            <th style="width:5%">SI No</th>
            <th style="width:8%">Item Code</th>
            <th style="width:25%">Description</th>
            <th>Type</th>
            <!-- <th>Lot Number</th>
            <th>Expiry Date</th> -->
            <th>WO Number</th>
            <th style="width:10%">WO Date</th>
            <th>Order Quantity</th>
            <th>WO Rate</th>
            <th>Accepted Quantity</th>
        </tr>
        @php $i=1; @endphp
        @foreach($items as $item)
        <tr>
            <?php
                if($item['po_number']==NULL)
                {
                    $po_info = $fn->getMergedPoinfo($item['invoice_item_id']);
                }
             ?>
            <td>{{$i++}}</td>
            <td>{{$item['item_code']}}</td>
            <td>{{$item['discription']}}</td>
            <td>{{$item['type_name']}}</td>
            <td>@if($item['po_number']!=NULL) {{$item['po_number']}} 
                @else
                @foreach($po_info as $info)
                    {{$info['po_number']}}<br/>
                @endforeach

                 @endif
            </td>
            <td>@if($item['po_date']!=NULL) {{date('d-m-Y', strtotime($item['po_date']))}}
                @else
                @foreach($po_info as $info)
                {{date('d-m-Y', strtotime($info['po_date']))}}<br/>
                @endforeach
                 @endif
            </td>
            <td>@if($item['order_qty']!=NULL) {{$item['order_qty']}} {{$item['unit_name']}}
                @else
                @foreach($po_info as $info)
                {{$info['order_qty']}} {{$item['unit_name']}}<br/>
                @endforeach
                @endif
            </td>
            <td>{{$item['po_rate']}}</td>
            {{--<td>{{$item['lot_number']}}</td>
            <td>@if($item['expiry_date']!=NULL) {{date('d-m-Y', strtotime($item['expiry_date']))}} @endif</td>--}}
            <td>@if($item['accepted_quantity']!=NULL) {{$item['accepted_quantity']}} {{$item['unit_name']}} @endif</td>
        </tr>
        @endforeach
         
    </table> 
    <br>
    <br>
    <label style="font-size:12px;float:left;">Signature of I/C Finance</label>
    <label style="font-size:12px;float:right;">Signature of Store</label>
    
</body>
</html>