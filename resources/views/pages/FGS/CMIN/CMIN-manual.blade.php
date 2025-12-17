@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">

            <div class="az-content-breadcrumb">
                <span><a href="" style="color: #596881;">CMIN(CMIN)</a></span>
                <span><a href="" style="color: #596881;">CMIN Item</a></span>
            </div>

            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">CMIN</h4>
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

        <!-- Row 1 -->
        <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4" >
                            <label>MIN number *</label>
                            @if(!empty($edit['min']))
                            <input type="hidden" name="min_number" value="{{$edit['min']->min_number}}">

                            @endif
                            <select class="form-control min_number" name="min_number" @if(!empty($edit['min'])) disabled @endif>
                                <!-- <option value="" ></option> -->
                                @if(!empty($edit['min']))
                                    <option value="{{$edit['min']->min_number}}" selected>{{$edit['min']->min_number}}</option>
                                @endif
                            </select>
                        </div>

        <!-- <div class="col-md-4">
            <label> Date</label>
            <input type="text" class="form-control" name="dc_date" id="dc_date" value="{{ old('dc_date') }}" readonly>
        </div> -->

         <div class="col-md-4">
            <label>Business Category</label>
            <input type="text" class="form-control" name="product_category" id="product_category" value="{{ !empty($deliveryChallan) ? $deliveryChallan->product_category : '' }}" >
            <input type="hidden" name="product_category_hidden" id="product_category_hidden" value="{{ !empty($deliveryChallan) ? $deliveryChallan->product_category : '' }}">
        </div>

         <div class="col-md-4">
            <label>Product Category</label>
            <input type="text" class="form-control" name="new_product_category" id="new_product_category" value="{{ !empty($deliveryChallan) ? $deliveryChallan->new_product_category : '' }}" >
            <input type="hidden" name="new_product_category_hidden" id="new_product_category_hidden" value="{{ !empty($deliveryChallan) ? $deliveryChallan->new_product_category : '' }}" >
        </div>

        <!-- <div class="col-md-4">
            <label for="customer_id1">Customer</label>
            <input type="text" class="form-control" name="customer_id" id="customer_id1" value="{{ !empty($customerData) && isset($customerData[0]['cust_id']) ? $customerData[0]['cust_id'] : '' }}" readonly>
            <input type="hidden" name="customer_id_hidden" id="customer_id1_hidden" value="{{ !empty($customerData) && isset($customerData[0]['cust_id']) ? $customerData[0]['cust_id'] : '' }}" readonly>
        </div> -->

        <div class="col-md-4">
            <label for="stock_location">Stock Location Increase</label>
            <select name="stock_location_increase" id="stock_location" class="form-control">
                <option value="">Select Stock Location</option>
                @foreach($locations as $location)
                <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Row 3 -->
        <div class="col-md-4">
            <label for="cmin_date">CMIN Date</label>
            <input type="date" class="form-control" name="cmin_date" placeholder="CMIN Date">
        </div>

        <div class="col-md-4">
            <label for="remarks">Remarks</label>
            <input type="text" class="form-control" name="remarks" placeholder="Remarks (Optional)">
        </div>

        <div class="col-md-4">
            <label for="exampleInputEmail1">CMIN Items *</label>
            <input type="file" required class="form-control file" name="cmin_file" id="file">
            <a href="{{ asset('uploads/cmin_items_manual_entry.xlsx') }}" target="_blank" style="float: right; font-size: 10px;"> Download Template</a>
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
    $(document).ready(function () {
        // Initialize Select2 for min_number
        $('.min_number').select2({
            placeholder: 'Choose one',
            searchInputPlaceholder: 'Search',
            minimumInputLength: 2,
            allowClear: true,
            ajax: {
                url: "{{ url('fgs/CMIN/find-min-number-for-cmin') }}",
                processResults: function (data) {
                    return { results: data };
                }
            }
        }).on('change', function (e) {
            let res = $(this).select2('data')[0];
            if (typeof res !== "undefined") {
                $.get("<?= url('your-route-to-fetch-categories-CMIN'); ?>?min_number=" + res.id, function (data) {
                    $('#product_category').val(data.product_category.category_name);
                    $('#product_category_hidden').val(data.product_category.id);
                    $('#new_product_category').val(data.new_product_category.category_name);
                    $('#new_product_category_hidden').val(data.new_product_category.id);
                });
            }
        });

        // Copy visible values to hidden inputs before form submit
        $('form').on('submit', function () {
            $('#product_category_hidden').val($('#product_category').val());
            $('#new_product_category_hidden').val($('#new_product_category').val());
            $(this).find(':submit').prop('disabled', true); // Optional: prevent double submit
        });
    });
</script>




@stop