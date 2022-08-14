@extends('layouts.default')
@section('content')

    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
            <div class="az-content-body">

                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/get-purchase-reqisition') }}" style="color: #596881;">PURCHASE
                            DETAILS</a></span>
                    <span><a href="">Lot Allocation</a></span>
                </div>

                <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                Lot Allocation</h4>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                        <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                        <div class="form-devider"></div>
                        <form method="POST" id="commentForm" novalidate="novalidate">
                            {{ csrf_field() }}

                            <div class="row">
                                @foreach ($errors->all() as $errorr)
                                <div class="alert alert-danger "  role="alert" style="width: 100%;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                {{ $errorr }}
                                </div>
                                @endforeach 
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label for="exampleInputEmail1">Lot Number * </label>
                                    <select class="form-control Item-code" name="Itemcode" id="Itemcode">  
                                    </select>
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label for="exampleInputEmail1">Purchase Order Number * </label>
                                    <select class="form-control Item-code" name="Itemcode" id="Itemcode">  
                                    </select>
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label for="exampleInputEmail1">Item Code * </label>
                                    <select class="form-control Item-code" name="Itemcode" id="Itemcode">  
                                    </select>
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label for="exampleInputEmail1">Item Name * </label>
                                    <select class="form-control Item-code" name="Itemname" id="Itemcode">  
                                    </select>
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Item description *</label>
                                    <textarea  class="form-control" id="Itemdescription" name="Itemdescription"
                                        placeholder=""></textarea>
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Quantity in Unit*</label>
                                    <input type="text" value="" class="form-control" name="HSNSAC" id="HSNSAC" placeholder="">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Received Date*</label>
                                    <input type="text" value="" class="form-control" name="HSNSAC" id="HSNSAC" placeholder="">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Supplier *</label>
                                    <select class="form-control Supplier" name="Supplier">
                                    </select>
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Invoice Number*</label>
                                    <input type="text" class="form-control" value="" id="StockQty" name="StockQty" placeholder="">
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Invoice Date *</label>
                                    <input type="text"  class="form-control" value="" id="OpenPOQty" name="OpenPOQty" placeholder="">
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Vehicle Number *</label>
                                    <input type="text" class="form-control" value="" name="" placeholder="">
                                </div>
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label> Name of Transporter*</label>
                                    <input type="text"  class="form-control" value="" id="MinLevel" name="MinLevel" placeholder="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span
                                            class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                            role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                        Save
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
    <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
    <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
    <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
@stop
