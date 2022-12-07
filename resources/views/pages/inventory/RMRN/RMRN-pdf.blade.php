<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <title> RMRN_{{$rmrn['vendor_name']}}_{{$rmrn['rmrn_date']}}</title>
</head>
<style>
    .main-heading{
        font-weight:500;
        font-size:20px;
        text-align:center;
        border-bottom: double;
    }
    .supplier-address{
        width:20%;
        font-size:12px;
        float:right;
        text-align:left;
    }
    .adler-address{
        width:50%;
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
</style>
<body>
    <div class="row1" style="height:12%;">
        <div class="adler-address">
            <strong>ADLER HEALTHCARE PVT. LTD</strong>
            <p> Plot No-A1 MIDC, Sadavali(Devrukh), <br/>
             Tal- Sangmeshwar, Dist -Ratnagiri ,<br/>
               PIN-415804, Maharashtra, India<br/>
                CIN :U33125PN2020PTC195161 <br/>
                Company GSTIN :27AAJCB3689C1J</p>
        </div>
        <div style="width:30%"></div>
        <div class="supplier-address">
        <strong>{{$rmrn['vendor_name']}}</strong>
        <p>{{$rmrn['address']}}</p>
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
        MRD Number : {{$rmrn['mrd_number']}}
    </div><br/><br/>
    <table border="1" style="font-size:12px;width:100%;">
        <tr class="head" style="font-size:10px;">
            <th>SI NO</th>
            <th>ITEM CODE</th>
            <th>HSN/SAC Code</th>
            <th>QTY</th>
            <th>COURIER /TRANSPORT NAME</th>
            <th>RECEIPT /LR NUMBER	</th>
            <th style="width:20%">REASON</th>
        </tr>
        <tr>
            @php $i=1; @endphp
            @foreach($items as $item)
            <td>{{$i++}}</td>
            <td>{{$item['item_code']}}</td>
            <td>{{$item['hsn_code']}}</td>
            <td>{{$item['rejected_quantity']}} {{$item['unit_name']}}</td>
            <td>{{$item['courier_transport_name']}}</td>
            <td>{{$item['receipt_lr_number']}}</td>
            <td style="width:20%">{{$item['remarks']}}</td>
            @endforeach
        </tr>
    </table>
</body>
</html>