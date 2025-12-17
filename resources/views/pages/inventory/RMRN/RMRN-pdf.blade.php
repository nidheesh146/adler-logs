<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <title> RMRN_{{$rmrn['vendor_name']}}_{{$rmrn['rmrn_date']}}</title>
</head>
<style>
    .main-heading{
        padding-bottom:10px;
        font-weight:500;
        font-size:20px;
        /* padding-left:18%; */
        text-align:center;
        border-bottom: double;
    }
    .supplier-address{
        width:20%;
        font-size:12px;
        float:left;
        text-align:left;
    }
    .adler-address{
        width:30%;
        font-size:12px;
        float:right;
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
        <div class="supplier-address">
            TO:<br>
        <strong>{{$rmrn['vendor_name']}}</strong>
        <p>{{$rmrn['address']}}
        Cell No : {{ SplitPhone($rmrn['contact_number']) }}</p><br/>
        </div>
        <div style="width:23%"></div>
        <div class="adler-address">
            FROM:<br>
            <strong>ADLER HEALTHCARE PVT. LTD</strong>
            <p> Plot No-A1 MIDC, Sadavali(Devrukh),
             Tal- Sangmeshwar, Dist -Ratnagiri ,<br/>
               PIN-415804, Maharashtra, India<br/>
                CIN :U33125PN2020PTC195161 <br/>
                Company GSTIN :27AAJCB3689C1ZJ</p>
                <br/>
        </div>

    </div><br/>
    <div class="main-heading">
        <div style="margin-top:-10px;">Rejected Material Return Note(RMRN)</div>
    </div><br/>
    <div class="rmrn-info" style="">
        Date:{{date('d-m-Y', strtotime($rmrn['rmrn_date']))}}
        <br/>
        Created By : {{$rmrn['f_name']}} {{$rmrn['l_name']}}
    </div>
    <div class="mrd-info" style="">
       @if($rmrn['type']=='PO') MRD  @else WOR @endif Number : {{$rmrn['mrd_number']}}
    </div><br/><br/>
    <table border="1" style="font-size:12px;width:100%;">
        <tr class="head" style="font-size:10px;">
            <th>SI NO</th>
            <th>ITEM CODE</th>
            <th style="width:20%">DESCRIPTION</th>
            <th>QTY</th>
            <th>LOT NO.</th>
            <th>COURIER /TRANSPORT NAME</th>
            <th>RECEIPT /LR NUMBER</th>
            <th >REASON</th>
        </tr>
        @php $i=1; @endphp
        @foreach($items as $item)
        <tr>
            <td>{{$i++}}</td>
            <td>{{$item['item_code']}}</td>
            <td style="width:20%">{{$item['short_description']}}</td>
            <td>{{$item['rejected_quantity']}} {{$item['unit_name']}}</td>
            <td>@if($item['lot_number']!=NULL) {{$item['lot_number']}} @else 'NA' @endif</td>
            <td>{{$item['courier_transport_name']}}</td>
            <td>{{$item['receipt_lr_number']}}</td>
            <td>{{$item['remarks']}}</td>
        </tr>
        @endforeach
    </table>
    <br>
    <br>


        <div  style="font-size:12px;">

                <label class="form-label form-label-left form-label-auto" id="label_6" for="input_6" align="left";>Sign of store incharge</label>
</div>

</body>
</html>
