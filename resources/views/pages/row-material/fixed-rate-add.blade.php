@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">

            <div class="az-content-breadcrumb">
                <span><a href="" style="color: #596881;"> RAW MATERIAL</a></span>
                <span><a href="">
                        FIXED RATE RAW MATERIAL
                    </a></span>
            </div>

            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                Fixed Rate Raw Material
            </h4>
            <div class="az-dashboard-nav">


            </div>

            <div class="row">

                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
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
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label>Supplier </label>
                                <select class="form-control supplier" id="1" name="supplier" id="supplier">
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Item Code*</label>
                                <select class="form-control  item_code" name="item_code" id="item_code">
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4" style="float:left;">
                                <label>Description * </label>
                                <textarea type="text" readonly class="form-control" id="item_description" name="item_description" placeholder="Description"></textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label> Rate </label>
                                <input type="number" value="" class="form-control" name="rate" id="rate">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>IGST</label>
                                <input type="text" value="0" class="form-control " name="igst" id="igst" >
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>CGST</label>
                                <input type="text" value="0" class="form-control " name="cgst" id="cgst" >
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>SGST</label>
                                <input type="text" value="0" class="form-control " name="sgst" id="sgst" >
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Discount</label>
                                <input type="text" value="" class="form-control " name="discount" id="discount">
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Currency</label>
                                <select class="form-control" name="currency" id="currency">
                                    <option value="">--- select one ---</option>
                                    @foreach($currency as $item)
                                    <option value="{{$item->currency_id}}">{{$item->currency_code}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Delivery within</label>
                                <input type="number" value="" class="form-control " name="delivery_within" id="delivery_within">
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Expiry Date Start</label>
                                <input type="text" name="expiry_date_start" class="form-control datepicker" value="{{date('d-m-Y')}}">
                            </div>

                            <div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-6">
                                <label>Expiry Date End</label>
                                <input type="text" value="{{date('d-m-Y')}}" class="form-control datepicker" name="expiry_date_end">

                            </div>

                            <!-- <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Conversion rate (INR) *</label>
                                <input type="text" class="form-control" value="" name="conversion_rate" id="conversion_rate" placeholder="Conversion rate">
                            </div>
                            <div class="form-group col-sm-12 $col-md-3 col-lg-3 col-xl-3">
                                <label>Value in INR </label>
                                <input type="text" readonly class="form-control" value="" name="value_inr" id="value_inr" placeholder="Value in INR">
                            </div> -->
                        </div>




                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    Save

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




<script src="<?= url(''); ?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"> </script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>

<script>
    'use strict'
    $('.item_code').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',
          minimumInputLength: 3,
          allowClear: true,
          ajax: {
          url: "{{ url('inventory/itemcodesearch') }}",
          processResults: function (data) {
            return { results: data };

          }
        }
    }).on('change', function (e) 
    {
         $("#item_description").text('');
        let res = $(this).select2('data')[0];
        if(typeof(res) != "undefined" )
        {
            if(res.discription){
                $("#item_description").text(res.discription);
            }
            if(res.unit_name){
                $("#unit-div").text(res.unit_name);
            }
            
        }

    });  
    $(".datepicker").datepicker({
        format: "dd-mm-yyyy",
        // viewMode: "months",
        // minViewMode: "months",
        // startDate: date,
        autoclose:true
    });
    // $(".Item-code").select2({
    //     placeholder: 'Choose one',
    //     searchInputPlaceholder: 'Search',
    //     minimumInputLength: 6,
    //     allowClear: true,
    //     ajax: {
    //         url: "{{ url('inventory/itemcodesearch') }}",
    //         processResults: function (data) {
    //             return { results: data };
    //         }
    //     }
    // });
    $('.supplier').select2({

        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 3,
        allowClear: true,
        theme: "classic",
        ajax: {
            url: "{{url('inventory/suppliersearch')}}",
            processResults: function(data) {
                return {
                    results: data
                };
            }
        }
    });
    $("#commentForm").validate({
        rules: {
            supplier: {
                required: true,
                //number: true,
            },
            item_code: {
                required: true,
                //number: true,
            },
            rate: {
                required: true,
                //number: true,
            },
            // gst: {
            //     required: true,
            //     //number: true,
            // },
            discount: {
                required: true,
                //number: true,
            },
            currency: {
                required: true,
                //number: true,
            },
            delivery_within: {
                required: true,
                //number: true,
            },
            
        },
        submitHandler: function(form) {
            $('.spinner-button').show();
            form.submit();
        }
    });

</script>


@stop