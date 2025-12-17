@extends('layouts.default')
@section('content')
<style>
    @media print {
        @page {
            margin: 0;
        }
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
            <?php 
            $label_per_page =12;
            $page_count = (int)($no_of_label/12);
            $remaining = $no_of_label%12;
            ?>
           <input type="hidden" name="batch_id" id="batch_id" value="{{$batchcard_data->batch_id}}">
                <input type="hidden" name="no_of_labels" id="no_of_labels" value="{{$no_of_label}}">
                <input type="hidden" name="manufacturing_date" id="manufacturing_date" value="{{$manufacture_date}}">
                <input type="hidden" name="product_id" id="product_id" value="{{$batchcard_data->product_id}}">
                <input type="hidden" name="expiry_date" id="expiry_date" value="{{date('Y-m-d', strtotime($sterilization_expiry_date))}}">
                <input type="hidden" name="label_name" id="label_name" value="Sterilization Label">
            <div class="label-div" id="label-div" style="margin-bottom:5px; margin-top:30px;">
                @for ($i = 1; $i<= $page_count; $i++)
                <div style="margin-top:-8px;margin-bottom:0px;">
                @for ($j = 1; $j<= $label_per_page; $j++)
                
                <div class=" column " style="height:8%;margin-top:15px;@if($j>=11)margin-bottom:5px;@else margin-bottom:80px;@endif;float:left;width: 48%;font-size:10px; margin-left:12px;">
                <div class="row1" style="font-size:8px;">
                                        <div class="subcolumn1" style="float:left; width:50px;">
                                            <div class="logo" style="text-align:center;">
                                                <img src="{{asset('/img/logo.png')}}" style="width:70%;height:32px;">
                                            </div>
                                            <div class="icons" style="margin-top:8px;margin-left:2px; text-align:center;">
                                                <!-- @if($batchcard_data->is_donot_reuse_logo==1)70 29 -->
                                                <img src="{{asset('/img/alderlogo/dot_not_reuse.png')}}" style="width:27%;">
                                                <!-- @endif
                                        @if($batchcard_data->is_read_instruction_logo==1) -->
                                                <img src="{{asset('/img/alderlogo/instruction_use.png')}}" style="width:27%;">
                                                <!-- @endif -->
                                                <!-- @if($batchcard_data->is_temperature_logo==1)
                                        <img src="{{asset('/img/alderlogo/instruction_use.png')}}"  style="width:40%;float:left;">
                                        @endif -->
                                            </div>
                                        </div>
                                        <div class="subcolumn2" style="float:left;width:220px">
                                            <div class="subdiv">
                                                <div class="ss" style="float:left;width:130px;height:15px;">
                                                    <span style="text-align:left;"><strong>Ref: {{$batchcard_data->sku_code}}</strong></span></br />
                                                    <img src="{{asset('/img/alderlogo/lot.png')}}" style="width:23px;height:8px; margin-top:3px;">
                                                    {{$batchcard_data->batch_no}}
                                                    <br/>
                                                    <!-- <img src="{{asset('/img/alderlogo/sterile_r.png')}}" alt="image" style="width:49px;"> -->
                                                    <div style="display:block;float:left;font-family: Arial, sans-serif;font-size:6px;font-weight:350;margin-top:4px;">
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
                                                        <div style="margin-bottom:-10px; margin-left:2px; display: inline;font-size:8px;">{{$lot_no}}</div>
                                                    </div>
                                                    <br />
                                                   <span style="font-size:12px; margin-top:11px; display:block; width:350px; white-space:normal; word-wrap:break-word;">{{$batchcard_data->ad_sp1}}</span>

                                                </div>
                                                <div class="ss" style="float:right;width:135px;text-align:center;margin-top:-15px; margin-left:20px;">
                                                    <span style="text-align:right;">Qty: {{$per_pack_quantity}}Nos</span>
                                                    <!-- <div style=" height: 6px;text-align: center"> -->
                                                        <!-- <span style="position: absolute;font-size: 8px; background-color: #f4f5f8; padding: 0 10px;margin-top: 1px;position: absolute;margin-left: -42px"> -->
                                                            <!-- STERILIZATION -->
                                                        <!-- </span> -->
                                                    <!-- </div> -->
                                                    <div class="box" style="font-size:9px;padding:6px 6px 6px 6px ; margin-top:-4px;">
    Expiry&nbsp;<img src="{{asset('/img/alderlogo/expiry_date.png')}}" style="width:8px; height:10px;">&nbsp;:{{ $sterilization_expiry_date }}
</div>
<img src="{{asset('/img/alderlogo/manufacturing.png')}}" style="width:18px;margin-top:-3px;margin-left:-10px;height:10px">&nbsp;
                                                    {{$manufacture_date}} 
                                                </div>
                                            </div><br />
                                            <div class="subdiv">
                                                <div class="group" style="font-weight:190;overflow:hidden;max-height:30px;padding:1p; margin-top:53px;font-size:6.5px;">
                                                    {{$batchcard_data->groups}}<br />
                                                    {{$batchcard_data->discription}}
                                                </div>
                                            </div>
                                            <div class="subdiv" style="margin-top:12px;">
                                                <div class="ss" style="float:left;width:150px; font-size:8px;">
                                                    <!-- <span style="margin-left:5px;margin-top:6.5px; font-size:11px;"><strong>{{$batchcard_data->ad_sp1}}</strong></span><br /> -->
                                                    <!-- <img src="data:image/png;base64,{{ base64_encode($sku_code_barcode)}}" style="width:90px;height:25px;margin-top:5px;"> -->
                                                    <img src="data:image/png;base64,{{ base64_encode($sku_code_barcode)}}" style="margin-top:2px;width:140px; height:15px;">
                                                    <br />
                                                    <small><span style="margin-left:0px;font-size:7px;font-weight:350;margin-top:-10px;">{{$batchcard_data->sku_code}}</span></small>
                                                    <br />
                                                    <!-- <span style="font-size:7px;margin-left:40px;">ML No:{{$batchcard_data->drug_license_number}}</span> -->
                                                    <div style="font-size:7px;margin-left:-1px;margin-top:-1px; padding-bottom:5px;">
                                                    &nbsp;{{$batchcard_data->drug_license_number}}
                                                    </div>
                                                </div>
                                                <div class="ss" style="float:right;width:150px;text-align:center;font-size:8px;margin-top:-45px;">
                                                    <strong style="font-size:6px; margin-left:-198px;margin-top:23px;">{{$batchcard_data->ad_sp2}}</strong><br />
                                                    {{-- <img src="data:image/png;base64,{{ base64_encode($gs1_code_barcode)}}" style="width:80px;height:35px;margin-top:3px;"> --}}
                                                    {{--<img src="data:image/png;base64,{{ base64_encode($gs1_code_barcode)}}" style="width:140px;margin-top:3px;">
                                                    <br />
                                                    <small><span style="font-weight:300">{{$batchcard_data->gs1_code}}</span></small>--}}
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="row2" style="height:fit-content; height:80px">
                                        <div class="subcolumn1" style="float:left;width:5px;margin-left:-245px;">
                                            <span class="smalltext" style="font-size:10px;font-weight:bold;margin-top:58px; float: right;writing-mode: vertical-lr;transform: rotate(360deg);">
                                                {{$batchcard_data->sku_code}}
                                            </span>
                                        </div>
                                        <div class="subcolumn2" style="float:left;margin-left:-280px;margin-top:115px; width:70px;display: inline-block;">
                                            <div class="prdct_img" style="text-align:center;">
                                                @if($batchcard_data->label_image)
                                                <?php $img_path = '/img/' . $batchcard_data->label_image; ?>
                                                @if(file_exists(public_path($img_path)))
                                                <!-- <img src="{{asset('/img/'.$batchcard_data->label_image)}}" style="width:55%;margin-top:128%;"> -->
                                                <img src="{{asset($img_path)}}?{{ time() }}" style="width:95%;margin-top:-35px;margin-left:235px;">
                                                @endif
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="subcolumn3" style="float:right; width:300px;margin-right:50px; margin-top:2px;">
                                            <div class="foot" style="display:block;float:left;margin-left:15px;">
                                                <div class="img" style="">
                                                    <img src="{{asset('/img/alderlogo/manufactured_address.png')}}" style="width:23px;height:10px;margin-left:9px;margin-top: -3px">
                                                </div>
                                                &nbsp;
                                                <div class="address" style="float:right;line-height:1; margin-left:35px;margin-top:-10px;">
                                                    <span class=" cls" style="font-size:7px;" ;>ADLER HEALTHCARE PVT. LTD</span>
                                                    <br  />
                                                    <span style="font-size:7px;">
                                                        Plot No-A1 MIDC, Sadavali,
                                                        Tal- Sangmeshwar, Dist -Ratnagiri, Maharastra-415804<br  />
                                                       <span style="font-size:6px;">
                                                        MADE IN INDIA
                                                    </span> 
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>   
                                    <div class="subcolumn3" style="margin-top:-80px;margin-left:265px;">
                                        <span class="smalltext" style="font-size:6.5px;">
                                            {{-- LBL/F-{{$batchcard_data->label_format_number}}_REV00_{{date( 'd-M-y' , strtotime($batchcard_data->start_date) )}} --}} 
                                            LBL/F-{{$batchcard_data->label_format_number}}_REV00_{{strtoupper(date( 'dMY' , strtotime('30-04-2024') ))}}   
                                        </span>
                                    </div>
                                    <div style="font-size:8px;margin-top:7px;">
                                    <span style="font-size:10px;">MRP: ₹{{ $mrp }} /-</span>
        </div>
                </div>

                
                @endfor
                </div>
                <div style="break-after:page"></div>
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
                <div  style="margin-top:0mm;max-height:29.3cm;"> 
                @for ($j=1;$j<=$label_per_page;$j++)
                <!-- <div class="column label-content{{$j}}" style="height:16%;float:left;width: 48%;padding:0px 2px;font-size:13px; margin-left:10px;@if($j >= 11) margin-bottom:0px; @else margin-bottom:40px; @endif"> -->
                    <div class="subcolumn1" style="float:left;width:95%; height:content-fit;">
                        <div class="sub1" style="float:left;width:content-fit;font-weight:bold;">
                            <img src="{{asset('/img/alderlogo/IFU.png')}}" style="float:left; height:81px;">
                        </div> 
                        
                        <div class="sub2" style="float:left;width:8%;padding:1px;height:70px">
                            <span class="smalltext" style="font-size:4.7px;font-weight:500;text-align: center;writing-mode: vertical-lr;transform: rotate(180deg);margin-left:40%;margin-top:5px;">
                            LBL/F-{{$batchcard_data->label_format_number}}_REV00_{{strtoupper(date( 'dMY' , strtotime('01-08-2023')) )}}
                            </span>
                        </div>
                        <div class="sub3" style="float:left;width:45%;padding:1px;font-size:8px;line-height:1.1;">
                            <span style="">{{$batchcard_data->groups}}</span><br/>
                            <span style="font-weight:bold;font-size:9px">{{$batchcard_data->brand}}</span><br/>
                            <span style="">{{$batchcard_data->family}}</span><br/>
                            <span style="">{{$batchcard_data->snn_description}}</span><br/>
                            <span style="font-weight:bold;font-size:9px">Ref: {{$batchcard_data->sku_code}}</span><br/>
                            <div style="padding-top:1px;width: fit-content;">
                                <img src="data:image/png;base64,{{ base64_encode($sku_code_barcode)}}" style="width:90px;height:20px">
                                <br/>
                                <div style="text-align:center;font-size:6.2px;font-weight:550;">{{$batchcard_data->sku_code}}</div>
                            </div>
                        </div>
                        <div class="sub4" style="float:left;width:content-fit;height:70px;padding:1px;font-size:9px;text-align:center;">
                            <img src="{{asset('/img/alderlogo/manufacturing.png')}}" style="width:15px;">&nbsp;
                            <span class="smalltext1">{{$manufacturing_date}}</span><br/>
                            <strong>Qty :</strong>{{$per_pack_quantity}} Nos
                            <img src="{{asset('/img/alderlogo/lot.png')}}" style="width:25px;">&nbsp;
                            <span class="smalltext1">{{$batchcard_data->batch_no}}</span></br>
                        </div>
                    </div> 
                    <div class="subcolumn2" style="float:left;margin-top:1px;">
                        <div style="float:left;width:40%;">
                            <div class="barcode2" style="font-size:5.6px">
                                <img src="data:image/png;base64,{{ base64_encode($label_batch_combo_barcode)}}" style="width:95%;height:22px">   
                                <br/>
                                <div style="text-align:center;margin-top:1px;font-size:6.2px;font-weight:550">{{$label_batch_combo}}</div>
                            </div>
                        </div>
                        <div class="barcode" style="width:30%;float:left;font-size:5.6px">
                            <img src="data:image/png;base64,{{ base64_encode($manf_date_combo_barcode)}}" style="width:100%;">   
                            <br/>
                            <div style="text-align:center;margin-top:1px;font-size:6.2px;font-weight:550">{{$manf_date_combo}}</div>
                        </div>
                        <div style="float:left;width:15%; margin-left:1px;">
                            <div class="logo" style="text-align:center;">
                            <img src="{{asset('/img/alderlogo/instruction_use.png')}}"  style="width:40%;float:left;padding:0px;margin-left: 3px;">
                                <img src="{{asset('/img/alderlogo/dot_not_reuse.png')}}"  style="width:36%;float:left;padding:0px;margin-left: 3px;">
                            </div>
                        </div>
                    </div>
                    <div class="subcolumn3" style="float:left;margin-top:2px;">
                        <div style="float:left;width:83%;">
                            <div class="barcode3"  style="float:left;margin-top:4px;">
                                {{--<div class="barcode" style="width:30%;float:left;font-size:5.6px">
                                    <img src="data:image/png;base64,{{ base64_encode($manf_date_combo_barcode)}}" style="width:100%;">   
                                    <br/>
                                    <div style="text-align:center;margin-top:1px;font-size:6.2px;font-weight:550">{{$manf_date_combo}}</div>
                                </div>--}}
                                <div style="margin-left:2px;float:left;margin-top:18px">
                                    <img src="{{asset('/img/alderlogo/manufactured_address.png')}}" style="float:left;width:32px;height:15px; margin-left:2.5px;margin-top: 4px">
                                    <span style="font-size:6px; padding-left:2px;font-weight:bold;display:block;margin-top:-4px">{{$batchcard_data->drug_license_number}}</span> 
                                    <span class=" cls" style="padding-left:2px;font-size:7.5px;font-weight:bold;display:block;margin-left: 35px;margin-top: 2px;";>ADLER HEALTHCARE PVT. LTD</span>
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
                                <img src="{{asset('/img/alderlogo/non_sterile.png')}}"  style="width:60%;margin-bottom:2px;">
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
      mywindow.document.write('<link href="" rel="stylesheet" type="text/css" media="print"/>');
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
    var label_name = $('#label_name').val();
    $.ajax({
           type:'POST',
           url:"{{ url('label/insert-printing-data') }}",
           data:{ "_token": "{{ csrf_token() }}",batch_id:batch_id, no_of_labels:no_of_labels, manufacturing_date:manufacturing_date, product_id:product_id,label_name:label_name},
           success:function(data)
           {
            if(data==1)
            Labelprint();
            else
            alert('Label printing failed!!!')
           }
    });
});
</script>
@stop

    
