<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <title> MRR_{{$mrr['vendor_name']}}_{{$mrr['mrr_date']}}</title>
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
                    <td>No</td>
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
                <td>MAC No</td>
                <td>:  &nbsp; &nbsp; &nbsp; {{$mrr['mac_number']}}</td>
            </tr>
            <tr>
                <td>MAC Date</td>
                <td>:  &nbsp; &nbsp; &nbsp; {{date('d-m-Y', strtotime($mrr['mac_date']))}}</td>
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
            <th>SI NO</th>
            <th>ITEM CODE</th>
            <th style="width:20%">DESCRIPTION</th>
            <th>ORDERED QTY</th>
            <th>RECEIVED QTY</th>
            <th>ACCEPTED QTY</th>
            <th>REJECTED QTY</th>
            <th>UNIT</th>
            <th style="width:20%">REASON FOR REJECTION</th>
            <th style="width:20%">REMARKS</th>
        </tr>
        @php $i=1; @endphp
            @foreach($items as $item)
        <tr>
            <td>{{$i++}}</td>
            <td>{{$item['item_code']}}</td>
            <td>{{$item['item_description']}}</td>
            <td>{{$item['actual_order_qty']}}</td>
            <td>{{$item['received_qty']}}</td>
            <td>{{$item['accepted_quantity']}}</td>
            <td>{{$item['rejected_quantity']}}</td>
            <td>{{$item['unit_name']}}</td>
            <td>{{$item['rejection_reason']}}</td>
            <td>{{$item['lot_number']}}</td>
            
        </tr>
        @endforeach
    </table> 
</body>
</html>