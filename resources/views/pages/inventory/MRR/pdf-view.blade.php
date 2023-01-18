<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <title> @if($type=='po') MRR_{{$mrr['vendor_name']}}_{{$mrr['mrr_date']}} @else SRR_{{$mrr['vendor_name']}}_{{$mrr['mrr_date']}} @endif </title>
</head>
<style>
    .main-heading{
        font-weight:500;
        font-size:20px;
        text-align:center;
        
    }
    .mrr_info{
        width:20%;
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
        width:20%;
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
    <div class="row1" style="height:16%;">
        
        <div class="adler-address">
            <strong>ADLER HEALTHCARE PVT. LTD</strong>
            <p> Plot No-A1 MIDC, Sadavali(Devrukh), <br/>
             Tal- Sangmeshwar, Dist -Ratnagiri ,<br/>
               PIN-415804, Maharashtra, India<br/>
                CIN :U33125PN2020PTC195161 <br/>
                Company GSTIN :27AAJCB3689C1J</p>
        </div>
        <div style="width:30%"></div>
        <div class="mrr_info">
            <strong>REPORT</strong>
            <table style="border-top:solid;">
                <tr>
                    <td>Date</td>
                    <td>: {{date('d-m-Y g:i a',strtotime($mrr['created_at']))}}</td>
                </tr>
                <tr>
                    <td>@if($type=='po') MRR @else SRR @endif No</td>
                    <td>: {{$mrr['mrr_number']}}</td>
                </tr>
            </table>
            <!-- <strong>{{$mrr['vendor_name']}}</strong>
            <p>{{$mrr['address']}}</p>
            Cell No : {{ SplitPhone($mrr['contact_number']) }}<br/> -->
        </div>
        
    </div><br/>
    <div class="main-heading">
        <div style="margin-top:-10px;">@if($type=='po') Material @else Service @endif Inspection & Receipt Report( @if($type=='po') MRR @else SRR @endif)</div>
    </div><br/>
    <div class="third-row" style="display:block;height:150px;">
    <div class="supplier-info" style="">
       <strong>SUPPLIER</strong>
       <table style="border-top:solid;">
            <tr>
                <td>{{$mrr['vendor_name']}}</td>
            </tr>
            <tr>
                <td>{{$mrr['address']}}</td>
            </tr>
            <tr>
                <td>Cell No : {{ SplitPhone($mrr['contact_number']) }}</td>
            </tr>
        </table>
    </div>
    <div style="width:50%"></div>
    <div class="reference" style="">
       <strong>REFEREANCE DETAILS</strong>
       <table style="border-top:solid;">
            <tr>
                <td>MIQ No</td>
                <td>: &nbsp; &nbsp; &nbsp; {{$mrr['miq_number']}}</td>
            </tr>
            <tr>
                <td>MIQ Date</td>
                <td>:  &nbsp; &nbsp; &nbsp; {{date('d-m-Y', strtotime($mrr['miq_date']))}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>@if($type=='po') MAC @else WOA @endif No</td>
                <td>:  &nbsp; &nbsp; &nbsp; {{$mrr['mac_number']}}</td>
            </tr>
            <tr>
                <td>@if($type=='po') MAC @else WOA @endif Date</td>
                <td>:  &nbsp; &nbsp; &nbsp; {{date('d-m-Y', strtotime($mrr['mac_date']))}}</td>
            </tr>
            <tr>
                <td>@if($type=='po') MRD @else WOR @endif No</td>
                <td>:  &nbsp; &nbsp; &nbsp; @if($mrr['mrd_number']) {{$mrr['mrd_number']}} @endif</td>
            </tr>
            <tr>
                <td>@if($type=='po') MRD @else WOR @endif Date</td>
                <td>:  &nbsp; &nbsp; &nbsp; @if($mrr['mrd_date']) {{date('d-m-Y', strtotime($mrr['mrd_date']))}} @endif</td>
            </tr>
        </table>
    </div>
    <br/>
    <br/>
    </div>
    <div class="paragraph" style=" display:block;">
        <p style="font-size:12px;">
        Dear Sir, <br/>
        We acknowledge the receipt of the following items mentioned below.
        </p><br>
    </div>
    <table border="1" style="font-size:12px;width:100%;">
        <tr class="head" style="font-size:10px;">
            <th>SI No</th>
            <th style="width:8%">Item Code</th>
            <th style="width:20%">Description</th>
            <th>Ordered Qty</th>
            <th>Received Qty</th>
            <th>Accepted Qty</th>
            <th>Rejected Qty</th>
            <th>MIQ Rate</th>
            <th>@if($type=='po') PO @else WO @endif Rate</th>
            <th style="width:7%">@if($type=='po') PO @else WO @endif No.</th>
            <th style="width:5%">@if($type=='po') PO @else WO @endif Date</th>
            <th style="width:10%">Reason for rejection</th>
            <th style="width:6%">Lot Number</th>
        </tr>
        @php $i=1; @endphp
        @foreach($items as $item)
        <tr>
            <td>{{$i++}}</td>
            <td>{{$item['item_code']}}</td>
            <td>{{$item['item_description']}}</td>
            <td>{{$item['actual_order_qty']}} {{$item['unit_name']}}</td>
            <td>{{$item['received_qty']}} {{$item['unit_name']}}</td>
            <td>{{$item['accepted_quantity']}} {{$item['unit_name']}}</td>
            <td>{{$item['rejected_quantity']}} {{$item['unit_name']}}</td>
            <td>{{$item['conversion_rate']}}</td>
            <td>{{$item['rate']}}</td>
            <td>{{$item['po_number']}}</td>
            <td>{{date('d-m-Y', strtotime($item['po_date']))}}</td>
            <td>{{$item['rejection_reason']}}</td>
            <td>{{$item['lot_number']}}</td>
            
        </tr>
        @endforeach
    </table> 
    <br>
    <br>
    <div>
        <label class="form-label form-label-left form-label-auto" id="label_6" for="input_6" align="left"; style="float:left;">Signature of I/C QC</label>
        <label class="form-label form-label-left form-label-auto" id="label_6" for="input_6" align="right"; style="float:right;">Signature of I/C Finance</label>
    </div>
</body>
</html>