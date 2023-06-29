<!DOCTYPE html>
@inject('fn', 'App\Http\Controllers\Web\BatchCardController')
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <title> BatchCard_</title>
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
  <table border="1" style="font-size:12px;width:100%;">
       <tr>
            <td  style="width:12%; text-align: center;" > <img src="{{asset('/img/logo.png')}}"  style="width:60px;"></td>
            <td  style="width:70%; text-align:center;font-weight:bold;">BATCH CARD <br>
            (TOP SHEET TO BE USED WITH LATEST REVISION OF PROCESS SHEET FOR RECORDING BATCH PROCESSING RECORDS & LINE CLEARANCE LOG)</td>
            <td style="width:18%">
              <table border="1" style="font-size:12px;width:100%;">
               <tr> <td><b>Doc. No :PC/F-01<b></td>
               </tr>
               <tr><td>Rev. No : 00</td>
               </tr>
               <tr><td>Rev. Date :12DEC2021</td>
               </tr>
              </table>
            </td>
        </tr>
    </table>
    <table border="1" style="font-size:12px;width:100%;margin-top:5px;">
        <tr  style="font-size:11px;text-align:right;">
            <th style="width:27%;height:30px;">START DATE :</th>
            <td style="width:30%">@if($batch->start_date) {{strtoupper(date( 'd M Y' , strtotime($batch->start_date)) )}} @endif</td>
            <th style="width:27%">TARGET COMPLETION DATE :</th>
            <td style="width:30%">@if($batch->target_date) {{strtoupper(date( 'd M Y' , strtotime($batch->target_date)) )}} @endif</td>
        </tr>
         <tr  style="font-size:11px;height:20px;text-align:right;">
            <th style="width:27%;height:30px;">BATCH NO.:</th>
            <td style="width:30%;font-size:12px"><b>{{ $batch->batch_no }}</b></td>
            <th style="width:27%">QTY :</th>
            <td style="width:30%">{{ $batch->quantity }} Nos</td>
        </tr>
         <tr style="font-size:11px;height:20px;text-align:right;">
            <th style="width:27%;height:30px;">ITEM CODE :</th>
            <td style="width:30%">{{ $batch->sku_code }}</td>
            <th style="width:27%">PROCESS SHEET NO :</th>
            <td style="width:30%">{{ $batch->process_sheet_no }}</td>
        </tr> 
         <tr style="font-size:11px;height:20px;text-align:right;">
            <th style="width:27%;height:30px;">INPUT MATERIAL CODE NO:</th>
            <td style="width:30%">{{ $batch->item_code }}</td>
            <th style="width:27%"></th>
            <td style="width:30%"></td>
        </tr>
         <tr  style="font-size:11px;text-align:right;">
            <th style="width:27%;height:50px;">INPUT MATERIAL LOT NO :</th>
            <td style="width:30%"></td>
            <th style="width:27%"></th>
            <td style="width:30%"></td>
        </tr>
         <tr style="font-size:11px;height:40px;">
            <th style="width:27%;text-align:right;">BARCODE BATCH NO :</th>
            <td style="width:30%;">
                <img src="data:image/png;base64,{{ base64_encode($batchno_barcode)}}" style="width:120px;height:30px;margin-left:10px;margin-top:10px;">
                <br/>
                <div style="font-size:8px;font-weight:bold;text-align:center;width:120px;margin-left:10px;">* {{ $batch->batch_no }} *</div>
            </td>
            <th style="width:27%;text-align:right;">BARCODE ITEM CODE :</th>
            <td style="width:30%">
                <img src="data:image/png;base64,{{ base64_encode($sku_code_barcode)}}" style="width:120px;height:30px;margin-left:10px;margin-top:10px;">
                <br/>
                <div style="font-size:8px;font-weight:bold;text-align:center;width:120px;margin-left:10px;">* {{ $batch->sku_code }} *</div>
            </td>
        </tr>
         <tr border="1" style="font-size:11px; width: 100%;height:20px;">
            <th style="width:20%;text-align:right;">ITEM DESCRIPTION :</th>
            <td colspan="3" style="width:30%;text-align:left;height:3%;">{{ $batch->discription }}</td>
             <!-- <th style="width:20%"></th>
            <td style="width:30%"></td> -->
         
        </tr>   
    </table>
        <div>
            <span style="font-size:9px;">
            * When “First Piece OK” column is signed off at start / intermediate stage of any batch at any operation; it means that operator has performed pre-manufacturing line clearance as Per work instruction QA/3-A and checked the first piece for conformance to process requirements. When this signature is done after any change in CNC programming or tooling set-up the person doing the change will sign off. In some cases the operator itself does this change and verified the first piece to confirm the setting is done correctly.<br/>
            ** When “Remaining OK” column is signed off at intermediate stage of any batch at any operation; it means that operator has completed the process on number of pieces Mentioned in column A & B and also remaining pieces mentioned in column C are present and available for next shift operations.<br/>
            **When “Remaining OK” column is signed off at end of any batch at any operation; it means that operator has reconciled all A, B, C quantities of the batch and the batch can be Forwarded to next operation & operator has performed post-manufacturing line clearance as per work instruction QA/3-A.<br/>
            </span>
       </div>
       <div class="row3" >
          @php
   $f = 1;
   $l = 23;
@endphp
        
            <table border="1" style="width:100%;">
              
            <tr style="font-size:11px;text-align:center;">
                <!-- <th rowspan="2">#</th> -->
                <th rowspan="2" style="width:70px;">Date</th>
                <th rowspan="2" style="width:40px;">Shift</th>
                <th rowspan="2"  style="width:40px;">OPN No</th>
                <th rowspan="2" style="width:70px;"> M/C No</th>
                <th rowspan="2" style="width:50px;">Operator No.</th>
                <th rowspan="2" style="width:80px;">Measuring inst. No.</th>
                <th  colspan="3">Qty. in Operation</th>
                <th rowspan="2"  style="width:60px;">Total</th>
                <th rowspan="2">First Piece OK*</th>
                <th rowspan="2">Remaining OK**</th>
            </tr>
            <tr style="font-size:10px;text-align:center;">
                <th style="width:50px;">A<br/> Accepted</th>
                <th style="width:50px;">B <br/>Rejected</th>
                <th style="width:50px;">C <br/>Remaining</th>
            </tr>
            @for ($i = $f; $i <= $l; $i++)
            <tr style="font-size:14px">
                <!-- <td width="5%">{{$i}} </td> -->
                <td width="20%;height:3.3%;"> </td>
                <td width="20%"></td>
                <td width="20%"></td>
                <td width="20%"></td>
                <td width="20%"></td>
                <td width="20%"></td>
                <td width="20%"></td>
               
                <td width="20%"></td>
                <td width="20%"></td>
                <td width="20%"></td>
                <td width="20%"></td>
                <td width="20%"></td>
            </tr>
            @endfor
             
            </table>
       </div>

</body>
</html>
