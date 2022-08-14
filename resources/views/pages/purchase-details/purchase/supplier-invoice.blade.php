@extends('layouts.default')
@section('content')

    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
            <div class="az-content-body">

                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/get-purchase-reqisition') }}" style="color: #596881;">PURCHASE
                            DETAILS</a></span>
                    <span><a href="">Supplier Invoice</a></span>
                </div>

                <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                Supplier Invoice</h4>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                        <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                        <div class="form-devider"></div>
                        <form method="POST" id="commentForm"  action="">
                        {{ csrf_field() }}  
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Purchase Order NO</label>
                                <input type="text" value="" class="form-control" name="purchase_order_no" placeholder="Purchase Order Number" >
                            </div><!-- form-group -->

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Supplier Quotation No</label>
                                <input type="number" value="" class="form-control" name="supplier_quotation_no" placeholder="No of label" >
                            </div><!-- form-group -->

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Supplier *</label>
                                <input type="number" value="" class="form-control" name="supplier" id="supplier"  placeholder="Supplier">
                            </div><!-- form-group -->

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Quotation Date *</label>
                                <input type="date" value="{{date('Y-m-d')}}" class="form-control" name="date" id="date"  placeholder="Date">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Purchase Requisition No *</label>
                                <input type="text" value="" class="form-control" name="date" id="date"  placeholder="Purchase requisition No">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Requestor *</label>
                                <input type="text" value="" class="form-control" name="date" id="date"  placeholder="Date">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Department *</label>
                                <input type="text" value="" class="form-control" name="date" id="date"  placeholder="Department">
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label>Remark*</label>
                                    <textarea  class="form-control" id="Remark" name="Itemdescription"
                                        placeholder=""></textarea>
                            </div><!-- form-group -->

                        </div> 
            
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="submit" class="btn btn-primary btn-rounded " style="float: right;">
                                <span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span> 
                                <i class="fas fa-save"></i>
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
