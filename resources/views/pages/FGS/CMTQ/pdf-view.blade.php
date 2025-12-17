<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    
    <title>cmtq_{{$cmtq['firm_name']}}_{{$cmtq['cmtq_number']}}</title
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
            /* border-bottom:solid 0.5px black; */
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
            border-collapse: collapse;

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
        Cancellation Material Transferred To Qurantine(CMTQ)<!--Padding is optional-->
        </span>
    </div>
 
    <div class="row2">
        <div class="col21">
            <table>
            <tr>
                    <td>Reference No.</td>
                    <td>: {{$cmtq['ref_number']}}</td>

                </tr>
                <tr>
                    <td>Reference Date</td>
                    <td>: {{date('d-m-Y', strtotime($cmtq['ref_date']))}}</td>
                </tr>
                <tr>
                    <td>MTQ No.</td>
                    <td>: {{$cmtq['mtq_number']}}</td>

                </tr>
                <tr>
                    <td>MTQ Date.</td>
                    <td>: {{date('d-m-Y',strtotime($cmtq['mtq_date']))}} </td>

                </tr>
            </table>
        </div>
        <div class="col22">
        </div>
        <div class="col23">
            <table style="float:left;">
                <tr>
                    <td>Doc  No</td>
                    <td>: {{$cmtq['cmtq_number']}}</td>
                </tr>
                <tr>
                    <td> Doc  Date</td>
                    <td>: {{date('d-m-Y', strtotime($cmtq['cmtq_date']))}}</td>
                </tr>
                <tr>
                    <td> Business Category</td>
                    <td>: {{$cmtq['category_name']}}</td>
                </tr>
                <tr>
                    <td> Product Category</td>
                    <td>: {{$cmtq['new_category_name']}}</td>
                </tr>
                <tr>
                    <td>Stock Location
                        Increase</td>
                    <td>: {{$cmtq['location_name1']}}</td> 
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
                <th>ITEM CODE</th>
                <th style="width:10%;">
                    HSN CODE  
                </th>
                <th width='35%'> DESCRIPTION</th>
                <th>BATCH NO</th>
                <th>BATCH QTY</th>
                <th>UOM</th>
                <th width='10%'>DATE OF MFG.</th>
                <th width='10%'>DATE OF EXPIRY.</th>
                
            </tr>
            <?php $i=1; 
            $qsum=0;
            ?>
            @foreach($items as $item)
            <tr style="text-align:right;">
            <td style="text-align:center;">{{$i++}}</td>
                <td style="text-align:center;">{{$item['sku_code']}}</td>
                <td style="text-align:center;">{{$item['hsn_code']}}</td>
                <td style="text-align:left;">{{$item['discription']}}</td>
                <td style="text-align:center;">{{$item['batch_no']}}</td>
                <td style="text-align:center;">{{$item['quantity']}}</td>
                <td style="text-align:left;">Nos</td>
                <td style="text-align:center;">{{date('d-m-Y', strtotime($item['manufacturing_date']))}}</td>
                <td style="text-align:center;">@if($item['expiry_date']=='0000-00-00') NA @else{{date('d-m-Y', strtotime($item['expiry_date']))}} @endif</td>
               <?php $qsum=$qsum+$item['quantity']; ?>
            </tr>
            @endforeach
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th style="text-align:center;">{{$qsum}}</th>
                <th style="text-align:left;">Nos</th>
                <th></th>
                <th></th>
            </tr>
        
        </table>
    </div>
    <br/>
    <div class="row4" style="border-bottom:solid 1px black;height:170px;">
        <div class="col41">
        <div class="remarks" style="">
                <strong>Remarks/Notes </strong><br />
                @if($cmtq['remarks'])
                <?= nl2br($cmtq['remarks']); ?><br />
                @endif

            </div>
        </div>
        <div class="col42">
            <div class="" style="height:50px;">
            </div>
        </div>
       
    </div>
   
    <div style="border-top:solid 1.5px black; margin-top:5px;font-size:10px;">
    
    </div>
    
    <script type="text/php">
    if (isset($pdf)) {
    $xPage = 535; // X-axis for "Page", positioned on the right side
    $yPage = 810; // Y-axis horizontal position

    $textPage = "Page {PAGE_NUM} of {PAGE_COUNT}"; // "Page" message

    $font = $fontMetrics->get_font("helvetica");
    $size = 7;
    $color = "#808080";

    $pdf->page_text($xPage, $yPage, $textPage, $font, $size, $color); // "Page" on the right
    $pageNumber = $pdf->get_page_number();
    // Check if it's not the first page
    if (var_dump($pageNumber) != 1) {
        $xDoc = 535;  // X-axis for "Doc", positioned on the left side
        $yDoc = 15; // Y-axis horizontal position
        $textDoc = "{{$cmtq['cmtq_number']}}"; // "Doc" message
        $pdf->page_text($xDoc, $yDoc, $textDoc, $font, $size, $color); // "Doc" on the left
    }
}
</script> 
   
</body>
</html>