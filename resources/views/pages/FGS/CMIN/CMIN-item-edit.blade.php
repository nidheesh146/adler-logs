@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">

            <div class="az-content-breadcrumb">
                <span><a href="" style="color: #596881;">
                        CANCELLATION MATERIAL ISSUE NOTE(CMIN)</a></span>
                <span><a href="" style="color: #596881;">
                        CMIN Item</a></span>
            </div>

            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                CMIN Item
            </h4>
            <div class="az-dashboard-nav">
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="post" id="commentForm" novalidate="novalidate" action="{{ url('fgs/CMIN/edit-item/' . $item_details->id) }}">

                    {{ csrf_field() }}
                        <div class="row">

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
                            <table class="table table-bordered">
                                <tbody id="dynamic_field">
                                    <input type="hidden" id="cmin_id" value="{{$item_details->cmin_id}}" name="cmin_id">
                                    <tr id="row1" rel="1">
                                        <td>

                                            <div class="row">
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label for="exampleInputEmail1">Product code * </label>
                                                    <input type="text" readonly class="form-control" name="prdctcode" id="prdctcode" value="{{$item_details->sku_code}}">
                                                    <input type="hidden" id="min_item_id" value="{{$item_details->id}}" name="min_item_id">
                                                    <input type="hidden" value="{{$item_details->product_id}}" name="product_id" id="product_id">
                                                    <!--input type="hidden" value="{{$item_details->batchcard_id}}" name="batchcard_id" id="batchcard_id"-->
                                                </div>
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                                    <label>HSN Code * </label>
                                                    <input type="text" readonly class="form-control" name="hsncode" value="{{$item_details->hsn_code}}" id="hsncode1" placeholder="HSN Code">
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label>Description * </label>
                                                    <textarea type="text" readonly class="form-control" id="Itemdescription1" name="Description" placeholder="Description">{{$item_details->discription}}</textarea>
                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label for="exampleInputEmail1">Batch No * </label>
                                                    <select class="form-control batch_no" name="batch_no" id="batch_no">
                                                        <option>..select one..</option>
                                                        @foreach($batchcards as $batchcard)
                                                        <option value="{{$batchcard['batch_id']}}" qty="{{$batchcard['quantity']}}" sterile="{{$batchcard['is_sterile']}}" @if($item_details->batch_no==$batchcard['batch_no']) selected="selected" @endif manufacturingDate="{{$batchcard['manufacturing_date']}}" expiryDate="{{$batchcard['expiry_date']}}">{{$batchcard['batch_no']}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label for="exampleInputEmail1">Current Stock quantity * </label>
                                                    <input type="text" readonly class="form-control" name="stk_qty" id="stk_qty" value="{{$item_details->stk_qty}}">
                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label>
                                                        {{--<span style="color: red;font-size: 10px;">Quantity should be less than stock qty</span><br>--}}
                                                        Quantity * </label>
                                                    <input type="number" class="form-control min_item_qty" name="quantity" id="quantity" placeholder="Quantity" max="{{$item_details->stk_qty}}" min="0" index="1" value="{{$item_details->quantity}}" readonly>
                                                    <span id="error1" style="color:red;"></span>
                                                </div>

                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                                    <label>UOM </label>
                                                    <input type="text" class="form-control" readonly name="uom" id="uom1" placeholder="Nos">
                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label>Date of Mfg. * </label>
                                                    <input type="text" class="form-control datepicker manufacturing_date" name="manufacturing_date" value="{{date('d-m-Y', strtotime($item_details->manufacturing_date))}}" id="manufacturing_date" placeholder="Date of Mfg.">
                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label>Date of Expiry * </label>

                                                    <input type="text" class="form-control datepicker expiry_date" name="expiry_date1" value="@if($item_details->expiry_date=='0000-00-00') NA @else{{date('d-m-Y', strtotime($item_details->expiry_date))}} @endif" id="expiry_date1" placeholder="Date of Expiry" >
                                                </div>
                                                <!-- <button type="button" name="add" id="add" class="btn btn-success"
                                                    style="height:38px;margin-top:28px;"><i
                                                        class="fas fa-plus"></i></button> -->
                                            </div>
                                        </td>
                                    </tr>


                                </tbody>

                            </table>
                            {{--<div class=" col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="button" name="add" id="add" class="btn btn-success btn-xs" style="height:38px;float:right;margin-right:19px;">
                                    <i class="fas fa-plus"></i></button>
                                </div>
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <label for="exampleInputEmail1">Remarks  *</label>
                                    <textarea type="text" class="form-control" name="remarks" value="" placeholder=""></textarea>
                                </div>--}}
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                    {{ request()->item ? 'Update' : 'Save' }}
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
<script src="<?= url('') ?>/lib/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= url('') ?>/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
<script src="<?= url('') ?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"> </script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>


<script>
    $(document).ready(function() {
        $('form').submit(function() {
            $(this).find(':submit').prop('disabled', true);
        });
    });
    $(".datepicker").datepicker({
            format: "dd-mm-yyyy",
            // viewMode: "months",
            // minViewMode: "months",
            // startDate: date,
            autoclose: true
        });
    $('.batch_no').on('change', function() {
        var element = $("option:selected", this);
        var batchqty = element.attr("qty");
        var manufacturing_date = element.attr("manufacturingDate"); 
        var expiry_date = element.attr("expiryDate");
        var sterile = element.attr("sterile");
        $('#quantity').attr('max', batchqty);
        $('#stk_qty').val(batchqty);
        $("#manufacturing_date").val(manufacturing_date);
        if (sterile == 0) {
            // $("#is_sterile" + select_id + "").val(0);
            $("#expiry_date" + select_id + "").val('N.A');

        } else {

            // $("#is_sterile" + select_id + "").val(1);
            $(".expiry_date" + select_id + "").datepicker();
            $("#expiry_date" + select_id + "").datepicker({
                format: " dd-mm-yyyy",
                autoclose: true
            });
            var date = new Date();
            date.setFullYear(date.getFullYear() + 5);
            date.setDate(date.getDate() - 2);
            $("#expiry_date" + select_id + "").datepicker("setDate", date);
        }
    });
    $(document).on('change', '.min_item_qty', function(e) {
        //alert('kk');
        $("#error1").text('');
        var val = parseInt($(this).val());
        var max = parseInt($(this).attr("max"));
        if (val > max)
            $("#error1").text('Please enter a value less than or equal to ' + max);
        else
            $("#error1").text('');
    });
    
    $(document).ready(function() {
        var expiryDateElement = $("#expiry_date1");
        var initialExpiryDate = expiryDateElement.val();

        $("#manufacturing_date").datepicker({
            format: " dd-mm-yyyy",
            autoclose: true,
            endDate: new Date()
        }).on("change", function() {

            if (expiryDateElement) {
                if (initialExpiryDate.trim() === 'NA') {
                    $("#expiry_date1").val('N.A').prop("readonly", true);
                } else {
                    var date = $("#manufacturing_date").datepicker('getDate');
                    var newDate = new Date(date);
                    newDate.setFullYear(newDate.getFullYear() + 5);
                    newDate.setDate(newDate.getDate() - 2);
                    var formattedDate = ('0' + newDate.getDate()).slice(-2) + '-' + ('0' + (newDate.getMonth() + 1)).slice(-2) + '-' + newDate.getFullYear();
                    $("#expiry_date1").val(formattedDate);
                }
            }
        });
    });
</script>
@stop