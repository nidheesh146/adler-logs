@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
  <br>
	<div class="container">
		<div class="az-content-body">

            <div class="az-content-breadcrumb"> 
                <span><a href="" style="color: #596881;">Tax Invoice Cum Delivery Note(DNI)</a></span> 
                <!-- <span><a href="" style="color: #596881;">MRN</a></span> -->
                <span><a href="">
                   
                </a></span>
            </div>
	
            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
            Tax Invoice Cum Delivery Note(DNI)
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
                    <form  method="post" autocomplete="off" >
               

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
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <label for="exampleInputEmail1">Customer  *</label>
                                <select  class="form-control customer" name="customer">
                                </select>
                            </div> 
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <label for="exampleInputEmail1">Customer Biiling Address</label>
                                <textarea name="billing_address" class="form-control" id="billing_address" readonly></textarea>
                            </div> 
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <label for="exampleInputEmail1">Customer Shipping Address</label>
                                <textarea name="shipping_address"  class="form-control" id="shipping_address" readonly></textarea>
                            </div> 
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
        <label for="charges">Other Charges *</label>
        <input type="text" value="" class="form-control charges" id="charges" name="charges" placeholder=""disabled>
    </div> 
                            <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <label>DNI Date *</label>
                                <input type="date" value="{{date('Y-m-d')}}" class="form-control dni_date" id="dni_date"  name="dni_date"  placeholder="">
                            </div>
                        </div> 
                        <div class="form-devider"></div>
                        <div class="row">
                            <div class="invoice-heading" style="display:none;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;margin-left:12px;">
                                <i class="fas fa-address-card"></i> Add Tax Invoice Cum Delivery Note  
                            </label>
                            </div>
                            <div class="form-devider"></div>
                            
                            <div class="table-responsive" id="pitable">
                                <!-- <table class="table table-bordered mg-b-0" id="example1">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th style="width:120px;">GRS Number</th>
                                            <th>OEF Number</th>
                                            <th>Product category</th>
                                            <th>STOCK LOCATION1(DECREASE)</th>
                                            <th>STOCK LOCATION2(INCREASE)</th>
                                            <th>GRS Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">

                                        
                                    </tbody>
                                </table> -->
                               
                            </div>
                            <div class="form-devider"></div>
                        </div>                      
                        <br/>
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded sbmit-btn" style="float: right;display:none;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                    role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
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
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>

<script>
  
  $(document).ready(function () {
    $('form').submit(function () {
        // Enable the 'other_charges' field before submission
        $('#charges').prop('disabled', false);
        $(this).find(':submit').prop('disabled', true);
    });

    // Initialize Select2 for customer selection
    $(".customer").select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 4,
        allowClear: true,
        ajax: {
            url: "{{ url('fgs/domestic_customersearch') }}",
            processResults: function (data) {
                return { results: data };
            }
        }
    }).on('change', function (e) {
        // Clear previous error messages and data
        $('#Itemcode-error').remove();
        $("#billing_address").text('');
        $("#shipping_address").text('');
        $('#pitable').empty();
        $('.invoice-heading').hide();

        let res = $(this).select2('data')[0];
        if (typeof res !== "undefined") {
            if (res.zone_name !== "Export") {
                if (res.dl_expiry_date) {
                    var currentDate = new Date();
                    var year = currentDate.getFullYear();
                    var month = String(currentDate.getMonth() + 1).padStart(2, '0');
                    var day = String(currentDate.getDate()).padStart(2, '0');
                    var formattedDate = `${year}-${month}-${day}`;

                    var dlExpiryDate = res.dl_expiry_date;
                    var date1 = new Date(formattedDate);
                    var date2 = new Date(dlExpiryDate);
                    var timeDifference = date2 - date1;
                    var daysDifference = Math.round(timeDifference / (1000 * 60 * 60 * 24));

                    if (daysDifference <= 30 && daysDifference > 0) {
                        alert('This seller DL will expire within ' + daysDifference + ' days.');
                    } else if (formattedDate === res.dl_expiry_date || daysDifference < 0) {
                        alert('This seller DL has expired.');
                    }

                    // Set billing and shipping addresses if available
                    if (res.billing_address) {
                        $("#billing_address").val(res.billing_address);
                    }
                    if (res.shipping_address) {
                        $("#shipping_address").val(res.shipping_address);
                    }

                    // Fetch PI details
                    $.get("{{ url('fgs/DNI/fetchPI') }}?customer_id=" + res.id, function (data) {
    if (data !== 0) {
        $('.invoice-heading').show();
        $('#pitable').append(data);
        $('.sbmit-btn').show();

        var allAWM = true; // Assume all are AWM initially

        $('#table-body tr').each(function () {
            var categoryName = $(this).find('td').eq(5).text().trim(); // Get category name
            if (categoryName !== "AWM") {
                allAWM = false; // If any row is not AWM, set this to false
            }
        });

        // Enable or disable the #charges field based on the allAWM flag
        if (allAWM) {
            $('#charges').prop('disabled', false).prop('required', true); // Enable and require if all are AWM
        } else {
            $('#charges').prop('disabled', true); // Disable if not all are AWM
        }
    }
});

                }
            } else {
                alert('This is not a domestic customer.');
                $('.customer').val('').trigger('change');
                $("#billing_address").html('');
                $("#shipping_address").html('');
            }
        }
    });
});

</script>
<script>
    $(document).ready(function() {
        $('form').submit(function() {
            $(this).find(':submit').prop('disabled', true);
        });
    });
</script>

@stop