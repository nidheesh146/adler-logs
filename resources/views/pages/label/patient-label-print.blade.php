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
                @for ($i = 0; $i< $page_count; $i++)
                <!-- <div class="page-container" style="margin-top:6mm;margin-bottom:0cm;width:21.1cm;height:29.3cm;"> -->
                <div class="page-container" style="margin-top:6mm;margin-bottom:0cm;width:21.1cm;height:29.3cm;margin-left:2px">
                    @for ($j=0;$j<$label_per_page;$j++)
                    <div class="label-container" style="float:left;width:6.9cm;height:2.45cm;margin-left:5px;" >
                        <div class="sub-columns" style="">
                            <div class="" style="width:7px;text-align: justify;">
                                <span class="smalltext" style="font-size:6px;float: left;writing-mode: vertical-lr;transform: rotate(180deg); margin-top:20%;margin-right:1px;">
                                LBL/F-{{$batchcard_data->label_format_number}}_REV00_{{date( 'd M y' , strtotime('14-12-2021') )}}
                                </span>
                            </div>
                            <div class="col-md-9 sub-column" style="width:64%;float: left; margin-left:2px;line-height:65%;">
                                <span style="font-size:9px;font-weight:bolder;">Ref: {{$batchcard_data->sku_code}}</span><br/>
                                @if($batchcard_data->discription!="")
                                <span class="smalltext" style=" font-size:7.5px;">{{$batchcard_data->discription}}</span><br/>
                                @endif
                                <img src="{{asset('/img/alderlogo/sterile_eo.png')}}" style="width:47px;height:13px;">
                                <span class="smalltext" style=" font-size:8px;">{{$lot_no}}</span>
                                <img src="{{asset('/img/alderlogo/lot.png')}}" style="width:20px;">
                                <span class="smalltext" style=" font-size:8px;">{{$batchcard_data->batch_no}}</span></br>
                                 <!-- <span style="font-size:8px;font-weight:bold;">STERILIZATION</span> -->
                                
                                 <div style=" height: 8px;width:90%; border-bottom: 1px solid black; text-align: center">
                                    <span style=" font-weight:bold;position: absolute;font-size: 7px; background-color: #f4f5f8; padding: 0 3px;margin-top: 4.0px;position: absolute;margin-left: -28px">
                                        STERILIZATION<!--Padding is optional-->
                                    </span>
                                </div>
                                <div class="" style="display:block;margin-bottom:3px;margin-top:7px;">
                                    <img src="{{asset('/img/alderlogo/expiry_date.png')}}" style="width:8px; height:10px;">
                                    <span class="" style=" font-size:7.5px;">{{$sterilization_expiry_date}}</span>
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
                @if($remaining!=0)
                <div class="page-container" style="margin-top:6mm;margin-bottom:0cm;width:21.1cm;height:29.3cm;margin-left:2px">
                    @for ($j=0;$j<$remaining;$j++)
                    <div class="label-container" style="float:left;width:6.9cm;height:2.45cm;margin-left:5px;" >
                        <div class="sub-columns" style="">
                            <div class="" style="width:7px;text-align: justify;">
                                <span class="smalltext" style="font-size:6px;float: left;writing-mode: vertical-lr;transform: rotate(180deg); margin-top:20%;margin-right:1px;">
                                LBL/F-{{$batchcard_data->label_format_number}}_REV00_{{date( 'd M y' , strtotime('14-12-2021') )}}
                                </span>
                            </div>
                            <div class="col-md-9 sub-column" style="width:64%;float: left; margin-left:2px;line-height:65%;">
                                <span style="font-size:9px;font-weight:bolder;">Ref: {{$batchcard_data->sku_code}}</span><br/>
                                @if($batchcard_data->discription!="")
                                <span class="smalltext" style=" font-size:7.5px;">{{$batchcard_data->discription}}</span><br/>
                                @endif
                                <img src="{{asset('/img/alderlogo/sterile_eo.png')}}" style="width:47px;height:13px;">
                                <span class="smalltext" style=" font-size:8px;">{{$lot_no}}</span>
                                <img src="{{asset('/img/alderlogo/lot.png')}}" style="width:20px;">
                                <span class="smalltext" style=" font-size:8px;">{{$batchcard_data->batch_no}}</span></br>
                                 <!-- <span style="font-size:8px;font-weight:bold;">STERILIZATION</span> -->
                                
                                 <div style=" height: 8px;width:90%; border-bottom: 1px solid black; text-align: center">
                                    <span style=" font-weight:bold;position: absolute;font-size: 7px; background-color: #f4f5f8; padding: 0 3px;margin-top: 4.0px;position: absolute;margin-left: -28px">
                                        STERILIZATION<!--Padding is optional-->
                                    </span>
                                </div>
                                <div class="" style="display:block;margin-bottom:3px;margin-top:7px;">
                                    <img src="{{asset('/img/alderlogo/expiry_date.png')}}" style="width:8px; height:10px;">
                                    <span class="" style=" font-size:7.5px;">{{$sterilization_expiry_date}}</span>
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
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
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
Labelprint();
      });
</script>
@stop

    
