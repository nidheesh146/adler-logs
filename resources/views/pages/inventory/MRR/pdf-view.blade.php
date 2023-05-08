<!DOCTYPE html>
@inject('fn', 'App\Http\Controllers\Web\PurchaseDetails\MRRController')
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
    <div class="row1" style="height:18.5%;">
        
        <div class="adler-address">
            IT/F-01-02<br/>
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
                <td>@if($type=='po') Supplier Invoice No  @else Service Provider Invoice No @endif</td>
                <td>:&nbsp; {{$mrr['invoice_number']}}</td>
            </tr>
            <tr>
                <td>@if($type=='po') Supplier Invoice Date  @else Service Provider Invoice Date @endif</td>
                <td>:&nbsp; {{date('d-m-Y', strtotime($mrr['invoice_date']))}} </td>
            </tr>
            @if($type=='po')
            <tr>
                <td>MIQ No</td>
                <td>:&nbsp; {{$mrr['miq_number']}}</td>
            </tr>
            <tr>
                <td>MIQ Date</td>
                <td>: @if($mrr['miq_number']) &nbsp; {{date('d-m-Y', strtotime($mrr['miq_date']))}} @endif</td>
            </tr>
            @endif
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>@if($type=='po') MAC @else WOA @endif No</td>
                <td>:&nbsp; {{$mrr['mac_number']}}</td>
            </tr>
            <tr>
                <td>@if($type=='po') MAC @else WOA @endif Date</td>
                <td>:&nbsp;@if($mrr['mac_number']) {{date('d-m-Y', strtotime($mrr['mac_date']))}} @endif</td>
            </tr>
            <tr>
                <td>@if($type=='po') MRD @else WOR @endif No</td>
                <td>:&nbsp;@if($mrr['mrd_status']==1)  @if($mrr['mrd_number']) {{$mrr['mrd_number']}} @endif @endif</td>
            </tr>
            <tr>
                <td>@if($type=='po') MRD @else WOR @endif Date</td>
                <td>:&nbsp;@if($mrr['mrd_status']==1)  @if($mrr['mrd_number']) {{date('d-m-Y', strtotime($mrr['mrd_date']))}} @endif @endif</td>
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
            @if($type=='po')
            <th>MIQ Rate</th>
            @endif
            <th>@if($type=='po') PO @else WO @endif Rate</th>
            <th style="width:7%">@if($type=='po') PO @else WO @endif No.</th>
            <th style="width:7%">@if($type=='po') PO @else WO @endif Date</th>
            <th style="width:10%">Reason for rejection</th>
            @if($type=='po')
            <th style="width:6%">Lot Number</th>
            @endif
        </tr>
        @php $i=1; @endphp
        @foreach($items as $item)
        <tr>
            <?php $pos=$fn->getPO_for_merged_si_item($item['supplier_invoice_item_id']); ?>
            <td>{{$i++}}</td>
            <td>{{$item['item_code']}}</td>
            <td>{{$item['item_description']}}</td>
            <td>@if(!$item['po_number']) 
                    @foreach($pos as $po)
                        {{$po['order_qty']}} {{$item['unit_name']}}<br/>
                     @endforeach
                @else
                    {{$item['actual_order_qty']}} {{$item['unit_name']}}
                @endif </td>
            <!-- <td>{{$item['accepted_quantity']}} {{$item['unit_name']}}</td> -->
            <td>{{$item['received_qty']}} {{$item['unit_name']}}</td>
            <td>{{$item['accepted_quantity']}} {{$item['unit_name']}}</td>
            <td>{{$item['rejected_quantity']}} {{$item['unit_name']}}</td>
            @if($type=='po')
            <td>{{$item['conversion_rate']}}</td>
            @endif
            <td>{{$item['rate']}}</td>
            <td>@if(!$item['po_number'])
                    @foreach($pos as $po)
                        {{$po['po_number']}}<br/>
                    @endforeach
                @else
                    {{$item['po_number']}}
                @endif
            </td>
            <td>
                @if(!$item['po_number'])
                    @foreach($pos as $po)
                    {{date('d-m-Y', strtotime($po['po_date']))}}<br/>
                    @endforeach
                @else
                    {{date('d-m-Y', strtotime($item['po_date']))}}
                @endif
                
            </td>
            <td>{{$item['rejection_reason']}}</td>
            @if($type=='po')
            <td>{{$item['lot_number']}}</td>
            @endif
            
        </tr>
        @endforeach
    </table> 
    <br>
    <div>
        <label class="form-label form-label-left form-label-auto" id="label_6" for="input_6" align="left"; style="float:left;">Signature of I/C QC  @if($type=='po') (Only for Direct Material) @else (Only for Direct Service) @endif</label>
        <label class="form-label form-label-left form-label-auto" id="label_6" for="input_6" align="right"; style="float:right;">Signature of I/C Finance</label><br/>
    </div>
</body>
</html>