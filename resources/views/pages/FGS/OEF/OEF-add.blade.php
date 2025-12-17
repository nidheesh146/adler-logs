@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">

            <div class="az-content-breadcrumb">
                <span><a href="" style="color: #596881;"> Order Execution Form(OEF)</a></span>
                <!-- <span><a href="" style="color: #596881;">MRN</a></span> -->
                <span><a href="">

                    </a></span>
            </div>

            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                Order Execution Form(OEF)
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
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Customer *</label>
                                <select class="form-control customer" name="customer">
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Customer Biiling Address</label>
                                <textarea name="billing_address" class="form-control" id="billing_address" ></textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Customer Shipping Address</label>
                                <textarea name="shipping_address" class="form-control" id="shipping_address" ></textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Order Number *</label>
                                <input type="text" class="form-control" name="order_number" value="" placeholder="Order Number">
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Order Date *</label>
                                <input type="date" value="{{date('Y-m-d')}}" class="form-control datepicker" name="order_date" value="" placeholder="">
                            </div>

                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Order Fulfil *</label>
                                <select class="form-control" name="order_fulfil">
                                    <!-- <option>Select one...</option> -->
                                    @foreach($order_fulfil as $type)
                                    <option value="{{$type['id']}}">{{$type['order_fulfil_type']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Transaction Type *</label>
                                <select class="form-control" name="transaction_type">
                                    <!-- <option>Select one...</option> -->
                                    @foreach($transaction_type as $type)
                                    <option value="{{$type['id']}}">{{$type['transaction_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                <label for="exampleInputEmail1">Business Category *</label>
                                <select class="form-control" name="product_category">
                                    <!-- <option value="">Select one...</option> -->
                                    @foreach($category as $cate)
                                    <option value="{{$cate['id']}}">{{$cate['category_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
    <label>Product Category *</label>
    <select class="form-control" name="new_product_category">
        <!-- <option>..select one..</option> -->
        @foreach($product_category as $category)
        <option value="{{ $category->id }}"
            @if(!empty($grs) && ($grs->new_product_category == $category->id)) selected="selected" @endif>
            {{ $category->category_name }}
        </option>
        @endforeach
    </select>
</div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <label>OEF Date *</label>
                                <input type="date" value="{{date('Y-m-d')}}" class="form-control oef_date" id="oef_date" name="oef_date" placeholder="">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                <label>Due Date *</label>
                                @php $date= date('Y-m-d', strtotime('+30 days')) @endphp
                                <input type="text" value="{{date('d-m-Y', strtotime($date))}}" class="form-control due_date" id="due_date" name="due_date" placeholder="">
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-8 col-xl-8">
                                <label for="exampleInputEmail1">Remarks </label>
                                <textarea type="text" class="form-control" name="remarks" value="" placeholder=""></textarea>
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
        // Disable submit button initially
        $(':submit').prop('disabled', true);

        // Disable submit button when form is submitted
        $('form').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission
            $(this).find(':submit').prop('disabled', true);

            // Check if form is valid
            if ($(this).valid()) {
                $('.spinner-button').show();
                $(this).off('submit').submit(); // Submit the form if valid
            } else {
                $('.spinner-button').hide();
                $(this).find(':submit').prop('disabled', false);
            }
        });

        // Update due_date based on oef_date
        $('.oef_date').on('change', function() {
            var oef_date = new Date($(this).val());
            var date = new Date(oef_date.setDate(oef_date.getDate() + 30));
            var aftr_30_days = (((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '-' + ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '-' + date.getFullYear());
            $('#due_date').val(aftr_30_days);
        });

        // Form validation
        $("#commentForm").validate({
            rules: {
                customer: { required: true },
                order_number: { required: true },
                order_date: { required: true },
                oef_date: { required: true },
                due_date: { required: true },
                order_fulfil: { required: true },
                transaction_type: { required: true },
                product_category: { required: true }
            },
            errorPlacement: function(error, element) {
                // Custom error placement
                error.insertAfter(element);
            },
            submitHandler: function(form) {
                $('.spinner-button').show();
                form.submit(); // Submit the form if valid
            },
            invalidHandler: function(event, validator) {
                // Errors are displayed only when clicking Save
                $('.spinner-button').hide();
                $(':submit').prop('disabled', false);
            }
        });

        // Enable/Disable submit button based on form validity
        $("#commentForm").on('keyup change', function() {
            if ($(this).valid()) {
                $(':submit').prop('disabled', false);
            } else {
                $(':submit').prop('disabled', true);
            }
        });

        // Initialize select2 for customer search
        $(".customer").select2({
            placeholder: 'Choose one',
            searchInputPlaceholder: 'Search',
            minimumInputLength: 4,
            allowClear: true,
            ajax: {
                url: "{{ url('fgs/customersearch') }}",
                processResults: function(data) {
                    return { results: data };
                }
            }
        }).on('change', function(e) {
            $('#Itemcode-error').remove();
            $("#billing_address").text('');
            $("#shipping_address").text('');
            let res = $(this).select2('data')[0];
            if (typeof(res) != "undefined") {
                if (res.dl_expiry_date) {
                    var currentDate = new Date();
                    var year = currentDate.getFullYear();
                    var month = String(currentDate.getMonth() + 1).padStart(2, '0');
                    var day = String(currentDate.getDate()).padStart(2, '0');
                    var formattedDate = `${year}-${month}-${day}`; // today date

                    var dlExpiryDate = res.dl_expiry_date;
                    var date1 = new Date(formattedDate);
                    var date2 = new Date(dlExpiryDate);
                    var timeDifference = date2 - date1;
                    var daysDifference = Math.round(timeDifference / (1000 * 60 * 60 * 24));
                    if (daysDifference <= 30 && daysDifference > 0) {
                        alert('This seller DL will expire within ' + daysDifference + ' days..');
                        $("#billing_address").val(res.billing_address || '').prop("readonly", ![93, 95, 96, 112].includes(res.id));
                        $("#shipping_address").val(res.shipping_address || '').prop("readonly", ![93, 95, 96, 112].includes(res.id));
                    } else if (formattedDate == res.dl_expiry_date || daysDifference < 0) {
                        alert('This seller DL expired..');
                        $("#billing_address").val(res.billing_address || '').prop("readonly", false);
                        $("#shipping_address").val(res.shipping_address || '').prop("readonly", false);
                    } else {
                        $("#billing_address").val(res.billing_address || '').prop("readonly", ![93, 95, 96, 112].includes(res.id));
                        $("#shipping_address").val(res.shipping_address || '').prop("readonly", ![93, 95, 96, 112].includes(res.id));
                    }
                }
            }
        });
    });
</script>

@stop