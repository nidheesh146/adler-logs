@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;"> Goods Reservation Slip(GRS)</a></span> 
                <!-- <span><a href="" style="color: #596881;">MRN</a></span> -->
                <span><a href="">
                   
                </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            Goods Reservation Slip(GRS)
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
                            @if(!empty($grs))
                            <div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <label>GRS Number *</label>
                                <input type="text"  class="form-control  grs_number" name="grs_number" value="{{$grs['grs_number']}}"placeholder="">
                                <input type="hidden"  class="form-control  grs_id" name="grs_id" value="{{$grs['id']}}">
                            </div><!-- form-group -->
                            @endif
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Customer  *</label>
                                @if(!empty($grs))
                                <input type="text" class="form-control  grs_number" value="{{$grs['firm_name']}}" readonly>
                                @else
                                <select  class="form-control customer" name="customer">
                                </select>
                                @endif
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Customer Biiling Address</label>
                                <textarea name="billing_address" class="form-control" id="billing_address" readonly>@if(!empty($grs)) {{$grs['shipping_address']}} @endif</textarea>
                            </div> 
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Customer Shipping Address</label>
                                <textarea name="shipping_address"  class="form-control" id="shipping_address" readonly>@if(!empty($grs)) {{$grs['billing_address']}} @endif</textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
    <label for="exampleInputEmail1">OEF Number *</label>
    @if(!empty($grs))
        <input type="text" class="form-control" name="" value="{{$grs['oef_number']}}" readonly>
    @else
        <select class="form-control oef_number" name="oef_number" id="oef_number" required>
            <option value="">Select One</option>
            @foreach($data['oef_numbers'] as $oef)
                <option value="{{ $oef->id }}">{{ $oef->oef_number }}</option>
            @endforeach
        </select>
    @endif
</div> 
<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
    <label for="exampleInputEmail1">Business Category *</label>
    @if(!empty($grs))
        <input type="text" class="form-control" name="" value="{{$grs['category_name']}}" readonly>
    @else
        <input type="text" class="form-control" name="product_category" id="business_category" readonly>
    @endif
</div>
<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
    <label for="exampleInputEmail1">Product Category *</label>
    @if(!empty($grs))
        <input type="text" class="form-control" name="" value="{{$grs['new_category_name']}}" readonly>
    @else
        <input type="text" class="form-control" name="new_product_category" id="new_product_category" readonly>
    @endif
</div>
<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
    <label for="stock_location1">Stock Location (Decrease) *</label>
    @if(!empty($grs))
        <input type="text" class="form-control" value="{{ $grs['location_name1'] }}" readonly>
    @else
        <select class="form-control" name="stock_location1" id="stock_location1" onchange="handleLocationChange()">
            <option value="">Select one...</option>
            @foreach($data['locations'] as $loc)
                @if($loc['location_name'] != 'MAA (Material Allocation Area)' && $loc['location_name'] != 'Quarantine' && $loc['location_name'] != 'Loaner' && $loc['location_name'] != 'Other' && $loc['location_name'] != 'Ganga' && $loc['location_name'] != 'Medilink')
                    <option value="{{ $loc['id'] }}">{{ $loc['location_name'] }}</option>
                @endif
            @endforeach
        </select>
    @endif
</div>

<!-- Subdivision field (initially hidden) -->
<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4" id="subdivision-field" style="display:none;">
    <label for="subdivision">Sub Division *</label>
    <select class="form-control" name="subdivision" id="subdivision">
        <!-- Options will be populated dynamically -->
    </select>
</div>

                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
    <label for="stock_location_increase_input">Stock Location (Increase) *</label>
    <input type="text" 
           class="form-control" 
           id="stock_location_increase_input" 
           name="stock_location2" 
           value="" 
           readonly>
</div>


                            <div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <label>GRS Date *</label>
                                <input type="text" class="form-control datepicker grs_date" name="grs_date" value="@if((!empty($grs)) && ($grs['grs_date'])) {{date('d-m-Y', strtotime($grs['grs_date']))}} @else {{date('Y-m-d')}} @endif"placeholder="">
                            </div><!-- form-group -->
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
    $('#oef_number').change(function() {
        var oefNumber = $(this).val();
        if (oefNumber) {
            $.ajax({
                url: "{{ route('fetch.product.category') }}",
                type: "GET",
                data: { oef_number: oefNumber },
                success: function(data) {
                    $('#new_product_category').val(data.new_product_category_name); // Update category

                    // Update stock location input based on transaction_type_name
                    if (data.transaction_type_name && data.transaction_type_name.toLowerCase() !== 'sales') {
                        $('#stock_location_increase_input').val(data.transaction_type_name);
                    } else {
                        $('#stock_location_increase_input').val('MAA (Material Allocation Area)');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            $('#new_product_category').val(''); // Clear the input if no OEF number is selected
        }
    });
});
</script>

<script>
$(document).ready(function() {
    $('#oef_number').change(function() {
        var oefNumber = $(this).val();
        if (oefNumber) {
            $.ajax({
                url: "{{ route('fetch.business.category') }}", // Use the named route
                type: "GET",
                data: { oef_number: oefNumber },
                success: function(data) {
                    $('#business_category').val(data.category_name); // Populate the business category input
                },
                error: function(xhr) {
                    console.error(xhr.responseText); // Log any errors for debugging
                }
            });
        } else {
            $('#business_category').val(''); // Clear the input if no OEF number is selected
        }
    });
});
</script>

<script>
    $(document).ready(function() {
            $('form').submit(function() {
                $(this).find(':submit').prop('disabled', true);
            });
        $("#stock_location2 option[value='15']").hide();
        $("#stock_location2 option[value='16']").hide();
        $("#stock_location2 option[value='17']").hide();
        $("#stock_location2 option[value='18']").hide();
        $("#stock_location2 option[value='19']").hide();
        // $("#stock_location2 option[value='22']").hide();
        // $("#stock_location2 option[value='21']").hide();

        $("#stock_location1 option[value='15']").hide();
        $("#stock_location1 option[value='16']").hide();
        $("#stock_location1 option[value='17']").hide();
        $("#stock_location1 option[value='18']").hide();
        // $("#stock_location1 option[value='22']").hide();
        // $("#stock_location1 option[value='20']").hide();
        // $("#stock_location1 option[value='21']").hide();
        // $("#stock_location1 option[value='23']").hide();

        });
  $(function(){
    'use strict'

    $(".datepicker").datepicker({
    format: " dd-mm-yyyy",
    autoclose:true,
    });
    $(".datepicker").datepicker("setDate", new Date());
  //  .datepicker('update', new Date());
    $('.datepicker').mask('99-99-9999');

    $("#commentForm").validate({
            rules: {
                customer: {
                    required: true,
                },
                oef_number: {
                    required: true,
                },
                supplier_doc_date: {
                    required: true,
                },
               
                stock_location1: {
                    required: true,
                },
                stock_location2: {
                    required: true,
                },
                grs_date: {
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
                processResults: function(data) {
                    return {
                        results: data
                    };
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

    });

 
 
</script>
<script>
    function handleLocationChange() {
        const locationId = document.getElementById('stock_location1').value;
        const subdivisionField = document.getElementById('subdivision-field');
        const subdivisionSelect = document.getElementById('subdivision');

        // Clear previous options
        subdivisionSelect.innerHTML = '';

        // Show or hide subdivision field based on location
        if (locationId === '8') {
            // Consignment
            subdivisionField.style.display = 'block';
            const options = [
                { value: '21', text: 'Medilink' },
                { value: '22', text: 'Ganga' },
                { value: '23', text: 'Other' }
            ];
            options.forEach(opt => {
                const option = document.createElement('option');
                option.value = opt.value;
                option.textContent = opt.text;
                subdivisionSelect.appendChild(option);
            });
        } else if (locationId === '20') {
            // Satellite
            subdivisionField.style.display = 'block';
            const options = [
                { value: '15', text: 'North' },
                { value: '16', text: 'East' },
                { value: '17', text: 'West' },
                { value: '18', text: 'South' }
            ];
            options.forEach(opt => {
                const option = document.createElement('option');
                option.value = opt.value;
                option.textContent = opt.text;
                subdivisionSelect.appendChild(option);
            });
        } else {
            subdivisionField.style.display = 'none';
        }
    }
</script>


@stop