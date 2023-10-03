@extends('layouts.default')
@section('content')

<div class="az-content az-content-dashboard">
    <br>
    <div class="container">
        <div class="az-content-body">

            <div class="az-content-breadcrumb">
                <span><a href="" style="color: #596881;">
                        Material Receip Note(MRN)</a></span>
                <span><a href="" style="color: #596881;">
                        MRN Item</a></span>
            </div>

            <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                MRN Item
            </h4>
            <div class="az-dashboard-nav">
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                    <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                    <form method="post" id="commentForm" novalidate="novalidate" action="{{ url('fgs/MRN-item-update') }}">
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

                                    <tr id="row1" rel="1">
                                        <td>
                                            <div class="row">
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label for="exampleInputEmail1">Product code * </label>
                                                    <input type="text" readonly class="form-control" name="prdctcode" id="hsncode1" value="{{$item_details->sku_code}}">
                                                    <input type="hidden" value="{{$id}}" name="mrn_item_id" id="mrn_item_id">
                                                    <input type="hidden" value="{{$item_details->product_id}}" name="product_id" id="product_id">
                                                    <!--input type="hidden" value="{{$item_details->batchcard_id}}" name="batchcard_id" id="batchcard_id"-->

                                                </div>
                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                                    <label>HSN Code * </label>
                                                    <input type="text" readonly class="form-control" name="hsncode" id="hsncode1" value="{{$item_details->hsn_code}}">
                                                </div><!-- form-group -->
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label>Description * </label>
                                                    <textarea type="text" readonly class="form-control" id="Itemdescription1" name="Description" placeholder="Description">{{$item_details->discription}}</textarea>
                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label for="exampleInputEmail1">Batch No* </label>
                                                    <select class="form-control batch_no" name="batch_no" id="batch_no">
                                                        <option>..select one..</option>
                                                        @foreach($batchcards as $batchcard)
                                                        <option value="{{$batchcard['batch_id']}}" qty="{{$batchcard['quantity']}}" @if($item_details->batch_no==$batchcard['batch_no']) selected="selected" @endif>{{$batchcard['batch_no']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label>Quantity * <span id="stock-span"></span></label>
                                                    <input type="number" class="form-control" name="stock_qty" id="stock_qty1" value="{{$item_details->quantity}}">
                                                </div>

                                                <div class="form-group col-sm-12 col-md-2 col-lg-2 col-xl-2" style="float:left;">
                                                    <label>UOM </label>
                                                    <input type="text" class="form-control" readonly name="uom" id="uom1" value="Nos">
                                                </div>
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label>Date of Mfg. * </label>
                                                    
                                                    <input type="text" class="form-control datepicker manufacturing_date" name="manufacturing_date" id="manufacturing_date" value="{{date('d-m-Y', strtotime($item_details->manufacturing_date))}}" onchange="myFunction()">
                                                    <input type="hidden" value="{{$item_details->is_sterile}}" id="sterile">
                                                </div>
                                                @if($item_details->is_sterile==0)
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label>Date of Expiry * </label>
                                                    @php $date= date('Y-m-d', strtotime('+5 years')) @endphp
                                                    <input type="text" class="form-control datepicker expiry_date" readonly name="expiry_date" value="N.A"  placeholder="Date of Expiry">
                                                
                                                    </div>
                                                @else
                                                <div class="form-group col-sm-12 col-md-3 col-lg-3 col-xl-3" style="float:left;">
                                                    <label>Date of Expiry * </label>
                                                    @php $date= date('Y-m-d', strtotime('+5 years')) @endphp
                                                    <input type="text" class="form-control datepicker expiry_date"  name="expiry_date" id="expiry_date" value="{{date('d-m-Y', strtotime($item_details->expiry_date))}}" id="expiry" placeholder="Date of Expiry">
                                                </div>
                                                @endif
                                                <!-- <button type="button" name="add" id="add" class="btn btn-success"
                                                    style="height:38px;margin-top:28px;"><i
                                                        class="fas fa-plus"></i></button> -->
                                            </div>
                                        </td>
                                    </tr>


                                </tbody>

                            </table>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                        {{ request()->item ? '' : 'Update' }}
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
<script src="<?=url('');?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>



<script>
    $(document).ready(function() {
        $('form').submit(function() {
            $(this).find(':submit').prop('disabled', true);
        });
    });
    $(".manufacturing_date").datepicker({
        format: " dd-mm-yyyy",
        autoclose: true,
        endDate: new Date()
    });
    $('.batch_no').on('change', function (){
        var element = $("option:selected", this); 
        var batchqty = element.attr("qty"); 
        $('#stock_qty1').val(batchqty);
        //alert(batchqty);
    });
    $("#manufacturing_date").datepicker({
                    format: " dd-mm-yyyy",
                    autoclose: true,
                    "setDate": new Date(),
                    onSelect: function(date) {
                        $(this).change();
                    },
                }).on("change", function() {
                    var date = $("#manufacturing_date").datepicker('getDate');
                    var new_date = new Date(date);
                    var myDate  = new Date(new_date.setDate(new_date.getDate()-1));
                    var date = new Date(myDate.setFullYear(myDate.getFullYear() + 5));
                    var expiry_date = ( ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '-' + ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '-' + date.getFullYear());
                    // new_date.setFullYear(new_date.getFullYear() + 5);
                    // new_date.setDate(new_date.getDate() - 2);
                    // var expiry_date = ( ((new_date.getDate() > 9) ? new_date.getDate() : ('0' + new_date.getDate())) + '-' + ((new_date.getMonth() > 8) ? (new_date.getMonth() + 1) : ('0' + (new_date.getMonth() + 1))) + '-' + new_date.getFullYear());
                    //alert(new_date);
                    $("#expiry_date").val('');
                    $("#expiry_date").val(expiry_date);
                });

    $(function() {
        $("#commentForm").validate({
            rules: {
                Itemcode: {
                    required: true,
                },
                ActualorderQty: {
                    required: true,
                    number: true
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
@stop