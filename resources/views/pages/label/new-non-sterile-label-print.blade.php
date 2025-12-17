@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">
            <div class="az-content-title">
                <button style="float: right; margin-left: 9px;font-size: 14px;width:90px;" class="badge badge-pill badge-info " id="print">
                    <i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print
                </button>
            </div>
            <?php
            $label_per_page = 12;
            $page_count = (int)($no_of_label / 12);
            $remaining = $no_of_label % 12;
            ?>
            <input type="hidden" name="batch_id" id="batch_id" value="{{$batchcard_data->batch_id}}">
            <input type="hidden" name="no_of_labels" id="no_of_labels" value="{{$no_of_label}}">
            <input type="hidden" name="manufacturing_date" id="manufacturing_date" value="{{$manufacturing_date}}">
            <input type="hidden" name="product_id" id="product_id" value="{{$batchcard_data->product_id}}">
            <input type="hidden" name="label_name" id="label_name" value="Non Sterilization Label">

            <div class="label-div" id="label-div" style="margin-bottom:5px; padding:0px;margin-left:50%" >
                @for ($i = 1; $i<= $page_count; $i++)
                 <div style="margin-top:10px;margin-left:-25px;">
                    @for ($j = 1; $j<= $label_per_page; $j++)
                     <div class=" column " style="float:left;width:45%;height:25%;margin-bottom:-76px;margin-top:-2px;margin-left:5.5px;">
                        <div class="subcolumn1" style="float:left;width:95%; height:content-fit;">
                            <div class="sub1" style="float:left;width:content-fit;font-weight:bold;">
                            @if ($j%2==0)
                                <img src="{{asset('/img/alderlogo/IFU.png')}}"style="float:left;height:60px;margin-right:-100px; margin-left:22px;">
                            @else
                                <img src="{{asset('/img/alderlogo/IFU.png')}}"style="float:left;height:60px;margin-right:-100px; margin-left:10px;">
                            @endif                     
                            </div>

                            <div class="sub2" style="float:left;width:8%;padding:1px;height:90px">
                            @if ($j%2==0)
                                <span class="smalltext" style="font-size:5px;font-weight:500;text-align: center;writing-mode: vertical-lr;transform: rotate(180deg);margin-top:-3px;margin-left:43px;">
                                    LBL/F-19_REV00_{{strtoupper(date( 'dMY' , strtotime('30-12-2023')) )}}
                                </span>
                            @else
                            <span class="smalltext" style="font-size:5px;font-weight:500;text-align: center;writing-mode: vertical-lr;transform: rotate(180deg);margin-top:-3px;margin-left:35px;">
                                    LBL/F-19_REV00_{{strtoupper(date( 'dMY' , strtotime('30-12-2023')) )}}
                                </span>
                            @endif 

                            </div>
                            <div class="sub3" style="float:left;width:45%;font-size:6px;line-height:1; margin-top:-5px;margin-left:25px;">
                                <span style="">{{$batchcard_data->groups}}</span><br 
                                <span style="font-weight:bold;font-size:8px;margin-top:-11px;">{{$batchcard_data->brand}}</span><br />
                                <span style="font-size:8px;">{{$batchcard_data->family}}</span><br />
                                <span style="font-size:8px;">{{$batchcard_data->snn_description}}</span><br />
                                <span style="font-size:8px;">{{$batchcard_data->ad_sp1}}</span>&nbsp;&nbsp;
                                <span style="font-size:8px;">{{$batchcard_data->ad_sp2}}</span><br />
                                <span style="font-weight:bold;font-size:8px; margin-top:20px; margin-left:5px;">Ref: {{$batchcard_data->sku_code}}</span><strong style="margin-left: 25px; margin-top:20px;">Qty :</strong>{{$per_pack_quantity}} Nos
                                <br />
                                <div style="padding-top:1px;width: fit-content;">
                                     <img src="data:image/png;base64,{{ base64_encode($sku_code_barcode)}}" style="width:70px;height:20px; margin-top:1px;"> 
                                    <div style="text-align:center;font-size:6.2px;font-weight:550;margin-top:0px;margin-left:-15px;">
                                    {{$batchcard_data->sku_code}}
                                </div>
                            </div>
                        </div>
                        <div class="sub4" style="float:left;width:content-fit;height:70px;padding:1px;font-size:9px;text-align:center;">
                            <img src="{{asset('/img/alderlogo/manufacturing.png')}}" style="width:15px;">&nbsp;
                            <span class="smalltext1">{{$manufacturing_date}}</span><br />
                            <img src="{{asset('/img/alderlogo/lot.png')}}" style="width:25px;">&nbsp;
                            <span class="smalltext1">{{$batchcard_data->batch_no}}</span><br><br>
                            @if($batchcard_data->label_image)
                                <?php $img_path = '/img/'.$batchcard_data->label_image; ?>
                                @if(file_exists(public_path($img_path)))
                                    <img src="{{asset($img_path)}}" style="width: 75px;">&nbsp;
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="subcolumn2" style="float:left;margin-top:-20px; margin-left: 34px;">
                        <div style="float:left;width:40%;">
                            <div class="barcode2" style="font-size:5.6px">
                                <img src="data:image/png;base64,{{ base64_encode($label_batch_combo_barcode)}}" style="width:70px;height:20px;margin-left:20px;margin-top:4px">
                                <br />
                               
                        </div>
                        <div style="text-align:center;margin-top:2px;margin-left:18px;font-size:6.2px;font-weight:550;width:10%;">
                                {{$label_batch_combo}}
                            </div>
                    </div>
                    <div class="barcode" style="width:25%;float:left;font-size:5.6px;margin-left:50px;margin-top:30px">
                        <img src="data:image/png;base64,{{ base64_encode($manf_date_combo_barcode)}}" style="width:90%;margin-top:-25px;margin-left:-45px;">
                        <br />
                        <div style="text-align:center;margin-top:-3px;margin-left:-60px;font-size:6px;font-weight:550;width:95%">
                            {{$manf_date_combo}}
                        </div>
                    </div>
                    <div style="float:left;width:15%; margin-left:-140px;">
                        <div class="logo" style="text-align:center;">
                            <img src="{{asset('/img/alderlogo/instruction_use.png')}}" style="width:40%;float:left;padding:0px;margin-left:140px">
                            <img src="{{asset('/img/alderlogo/dot_not_reuse.png')}}" style="width:40%;float:left;padding:0px;margin-top:-19px;margin-left:170px">
                        </div>
                    </div>
                </div>
                <div class="subcolumn3" style="float:left;margin-top:2px;">
                    <div style="float:left;width:83%;">
                        <div class="barcode3" style="float:left;margin-top:4px;">
                            {{--<div class="barcode" style="width:30%;float:left;font-size:5.6px">
                                <!-- <img src="data:image/png;base64,{{ base64_encode($manf_date_combo_barcode)}}" style="width:100%;"> -->
                                <br />
                                <div style="text-align:center;margin-top:-25px;font-size:6.2px;font-weight:550">{{$manf_date_combo}}</div>
                            </div>--}}

                            <div style="margin-left:30px;float:left;margin-top:17px;">
                                <img src="{{asset('/img/alderlogo/manufactured_address.png')}}" style="float:left;width:20px;height:15px; margin-left:15px;margin-top:-15px;">
                                <span style="font-size:5.5px; padding-left:2px;font-weight:bold;display:block;margin-top:-22px;margin-left:40px">{{$batchcard_data->drug_license_number}}</span>
                                <span class=" cls" style="padding-left:2px;font-size:6px;font-weight:bold;display:block;margin-left: 39px;margin-top: 1px;" ;>ADLER HEALTHCARE PVT. LTD</span>
                                <div class="" style="display:block;margin-left: 39px;margin-top:0px;">
                                    <span style="font-weight:550;font-size:4.5px;display:block;padding-left:2px;">
                                    Plot No-A1 MIDC, Sadavali, Tal- Sangmeshwar</span>
                                    <span style="font-weight:550;font-size:4.5px;display:block;padding-left:2px">Dist -Ratnagiri, Maharashtra-415804 MADE IN INDIA</span>                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="float:left;width:15%; margin-left:1px;">
                        <div class="logo" style="    margin-top: -3px;">
                            <img src="{{asset('/img/alderlogo/non_sterile.png')}}" style="width:50%;margin-bottom:10px;margin-left:2px;margin-top:-75px;"> 
                        </div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
    <div style="break-after:page">
    </div>
    @endfor
    <style>
        <?php for ($i = 1; $i <= $label_per_page; $i++) { ?>.label-content<?php echo $i; ?> {
            display: none;
        }

        <?php } ?>
    </style>

    @if($remaining!=0)
        <style>
            <?php for ($i = 1; $i <= $remaining; $i++) { ?>.label-content<?php echo $i; ?> {
                display: block;
            }

            <?php } ?>
        </style>
        <div style="margin-top:0mm;">
            @for ($j=1;$j<=$label_per_page;$j++) 
            <div class=" column label-content{{$j}}" style="float:left;width:48%;height:25%;margin-bottom:0px;margin-left:5cm;margin-top:5px;">
                <div class="subcolumn1" style="float:left;width:95%; height:content-fit;">
                    <div class="sub1" style="float:left;width:content-fit;font-weight:bold;">
                        <img src="{{asset('/img/alderlogo/IFU.png')}}" style="float:left; height:105px;width:15px;margin-left:50px">
                    </div>
                    <div class="sub2" style="float:left;width:8%;padding:1px;height:90px">
                        <span class="smalltext" style="font-size:5px;font-weight:500;text-align: center;writing-mode: vertical-lr;transform: rotate(180deg);margin-top:18px;margin-left:3.5px">
                        LBL/F-19_REV00_{{strtoupper(date( 'dMY' , strtotime('30-12-2023')) )}}
                        </span>
                    </div>
                    <div class="sub3" style="float:left;width:45%;font-size:9px;line-height:1.5; margin-left:-20px;">
                        <span style="">{{$batchcard_data->groups}}</span><br />
                        <span style="font-weight:bold;font-size:9px">{{$batchcard_data->brand}}</span><br />
                        <span style="">{{$batchcard_data->family}}</span><br />
                        <span style="">{{$batchcard_data->snn_description}}</span><br />
                        <span style="">{{$batchcard_data->ad_sp1}}</span>&nbsp;&nbsp;<span style="">{{$batchcard_data->ad_sp2}}</span><br />
                        <span style="font-weight:bold;font-size:9px;">Ref: {{$batchcard_data->sku_code}}</span><strong style="margin-left: 20px;">Qty :</strong>{{$per_pack_quantity}} Nos
                        <br />
                        <div style="padding-top:1px;width: fit-content;">
                            <img src="data:image/png;base64,{{ base64_encode($sku_code_barcode)}}" style="width:150px;height:20px">
                            <div style="text-align:center;font-size:6.2px;font-weight:550;">
                                {{$batchcard_data->sku_code}}
                            </div>
                        </div>
                    </div>
                    <div class="sub4" style="float:left;width:content-fit;height:70px;padding:1px;font-size:9px;text-align:center;">
                        <img src="{{asset('/img/alderlogo/manufacturing.png')}}" style="width:15px;">&nbsp;
                        <span class="smalltext1">{{$manufacturing_date}}</span><br />
                        <img src="{{asset('/img/alderlogo/lot.png')}}" style="width:25px;">&nbsp;
                        <span class="smalltext1">{{$batchcard_data->batch_no}}</span><br><br>
                        @if($batchcard_data->label_image)
                            <?php $img_path = '/img/'.$batchcard_data->label_image; ?>
                            @if(file_exists(public_path($img_path)))
                                <img src="{{asset($img_path)}}" style="width:75px;">&nbsp;
                            @endif
                        @endif
                    </div>
                </div>
                <div class="subcolumn2" style="float:left;margin-top:1px; margin-left: 35px;">
                        <div style="float:left;width:40%;">
                            <div class="barcode2" style="font-size:5.6px">
                                <img src="data:image/png;base64,{{ base64_encode($label_batch_combo_barcode)}}" style="width:95%;height:22px">
                                <br />
                                <div style="text-align:center;margin-top:1px;font-size:6.2px;font-weight:550">
                                {{$label_batch_combo}}
                            </div>
                        </div>
                    </div>
                    <div class="barcode" style="width:30%;float:left;font-size:5.6px">
                        <img src="data:image/png;base64,{{ base64_encode($manf_date_combo_barcode)}}" style="width:95%;">
                        <br />
                        <div style="text-align:center;margin-top:1px;font-size:6.2px;font-weight:550">
                            {{$manf_date_combo}}
                        </div>
                    </div>
                    <div style="float:left;width:15%; margin-left:1px;">
                        <div class="logo" style="text-align:center;">
                            <!-- <img src="{{asset('/img/alderlogo/instruction_use.png')}}" style="width:40%;float:left;padding:0px;margin-left: 3px;"> -->
                            <img src="{{asset('/img/alderlogo/dot_not_reuse.png')}}" style="width:36%;float:left;padding:0px;margin-left: 3px;">
                        </div>
                    </div>
                </div>
                <div class="subcolumn3" style="float:left;margin-top:2px;">
                    <div style="float:left;width:83%;">
                        <div class="barcode3" style="float:left;margin-top:4px;">
                            {{--<div class="barcode" style="width:30%;float:left;font-size:5.6px">
                                <img src="data:image/png;base64,{{ base64_encode($manf_date_combo_barcode)}}" style="width:100%;">
                                <br />
                                <div style="text-align:center;margin-top:1px;font-size:6.2px;font-weight:550">{{$manf_date_combo}}</div>
                            </div>--}}
                            <div style="margin-left:2px;float:left;">
                                <img src="{{asset('/img/alderlogo/manufactured_address.png')}}" style="float:left;width:25px;height:16px; margin-left:0.5px;margin-top: -49px">
                                <span style="font-size:6px; padding-left:2px;font-weight:bold;display:block;margin-top:-4px">{{$batchcard_data->drug_license_number}}</span>
                                <span class=" cls" style="padding-left:2px;font-size:7.5px;font-weight:bold;display:block;margin-left: 35px;margin-top: 2px;" ;>ADLER HEALTHCARE PVT. LTD</span>
                                <div class="" style="display:block;margin-left: 35px;">
                                    <span style="font-weight:550;font-size:6.1px;display:block;padding-left:2px">
                                    Plot No-A1 MIDC, Sadavali, Tal- Sangmeshwar</span>
                                    <span style="font-weight:550;font-size:6.3px;display:block;padding-left:2px">Dist -Ratnagiri, Maharashtra-415804 MADE IN INDIA</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="float:left;width:15%; margin-left:1px;">
                        <div class="logo" style="    margin-top: 5px;">
                            <img src="{{asset('/img/alderlogo/non_sterile.png')}}" style="width:60%;margin-bottom:2px;">
                        </div>
                    </div>
                </div>
            </div>
        @endfor 
    </div>
    @endif
</div>
</div>
</div>
</div>
<script>
    // JavaScript function to handle printing
    document.getElementById('printButton').addEventListener('click', function() {
        window.print();
    });
</script>
<script src="<?= url('') ?>/js/azia.js"></script>

<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>

<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>

<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/lib/print/jQuery.print.min.js"></script>
<script>
//$("#print").on("click", function () {
function Labelprint(){

    var mywindow = window.open();
      var content = document.getElementById("label-div").innerHTML;
      var realContent = document.body.innerHTML;
      mywindow.document.write('<html><head><title></title>');
      mywindow.document.write( "<link rel='stylesheet' href='/public/css/print.css' type='text/css' media='print'/>" );
      mywindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="print"/>');
      mywindow.document.write('</head><body>');

      mywindow.document.write(content);
      mywindow.document.write('</body></html>')
      mywindow.document.close(); // necessary for IE >= 10
      //mywindow.focus(); // necessary for IE >= 10*/
      mywindow.print();
      document.body.innerHTML = realContent;
      mywindow.close();
      history.back();
      //return false;
}
$("#print").on("click", function () {
    var batch_id = $('#batch_id').val();
    var no_of_labels = $('#no_of_labels').val();
    var manufacturing_date = $('#manufacturing_date').val();
    var product_id = $('#product_id').val();
    var expiry_date = $('#expiry_date').val();
    var label_name = $('#label_name').val();
    $.ajax({
           type:'POST',
           url:"{{ url('label/insert-printing-data') }}",
           data:{ "_token": "{{ csrf_token() }}",batch_id:batch_id, no_of_labels:no_of_labels, manufacturing_date:manufacturing_date, product_id:product_id,expiry_date:expiry_date,label_name:label_name},
           success:function(data)
           {
            if(data==1)
            Labelprint();
            else
            alert('Label printing failed!!!')
           }
    });
});
function Labelprint() {
    var printWindow = window.open('', '', 'height=800,width=600');
    var content = document.getElementById("label-div").innerHTML;
    
    printWindow.document.write('<html><head><title>Print Labels</title>');
    printWindow.document.write("<style>@media print { .page-container { width: 29.7cm; height: 21cm; margin: 0; padding: 0; overflow: visible; } .label-container { width: 7cm; height: 2.5cm; margin-left: 0.1cm; margin-bottom: 0.1cm; } }</style>");
    printWindow.document.write('</head><body >');
    printWindow.document.write(content);
    printWindow.document.write('</body></html>');
    printWindow.document.close(); // necessary for IE >= 10
    printWindow.focus(); // necessary for IE >= 10
    printWindow.print();
}

</script>
@stop