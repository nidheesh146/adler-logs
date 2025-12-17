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
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ã—</button>
                    {{Session::get('error')}}
                </div>
                @endif
                @if (Session::get('success'))
                <div class="alert alert-success " style="width: 100%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ã—</button>
                    <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                </div>
                @endif
                @foreach ($errors->all() as $errorr)
                <div class="alert alert-danger "  role="alert" style="width: 100%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ã—</button>
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
                                <input type="text" class="form-control"  id="ref_no" value="" placeholder="Ref Number" readonly>
                                <input type="hidden"  name="ref_no" id="ref_no1" value="">
                            </div> 
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Ref Date *</label>
                                <input type="text" value="{{date('d-m-Y')}}" id="ref_date" class="form-control" readonly>
                                <input type="hidden"  name="ref_date" id="ref_date1" value="">
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
                                <select class="form-control"  onchange="myFunction()" id="transaction_type">
                                    <option>Select one...</option> 
                                    @foreach($transaction_type as $type)
                                    <option value="{{$type['id']}}">{{$type['transaction_name']}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="transaction_type" id="transaction_type1" value="">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Business Category  *</label>
                                <select class="form-control" id="product_category">
                                    <option>Select one...</option>
                                    @foreach($category as $cate)
                                    <option value="{{$cate['id']}}">{{$cate['category_name']}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden"  name="product_category" id="product_category1" value="">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
    <label>Product Category *</label>
    <!-- @if(!empty($grs))
    <input type="text"  class="form-control" name="" value="{{$grs['new_category_name']}}" readonly >
     @else -->
    <select class="form-control" name="new_product_category">
        <!-- <option>..select one..</option> -->
        @foreach($product_category as $category)
        <option value="{{ $category->id }}"
            @if(!empty($grs) && ($grs->new_product_category == $category->id)) selected="selected" @endif>
            {{ $category->category_name }}
        </option>
        @endforeach
    </select>
    @endif
</div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Transaction Condition  *</label>
                                <select class="form-control" name="transaction_condition" id="transaction_condition" onchange="myFunction()">
                                    <!-- <option>Select one...</option> -->
                                    
                                    <option value="1">Returnable</option>
                                    <option value="2">NON Returnable</option>

                                    
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Stock Location(Decrease) *</label>
                                <select class="form-control" name="stock_location_decrease" id="stock_location1" >
                                    <option value="">Select one...</option>
                                    @foreach($data['locations'] as $loc)
                                    @if($loc['location_name']!='Scheme' && $loc['location_name']!='Loaner' && $loc['location_name']!='Replacement' && $loc['location_name']!='Demo' && $loc['location_name']!='Samples' && $loc['location_name']!='MAA (Material Allocation Area)' && $loc['location_name']!='Quarantine' && $loc['location_name']!='Other'     || $loc['location_name'] == 'Satellite'
)
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
                                    <option value="0">N.A</option>
                                </select>
                                <input type="hidden" name="stock_location_increase" id="stock_location_increase" value="">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4" id="sub-division-wrapper" style="display: none;">
    <label for="sub_locations">Sub division</label>
    <select class="form-control" name="sub_locations" id="sub_locations">
        <option value="">Select one...</option>
        @foreach($data['locations'] as $loc)
        <option value="{{ $loc['id'] }}">{{ $loc['location_name'] }}</option>
        @endforeach
    </select>
</div>

                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Remarks</label>
                                <input name="remarks" class="form-control" id="remarks" class="form-control"  >
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
    $(document).ready(function() {
            $('form').submit(function() {
                $(this).find(':submit').prop('disabled', true);
            });
        $("#stock_location2 option[value='15']").hide();
        $("#stock_location2 option[value='16']").hide();
        $("#stock_location2 option[value='17']").hide();
        $("#stock_location2 option[value='18']").hide();
        $("#stock_location2 option[value='21']").hide();
        $("#stock_location2 option[value='22']").hide();

        $("#stock_location1 option[value='15']").hide();
        $("#stock_location1 option[value='16']").hide();
        $("#stock_location1 option[value='17']").hide();
        $("#stock_location1 option[value='18']").hide();
        $("#stock_location1 option[value='22']").hide();
        $("#stock_location1 option[value='21']").hide();
        });
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
                stock_location_decrease: {
                    required: true,
                },
                stock_location_increase: {
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
    $('#stock_location2').on('change',function(e){
        var stock_location2 = $(this).val();
        $('#stock_location_increase').val(stock_location2);
        var loc = document.getElementById("stock_location2").value;
            if (loc == 8) {
                $("#sub_locations option").hide(); // Hide all options first
                $("#sub_locations option[value='22']").show();
                $("#sub_locations option[value='21']").show();
                $("#sub_locations option[value='23']").show();

                // $("#sub_locations option[value='13']").show();
                // $("#sub_locations option[value='14']").show();
            } else if (loc == 20) {
                $("#sub_locations option").hide(); // Hide all options first
                $("#sub_locations option[value='15']").show();
                $("#sub_locations option[value='16']").show();
                $("#sub_locations option[value='17']").show();
                $("#sub_locations option[value='18']").show();
            } else {
                $("#sub_locations").prop("disabled", true);
            }
    });
    $('.oef_number').on('change',function(e){
        let oef_id = $(this).val();
        if(oef_id!=0)
        {
           // $('#product_category option').attr("selected", "");
           // $('#transaction_type option').attr("selected", "");
            $('#ref_no1').val('');
            $('#ref_date1').val('');
            $('#product_category1').val('');
            $('#transaction_type1').val('');
            $('#stock_location2').val('');

            $("#transaction_type").find('option:selected').removeAttr("selected");
            $("#product_category").find('option:selected').removeAttr("selected");
            $("#product_category").removeAttr("disabled");
            $("#transaction_type").removeAttr("disabled");
            $.get("{{ url('fgs/GRS/find-oef-info') }}?id="+oef_id,function(data){
                $('.oef-info-binding').html(data);
                $('.spinner-button').hide();
            });
            $.get("{{ url('fgs/Delivery-challan/oef-info') }}?oef_id="+oef_id,function(info){
                $('#ref_no').val(info.order_number);
                $('#ref_date').val(info.order_date);
                $('#ref_no1').val(info.order_number);
                $('#ref_date1').val(info.order_date);
                $('#product_category1').val(info.product_category);
                $('#transaction_type1').val(info.transaction_type);

                $('#product_category option[value="'+info.product_category+'"]').attr("selected", "selected");
                $('#transaction_type option[value="'+info.transaction_type+'"]').attr("selected", "selected");
                $('#product_category').attr("disabled", "disabled");
                $('#transaction_type').attr("disabled", "disabled");
                $("#stock_location2 option[value='9']").show();
                $("#stock_location2 option[value='12']").show();
                $("#stock_location2 option[value='13']").show();
                $("#stock_location2 option[value='14']").show();
                var x =info.transaction_type;
                if(x == 2)
                {
                    var optionToDisplay = document.querySelector("#stock_location2 option[value='13']");
                    $('#stock_location_increase').val(13);
                    document.getElementById("stock_location2").setAttribute("disabled", "disabled");
                    if (optionToDisplay) {
                        optionToDisplay.selected = true;
                    }
                }
                else if(x == 3)
                {
                    var optionToDisplay = document.querySelector("#stock_location2 option[value='9']");
                    $('#stock_location_increase').val(9);
                    document.getElementById("stock_location2").setAttribute("disabled", "disabled");
                    if (optionToDisplay) {
                        optionToDisplay.selected = true;
                    }
                }
                else if(x == 5)
                {
                    var optionToDisplay = document.querySelector("#stock_location2 option[value='12']");
                    $('#stock_location_increase').val(12);
                    document.getElementById("stock_location2").setAttribute("disabled", "disabled");
                    if (optionToDisplay) {
                        optionToDisplay.selected = true;
                    }
                }
                else if(x == 6)
                {
                    // var optionToDisplay = document.querySelector("#stock_location2 option[value='8']");
                    // $('#stock_location_increase').val(8);
                    // document.getElementById("stock_location2").setAttribute("disabled", "disabled");
                    // if (optionToDisplay) {
                    //     optionToDisplay.selected = true;
                    // }
                    var optionToDisplay = document.querySelector("#stock_location2 option[value='8']");
                    $('#stock_location_increase').val(8);
                    $("#stock_location2 option[value='9']").hide();
                    $("#stock_location2 option[value='12']").hide();
                    $("#stock_location2 option[value='13']").hide();
                    $("#stock_location2 option[value='14']").hide();
                    $("#sub_locations option").hide();
                    $("#sub_locations option[value='22']").show();
                    $("#sub_locations option[value='21']").show();
                    $("#sub_locations option[value='23']").show();
                    $("#sub_locations option").hide();
    $("#sub_locations option[value='21']").show();
    $("#sub_locations option[value='22']").show();
    $("#sub_locations option[value='23']").show();

    // ✅ Show the wrapper for sub division
    $('#sub-division-wrapper').show();  // <--- this line enables it

                    //document.getElementById("stock_location2").setAttribute("disabled", "disabled");
                    if (optionToDisplay) {
                        optionToDisplay.selected = true;
                    }
                }
                else if(x == 7)
                {
                    var optionToDisplay = document.querySelector("#stock_location2 option[value='0']");
                    var optionToDisplay1 = document.querySelector("#transaction_condition option[value='2']");
                    $('#stock_location_increase').val(0);
                    document.getElementById("stock_location2").setAttribute("disabled", "disabled");
                    if (optionToDisplay && optionToDisplay1) {
                        optionToDisplay.selected = true;
                        optionToDisplay1.selected = true;
                    }
                }
                else if(x == 1)
                {
                    var optionToDisplay = document.querySelector("#stock_location2 option[value='']");
                    $('#stock_location_increase').val('');
                    if (optionToDisplay) {
                        optionToDisplay.selected = true;
                    }
                }
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
    }).on('change', function(e) {
            $('#Itemcode-error').remove();
            $("#billing_address").text('');
            $("#shipping_address").text('');
            $('.oef_number option:gt(0)').remove();
            let res = $(this).select2('data')[0];
            if (typeof(res) != "undefined") {
                if (res.dl_expiry_date) 
                {
                    var currentDate = new Date();
                    var year = currentDate.getFullYear();
                    var month = String(currentDate.getMonth() + 1).padStart(2, '0'); // Months are zero-based
                    var day = String(currentDate.getDate()).padStart(2, '0');
                    var formattedDate = `${year}-${month}-${day}`; // today date

                    var dlExpiryDate = res.dl_expiry_date; // diff of the dates
                    var date1 = new Date(formattedDate);
                    var date2 = new Date(dlExpiryDate);
                    var timeDifference = date2 - date1;
                    // Convert the time difference to days and round to the nearest integer
                    var daysDifference = Math.round(timeDifference / (1000 * 60 * 60 * 24));

                    if (daysDifference <= 30 && daysDifference > 0) {
                        alert('This seller DL will expire within ' + daysDifference + ' days..');
                        if (res.billing_address) {
                            $("#billing_address").val(res.billing_address);
                        }
                        if (res.shipping_address) {
                            $("#shipping_address").val(res.shipping_address);
                        }
                        $.get("{{ url('fgs/GRS/find-oef-number-for-grs') }}?customer_id=" + res.id, function(data) {
                            if (data != 0) {
                                $.each(data, function(key, value) {
                                    $(".oef_number").append('<option  value=' + value.id + '>' + value.text + '</option>');
                                });
                            }
                        });
                    } else if (formattedDate == res.dl_expiry_date || daysDifference < 0) {
                        alert('This seller DL expired..');
                    } else {
                        if (res.billing_address) {
                            $("#billing_address").val(res.billing_address);
                        }
                        if (res.shipping_address) {
                            $("#shipping_address").val(res.shipping_address);
                        }
                        $.get("{{ url('fgs/GRS/find-oef-number-for-grs') }}?customer_id=" + res.id, function(data) {
                            if (data != 0) {
                                $.each(data, function(key, value) {
                                    $(".oef_number").append('<option  value=' + value.id + '>' + value.text + '</option>');
                                });
                            }
                        });
                    }
                } else {
                    if (res.billing_address) {
                        $("#billing_address").val(res.billing_address);
                    }
                    if (res.shipping_address) {
                        $("#shipping_address").val(res.shipping_address);
                    }
                    $.get("{{ url('fgs/GRS/find-oef-number-for-grs') }}?customer_id=" + res.id, function(data) {
                        if (data != 0) {
                            $.each(data, function(key, value) {
                                $(".oef_number").append('<option  value=' + value.id + '>' + value.text + '</option>');
                            });
                        }
                    });

                }
            }
        });
    $(".oef_number").on('change',function(e) 
    {
        $oef_id = $(this).val();
    });

  });
$(document).ready(function () {
    $('#stock_location2').on('change', function () {
        let selectedText = $('#stock_location2 option:selected').text().toLowerCase();

        if (selectedText.includes('consignment') || selectedText.includes('satellite')) {
            $('#sub-division-wrapper').show();
        } else {
            $('#sub-division-wrapper').hide();
            $('#sub_locations').val('');
        }
    });
});

  function myFunction() {
    //$("#stock_location2").refresh();
    $("#stock_location2 option[value='9']").show();
        $("#stock_location2 option[value='12']").show();
        $("#stock_location2 option[value='13']").show();
        $("#stock_location2 option[value='14']").show();
    $("#stock_location2").find('option:selected').removeAttr("selected");
    $("#stock_location2").val("");
    $("#stock_location2").removeAttr("disabled");
    var x = document.getElementById("transaction_type").value;
    var y = document.getElementById("transaction_condition").value;
    if (x == 2 && y == 2) {
        var optionToDisplay = document.querySelector("#stock_location2 option[value='0']");
        $('#stock_location_increase').val(0);
        document.getElementById("stock_location2").setAttribute("disabled", "disabled");
        if (optionToDisplay) {
            optionToDisplay.selected = true;
        }
    } 
    else if(x == 2 && y == 1)
    {
        var optionToDisplay = document.querySelector("#stock_location2 option[value='13']");
        $('#stock_location_increase').val(13);
        //document.getElementById("stock_location2").removeAttribute("disabled");
        document.getElementById("stock_location2").setAttribute("disabled", "disabled");
        if (optionToDisplay) {
            optionToDisplay.selected = true;
        }
    }

    if (x == 3 && y == 2) {
        var optionToDisplay = document.querySelector("#stock_location2 option[value='0']");
        $('#stock_location_increase').val(0);
        document.getElementById("stock_location2").setAttribute("disabled", "disabled");
        if (optionToDisplay) {
            optionToDisplay.selected = true;
        }
    } 
    else if(x == 3 && y == 1)
    {
        var optionToDisplay = document.querySelector("#stock_location2 option[value='9']");
        $('#stock_location_increase').val(9);
        //document.getElementById("stock_location2").removeAttribute("disabled");
        document.getElementById("stock_location2").setAttribute("disabled", "disabled");
        if (optionToDisplay) {
            optionToDisplay.selected = true;
        }
    }

    if (x == 4 && y == 2) {
        var optionToDisplay = document.querySelector("#stock_location2 option[value='0']");
        $('#stock_location_increase').val(0);
        document.getElementById("stock_location2").setAttribute("disabled", "disabled");
        if (optionToDisplay) {
            optionToDisplay.selected = true;
        }
    } 
    else if(x == 4 && y == 1)
    {
        var optionToDisplay = document.querySelector("#stock_location2 option[value='14']");
        $('#stock_location_increase').val(14);
       // document.getElementById("stock_location2").removeAttribute("disabled");
       document.getElementById("stock_location2").setAttribute("disabled", "disabled");
        if (optionToDisplay) {
            optionToDisplay.selected = true;
        }
    }

    if (x == 5 && y == 2) {
        var optionToDisplay = document.querySelector("#stock_location2 option[value='0']");
        $('#stock_location_increase').val(0);
        document.getElementById("stock_location2").setAttribute("disabled", "disabled");
        if (optionToDisplay) {
            optionToDisplay.selected = true;
        }
    } 
    else if(x == 5 && y == 1)
    {
        var optionToDisplay = document.querySelector("#stock_location2 option[value='12']");
        $('#stock_location_increase').val(12);
        //document.getElementById("stock_location2").removeAttribute("disabled");
        document.getElementById("stock_location2").setAttribute("disabled", "disabled");
        if (optionToDisplay) {
            optionToDisplay.selected = true;
        }
    }

    if (x == 6 && y == 2) {
        var optionToDisplay = document.querySelector("#stock_location2 option[value='0']");
        $('#stock_location_increase').val(10);
        document.getElementById("stock_location2").setAttribute("disabled", "disabled");
        if (optionToDisplay) {
            optionToDisplay.selected = true;
        }
    } 
    else if(x == 6 && y == 1)
    {
        var optionToDisplay = document.querySelector("#stock_location2 option[value='8']");
        //$('#stock_location_increase').val(8);
        $("#stock_location2 option[value='9']").hide();
        $("#stock_location2 option[value='12']").hide();
        $("#stock_location2 option[value='13']").hide();
        $("#stock_location2 option[value='14']").hide();
        //document.getElementById("stock_location2").setAttribute("disabled", "disabled");
        if (optionToDisplay) {
            optionToDisplay.selected = true;
        }
    }
    else if(x == 7)
    {
        var optionToDisplay = document.querySelector("#stock_location2 option[value='0']");
        var optionToDisplay1 = document.querySelector("#transaction_condition option[value='2']");
        $('#stock_location_increase').val(0);
        document.getElementById("stock_location2").setAttribute("disabled", "disabled");
        if (optionToDisplay && optionToDisplay1) {
            optionToDisplay.selected = true;
            optionToDisplay1.selected = true;
        }
    }
    if (x == 1 && y == 2) {
        var optionToDisplay = document.querySelector("#stock_location2 option[value='0']");
        $('#stock_location_increase').val(0);
        document.getElementById("stock_location2").setAttribute("disabled", "disabled");

        if (optionToDisplay) {
            optionToDisplay.selected = true;
        }
    } 
    else if(x == 1 && y == 1)
    {
        var optionToDisplay1 = document.querySelector("#stock_location2 option[value='0']");
        var optionToDisplay2 = document.querySelector("#stock_location2 option[value='8']");
        var optionToDisplay3 = document.querySelector("#stock_location2 option[value='12']");
        var optionToDisplay4 = document.querySelector("#stock_location2 option[value='14']");
        var optionToDisplay5 = document.querySelector("#stock_location2 option[value='9']");
        var optionToDisplay6 = document.querySelector("#stock_location2 option[value='13']");
        if (optionToDisplay) {
            optionToDisplay1.selected = false;
            optionToDisplay2.selected = false;
            optionToDisplay3.selected = false;
            optionToDisplay4.selected = false;
            optionToDisplay5.selected = false;
            optionToDisplay6.selected = false;
        }
    }

} 
</script>


@stop