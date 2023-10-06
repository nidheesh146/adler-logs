<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

</head>

<body>
    @php
    use App\Http\Controllers\Web\FGS\DeliveryNoteController;
    $obj_challan=new DeliveryNoteController;
    @endphp
    @inject('fn', 'App\Http\Controllers\Web\FGS\OEFController')
    <style>
        #table,
        td,
        th {
            border-collapse: separate;
            border: 1px solid black;
        }

        div {
            font-size: 12px;
        }
    </style>

    <!-- <div class="row1" style="height:150px;border-bottom:solid 2px black;"> -->




    <div class="row" style="margin: 10px; ">
        <table style="width: 100%;" id="table">
            <tr>
                <td colspan="7">
                    <img src="{{asset('/img/logo.png')}}" style="width:80px;margin:10px ;">
                    <span style="font-weight: bold; font-size: 25px;margin-left: 50px;">Adler Healthcare Pvt. Ltd.</span> <span style="margin-left: 30px;">CIN : U33125PN2020PTC195161</span> <br>
                    <p style="margin-left: 100px;">Plot No. A-1, MIDC Sadavali, Tal. : Sangameshwar, Dist. Ratnagiri, PIN - 415 804. Contact No.: 8055136000 / 8055146000</p>


                </td>
            </tr>
            <tr>
                <td colspan="7">
                    <h2 style="text-align: center;">DELIVERY CHALLAN</h2>
                </td>
            </tr>
            <tr>
                <td colspan="7" style="border: none;">
                    <table id="table1">
                        <tr>

                            <td colspan="3" style="font-weight: bold;">To:
                                <span style=" font-weight: bold;">{{$data->firm_name}}</span>
                            </td>
                            <td colspan="2" rowspan="2" style="width: 20%; font-size: bold;">Ref. No.:{{$data->ref_no}} <br>
                                Ref. Date:{{date('d-m-Y',strtotime($data->ref_date))}}
                            </td>
                            <td style="width: 15%;" style="font-weight: bold;">Doc No. :</td>
                            <td style="width: 15%;">{{$data->doc_no}}</td>
                        </tr>
                        <tr>
                            <td style="">
                            </td>
                            <td colspan="2" rowspan="2">{{$data->shipping_address}}</td>

                            <td style="font-weight: bold;">Doc Date :</td>
                            <td>{{date('d-m-Y',strtotime($data->doc_date))}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2">OEF No.:{{$data->oef_number}}<br/>
                                OEF. Date:{{date('d-m-Y',strtotime($data->oef_date))}}
                            </td>
                            <td style="font-weight: bold;">Trnsctn Type:</td>
                            <td>{{$data->transaction_name}}</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-weight: bold;">Zone: <span>{{$data->zone_name}}</span></td>
                            <td colspan="2"></td>
                            <td style="font-weight: bold;">Trnsctn Cndtn:</td>
                            <td>@if($data->transaction_condition==1)
                                Returnable
                                @else
                                Non Returnable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-weight: bold;">Stock Location (Decrese): <span>{{$data->location_decrease}}</span></td>
                            <td colspan="2"></td>
                            <td style="font-weight: bold;">Product Category:</td>
                            <td>{{$data->category_name}}</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-weight: bold;">Stock Location (Increase): <span>@if($data->location_increase) {{$data->location_increase}} @else N.A @endif</span></td>
                            <td colspan="4"></td>

                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="7" style="border:none;">
                    <table id="table" style="width: 100%;">
                        <tr>
                            <td style="font-weight: bold;">Sr. No.</td>
                            <td style="font-weight: bold;">Item Code</td>
                            <td style="font-weight: bold;">Item Description</td>
                            <td style="font-weight: bold;">Batch No.</td>
                            <td style="font-weight: bold;">Qty.</td>
                            <td style="font-weight: bold;">Date of Mfg.</td>
                            <td style="font-weight: bold;">Date of Expiry</td>
                        </tr>
                        @php
                        $s=1;
                        @endphp
                        @foreach($item as $data)
                        <tr>
                            <td style="height: 35px;">{{$s++}}</td>
                            <td>{{$data->sku_code}}</td>
                            <td>{{$data->discription}}</td>
                            <td>{{$data->batch_no}}</td>
                            <td>{{$data->batch_qty}}</td>
                            <td>{{date('d-m-Y',strtotime($data->manufacturing_date))}}</td>
                            <td>@if($data->expiry_date!='0000-00-00') {{date('d-m-Y',strtotime($data->expiry_date))}} @else N.A @endif</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" style="height: 250px;" valign="top">
                                Remarks:</td>
                            <td colspan="4"><span>For Adler Healthcare Pvt. Ltd. </span><br>
                                <span>Authorised signatory</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </div>


</body>

</html>