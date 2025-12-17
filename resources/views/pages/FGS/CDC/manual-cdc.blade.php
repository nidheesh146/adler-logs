@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br> 
    <div class="container">
        <div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">Manual Cancellation Delivery Challan(CDC)</a></span> 
                <!-- <span><a href="" style="color: #596881;">MRN</a></span> -->
                <span><a href="">
                   
                </a></span>
            </div>
    
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            Manual Cancellation Delivery Challan(CDC)
            </h4>
            <div class="az-dashboard-nav">
           
            </div>

            <div class="row">
                    
                <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                @if(Session::get('error'))
                <div class="alert alert-danger "  role="alert" style="width: 100%;">
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
                <div class="alert alert-danger "  role="alert" style="width: 100%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ $errorr }}
                </div>
             @endforeach    
            <form method="POST" id="commentForm" autocomplete="off"  action="{{url('fgs/manual-CDC/insert')}}">
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
                                <label for="exampleInputEmail1">Customer  *</label>
                                <select  class="form-control customer" name="customer">
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Customer Biiling Address</label>
                                <textarea name="billing_address" class="form-control" id="billing_address" readonly></textarea>
                            </div> 
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Zone</label>
                                <textarea name="zone" class="form-control" id="zone" readonly></textarea>
                            </div> 
                            <!-- <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">OEF Number *</label>
                                <select class="form-control oef_number" name="oef_number">
                                <option value="">Select One</option>
                                </select>
                            </div> -->
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Ref Number *</label>
                                <input type="text" class="form-control"  name="ref_no" value="" placeholder="Ref Number" >
                            </div> 
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Ref Date *</label>
                                <input type="text" value="{{date('d-m-Y')}}" name="ref_date" class="form-control" >
                                
                            </div> 
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">CDC Date *</label>
                                <input type="text"  class="form-control datepicker" name="cdc_date" value="{{date('d-m-Y')}}">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Transaction Type  *</label>
                                <select class="form-control"  onchange="myFunction()" id="transaction_type" name="transaction_type">
                                    <option>Select one...</option> 
                                    @foreach($transaction_type as $type)
                                    <option value="{{$type['id']}}">{{$type['transaction_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Product Category  *</label>
                                <select class="form-control" id="product_category"  name="product_category">
                                    <option>Select one...</option>
                                    @foreach($category as $cate)
                                    <option value="{{$cate['id']}}">{{$cate['category_name']}}</option>
                                    @endforeach
                                </select>
                               
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Transaction Condition  *</label>
                                <select class="form-control" name="transaction_condition" id="transaction_condition" onchange="myFunction()">
                                    <!-- <option>Select one...</option> -->
                                    
                                    <option value="1" selected>Returnable</option>
                                    <!-- <option value="2">NON Returnable</option> -->

                                    
                                </select>
                            </div>
                           {{-- <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Stock Location(Decrease) *</label>
                                <select class="form-control" name="stock_location_decrease" id="stock_location1" >
                                    <option value="">Select one...</option>
                                    @foreach($data['locations'] as $loc)
                                    @if($loc['location_name']!='Consignment' && $loc['location_name']!='Loaner' && $loc['location_name']!='Replacement' && $loc['location_name']!='Demo' && $loc['location_name']!='Samples' && $loc['location_name']!='MAA (Material Allocation Area)' && $loc['location_name']!='Quarantine')
                                    <option value="{{$loc['id']}}">{{$loc['location_name']}}</option>
                                    @endif 
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Stock Location(Increase) *</label>
                                <select class="form-control" name="stock_location_increase" id="stock_location2">
                                     <option value="">Select one...</option> 
                                     @foreach($data['locations'] as $loc)
                                     @if($loc['id']=="1" || $loc['id']=="2" || $loc['id']=="3" || $loc['id']=="6" || $loc['id']=='7' || $loc['id']=='10' || $loc['id']=='11')
                                     <option value="{{$loc['id']}}">{{$loc['location_name']}}</option>
                                    @endif 
                                    @endforeach
                                    <option value="0">N.A</option>
                                </select>
                               
                            </div>
                            {{--<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Sub division</label>
                                <select class="form-control" name="sub_locations" id="sub_locations">
                                    <option value="">Select one...</option>
                                    @foreach($data['locations'] as $loc)
                                    <option value="{{$loc['id']}}">{{$loc['location_name']}}</option>
                                    @endforeach
                                </select>
                            </div>--}}
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Remarks</label>
                                <input name="remarks" class="form-control" id="remarks" class="form-control"  >
                            </div> 
                               
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
        <!-- az-content-body -->
    </div>

    <script src="<?=url('');?>/js/azia.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
    <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
    <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
    <script src="<?= url('') ?>/js/jquery.validate.js"></script>
    <script src="<?= url('') ?>/js/additional-methods.js"></script>
    <script>
        $(document).ready(function() {
            $('form').submit(function() {
                $(this).find(':submit').prop('disabled', true);
            });
        });
      $(function(){
        'use strict'

        $("#commentForm").validate({
        rules: {
            miq_number: {
                required: true,
            },
            mac_date:{
                required: true,
            },
            created_by:{
                required: true,
            }

        },
        submitHandler: function(form) {
            $('.spinner-button').show();
            form.submit();
        }
    });


    $('.user_list').select2({
          placeholder: 'Choose one',
          searchInputPlaceholder: 'Search',

      });


      });

    $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true,
    endDate: new Date()
    });
    $('.datepicker').mask('99-99-9999');
    $(".customer").select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 4,
        allowClear: true,
        ajax: {
            url: "{{ url('fgs/customersearch') }}",
            processResults: function (data) {
                return { results: data };
            }
        }
    }).on('change', function(e) {
       // alert('kk');
        $("#billing_address").text('');
        $("#shipping_address").text('');
        let res = $(this).select2('data')[0];
        if ((res)) {
            $("#billing_address").val(res.billing_address).prop("readonly", true);
            $("#zone").val(res.zone_name).prop("readonly", true);
        }
    });



    
    </script>


@stop
