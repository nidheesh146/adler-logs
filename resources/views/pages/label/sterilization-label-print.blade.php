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
	    <div class="az-content-body">     
            <div class="az-content-title">
                <button  style="float: right; margin-left: 9px;font-size: 14px;width:90px;" class="badge badge-pill badge-info " id="print">
                <i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print
            </button>
            </div>
            <div class="row label-div columns" id="label-div">
                <?php
                    $label_per_page =8;
                    $page_count = (int)($no_of_label/8);
                    $remaining = $no_of_label%8;
                 ?>
                <div class="label-div" id="label-div">
                    @for ($i = 0; $i< $page_count; $i++)
                    <div class="page-container" style="margin-top:0.9cm;margin-bottom:0.45cm;width:21cm;height:29.7cm;">
                        @for ($j=0;$j<$label_per_page;$j++)
                        <div class="label-container" style="float:left;width:10.5cm;height:7cm;padding:2px;margin-bottom:0.08cm;">
                            <div class="row1" style="font-size:12px;height:fit-content;">    
                                <div class="subcolumn1" style="float:left; width:75px">
                                    <div class="logo" style="text-align:center;">
                                        <img src="{{asset('/img/logo.png')}}"  style="width:50%;">
                                    </div>
                                    <div class="icons" style="margin-top:8px;margin-left:2px; text-align:center;">
                                        @if($batchcard_data->is_donot_reuse_logo==1)
                                        <img src="{{asset('/img/alderlogo/dot_not_reuse.png')}}"  style="width:25%;">
                                        @endif
                                        @if($batchcard_data->is_read_instruction_logo==1)
                                        <img src="{{asset('/img/alderlogo/instruction_use.png')}}"  style="width:25%;">
                                        @endif
                                        <!-- @if($batchcard_data->is_temperature_logo==1)
                                        <img src="{{asset('/img/alderlogo/instruction_use.png')}}"  style="width:40%;float:left;">
                                        @endif -->
                                    </div>
                                </div>
                                <div class="subcolumn2" style="float:left;width:300px">
                                    <div class="subdiv">
                                        <div class="ss" style="float:left;width:150px">
                                            <span style="text-align:left;"><strong>Ref: {{$batchcard_data->sku_code}}</strong></span></br/>
                                            <img src="{{asset('/img/alderlogo/lot.png')}}" style="width:25px;">
                                            {{$batchcard_data->batch_no}}
                                            <br/>
                                            <img src="{{asset('/img/alderlogo/sterile_r.png')}}" alt="image" style="width:47px;">
                                            {{$lot_no}}
                                            <br/>
                                            <img src="{{asset('/img/alderlogo/manufacturing.png')}}" style="width:15px;">&nbsp;
                                            {{$manufacture_date}}
                                        </div>
                                        <div class="ss" style="float:left;width:150px;text-align:center;">
                                            <span style="text-align:right;"><strong>Qty: </strong>{{$batchcard_data->quantity_per_pack}}Nos</span>
                                            <div style=" height: 6px; border-bottom: 1px solid black; text-align: center">
                                                <span style=" font-weight:bold;position: absolute;font-size: 8px; background-color: #f4f5f8; padding: 0 10px;margin-top: 1px;position: absolute;margin-left: -42px">
                                                    STERILIZATION<!--Padding is optional-->
                                                </span>
                                            </div>
                                            <div class="box" style="font-size:9px;padding:10px;border-bottom:0.7px solid black;border-left:0.7px solid black;border-right:0.7px solid black;">
                                                Expiry&nbsp;<img src="{{asset('/img/alderlogo/expiry_date.png')}}" style="width:8px; height:10px;">&nbsp;:{{$sterilization_expiry_date}}
                                            </div>
                                        </div>
                                    </div><br/>
                                    <div class="subdiv">
                                        <div class="group" style= "overflow:hidden;max-height:48px;;padding:2px;border-bottom: 1.5px solid black;border-top: 1.5px solid black; margin-top:60px;font-size:10px;">
                                            {{$batchcard_data->groups}}<br/>
                                            {{$batchcard_data->discription}}
                                        </div>
                                    </div>
                                    <div class="subdiv" style="margin-top:4px;">
                                        <div class="ss" style="float:left;width:150px; font-size:9px;">
                                            <strong>{{$batchcard_data->ad_sp1}}</strong><br/>
                                            <img src="data:image/png;base64,{{ base64_encode($sku_code_barcode)}}" style="width:90px;height:25px;margin-top:5px;">
                                            <br/>
                                            <span style="margin-left:26px;"><small>{{$batchcard_data->sku_code}}</small></span>
                                            <br/>
                                            <!-- <span style="font-size:7px;margin-left:40px;">ML No:{{$batchcard_data->drug_license_number}}</span> -->
                                            <div style="font-size:7px;margin-left:40px;margin-top:5px; padding-bottom:4.5px;">
                                                ML No:{{$batchcard_data->drug_license_number}}
                                            </div>
                                        </div>
                                        <div class="ss" style="float:left;width:150px;text-align:center;font-size:9px;">
                                            <strong>{{$batchcard_data->ad_sp2}}</strong><br/>
                                            <img src="data:image/png;base64,{{ base64_encode($gs1_code_barcode)}}" style="width:80px;height:35px;margin-top:5px;">
                                            <br/>
                                            <span><small>{{$batchcard_data->gs1_code}}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="subcolumn3" style="float:right;width:10px;">
                                    <span class="smalltext" style="font-size:8px;margin-top:75px; float: left;writing-mode: vertical-lr;transform: rotate(180deg);">
                                        <!-- LBL/F-{{$batchcard_data->label_format_number}}_REV00_{{date( 'd-M-y' , strtotime($batchcard_data->start_date) )}} -->
                                        LBL/F-{{$batchcard_data->label_format_number}}_REV00_{{date( 'd M y' , strtotime('14-12-2021') )}}
                                    </span>
                                </div>
                            </div>
                            <div class="row2" style="height:fit-content;">
                                <div class="subcolumn1" style="float:left; width:79px;display: inline-block;">
                                    <div class="prdct_img" style="text-align:center;">
                                    @if($batchcard_data->label_image)
                                        <?php $img_path = '/img/'.$batchcard_data->label_image; ?>
                                        @if(file_exists(public_path($img_path))) 
                                            <!-- <img src="{{asset('/img/'.$batchcard_data->label_image)}}" style="width:55%;margin-top:128%;"> -->
                                            <img src="{{asset($img_path)}}" style="width:55%;margin-top:-14px;">
                                        @endif
                                    @endif
                                    </div>
                                </div>
                                <div class="subcolumn2" style="float:right; width:300px;margin-right:12px;">
                                    <div class="foot" style="display:block;float:left;"> 
                                        <div class="img" style="">
                                            <img src="{{asset('/img/alderlogo/manufactured_address.png')}}" style="width:36px;height:22px;">
                                        </div>
                                        &nbsp;
                                        <div class="address"  style="float:left;line-height:0.95;margin-left:41.5px;margin-top: -28px;">
                                            <span class=" cls" style="font-size:8px;font-weight:bold";>ADLER HEALTHCARE PVT. LTD</span>
                                            <br/>
                                            <span style="font-size:8px;">
                                                Plot No-A1 MIDC, Sadavali, Tal- Sangmeshwar<br/>
                                                Dist -Ratnagiri, Maharashtra-415804 MADE IN INDIA
                                            </span>
                                        </div>   
                                    </div> 
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                    @endfor
                    @if($remaining!=0)
                    <div class="page-container" style="margin-top:34.0157px;margin-bottom:17.007px">
                    @for ($k=0;$k<$remaining;$k++)
                        <div class="label-container" style="float:left;width:396.85px;height:264.566px;padding:2px;">
                            <div class="row1" style="font-size:12px;height:fit-content;">    
                                <div class="subcolumn1" style="float:left; width:75px">
                                    <div class="logo" style="text-align:center;">
                                        <img src="{{asset('/img/logo.png')}}"  style="width:50%;">
                                    </div>
                                    <div class="icons" style="margin-top:8px;margin-left:2px; text-align:center;">
                                        @if($batchcard_data->is_donot_reuse_logo==1)
                                        <img src="{{asset('/img/alderlogo/dot_not_reuse.png')}}"  style="width:25%;">
                                        @endif
                                        @if($batchcard_data->is_read_instruction_logo==1)
                                        <img src="{{asset('/img/alderlogo/instruction_use.png')}}"  style="width:25%;">
                                        @endif
                                        <!-- @if($batchcard_data->is_temperature_logo==1)
                                        <img src="{{asset('/img/alderlogo/instruction_use.png')}}"  style="width:40%;float:left;">
                                        @endif -->
                                    </div>
                                </div>
                                <div class="subcolumn2" style="float:left;width:300px">
                                    <div class="subdiv">
                                        <div class="ss" style="float:left;width:150px">
                                            <span style="text-align:left;"><strong>Ref: {{$batchcard_data->sku_code}}</strong></span></br/>
                                            <img src="{{asset('/img/alderlogo/lot.png')}}" style="width:25px;">
                                            {{$batchcard_data->batch_no}}
                                            <br/>
                                            <img src="{{asset('/img/alderlogo/sterile_r.png')}}" alt="image" style="width:47px;">
                                            {{$lot_no}}
                                            <br/>
                                            <img src="{{asset('/img/alderlogo/manufacturing.png')}}" style="width:15px;">&nbsp;
                                            {{$manufacture_date}}
                                        </div>
                                        <div class="ss" style="float:left;width:150px;text-align:center;">
                                            <span style="text-align:right;"><strong>Qty: </strong>{{$batchcard_data->quantity_per_pack}}Nos</span>
                                            <div style=" height: 6px; border-bottom: 1px solid black; text-align: center">
                                                <span style=" font-weight:bold;position: absolute;font-size: 8px; background-color: #f4f5f8; padding: 0 10px;margin-top: 1px;position: absolute;margin-left: -42px">
                                                    STERILIZATION<!--Padding is optional-->
                                                </span>
                                            </div>
                                            <div class="box" style="font-size:9px;padding:10px;border-bottom:0.7px solid black;border-left:0.7px solid black;border-right:0.7px solid black;">
                                                Expiry&nbsp;<img src="{{asset('/img/alderlogo/expiry_date.png')}}" style="width:8px; height:10px;">&nbsp;:{{$sterilization_expiry_date}}
                                            </div>
                                        </div>
                                    </div><br/>
                                    <div class="subdiv">
                                        <div class="group" style= "overflow:hidden;max-height:48px;;padding:2px;border-bottom: 1.5px solid black;border-top: 1.5px solid black; margin-top:60px;font-size:10px;">
                                            {{$batchcard_data->groups}}<br/>
                                            {{$batchcard_data->discription}}
                                        </div>
                                    </div>
                                    <div class="subdiv" style="margin-top:4px;">
                                        <div class="ss" style="float:left;width:150px; font-size:9px;">
                                            <strong>{{$batchcard_data->ad_sp1}}</strong><br/>
                                            <img src="data:image/png;base64,{{ base64_encode($sku_code_barcode)}}" style="width:90px;height:25px;margin-top:5px;">
                                            <br/>
                                            <span style="margin-left:26px;"><small>{{$batchcard_data->sku_code}}</small></span>
                                            <br/>
                                            <!-- <span style="font-size:7px;margin-left:40px;">ML No:{{$batchcard_data->drug_license_number}}</span> -->
                                            <div style="font-size:7px;margin-left:40px;margin-top:5px; padding-bottom:4.5px;">
                                                ML No:{{$batchcard_data->drug_license_number}}
                                            </div>
                                        </div>
                                        <div class="ss" style="float:left;width:150px;text-align:center;font-size:9px;">
                                            <strong>{{$batchcard_data->ad_sp2}}</strong><br/>
                                            <img src="data:image/png;base64,{{ base64_encode($gs1_code_barcode)}}" style="width:80px;height:35px;margin-top:5px;">
                                            <br/>
                                            <span><small>{{$batchcard_data->gs1_code}}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="subcolumn3" style="float:right;width:10px;">
                                    <span class="smalltext" style="font-size:8px;margin-top:75px; float: left;writing-mode: vertical-lr;transform: rotate(180deg);">
                                        <!-- LBL/F-{{$batchcard_data->label_format_number}}_REV00_{{date( 'd-M-y' , strtotime($batchcard_data->start_date) )}} -->
                                        LBL/F-{{$batchcard_data->label_format_number}}_REV00_{{date( 'd M y' , strtotime('14-12-2021') )}}
                                    </span>
                                </div>
                            </div>
                            <div class="row2" style="height:fit-content;">
                                <div class="subcolumn1" style="float:left; width:79px;display: inline-block;">
                                    <div class="prdct_img" style="text-align:center;">
                                    @if($batchcard_data->label_image)
                                        <?php $img_path = '/img/'.$batchcard_data->label_image; ?>
                                        @if(file_exists(public_path($img_path))) 
                                            <!-- <img src="{{asset('/img/'.$batchcard_data->label_image)}}" style="width:55%;margin-top:128%;"> -->
                                            <img src="{{asset($img_path)}}" style="width:55%;margin-top:-14px;">
                                        @endif
                                    @endif
                                    </div>
                                </div>
                                <div class="subcolumn2" style="float:right; width:300px;margin-right:12px;">
                                    <div class="foot" style="display:block;float:left;"> 
                                        <div class="img" style="">
                                            <img src="{{asset('/img/alderlogo/manufactured_address.png')}}" style="width:36px;height:22px;">
                                        </div>
                                        &nbsp;
                                        <div class="address"  style="float:left;line-height:0.95;margin-left:41.5px;margin-top: -28px;">
                                            <span class=" cls" style="font-size:8px;font-weight:bold";>ADLER HEALTHCARE PVT. LTD</span>
                                            <br/>
                                            <span style="font-size:8px;">
                                                Plot No-A1 MIDC, Sadavali, Tal- Sangmeshwar<br/>
                                                Dist -Ratnagiri, Maharashtra-415804 MADE IN INDIA
                                            </span>
                                        </div>   
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

    
