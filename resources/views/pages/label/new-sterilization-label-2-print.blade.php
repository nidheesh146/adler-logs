@extends('layouts.default')
@section('content')
<style>
    .smalltext{
        font-size:6px;
    }
    .column {
        float: left;
        width: 33.33%;
    }

    /* Stops the float property from affecting content after the columns */
    .columns:after {
        content: "";
        display: table;
        clear: both;
    }

    .label-container::after {
        content: "";
        display: block;
        clear: both;
    }

    .label-container { 
        height:auto !important; 
        min-height:310px; /* ensures spacing */
        position:relative;
        display:block;
    }
    
    .img{
        margin-top:5px;
    }
    
    /* Print specific styles */
    @media print {
        .page-container {
            page-break-after: always !important;
            page-break-inside: avoid !important;
        }
        
        .label-container {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>

<div class="az-content az-content-dashboard">
  <br>
    <div  class="container">
        <div class="az-content-body" style="color:black;">     
            <div class="az-content-title">
                <button style="float: right; margin-left: 9px;font-size: 14px;width:90px;" class="badge badge-pill badge-info " id="print">
                    <i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print
                </button>
            </div>
            
            <div class="row columns">
                <?php
                    $label_per_page = 14;
                    $page_count = (int)($no_of_label/14);
                    $remaining = $no_of_label % 14;
                ?>
                
                <input type="hidden" name="batch_id" id="batch_id" value="{{$batchcard_data->batch_id}}">
                <input type="hidden" name="no_of_labels" id="no_of_labels" value="{{$no_of_label}}">
                <input type="hidden" name="manufacturing_date" id="manufacturing_date" value="{{$manufacture_date}}">
                <input type="hidden" name="product_id" id="product_id" value="{{$batchcard_data->product_id}}">
                <input type="hidden" name="expiry_date" id="expiry_date" value="{{date('Y-m-d', strtotime($sterilization_expiry_date))}}">
                <input type="hidden" name="label_name" id="label_name" value="Sterilization Label">

                <div class="label-div" id="label-div">
                    @for ($i = 0; $i < $page_count; $i++)
                    <div class="page-container" style="margin-top:0.4cm;margin-bottom:0.45cm;margin-left:-5px;width:21.1cm;height:29.3cm;">
                        @for ($j = 0; $j < $label_per_page; $j++)
                        <div class="label-container" style="float:left; width:50%; margin-bottom:12px; @if($j%2==0) margin-right:0px; @else margin-right:0px; @endif"> 
                            <div class="row1" style="font-size:12px;">    
                                <div class="subcolumn1" style="float:left; width:60px">
                                    <div class="logo" style="text-align:center;">
                                        <img src="{{asset('/img/logo.png')}}" style="width:78%;">
                                    </div>
                                    <div class="icons" style="margin-top:8px;margin-left:2px; text-align:center;">
                                        <img src="{{asset('/img/alderlogo/dot_not_reuse.png')}}" style="width:35%;">
                                        <img src="{{asset('/img/alderlogo/instruction_use.png')}}" style="width:35%;">
                                    </div>
                                    <span style="text-align:left; writing-mode: vertical-rl; transform: rotate(360deg); margin-top:15px; margin-left:16px;">
                                        <strong>{{$batchcard_data->sku_code}}</strong>
                                    </span>
                                </div>
                                
                                <div class="subcolumn2" style="float:left; width:300px">
                                    <!-- First Row: Ref, Lot, Sterile, Manufacturing -->
                                    <div class="subdiv compact-div" style="display:flex; justify-content:space-between; width:100%;">
                                        <div class="ss" style="width:150px;">
                                            <div style="font-size:12px;">
                                                <strong>Ref: {{$batchcard_data->sku_code}}</strong>
                                            </div>
                                            <div style="margin-top:5px;">
                                                <img src="{{asset('/img/alderlogo/lot.png')}}" style="width:28px; vertical-align:middle;">
                                                <span style="font-size:11px; margin-left:5px;">{{$batchcard_data->batch_no}}</span>
                                            </div>
                                            <div style="margin-top:5px; display:inline-block;">
                                                <div style="display:inline-block; font-family: Arial, sans-serif; font-size:7px; font-weight:400;">
                                                    <span style="padding:2px 4px; border:1px solid #000;">STERILE</span>
                                                    <span style="padding:2px 4px; border-top:1px solid #000; border-right:1px solid #000; border-bottom:1px solid #000; margin-left:-1px;">
                                                    @if($batchcard_data->sterilization_type=='R')
                                                        R
                                                    @elseif($batchcard_data->sterilization_type=='EO')
                                                        EO
                                                    @else
                                                        &nbsp;
                                                    @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div style="font-size:10px; margin-top:3px;">
                                                {{$lot_no}}
                                            </div>
                                            <div style="margin-top:5px; font-size:10px;">
                                                <img src="{{asset('/img/alderlogo/manufacturing.png')}}" style="width:18px; vertical-align:middle;">
                                                {{$manufacture_date}}
                                            </div>
                                        </div>
                                        
                                        <div class="ss" style="width:150px; text-align:right;">
                                            <div style="font-size:12px; margin-right:20px;">
                                                <strong>Qty: </strong>{{$per_pack_quantity}} Nos
                                            </div>
                                            <div style="height:6px; margin-top:10px; margin-right:20px;">
                                                <span style="font-weight:bold; font-size:8px; background-color:#f4f5f8; padding:0 10px;">
                                                    STERILIZATION
                                                </span>
                                            </div>
                                            <div class="box" style="font-size:9px; padding:10px; margin-top:5px; margin-right:20px;">
                                                Expiry&nbsp;<img src="{{asset('/img/alderlogo/expiry_date.png')}}" style="width:8px; height:10px; vertical-align:middle;">
                                                &nbsp;: {{ date('m-Y', strtotime($sterilization_expiry_date)) }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Product Description -->
                                    <div class="subdiv compact-div" style="margin-top:10px;">
                                        <div class="group" style="font-weight:400; font-size:9px; line-height:1.2;">
                                            <span style="font-size:11px; font-weight:bold;">{{$batchcard_data->ad_sp1}}</span><br/>
                                            {{$batchcard_data->groups}}<br/>
                                            {{$batchcard_data->discription}}
                                        </div>
                                    </div>
                                    
                                    <!-- Barcode Section -->
                                    <div class="subdiv compact-div" style="margin-top:10px;">
                                        <div style="display:flex; justify-content:space-between; width:100%;">
                                            <div style="width:150px;">
                                                <img src="data:image/png;base64,{{ base64_encode($sku_code_barcode)}}" style="height:30px; width:140px;">
                                                <div style="font-size:10px; text-align:center; margin-top:3px;">
                                                    {{$batchcard_data->sku_code}}
                                                </div>
                                                <div style="font-size:8px; margin-top:3px; text-align:center;">
                                                    {{$batchcard_data->drug_license_number}}
                                                </div>
                                            </div>
                                            <div style="width:150px; text-align:center;">
                                                <div style="font-size:11px; font-weight:bold; margin-bottom:5px;">
                                                    {{$batchcard_data->ad_sp2}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Right Column - LBL and MRP -->
                                <div style="float:right; width:40px; display:flex; flex-direction:column;">
                                    <!-- LBL Reference -->
                                    <span style="font-size:7px; writing-mode: vertical-lr; transform: rotate(180deg); margin-top:20px; margin-right:10px;">
                                        LBL/F-{{$batchcard_data->label_format_number}}_REV01_{{strtoupper(date('dMY', strtotime('27-11-2024')))}}
                                    </span>
                                    
                                    <!-- MRP -->
                                    <div style="font-size:12px; font-weight:bold; margin-top:80px; margin-right:15px; transform: rotate(-90deg); white-space: nowrap;">
                                        MRP: {{$mrp}}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Bottom Row - Manufacturer Info and Product Image -->
                            <div class="row2" style="margin-top:10px; clear:both;">
                                <!-- Manufacturer Address -->
                                <div style="float:left; width:200px; margin-left:60px;">
                                    <div style="display:flex; align-items:flex-start;">
                                        <div>
                                            <img src="{{asset('/img/alderlogo/manufactured_address.png')}}" style="width:32px; height:18px;">
                                        </div>
                                        <div style="font-size:8px; line-height:1.1; margin-left:5px;">
                                            <span style="font-weight:bold;">ADLER HEALTHCARE PVT. LTD</span><br/>
                                            Plot No-A1 MIDC, Sadavali, Tal- Sangmeshwar<br/>
                                            Dist -Ratnagiri, Maharashtra-415804<br/>
                                            MADE IN INDIA
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Product Image -->
                                <div style="float:right; width:100px; text-align:center; margin-right:20px; margin-top:-20px;">
                                    @if($batchcard_data->label_image)
                                        <?php $img_path = '/img/'.$batchcard_data->label_image; ?>
                                        @if(file_exists(public_path($img_path))) 
                                            <img src="{{asset($img_path)}}" style="width:80px; height:auto;">
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endfor
                        <div style="break-after:page"></div>
                    </div>
                    @endfor
                    
                    @if($remaining != 0)
                    <div class="page-container" style="margin-top:0.2cm;margin-left:0px;margin-bottom:0.45cm;width:21.1cm;height:29.3cm;">
                        @for ($j = 1; $j <= $label_per_page; $j++)
                        <div class="label-container" style="float:left; width:50%; margin-bottom:12px; @if($j%2==0) margin-right:0px; @else margin-right:0px; @endif"> 
                            @if($j <= $remaining)
                            <div class="row1" style="font-size:12px;">    
                                <div class="subcolumn1" style="float:left; width:60px">
                                    <div class="logo" style="text-align:center;">
                                        <img src="{{asset('/img/logo.png')}}" style="width:78%;">
                                    </div>
                                    <div class="icons" style="margin-top:8px;margin-left:2px; text-align:center;">
                                        <img src="{{asset('/img/alderlogo/dot_not_reuse.png')}}" style="width:35%;">
                                        <img src="{{asset('/img/alderlogo/instruction_use.png')}}" style="width:35%;">
                                    </div>
                                    <span style="text-align:left; writing-mode: vertical-rl; transform: rotate(360deg); margin-top:15px; margin-left:16px;">
                                        <strong>{{$batchcard_data->sku_code}}</strong>
                                    </span>
                                </div>
                                
                                <div class="subcolumn2" style="float:left; width:300px">
                                    <!-- First Row: Ref, Lot, Sterile, Manufacturing -->
                                    <div class="subdiv compact-div" style="display:flex; justify-content:space-between; width:100%;">
                                        <div class="ss" style="width:150px;">
                                            <div style="font-size:12px;">
                                                <strong>Ref: {{$batchcard_data->sku_code}}</strong>
                                            </div>
                                            <div style="margin-top:5px;">
                                                <img src="{{asset('/img/alderlogo/lot.png')}}" style="width:28px; vertical-align:middle;">
                                                <span style="font-size:11px; margin-left:5px;">{{$batchcard_data->batch_no}}</span>
                                            </div>
                                            <div style="margin-top:5px; display:inline-block;">
                                                <div style="display:inline-block; font-family: Arial, sans-serif; font-size:7px; font-weight:400;">
                                                    <span style="padding:2px 4px; border:1px solid #000;">STERILE</span>
                                                    <span style="padding:2px 4px; border-top:1px solid #000; border-right:1px solid #000; border-bottom:1px solid #000; margin-left:-1px;">
                                                    @if($batchcard_data->sterilization_type=='R')
                                                        R
                                                    @elseif($batchcard_data->sterilization_type=='EO')
                                                        EO
                                                    @else
                                                        &nbsp;
                                                    @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div style="font-size:10px; margin-top:3px;">
                                                {{$lot_no}}
                                            </div>
                                            <div style="margin-top:5px; font-size:10px;">
                                                <img src="{{asset('/img/alderlogo/manufacturing.png')}}" style="width:18px; vertical-align:middle;">
                                                11-2025
                                            </div>
                                        </div>
                                        
                                        <div class="ss" style="width:150px; text-align:right;">
                                            <div style="font-size:12px; margin-right:20px;">
                                                <strong>Qty: </strong>{{$per_pack_quantity}} Nos
                                            </div>
                                            <div style="height:6px; margin-top:10px; margin-right:20px;">
                                                <span style="font-weight:bold; font-size:8px; background-color:#f4f5f8; padding:0 10px;">
                                                    STERILIZATION
                                                </span>
                                            </div>
                                            <div class="box" style="font-size:9px; padding:10px; margin-top:5px; margin-right:20px;">
                                                Expiry&nbsp;<img src="{{asset('/img/alderlogo/expiry_date.png')}}" style="width:8px; height:10px; vertical-align:middle;">
                                                &nbsp;: @if($batchcard_data->batch_id == 47172)
                                                    10-2030
                                                @else
                                                    {{ date('m-Y', strtotime($sterilization_expiry_date)) }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Product Description -->
                                    <div class="subdiv compact-div" style="margin-top:10px;">
                                        <div class="group" style="font-weight:400; font-size:9px; line-height:1.2;">
                                            <span style="font-size:11px; font-weight:bold;">{{$batchcard_data->ad_sp1}}</span><br/>
                                            {{$batchcard_data->groups}}<br/>
                                            {{$batchcard_data->discription}}
                                        </div>
                                    </div>
                                    
                                    <!-- Barcode Section -->
                                    <div class="subdiv compact-div" style="margin-top:10px;">
                                        <div style="display:flex; justify-content:space-between; width:100%;">
                                            <div style="width:150px;">
                                                <img src="data:image/png;base64,{{ base64_encode($sku_code_barcode)}}" style="height:30px; width:140px;">
                                                <div style="font-size:10px; text-align:center; margin-top:3px;">
                                                    {{$batchcard_data->sku_code}}
                                                </div>
                                                <div style="font-size:8px; margin-top:3px; text-align:center;">
                                                    {{$batchcard_data->drug_license_number}}
                                                </div>
                                            </div>
                                            <div style="width:150px; text-align:center;">
                                                <div style="font-size:11px; font-weight:bold; margin-bottom:5px;">
                                                    {{$batchcard_data->ad_sp2}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Right Column - LBL and MRP -->
                                <div style="float:right; width:40px; display:flex; flex-direction:column;">
                                    <!-- LBL Reference -->
                                    <span style="font-size:7px; writing-mode: vertical-lr; transform: rotate(180deg); margin-top:20px; margin-right:10px;">
                                        LBL/F-{{$batchcard_data->label_format_number}}_REV01_{{strtoupper(date('dMY', strtotime('27-11-2024')))}}
                                    </span>
                                    
                                    <!-- MRP -->
                                    <div style="font-size:12px; font-weight:bold; margin-top:80px; margin-right:15px; transform: rotate(-90deg); white-space: nowrap;">
                                        MRP: {{$mrp}}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Bottom Row - Manufacturer Info and Product Image -->
                            <div class="row2" style="margin-top:10px; clear:both;">
                                <!-- Manufacturer Address -->
                                <div style="float:left; width:200px; margin-left:60px;">
                                    <div style="display:flex; align-items:flex-start;">
                                        <div>
                                            <img src="{{asset('/img/alderlogo/manufactured_address.png')}}" style="width:32px; height:18px;">
                                        </div>
                                        <div style="font-size:8px; line-height:1.1; margin-left:5px;">
                                            <span style="font-weight:bold;">ADLER HEALTHCARE PVT. LTD</span><br/>
                                            Plot No-A1 MIDC, Sadavali, Tal- Sangmeshwar<br/>
                                            Dist -Ratnagiri, Maharashtra-415804<br/>
                                            MADE IN INDIA
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Product Image -->
                                <div style="float:right; width:100px; text-align:center; margin-right:20px; margin-top:-20px;">
                                    @if($batchcard_data->label_image)
                                        <?php $img_path = '/img/'.$batchcard_data->label_image; ?>
                                        @if(file_exists(public_path($img_path))) 
                                            <img src="{{asset($img_path)}}" style="width:80px; height:auto;">
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                        @endfor
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= url('') ?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/lib/print/jQuery.print.min.js"></script>

<script>
function Labelprint() {
    var mywindow = window.open();
    var content = document.getElementById("label-div").innerHTML;
    var realContent = document.body.innerHTML;
    
    mywindow.document.write('<html><head><title>Print Labels</title>');
    
    // Add print-specific styles
    mywindow.document.write(`
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                color: black;
                background: white;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .page-container {
                width: 21.1cm !important;
                height: 29.3cm !important;
                margin: 0 !important;
                padding: 0 !important;
                page-break-after: always !important;
            }
            
            .label-container {
                width: 10.4cm !important;
                height: 7.3cm !important;
                float: left !important;
                margin: 0 !important;
                padding: 5px !important;
                border: none !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            
            .row1 {
                display: flex !important;
                justify-content: space-between !important;
                align-items: flex-start !important;
                height: 120px !important;
            }
            
            .subcolumn1 {
                width: 60px !important;
                float: left !important;
            }
            
            .subcolumn2 {
                width: 300px !important;
                float: left !important;
                padding-left: 5px !important;
            }
            
            /* Vertical text styles */
            [style*="writing-mode"] {
                writing-mode: vertical-lr !important;
                transform: rotate(180deg) !important;
                font-size: 7px !important;
            }
            
            /* MRP rotation */
            [style*="rotate(-90deg)"] {
                transform: rotate(-90deg) !important;
                white-space: nowrap !important;
            }
            
            /* Barcode styling */
            img[src*="data:image/png;base64"] {
                max-width: 140px !important;
                height: 30px !important;
            }
            
            /* Address section */
            .address {
                font-size: 7px !important;
                line-height: 1.1 !important;
            }
            
            /* Logo and icons */
            .logo img {
                width: 45px !important;
            }
            
            .icons img {
                width: 20px !important;
                display: block !important;
                margin: 2px auto !important;
            }
            
            /* Print-specific fixes */
            @media print {
                .page-container {
                    margin: 0 !important;
                    padding: 0 !important;
                }
                
                .label-container {
                    margin: 0.08cm !important;
                    border: none !important;
                }
            }
        </style>
    `);
    
    mywindow.document.write('</head><body>');
    mywindow.document.write(content);
    mywindow.document.write('</body></html>');
    
    mywindow.document.close();
    mywindow.focus();
    
    // Wait for images to load before printing
    mywindow.onload = function() {
        mywindow.print();
        mywindow.close();
    };
    
    // Fallback in case onload doesn't fire
    setTimeout(function() {
        mywindow.print();
        mywindow.close();
    }, 1000);
    
    history.back();
}

$("#print").on("click", function () {
    var batch_id = $('#batch_id').val();
    var no_of_labels = $('#no_of_labels').val();
    var manufacturing_date = $('#manufacturing_date').val();
    var product_id = $('#product_id').val();
    var expiry_date = $('#expiry_date').val();
    var label_name = $('#label_name').val();
    
    $.ajax({
        type: 'POST',
        url: "{{ url('label/insert-printing-data') }}",
        data: { 
            "_token": "{{ csrf_token() }}",
            batch_id: batch_id, 
            no_of_labels: no_of_labels, 
            manufacturing_date: manufacturing_date, 
            product_id: product_id,
            expiry_date: expiry_date,
            label_name: label_name
        },
        success: function(data) {
            if(data == 1) {
                Labelprint();
            } else {
                alert('Label printing failed!!!');
            }
        }
    });
});
</script>
@stop