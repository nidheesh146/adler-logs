@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;"> Delivery Challan</a></span> 
                <!-- <span><a href="" style="color: #596881;">MRN</a></span> -->
                <span><a href="">
                   
                </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            Delivery Challan
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
                   
                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="POST" id="commentForm" autocomplete="off" >
               

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
                                <textarea name="billing_address" class="form-control" id="zone" readonly></textarea>
                            </div> 
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">OEF Number *</label>
                                <select class="form-control oef_number" name="oef_number">
                                <option value="">Select One</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Ref Number *</label>
                                <input type="text" class="form-control" name="ref_no" value="" placeholder="Ref Number">

                            </div> 
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Ref Date *</label>
                                <input type="text" value="{{date('d-m-Y')}}" class="form-control datepicker" name="ref_date">
                            </div> 
                            {{--<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Doc Number *</label>
                                <input type="text" class="form-control" name="doc_no" value="" placeholder="Doc Number">
                            </div> --}}
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Doc Date *</label>
                                <input type="text"  class="form-control datepicker" name="doc_date" value="{{date('d-m-Y')}}">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Transaction Type  *</label>
                                <select class="form-control" name="transaction_type">
                                    <option>Select one...</option> 
                                    @foreach($transaction_type as $type)
                                    <option value="{{$type['id']}}">{{$type['transaction_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Product Category  *</label>
                                <select class="form-control" name="product_category">
                                    <option>Select one...</option>
                                    @foreach($category as $cate)
                                    <option value="{{$cate['id']}}">{{$cate['category_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Stock Location(Decrease) *</label>
                                <select class="form-control" name="stock_location1" id="stock_location1" >
                                    <option value="">Select one...</option>
                                    @foreach($data['locations'] as $loc)
                                    @if($loc['location_name']!='Consignment' && $loc['location_name']!='Loaner' && $loc['location_name']!='Replacement' && $loc['location_name']!='Demo' && $loc['location_name']!='Samples' && $loc['location_name']!='MAA (Material Allocation Area)' && $loc['location_name']!='Quarantine')
                                    <option value="{{$loc['id']}}">{{$loc['location_name']}}</option>
                                    @endif 
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Stock Location(Increase) *</label>
                                <select class="form-control" name="stock_location2" id="stock_location2">
                                     <option value="">Select one...</option> 
                                     @foreach($data['locations'] as $loc)
                                    @if($loc['id']!="1" && $loc['id']!="2" && $loc['id']!="3" && $loc['id']!="4" && $loc['id']!='5' && $loc['id']!='6' && $loc['id']!='7' && $loc['id']!='10' && $loc['id']!='11')
                                    <option value="{{$loc['id']}}">{{$loc['location_name']}}</option>
                                    @endif 
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Transaction Condition  *</label>
                                <select class="form-control" name="transaction_condition">
                                    <!-- <option>Select one...</option> -->
                                    
                                    <option value="1">Returnable</option>
                                    <option value="2">NON Returnable</option>

                                    
                                </select>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit"  class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    Save & Next
                                
                                </button>
                            </div>
                        </div>
                        <div class="form-devider"></div>
                    </form>

                </div>
            </div>
            <div class="oef-info-binding">

            </div>
        </div>
    
	</div>
	<!-- az-content-body -->
</div>




<script src="<?= url('') ?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>

<script>
  $(function(){
    'use strict'

    $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true,
    });
    //$(".datepicker").datepicker("setDate", new Date());
  //  .datepicker('update', new Date());
    //$('.datepicker').mask('99-99-9999');

    $("#commentForm").validate({
            rules: {
                customer: {
                    required: true,
                },
                ref_no: {
                    required: true,
                },
                oef_number: {
                    required: true,
                },
                ref_date: {
                    required: true,
                },
                product_category: {
                    required: true,
                },
                stock_location1: {
                    required: true,
                },
                stock_location2: {
                    required: true,
                },
                
                // doc_date: {
                //     required: true,
                // },
                transaction_type: {
                    required: true,
                },
                transaction_condition: {
                    required: true,
                },
                
                
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit();
            }
        });
    //     $('.oef_number').select2({
    //       placeholder: 'Choose one',
    //       searchInputPlaceholder: 'Search',
    //       minimumInputLength: 2,
    //       allowClear: true,
    //       ajax: {
    //       url: "{{ url('fgs/GRS/find-oef-number-for-grs') }}",
    //       processResults: function (data) {
    //         return { results: data };

    //       }
    //     }
    //   }).on('change', function (e) {
    //     $('.spinner-button').show();

    //     let res = $(this).select2('data')[0];
    //     if(res){
    //       $.get("{{ url('fgs/GRS/find-oef-info') }}?id="+res.id,function(data){
    //         $('.oef-info-binding').html(data);
    //         $('.spinner-button').hide();
    //       });
    //     }else{
    //       $('.oef-info-binding').html('');
    //       $('.spinner-button').hide();
    //     }
    //   });
    $('.oef_number').on('change',function(e){
        let oef_id = $(this).val();
        if(oef_id!=0)
        {
            $.get("{{ url('fgs/GRS/find-oef-info') }}?id="+oef_id,function(data){
                $('.oef-info-binding').html(data);
                $('.spinner-button').hide();
            });
        }
    });

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
    }).on('change', function (e) {
        $('#Itemcode-error').remove();
        $("#billing_address").text('');
        $("#zone").text('');
        $('.oef_number option:gt(0)').remove();
        let res = $(this).select2('data')[0];
        if(typeof(res) != "undefined" )
        {
            if(res.billing_address){
                $("#billing_address").val(res.billing_address);
            }
            if(res.shipping_address){
                $("#zone").val(res.zone_name);
            }
            $.get("{{ url('fgs/GRS/find-oef-number-for-grs') }}?customer_id="+res.id,function(data){
                if(data!=0)
                {
                    $.each(data,function(key, value)
                    {
                      $(".oef_number").append('<option  value=' + value.id + '>' + value.text + '</option>');
                    });
                }
            });
        }
    });

  });
 
</script>


@stop