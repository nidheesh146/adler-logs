@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">

            <div class="az-content-breadcrumb">
                <span><a href="" style="color: #596881;"> Material Receipt Note(MRN)</a></span>
                <!-- <span><a href="" style="color: #596881;">MRN</a></span> -->
                <span><a href="">

                    </a></span>
            </div>

            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                Material Receipt Note(MRN)
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
                            @if(!empty($mrn))
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">MRN Number</label>
                                <input type="text" class="form-control" name="mrn_number" value="{{$mrn->mrn_number}}" placeholder="MRN Number" readonly>
                                <input type="hidden" class="form-control" name="mrn_id" value="{{$mrn->id}}" placeholder="MRN id">
                            </div>
                            @endif
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Supplier Doc No. *</label>
                                <input type="text" class="form-control" name="supplier_doc_number" value="@if(!empty($mrn)) {{$mrn->supplier_doc_number}} @endif" placeholder="Supplier Doc No">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Supplier Doc Date *</label>
                                <input type="text" class="form-control datepicker supplier_doc_date" name="supplier_doc_date" value="@if(!empty($mrn)) {{date('d-m-Y', strtotime($mrn->supplier_doc_date))}} @else {{date('d-m-Y')}} @endif" placeholder="">
                            </div>

                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Product Category *</label>
                                <select class="form-control" name="product_category" id="product_category" onchange="myFunction()">
                                    <option>Select one...</option>
                                    @foreach($category as $cate)
                                    <option value="{{$cate['id']}}" @if(!empty($mrn) && ($mrn->product_category==$cate['id'])) selected="selected" @endif>{{$cate['category_name']}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Supplier</label>
                                <select class="form-control" name="supplier" id="supplier" onchange="myFunction1()">
                                    <option>Select one...</option>
                                    <option value="1" @if(!empty($mrn) && ($mrn->supplier==1)) selected="selected" @endif>Adler Healthcare Pvt. Ltd.</option>
                                    <option value="2" @if(!empty($mrn) && ($mrn->supplier==2)) selected="selected" @endif>Smith & Nephew Healthcare Pvt. Ltd.</option>

                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Supplier Address</label>
                                <select class="form-control" name="supplier_address" id="supplier_address" readonly>
                                    <option>Select one...</option>
                                    <option value="1" @if(!empty($mrn) && ($mrn->supplier==1)) selected="selected" @endif>MIDC Sadavali, Devrukh, Tal. Sangameshwar – 415 804</option>
                                    <option value="2" @if(!empty($mrn) && ($mrn->supplier==2)) selected="selected" @endif>Andheri (East), Mumbai – 400 059</option>

                                </select>
                            </div>


                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Stock Location *</label>
                                <select class="form-control" name="stock_location" id="stock_location" @if(!empty($mrn)) readonly @endif>
                                    <option>Select one...</option>
                                    @foreach($locations as $loc)
                                    @if($loc['location_name']!='MAA (Material Allocation Area)' && $loc['location_name']!='Quarantine' && $loc['location_name']!='Consignment' && $loc['location_name']!='Loaner')
                                    <option value="{{$loc['id']}}" @if(!empty($mrn) && ($mrn->stock_location==$loc['id'])) selected="selected" @endif>{{$loc['location_name']}}</option>
                                    
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <label>MRN Date *</label>
                                <input type="text" value="@if(!empty($mrn)) {{date('d-m-Y', strtotime($mrn->mrn_date))}} @else {{date('d-m-Y')}} @endif" class="form-control datepicker mrn_date" name="mrn_date"  placeholder="">
                            </div><!-- form-group -->
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    Save & Next

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
    $(function() {
        'use strict'

        $(".datepicker").datepicker({
            format: " dd-mm-yyyy",
            autoclose: true,
        });
        // $('.supplier_doc_date').datepicker("setDate", new Date());
        // $(".mrn_date").datepicker("setDate", new Date());
        //  .datepicker('update', new Date());

        $('.datepicker').mask('99-99-9999');


        $("#commentForm").validate({
            rules: {
                supplier_doc_number: {
                    required: true,
                },
                supplier_doc_date: {
                    required: true,
                },
                product_category: {
                    required: true,
                },
                stock_location: {
                    required: true,
                },
                mrn_date: {
                    required: true,
                },


            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });


    });
    $(document).ready(function() {
        $('form').submit(function() {
            $(this).find(':submit').prop('disabled', true);
        });
    });
</script>
<script>
    function myFunction() {

        var x = document.getElementById("product_category").value;
        if (x == 1) {
            var optionToRemove = document.querySelector("#stock_location option[value='10']");
            if (optionToRemove) {
                optionToRemove.remove();
            }
            var optionToDisplay = document.querySelector("#supplier option[value='1']");
            if (optionToDisplay) {
                optionToDisplay.selected = true;
                //         var supplierSelect = document.querySelector("#supplier");

                // if (supplierSelect) {
                //     supplierSelect.setAttribute("readonly", true);
                // }
            }
            var optionToDisplay = document.querySelector("#supplier_address option[value='1']");
            if (optionToDisplay) {
                optionToDisplay.selected = true;
            }

        } else if (x == 3) {
            var optionToDisplay = document.querySelector("#stock_location option[value='10']");
            if (optionToDisplay) {
                optionToDisplay.selected = true;
            }
            var optionToDisplay = document.querySelector("#supplier option[value='2']");
            if (optionToDisplay) {
                optionToDisplay.selected = true;
            }
            var optionToDisplay = document.querySelector("#supplier_address option[value='2']");
            if (optionToDisplay) {
                optionToDisplay.selected = true;
            }
        } else if (x == 2) {
            var optionToDisplay = document.querySelector("#stock_location option[value='11']");
            if (optionToDisplay) {
                optionToDisplay.selected = true;
            }
            var optionToDisplay = document.querySelector("#supplier");
            if (optionToDisplay) {
                optionToDisplay.display = block;
            }

            var optionToDisplay = document.querySelector("#supplier_address");
            if (optionToDisplay) {
                optionToDisplay.display = block;
            }


            // if(optionToDisplay==1)
            // var optionToDisplay = document.querySelector("#supplier_address option[value='1'");
            // if (optionToDisplay) {
            //     optionToDisplay.selected = true;
            // }
            // var optionToRAdd = document.querySelector("#stock_location option[value='10']");
            // if (!optionToRAdd) {
            //     // Create a new option element
            //     optionToRAdd = new Option('SNN Trade', '10'); // Replace 'SNN Trade' with the actual text you want

            //     // Append the option element to the select dropdown
            //     var stockLocationDropdown = document.getElementById("stock_location");
            //     stockLocationDropdown.appendChild(optionToRAdd);
            // }
        }



    }
</script>
<script>
    function myFunction1() {
        var y = document.getElementById("supplier").value;
if(y==1)
{
    var optionToDisplay = document.querySelector("#supplier_address option[value='1']");
            if (optionToDisplay) {
                optionToDisplay.selected = true;
            }
}else{
    var optionToDisplay = document.querySelector("#supplier_address option[value='2']");
            if (optionToDisplay) {
                optionToDisplay.selected = true;
            }
}
    }
</script>

@stop