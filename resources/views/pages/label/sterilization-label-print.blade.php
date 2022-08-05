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
                @for ($i = 0; $i< $no_of_label; $i++)
                <div class=" column" style=" height:22.22%;float: left;width: 45%;border-style: ridge;border-color:#f0f0f5 ;margin:5px;padding:8px;font-size:12px;">
                    <div class="subcolumn" style="float:left; width:20%">
                        <div class="logo" style="text-align:center;">
                            <img src="{{asset('/img/alderlogo/alder_logo.png')}}"  style="width:60%;">
                        </div>
                        <div class="icons" style="margin-top:20%;margin-left:2px; text-align:center;">
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
                        <div class="prdct-img" style="text-align:center;">
                        @if($batchcard_data->label_image)
                            <img src="{{asset('/img/'.$batchcard_data->label_image)}}" style="width:55%;margin-top:128%;">
                        @endif
                        </div>
                    </div>
                    <div class="subcolumn" style="float:left;width:70%;">
                        <div class="subdiv">
                            <div class="ss" style="float:left;width:50%;">
                                <span style="text-align:left;"><strong>Ref: {{$batchcard_data->sku_code}}</strong></span></br/>
                                <img src="{{asset('/img/alderlogo/lot.png')}}" style="width:25px;">
                                {{$batchcard_data->batch_no}}
                                <br/>
                                <img src="{{asset('/img/alderlogo/sterile_eo.png')}}" alt="image">
                                {{$lot_no}}
                                <br/>
                                <img src="{{asset('/img/alderlogo/manufacturing.png')}}" style="width:15px;">&nbsp;
                                {{$manufacture_date}}
                            </div>
                            <div class="ss" style="float:left;width:50%;text-align:center;">
                                <span style="text-align:right;"><strong>Qty: </strong>{{$batchcard_data->quantity_per_pack}}Nos</span>
                                <div style=" height: 6px; border-bottom: 1px solid black; text-align: center">
                                    <span style=" font-weight:bold;position: absolute;font-size: 8px; background-color: #f4f5f8; padding: 0 10px;margin-top: 1px;position: absolute;margin-left: -42px">
                                        STERILIZATION<!--Padding is optional-->
                                    </span>
                                </div>

                                <!-- <span style="font-size:8px;font-weight:bold;">STERILIZATION</span> -->
                              <!-- <hr class="hr-text" data-content="STERILIZATION"> -->
                                <div class="box" style="font-size:8px;padding:10px;border-bottom:0.7px solid black;border-left:0.7px solid black;border-right:0.7px solid black;">
                                    Expiry&nbsp;<img src="{{asset('/img/alderlogo/expiry_date.png')}}" style="width:8px; height:10px;">&nbsp;:{{$sterilization_expiry_date}}
                                </div>
                            </div>       
                        </div> <br/>
                        <!-- <div class="manufacturing">
                            <img src="/img/alderlogo/manufacturing.png" style="width:15px;">&nbsp;
                            {{$batchcard_data->start_date}}
                        </div>  -->
                        <div class="group" style="padding:2px;border-bottom: 1.5px solid black;border-top: 1.5px solid black; margin-top:60px;">
                            {{$batchcard_data->groups}}<br/>
                            {{$batchcard_data->discription}}
                        </div>
                        <div class="subdiv" style="margin-top:3px;">
                            <div class="ss" style="float:left;width:50%; font-size:10px;">
                                <strong>{{$batchcard_data->ad_sp1}}</strong><br/>
                                <img src="data:image/png;base64,{{ base64_encode($sku_code_barcode)}}" style="width:90px;height:25px;margin-top:5px;">
                                <br/>
                                <small>{{$batchcard_data->sku_code}}</small>
                                <br/>
                                <span style="font-size:7px;">ML No:{{$batchcard_data->drug_license_number}}</span>
                               
                            </div>
                            <div class="ss" style="float:left;width:50%;text-align:center;font-size:10px;">
                            <strong>{{$batchcard_data->ad_sp2}}</strong><br/>
                            <img src="data:image/png;base64,{{ base64_encode($gs1_code_barcode)}}" style="width:105px;height:45px;margin-top:5px;">
                                <br/>
                                <small>{{$batchcard_data->gs1_code}}</small>
                            </div> 
                            <div class="foot"> 
                                <div class="img" style="float:left;">
                                    <img src="{{asset('/img/alderlogo/manufactured_address.png')}}" style="width:36px;height:28px;">
                                </div>
                                &nbsp;
                                <div class="address"  style="float:left;line-height: 75%;margin-left:1px;"> 
                                    <span class=" cls" style="font-size:7px;font-weight:bold";>ADLER HEALTHCARE PVT. LTD</span>
                                    <br/>
                                    <span style="font-size:5px;">
                                        Plot No-A1 MIDC, Sadavali, Tal- Sangmeshwar<br/>
                                        Dist -Ratnagiri, Maharashtra-415804 MADE IN INDIA
                                    </span>
                                </div>  
                            </div>   
                        </div>               
                    </div> 
                    <div class="subcolumn" style="float:left;width:5%;">
                        <span class="smalltext" style="font-size:7px;margin-top:85px; float: left;writing-mode: vertical-lr;transform: rotate(180deg);">
                               LBL/F-{{$batchcard_data->label_format_number}}_REV00_{{date( 'd-M-y' , strtotime($batchcard_data->start_date) )}}
                        </span>

                    </div>    
                </div>
                @endfor
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

    
