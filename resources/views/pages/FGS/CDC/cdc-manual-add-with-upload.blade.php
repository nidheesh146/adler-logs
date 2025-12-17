@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">

            <div class="az-content-breadcrumb">
                <span><a href="" style="color: #596881;">CDC(CDC)</a></span>
                <span><a href="" style="color: #596881;">MRN Item</a></span>
            </div>

            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">CDC</h4>
            <div class="az-dashboard-nav"></div>

            <div class="row">
                <div class="col-12" style="border: 0px solid rgba(28, 39, 60, 0.12);">
                <form method="POST" id="commentForm" autocomplete="off" enctype='multipart/form-data'>
    {{ csrf_field() }}

    <div class="row">
        <!-- Display error messages -->
        @if(Session::get('error'))
        <div class="alert alert-danger" role="alert" style="width: 100%;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ Session::get('error') }}
        </div>
        @endif

        <!-- Display success messages -->
        @if(Session::get('success'))
        <div class="alert alert-success" style="width: 100%;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <i class="icon fa fa-check"></i> {{ Session::get('success') }}
        </div>
        @endif

        <!-- Display validation errors -->
        @foreach ($errors->all() as $errorr)
        <div class="alert alert-danger" role="alert" style="width: 100%;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ $errorr }}
        </div>
        @endforeach
        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
    <label for="exampleInputEmail1">Customer *</label>
    <select class="form-control customer" name="customer"></select>
</div>

<div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
    <label for="exampleInputEmail1">Customer Billing Address</label>
    <textarea name="billing_address" class="form-control" id="billing_address" readonly></textarea>
</div>

        <!-- Row 1 -->
        <div class="col-md-4">
            <label>DC Number *</label>
            <select class="form-control dc_number" name="dc_number" id="dc_number">
                
            </select>
        </div>

        <div class="col-md-4">
            <label>DC Date</label>
            <input type="text" class="form-control" name="dc_date" id="dc_date" value="{{ old('dc_date') ?: date('d-m-Y') }}" >
        </div>

        <div class="col-md-4">
            <label>Business Category</label>
            <input type="text" class="form-control" name="product_category" id="product_category" value="{{ !empty($deliveryChallan) ? $deliveryChallan->product_category : 'OBM' }}" >
            <input type="hidden" name="product_category_hidden" id="product_category_hidden" value="{{ !empty($deliveryChallan) ? $deliveryChallan->product_category : 'OBM' }}">
        </div>

        <!-- Row 2 -->
        <div class="col-md-4">
            <label>Product Category</label>
            <input type="text" class="form-control" name="new_product_category" id="new_product_category" value="{{ !empty($deliveryChallan) ? $deliveryChallan->new_product_category : 'ASD' }}" >
            <input type="hidden" name="new_product_category_hidden" id="new_product_category_hidden" value="{{ !empty($deliveryChallan) ? $deliveryChallan->new_product_category : 'ASD' }}" readonly>
        </div>

        

        <div class="col-md-4">
            <label for="stock_location">Stock Location(Increase)</label>
            <select name="stock_location_increase" id="stock_location" class="form-control">
                <option value="">Select Stock Location</option>
                @foreach($locations as $location)
                <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
    <label for="cdc_date">CDC Date</label>
    <input type="date" class="form-control" name="cdc_date" id="cdc_date" placeholder="CDC Date">
</div>

        <div class="col-md-4">
            <label for="remarks">Remarks</label>
            <input type="text" class="form-control" name="remarks" placeholder="Remarks (Optional)">
        </div>

        <div class="col-md-4">
            <label for="exampleInputEmail1">CDC Items *</label>
            <input type="file" required class="form-control file" name="cdc_file" id="file">
            <a href="{{ asset('uploads/cdc_items_manual_entry.xlsx') }}" target="_blank" style="float: right; font-size: 10px;"> Download Template</a>
        </div>
        <div class="col-md-4">
            <label for="customer_id1"></label>
            <input type="hidden" class="form-control" name="customer_id" id="customer_id1" value="{{ !empty($customerData) && isset($customerData[0]['cust_id']) ? $customerData[0]['cust_id'] : '' }}" readonly>
            <input type="hidden" name="customer_id_hidden" id="customer_id1_hidden" value="{{ !empty($customerData) && isset($customerData[0]['cust_id']) ? $customerData[0]['cust_id'] : '' }}" readonly>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="row">
        <div class="form-group col-12">
            <button type="submit" class="btn btn-primary btn-rounded" style="float: right;">
                <span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span>
                <i class="fas fa-save"></i> {{ request()->item ? 'Update' : 'Save' }}
            </button>
        </div>
    </div>
</form>


                </div>
            </div>
        </div>
    </div>

    <!-- az-content-body -->
</div>
<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script src="<?= url('') ?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for customer
        $(".customer").select2({
            placeholder: 'Choose one',
            searchInputPlaceholder: 'Search',
            minimumInputLength: 2,
            allowClear: true,
            ajax: {
                url: "{{ url('fgs/customersearch') }}",
                processResults: function (data) {
                    return { results: data };
                }
            }
        }).on('change', function(e) {
            let res = $(this).select2('data')[0]; // Get selected customer data
            if (typeof res !== "undefined") {
                $.get("<?=url('your-route-to-fetch-categories');?>?customer=" + res.id, function(data) {
                    // Debugging: Check the response in console
                    console.log(data);

                    // Autofill the fields with the customer data
                    if (data.success) {
                        $('#billing_address').val(data.billing_address); // Autofill the billing address
                    }
                });
            }
        });

        // Initialize Select2 for dc_number
        $('.dc_number').select2({
            placeholder: 'Choose one',
            searchInputPlaceholder: 'Search',
            minimumInputLength: 2,
            allowClear: true,
            ajax: {
                url: "{{ url('fgs/CDC/find-dc-number-for-manualcdc') }}",
                data: function (params) {
                    // Get customer ID from the customer select
                    var customerId = $(".customer").val(); 
                    return {
                        q: params.term, // Search query (DC number)
                        customer_id: customerId // Add customer ID as a parameter
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    }; // Populate dropdown with returned data
                }
            }
        }).on('change', function(e) {
            let res = $(this).select2('data')[0];
            if (typeof res !== "undefined") {
                $.get("<?=url('your-route-to-fetch-categories-dc');?>?dc_number=" + res.id, function(data) {
                    $('#product_category').val(data.product_category.category_name);
                    $('#product_category_hidden').val(data.product_category.id);
                    $('#new_product_category').val(data.new_product_category.category_name);
                    $('#new_product_category_hidden').val(data.new_product_category.id);

                    $('#customer_id1').val(data.customer_id.firm_name);
                    $('#customer_id1_hidden').val(data.customer_id.id);

                    $('#dc_date').val(data.doc_date);
                });
            }
        });
    });
    window.onload = function() {
        let today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
        document.getElementById('cdc_date').value = today;
    };
</script>




@stop