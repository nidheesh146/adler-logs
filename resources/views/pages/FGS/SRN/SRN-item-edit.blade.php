@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">

            <div class="az-content-breadcrumb">
                <span><a href="" style="color: #596881;">Sales Return Note(SRN)</a></span>
                <!-- <span><a href="" style="color: #596881;">MRN</a></span> -->
                <span><a href="">

                    </a></span>
            </div>

            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                Sales Return Note(SRN)
            </h4>
            <div class="az-dashboard-nav">

            </div>

            <div class="row">

                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                    @if(Session::get('error'))
                    <div class="alert alert-danger " role="alert" style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{Session::get('error')}}
                    </div>
                    @endif
                    @if (Session::get('success'))
                    <div class="alert alert-success " style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                    </div>
                    @endif
                    @foreach ($errors->all() as $errorr)
                    <div class="alert alert-danger " role="alert" style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ $errorr }}
                    </div>
                    @endforeach

                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="POST" id="commentForm" autocomplete="off">


                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-address-card"></i> Basic details
                                </label>
                                <div class="form-devider"></div>
                            </div>
                        </div>
                        <div class="row">
                            <table class="table table-bordered">
                                <tbody id="dynamic_field">
                                    <tr id="row1" rel="1">
                                        <td>

                                            <div class="row">
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label for="exampleInputEmail1">Product code * </label>
                                                    <input type="text" readonly class="form-control" name="prdctcode" id="prdctcode" value="{{$item_details->sku_code}}">
                                                    <input type="hidden" id="srn_number" value="{{$srn_info->id}}" name="srn_number">
                                                    <input type="hidden" value="{{$item_details->product_id}}" name="product_id" id="product_id">
                                                    <!--input type="hidden" value="{{$item_details->batchcard_id}}" name="batchcard_id" id="batchcard_id"-->
                                                </div>
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                                    <label>HSN Code * </label>
                                                    <input type="text" readonly class="form-control" name="hsncode" value="{{$item_details->hsn_code}}" id="hsncode1" placeholder="HSN Code">
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label>Description * </label>
                                                    <textarea type="text" readonly class="form-control" id="Itemdescription1" name="Description" placeholder="Description">{{$item_details->discription}}</textarea>
                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label for="exampleInputEmail1">Batch No * </label>
                                                    <select class="form-control batch_no" name="batch_no" id="batch_no">
    @foreach($batchcards as $batchcard)
        <option value="{{$batchcard['batch_id']}}" {{$item_details->batchcard_id == $batchcard['batch_id'] ? 'selected' : ''}}>
            {{$batchcard['batch_no']}}
        </option>
    @endforeach
</select>

                                                </div>
                                            </div>
                                            <div class="row">
                                               
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label>
                                                        {{--<span style="color: red;font-size: 10px;">Quantity should be less than stock qty</span><br>--}}
                                                        Quantity * </label>
                                                    <input type="number" class="form-control min_item_qty" name="quantity" id="quantity"  max="{{$item_details->quantity}}" min="0" value="{{$item_details->quantity}}" index="1">
                                                    <span id="error1" style="color:red;"></span>
                                                </div>

                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                                    <label>UOM </label>
                                                    <input type="text" class="form-control" readonly name="uom" id="uom1" placeholder="Nos">
                                                </div>
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                                    <label>Rate </label>
                                                    @if($srn_info->dni_number_manual!=NULL) 
                                                    <input type="text" class="form-control"  name="rate"  id="rate"value="{{$item_details->rate}}" >
                                                    @else
                                                    <input type="text" class="form-control"  name="rate"readonly  id="rate"placeholder="{{$item_details->rate}}" >
                                                    @endif
                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
    <label>Date of Mfg. * </label>
    <!-- Updated name attribute to match controller -->
    <input type="text" class="form-control datepicker manufacturing_date" name="manufacturing_date" 
           value="{{date('d-m-Y', strtotime($item_details->manufacturing_date))}}" 
           id="manufacturing_date1" placeholder="Date of Mfg.">
</div>
<div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
    <label>Date of Expiry * </label>
    <input type="text" class="form-control datepicker expiry_date" name="expiry_date1" 
           value="@if($item_details->expiry_date <= '1990-01-01' || $item_details->expiry_date == '0000-00-00') NA @else{{ date('d-m-Y', strtotime($item_details->expiry_date)) }} @endif" 
           id="expiry_date1" placeholder="Date of Expiry" readonly>
</div>


                                                <!-- <button type="button" name="add" id="add" class="btn btn-success"
                                                    style="height:38px;margin-top:28px;"><i
                                                        class="fas fa-plus"></i></button> -->
                                            </div>
                                        </td>
                                    </tr>


                                </tbody>

                            </table>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                        Update

                                    </button>
                                </div>
                            </div>
                            <div class="form-devider"></div>
                    </form>

                </div>
            </div>






        </div>






    </div>
    <!-- az-content-body -->
</div>




<script src="<?= url('') ?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"> </script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>

<script>
    $(document).ready(function() {
        $('form').submit(function() {
            $(this).find(':submit').prop('disabled', true);
        });
    });
    $(function() {
        'use strict'

        $(".datepicker").datepicker({
            format: " dd-mm-yyyy",
            autoclose: true
        });
        // $(".min_date").datepicker("setDate", new Date());
       


        $("#commentForm").validate({
            rules: {
                Requestor: {
                    required: true,
                },
                Department: {
                    required: true,
                },
                Date: {
                    required: true,
                },


            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });


    });
    $('.requestor').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
    });
</script>


@stop