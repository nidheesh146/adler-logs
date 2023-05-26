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
            <td  style="width:20%; text-align: center;" > <img src="{{asset('/img/logo.png')}}"  style="width:80px;"></td>
            <td  style="width:60%; text-align:center;">BATCH CARD <br>
            (TOP SHEET TO BE USED WITH LATEST REVISION OF PROCESS SHEET FOR RECORDING BATCH PROCESSING RECORDS & LINE CLEARANCE LOG)</td>
            <td style="width:20%">
              <table border="1" style="font-size:12px;width:100%;">
               <tr> <td>Doc. No :</td>
               </tr>
               <tr><td>Rev. No :</td>
               </tr>
               <tr><td>Rev. Date :</td>
               </tr>
              </table>
            </td>
        </tr>
    </table><br/><br/>
    <table border="1" style="font-size:12px;width:100%;">
        <tr  style="font-size:10px;">
            <th style="width:20%">START DATE :</th>
            <td style="width:30%">{{ $batch->start_date }}</td>
            <th style="width:20%">TARGET COMPLETION DATE :</th>
            <td style="width:30%">{{ $batch->target_date }}</td>
        </tr>
         <tr  style="font-size:10px;">
            <th style="width:20%">BATCH NO.:</th>
            <td style="width:30%">{{ $batch->batch_no }}</td>
            <th style="width:20%">QTY :</th>
            <td style="width:30%">{{ $batch->quantity }}</td>
        </tr>
         <tr style="font-size:10px;">
            <th style="width:20%">ITEM CODE :</th>
            <td style="width:30%">{{ $batch->item_code }}</td>
            <th style="width:20%">PROCESS SHEET NO :</th>
            <td style="width:30%">{{ $batch->process_sheet_id }}</td>
        </tr> 
         <tr style="font-size:10px;">
            <th style="width:20%">INPUT MATERIAL CODE NO:</th>
            <td style="width:30%">{{ $batch->unit_name }}</td>
            <th style="width:20%"></th>
            <td style="width:30%"></td>
        </tr>
         <tr  style="font-size:10px;">
            <th style="width:20%">INPUT MATERIAL LOT NO :</th>
            <td style="width:30%"></td>
            <th style="width:20%"></th>
            <td style="width:30%"></td>
        </tr>
         <tr style="font-size:10px;">
            <th style="width:20%">BARCODE BATCH NO :</th>
            <td style="width:30%"></td>
            <th style="width:20%">BARCODE ITEM CODE :</th>
            <td style="width:30%">{{ $batch->barcode_item_code }}</td>
        </tr>
         <tr border="1" style="font-size:10px; width: 100%;">
            <th style="width:20%">ITEM DESCRIPTION :</th>
            <td style="width:30%">{{ $batch->item_description }}</td>
             <th style="width:20%"></th>
            <td style="width:30%"></td>
         
        </tr>   
    </table>
    <br/><br/>
        <div>
            <span style="font-size:10px;">
            * When “First Piece OK” column is signed off at start / intermediate stage of any batch at any operation; it means that operator has performed pre-manufacturing line clearance as Per work instruction QA/3-A and checked the first piece for conformance to process requirements. When this signature is done after any change in CNC programming or tooling set-up the person doing the change will sign off. In some cases the operator itself does this change and verified the first piece to confirm the setting is done correctly.
            ** When “Remaining OK” column is signed off at intermediate stage of any batch at any operation; it means that operator has completed the process on number of pieces Mentioned in column A & B and also remaining pieces mentioned in column C are present and available for next shift operations.
            **When “Remaining OK” column is signed off at end of any batch at any operation; it means that operator has reconciled all A, B, C quantities of the batch and the batch can be Forwarded to next operation & operator has performed post-manufacturing line clearance as per work instruction QA/3-A.
            </span>
       </div>
       <div class="row3" >
          @php
   $f = 1;
   $l = 10;
@endphp
        
            <table border="1" style="width:100%;">
              
            <tr style="font-size:10px">
                <th>#</th>
                <th>Date</th>
                <th>Shift</th>
                <th>OPN No</th>
                <th> M/C No</th>
                <th>Operator No.</th>
                <th>Measuring inst. No.</th>
                <th>Qty. in Operation</th>
                <th>Total</th>
                <th >First Piece OK*</th>
                <th>First Piece OK*</th>
                <th>Remaining OK**</th>
                
            </tr>
            @for ($i = $f; $i <= $l; $i++)
            <tr style="font-size:14px">
                <td width="5%">{{$i}} </td>
                <td width="20%"> </td>
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
