@extends('layouts.default')

@section('content')
<style>
.autosize{
  resize: none;
  overflow: hidden;
  min-height: 220px;
}
</style>
<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">
            <div class="az-content-breadcrumb"> 
                <span><a href="{{ url('inventory/suppliers-list') }}" style="color: #596881;">Customer - Supplier Master</a></span> 
                <span><a href=""> Price Master </a></span>
            </div>
            <h4 class="az-content-title" style="font-size: 20px; margin-bottom: 18px !important;"> Price Master</h4>
            
            @foreach ($errors->all() as $errorr)
                <div class="alert alert-danger" role="alert" style="width: 100%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ $errorr }}
                </div>
            @endforeach
            
            @if (Session::get('success'))
                <div class="alert alert-success" style="width: 100%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                </div>
            @endif
            
            @if (Session::get('error'))
                <div class="alert alert-danger" style="width: 100%;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fa fa-check"></i> {{ Session::get('error') }}
                </div>
            @endif
            
            <div class="row">  
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <form method="POST" id="commentForm" autocomplete="off" novalidate="novalidate">
                        {{ csrf_field() }}  
                        <div class="form-devider"></div>
                        <div class="row">
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
    <label>Product SKU Code *</label>

    <select class="form-control Product" name="product" id="product">
        <!-- Directly display product_id from priceData -->
        <option value="{{ $priceData->product_id }}" selected>{{ $priceData->sku_code }}</option>
    </select>
</div>

<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
    <label>Description</label>
    <textarea type="text" name="description" id="description" class="form-control" placeholder="Description" readonly>{{ !empty($priceData->discription) ? $priceData->discription : "" }}</textarea>
</div>

<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
    <label>Product Group *</label>
    <input type="text" name="hsn_code" id="product_group" class="form-control" value="{{ !empty($priceData->group_name) ? $priceData->group_name : "" }}" placeholder="Product Group" readonly>
</div>

<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
    <label>HSN Code *</label>
    <input type="text" name="hsn_code" id="hsn_code" class="form-control" value="{{ !empty($priceData->hsn_code) ? $priceData->hsn_code : "" }}" placeholder="HSN Code" readonly>
</div>

<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
    <label>Purchase Price</label>
    <input type="text" name="purchase_price" class="form-control" value="{{ !empty($priceData->purchase) ? $priceData->purchase : "" }}" placeholder="Purchase Price">
</div> 

<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
    <label>Sales Price</label>
    <input type="text" name="sales_price" class="form-control" value="{{ !empty($priceData->sales) ? $priceData->sales : "" }}" placeholder="Sales Price">
</div>

<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
    <label>Transfer Price</label>
    <input type="text" name="transfer_price" class="form-control" value="{{ !empty($priceData->transfer) ? $priceData->transfer : "" }}" placeholder="Transfer Price">
</div>

<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
    <label>MRP</label>
    <input type="text" name="mrp" class="form-control" value="{{ !empty($priceData->mrp) ? $priceData->mrp : "" }}" placeholder="MRP">
</div>

<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
    <label>With Effective From</label>
    <input type="date" name="with_effective_from" class="form-control" value="{{ !empty($priceData->with_effective_from) ? $priceData->with_effective_from : "" }}" placeholder="Select Date">
</div>

<div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
    <label>With Effective To</label>
    <input type="date" name="with_effective_to" class="form-control" value="{{ !empty($priceData->with_effective_to) ? $priceData->with_effective_to : "" }}" placeholder="Select Date">
</div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Status</label>
                                <select name="status_type" class="form-control">
                                    <option value="">-- Select one ---</option>
                                    <option value="1" @if(!empty($data) && $data['status_type'] == '1') selected @endif>Active</option>
                                    <option value="0" @if(!empty($data) && $data['status_type'] == '0') selected @endif>Inactive</option>
                                </select>
                            </div> 
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded" style="float: right;">
                                    <span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span>
                                    <i class="fas fa-save"></i> Update
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

<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>

<script>
$('#product').select2({
    placeholder: 'Choose one',
    searchInputPlaceholder: 'Search',
    minimumInputLength: 4,
    allowClear: true,
    ajax: {
        url: "{{ url('fgs/productsearch') }}",
        processResults: function(data) {
            return { results: data };
        }
    }
}).on('change', function(e) {
    $('.spinner-button').show();
    let res = $(this).select2('data')[0];
    if(res){
        $('#description').text(res.discription)
        $('#product_group').val(res.group_name)
        $('#hsn_code').val(res.hsn_code)
    }
});

$("#commentForm").validate({
    rules: {
        product: {
            required: true,
        },
        mrp: {
            required: true,
        },
        purchase_price: {
            required: true,
        },
        sales_price: {
            required: true,
        },
        transfer_price: {
            required: true,
        },
    },
    submitHandler: function(form) {
        form.submit();
    }
});
</script>

@stop
