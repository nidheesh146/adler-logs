@extends('layouts.default')
@section('content')
@inject('fn', 'App\Http\Controllers\Web\PurchaseDetails\PurchaseController')
    <div class="az-content az-content-dashboard">
        <br>
        <div class="container" data-select2-id="9">
            <div class="az-content-body" data-select2-id="8">
                <div class="az-content-breadcrumb">
                    <span><a href="">Supplier - Invoice</a></span>
                    <span>Supplier Invoice Item Split</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">
                Supplier Invoice Item Split
                </h4>
                <div class="data-bindings">
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                            <i class="fas fa-hand-point-right"></i>
                            Supplier Invoice Info
                                </label>
                            <div class="form-devider"></div>
                        </div>
                    </div>
                    <table class="table table-bordered mg-b-0">    
                        <tbody>
                            <tr>
                                
                            </tr>
                            <tr>
                                <th>Invoice Number</th>
                                <th>{{$item->invoice_number}}</th>
                                <th>Supplier</th>
                                <th>{{$item->vendor_name}}</th>
                            </tr>
                            <tr>
                                <th>Invoice Date</th>
                                <th>{{date('d-m-Y',strtotime($item->invoice_date)) }}</th>
                                <th>Transaction Date</th>
                                <th>{{date('d-m-Y',strtotime($item->transaction_date)) }}</th>
                            </tr>
                        </tbody> 
                    </table>
                   
                    <br/>
                    <div class="form-devider"></div>
                    
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                            <i class="fas fa-hand-point-right"></i>
                                Invoice Item Details
                                </label>
                            <div class="form-devider"></div>
                        </div>
                    </div>
                    <table class="table table-bordered mg-b-0">     
                        <tbody>
                            <tr>
                                <th>ItemCode</th>
                                <th>{{$item->item_code}}</th>
                                <th>Description</th>
                                <th>{{$item->discription}}</th>
                            </tr>
                            <tr>
                                <th>HSNCode</th>
                                <th>{{$item->hsn_code}}</th>
                                <th>Quantity</th>
                                <th>{{$item->invoice_qty}} {{$item->unit_name}}</th>
                            </tr>
                        </tbody>
                    </table><br>
                    
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                            <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                            Split Invoice Item
                           </label>
                            <div class="form-devider"></div>
                            <form autocomplete="off"  id="form1" method="POST">
                            {{ csrf_field() }}   
                                <div class="row">
                                    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                        <label>Actual Invoice Quantity</label>
                                        <div class="input-group mb-6">
                                            <input type="text" class="form-control" name="invoice_qty"  value="{{$item->invoice_qty}}"  aria-describedby="unit-div2" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text unit-div" id="unit-div">{{$item->unit_name}}</span>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                        <label>Quantity to be Split</label>
                                        <div class="input-group mb-6">
                                            <input type="text" class="form-control" name="split_qty"  value="" required  aria-describedby="unit-div2">
                                            <div class="input-group-append">
                                                <span class="input-group-text unit-div" id="unit-div">{{$item->unit_name}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    {{--<input type="hidden" class="form-control" name="invoice_item_id"  value="{{$item->invoice_item_id}}">--}}
                                    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                        <button type="submit" class="btn btn-primary btn-rounded submitbtn" style="margin-top:28px;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"role="status" aria-hidden="true"></span>  <i class="fas fa-save"></i>
                                            Save 
                                        </button>
                                    </div>
                                </div>                        
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- az-content-body -->
    </div>

    <script src="<?=url('');?>/js/azia.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js">  </script>
    <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
    <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
    <script src="<?= url('') ?>/js/jquery.validate.js"></script>
    <script src="<?= url('') ?>/js/additional-methods.js"></script>

@stop
