@extends('layouts.default')
@section('content')
<style>
    .smalltext{
        font-size:8px;
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
</style>
<div class="az-content az-content-dashboard">
  <br>
    <div class="container">
	    <div class="az-content-body"  style="color:black;">     
            <div class="az-content-title">
                <button  style="float: right; margin-left: 9px;font-size: 14px;width:90px;" class="badge badge-pill badge-info " id="print">
                <i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print
            </button>
            </div>
            <div class="label-div" id="label-div">
                <?php
                    $label_per_page =36;
                    $page_count = (int)($no_of_label/36);
                    $remaining = $no_of_label%36;
                 ?>
                 <input type="hidden" name="batch_id" id="batch_id" value="{{$batchcard_data->batch_id}}">
                <input type="hidden" name="no_of_labels" id="no_of_labels" value="{{$no_of_label}}">
                <input type="hidden" name="manufacturing_date" id="manufacturing_date" value="{{$manufacture_date}}">
                <input type="hidden" name="product_id" id="product_id" value="{{$batchcard_data->product_id}}">
                <input type="hidden" name="expiry_date" id="expiry_date" value="{{date('Y-m-d', strtotime($sterilization_expiry_date))}}">
                <input type="hidden" name="label_name" id="label_name" value="Patient Label">

                @for ($i = 0; $i< $page_count; $i++)
                <!-- <div class="page-container" style="margin-top:2mm;margin-bottom:0cm;width:21.1cm;height:29.3cm;"> -->
                <div class="page-container" style="margin-top:6mm;margin-bottom:0cm;width:21.1cm;height:29.3cm;margin-left:2px;">
                    @for ($j=0;$j<$label_per_page;$j++)
                    <div class="label-container" style="float:left;width:6.7cm;height:2.29cm;margin-left:11px;margin-bottom:9.3px;margin-top:-1.85px" >
                     
                    <div class="sub-columns" style="">
                            <div class="" style="width:7px;text-align: justify;">
                                <span class="smalltext" style="font-size:5px;float: left;writing-mode: vertical-lr;transform: rotate(180deg); margin-top:11%;margin-right:1px;">
                                LBL/F-10_REV00_{{strtoupper(date( 'dMY' , strtotime('14-12-2021') ))}}
                                </span>
                            </div>
                            <div class="col-md-9 sub-column" style="width:64%;float: left; margin-left:2px;line-height:60%;">
                                <span style="font-size:7px;font-weight:bolder;">Ref: {{$batchcard_data->sku_code}}</span><br/>
                                @if($batchcard_data->discription!="")
                                <span class="smalltext" style=" font-size:5.4px;">{{$batchcard_data->discription}}</span><br/>
                                @endif
                                <div class="" style="display:block;margin-top:1.5px;">
                                    <!-- <img src="{{asset('/img/alderlogo/sterile_eo.png')}}" style="width:47px;height:13px;"> -->
                                    <div style="display:block;float:left;font-family: Arial, sans-serif;font-size:6px;font-weight:400;">
                                        <span style="padding:1.5px; border-style: solid;border-width: thin;border-color:#101010;">STERILE</span>
                                        <span style="border-top: solid 0.1px #101010;border-bottom: solid 0.1px black;border-right: solid 0.1px black;border-width: thin;padding:1.5px; padding-left:7px;margin-left:-2px;">
                                        @if($batchcard_data->sterilization_type=='R')
                                            R
                                        @elseif($batchcard_data->sterilization_type=='EO')
                                            EO
                                        @else
                                            &nbsp;
                                        @endif
                                        </span>
                                    </div>
                                    <!-- <img src="{{asset('/img/alderlogo/sterile_r1.png')}}" style="width:47px;height:11px;"> -->
                                    <span class="smalltext" style=" font-size:8px;float:left;">&nbsp;{{$lot_no}}&nbsp;</span>
                                    <!-- <img src="{{asset('/img/alderlogo/lot.png')}}" style="width:20px;float:left;"> -->
                                    <span style="float:left;height:10px;padding-left:1.2px;padding-right:1.2px; border-style: solid;border-width: thin;border-color:#101010;font-family: Arial, sans-serif;font-size:6px;font-weight:400;margin-top:-1px">LOT</span>
                                    <span class="smalltext" style=" font-size:8px;float:left;">&nbsp;{{$batchcard_data->batch_no}}&nbsp;</span>      
                                </div>
                                </br> 
                                 <div style="display:block;height: 8px;width:90%; border-bottom: 1px solid black; ">
                                    <span style=" font-weight:bold;position: absolute;font-size: 6px; background-color: #f4f5f8; padding: 0 3px;margin-top: 4.0px;position: absolute;margin-left:-28px">
                                        STERILIZATION<!--Padding is optional-->
                                    </span>
                                </div>
                                <div class="" style="display:block;margin-bottom:3px;margin-top:5px;">
                                    <img src="{{asset('/img/alderlogo/expiry_date.png')}}" style="width:8px; height:10px;">
                                    <span class="" style=" font-size:7.5px;">{{date('Y-m-d', strtotime($sterilization_expiry_date))}}</span>
                                    <img src="{{asset('/img/alderlogo/manufacturing.png')}}" style="width:14px;height:9px;">
                                    <span class="s" style=" font-size:7.5px;">{{$manufacture_date}}</span>
                                </div>
                            </div>
                            <div class="sub-column" style="width:30%;float: left;margin-top: 40px;margin-left:2px;" >
                                <!-- <div class="width:30%;float: left;margin-top:60px;padding:2px"> -->
                                    <img src="{{asset('/img/alderlogo/manufactured_address.png')}}" style="width: 22px;float:left;">
                                    <img src="{{asset('/img/alderlogo/alder_logo.png')}}"  style="width:22px;float:left;">
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                    @endfor
                    <div style="break-after:page"></div>
                </div>
                @endfor
                <style>
                        <?php for($i=1;$i<=$label_per_page;$i++) { ?>
                            .label-content<?php echo $i; ?>{
                                display:none;
                            }
                        <?php }?>
                </style>
                @if($remaining!=0)
                    <style>
                        <?php for($i=1;$i<=$remaining;$i++) { ?>
                            .label-content<?php echo $i; ?>{
                                display:block;
                            }
                        <?php }?>
                    </style>
                     <div class="page-container" style="margin-top:6mm;margin-bottom:0cm;width:21.1cm;height:29.3cm;margin-left:2px;">
                     @for ($j=1;$j<=$label_per_page;$j++)
                      <div class="label-container" style="float:left;width:6.7cm;height:2.29cm;margin-left:11px;margin-bottom:9.3px;margin-top:-1.85px" >
                      <div class="label-content{{$j}}" style="">
                        <!-- <div class="sub-columns" style=""> -->
                            <div class="" style="width:7px;text-align: justify;">
                                <span class="smalltext" style="font-size:5px;float: left;writing-mode: vertical-lr;transform: rotate(180deg); margin-top:20%;margin-right:1px;">
                                LBL/F-10_REV00_{{date( 'd M y' , strtotime('14-12-2021') )}}
                                </span>
                    
                
                        <!-- <div class="sub-columns" style=""> -->
                            
                    
                                </span>
                            </div>
                            <div class="col-md-9 sub-column" style="width:64%;float: left; margin-left:2px;line-height:65%;">
                                <span style="font-size:7px;font-weight:bolder;">Ref: {{$batchcard_data->sku_code}}</span><br/>
                                @if($batchcard_data->discription!="")
                                <span class="smalltext" style=" font-size:5px;">{{$batchcard_data->discription}}</span><br/>
                                @endif
                                <div class="" style="display:block;margin-top:1.5px;">
                                    <!-- <img src="{{asset('/img/alderlogo/sterile_eo.png')}}" style="width:47px;height:13px;"> -->
                                    <div style="display:block;float:left;font-family: Arial, sans-serif;font-size:6px;font-weight:400;">
                                        <span style="padding:1.5px; border-style: solid;border-width: thin;border-color:#101010;">STERILE</span>
                                        <span style="border-top: solid 0.1px #101010;border-bottom: solid 0.1px black;border-right: solid 0.1px black;border-width: thin;padding:1.5px; padding-left:4px;margin-left:-2px;">
                                        @if($batchcard_data->sterilization_type=='R')
                                            R
                                        @elseif($batchcard_data->sterilization_type=='EO')
                                            EO
                                        @else
                                            &nbsp;
                                        @endif
                                        </span>
                                    </div>
                                    <!-- <img src="{{asset('/img/alderlogo/sterile_r1.png')}}" style="width:47px;height:11px;"> -->
                                    <span class="smalltext" style=" font-size:8px;float:left;">&nbsp;{{$lot_no}}&nbsp;</span>
                                    <!-- <img src="{{asset('/img/alderlogo/lot.png')}}" style="width:20px;float:left;"> -->
                                    <span style="float:left;height:10px;padding-left:1.2px;padding-right:1.2px; border-style: solid;border-width: thin;border-color:#101010;font-family: Arial, sans-serif;font-size:6px;font-weight:400;margin-top:-1px">LOT</span>
                                    <span class="smalltext" style=" font-size:8px;float:left;">&nbsp;{{$batchcard_data->batch_no}}&nbsp;</span>      
                                </div>
                                </br> 
                                 <div style="display:block;height: 8px;width:90%; border-bottom: 1px solid black; ">
                                    <span style=" font-weight:bold;position: absolute;font-size: 7px; background-color: #f4f5f8; padding: 0 3px;margin-top: 4.0px;position: absolute;margin-left:-28px">
                                        STERILIZATION<!--Padding is optional-->
                            </span>
                                </div>
                                <div class="" style="display:block;margin-bottom:3px;margin-top:7px;">
                                    <img src="{{asset('/img/alderlogo/expiry_date.png')}}" style="width:8px; height:10px;">
                                    <span class="" style=" font-size:7.5px;">{{date('Y-m-d', strtotime($sterilization_expiry_date))}}</span>
                                    <img src="{{asset('/img/alderlogo/manufacturing.png')}}" style="width:14px;height:9px;">
                                    <span class="s" style=" font-size:7.5px;">{{$manufacture_date}}</span>
                                </div>
                            </div>
                            <div class="sub-column" style="width:30%;float: left;margin-top: 40px;margin-left:2px;" >
                                <!-- <div class="width:30%;float: left;margin-top:60px;padding:2px"> -->
                                    <img src="{{asset('/img/alderlogo/manufactured_address.png')}}" style="width: 22px;float:left;">
                                    <img src="{{asset('/img/alderlogo/alder_logo.png')}}"  style="width:22px;float:left;">
                                <!-- </div> -->
                            </div>
                        <!-- </div> -->
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

    
