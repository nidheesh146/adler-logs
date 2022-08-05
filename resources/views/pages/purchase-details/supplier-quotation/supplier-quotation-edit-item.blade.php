@extends('layouts.default')
@section('content')

    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
            <div class="az-content-body">

                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('inventory/supplier-quotation') }}" style="color: #596881;">SUPPLIER QUOTATION
                    </a></span>
                    <!-- <span><a href="{{ url('inventory/get-purchase-reqisition') }}" style="color: #596881;">EDIT SUPPLIER QUOTATION ITEM </a></span> -->
                    <!-- <span><a href="">{{ request()->item ? 'Edit' : 'Add' }} Supplier quotation item</a></span> -->
                    <span><a> Edit Supplier Quotation Item</a></span>
                </div>

                <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                    <!-- {{ request()->item ? 'Edit' : 'Add' }}  Supplier quotation item</h4> -->
                    Edit Supplier Quotation Item for  ( {{request()->name}} ) 
                </h4>
                <!-- <div class="az-dashboard-nav">
                    <nav class="nav">
                        <a class="nav-link" href="{{ url('inventory/edit-purchase-reqisition?pr_id=' . request()->pr_id) }}">Supplier Quotation</a>
                        <a class="nav-link  active" @if (request()->pr_id) href="{{ url('inventory/get-purchase-reqisition-item?pr_id=' . request()->pr_id) }}" @endif>Edit Supplier Quotation item </a>
                        <a class="nav-link  " href=""> </a>
                    </nav>

                </div> -->

                <div class="row">

                    <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 "
                        style="border: 0px solid rgba(28, 39, 60, 0.12);">



                        <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                        <form method="POST" id="commentForm" novalidate="novalidate">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                        <i class="fas fa-address-card"></i> Item details </label>
                                    <div class="form-devider"></div>
                                </div>
                            </div>


                            <div class="row">

                                <table class="table table-bordered mg-b-0">
                    
                                    <tbody>
                                      <tr>
                                        <th scope="row">Code</th>
                                      <td>{{(!empty($data['response']['supplier_quotation'])) ? $data['response']['supplier_quotation'][0]['purchase_reqisition_approval']['purchase_reqisition_list'][0]['item_code']['item_code'] : '-' }}</td>
                                        <th scope="row">Discount Value</th>
                                        <td>{{(!empty($data['response']['supplier_quotation'])) ? $data['response']['supplier_quotation'][0]['purchase_reqisition_approval']['purchase_reqisition_list'][0]['discount_value'] : '-' }}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">Name</th>
                                        <td>{{(!empty($data['response']['supplier_quotation'])) ? $data['response']['supplier_quotation'][0]['purchase_reqisition_approval']['purchase_reqisition_list'][0]['item_code']['item_name'] : '-' }}</td>
                                        <th scope="row">GST</th>
                                        <td>{{(!empty($data['response']['supplier_quotation'])) ? $data['response']['supplier_quotation'][0]['purchase_reqisition_approval']['purchase_reqisition_list'][0]['gst'] : '-' }}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">HSN code</th>
                                        <td>{{(!empty($data['response']['supplier_quotation'])) ? $data['response']['supplier_quotation'][0]['purchase_reqisition_approval']['purchase_reqisition_list'][0]['item_code']['hsn_code'] : '-' }}</td>
                                        <th scope="row">Unit</th>
                                        <td>{{(!empty($data['response']['supplier_quotation'])) ? $data['response']['supplier_quotation'][0]['purchase_reqisition_approval']['purchase_reqisition_list'][0]['item_code']['receipt_unit']['unit_name'] : '-' }}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">Basic Value</th>
                                        <td>{{(!empty($data['response']['supplier_quotation'])) ? $data['response']['supplier_quotation'][0]['purchase_reqisition_approval']['purchase_reqisition_list'][0]['basic_value'] : '-' }}</td>
                                        <th scope="row">Item description </th>
                                        <td>{{(!empty($data['response']['supplier_quotation'])) ? $data['response']['supplier_quotation'][0]['purchase_reqisition_approval']['purchase_reqisition_list'][0]['discount_percent'] : '-' }}</td>
                                      </tr>
                                      <tr>
                                        <th scope="row">Discount %</th>
                                        <td>{{(!empty($data['response']['supplier_quotation'])) ? $data['response']['supplier_quotation'][0]['purchase_reqisition_approval']['purchase_reqisition_list'][0]['remarks'] : '-' }}</td>
                                        <th scope="row">Requested QTY</th>
                                        <td>{{(!empty($data['response']['supplier_quotation'])) ? $data['response']['supplier_quotation'][0]['purchase_reqisition_approval']['purchase_reqisition_list'][0]['remarks'] : '-' }}</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                                <br>
                                  <div class="row">
                                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                            <i class="fas fa-address-card"></i> Supplier required details </label>
                                        <div class="form-devider"></div>
                                    </div>
                                </div>
                                <div class="row">   
                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                  <label>Quantity *</label>
                                <input type="text" name="quantity" value="{{(!empty($data['response']['supplier_quotation'])) ? $data['response']['supplier_quotation'][0]['quantity'] : ''}}" class="form-control" placeholder="Quantity">
                                  <input type="hidden" name="supplier_quotation" value="{{(!empty($data['response']['supplier_quotation'])) ? $data['response']['supplier_quotation'][0]['id'] : ''}}" class="form-control" placeholder="Quantity">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label> Rate *</label>
                                    <input type="text" class="form-control" value="{{(!empty($data['response']['supplier_quotation'])) ? $data['response']['supplier_quotation'][0]['supplier_rate'] : ''}}" name="rate" id="rate" placeholder="Rate">
                                </div>
                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label> Discount *</label>
                                    <input type="text" class="form-control" value="{{(!empty($data['response']['supplier_quotation'])) ? $data['response']['supplier_quotation'][0]['supplier_discount'] : ''}}" name="discount" id="discount" placeholder="Discount">
                                </div>
                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label> Specification *</label>
                                    <textarea  class="form-control"  id="Specification" name="Specification" placeholder="Specification">{{(!empty($data['response']['supplier_quotation'])) ? $data['response']['supplier_quotation'][0]['specifications'] : ''}}</textarea>
                                </div>
                                
                            </div>                           

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
                                      role="status" aria-hidden="true"></span> <i
                                            class="fas fa-save"></i>
                                            {{ request()->item ? 'Update' : 'Save' }}
                                            </button>
                                </div>
                            </div>
                         
                        </form>
                        <div class="form-devider"></div>
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
    <script>
      $(function(){

    
        
        $("#commentForm").validate({
                rules: {
                    quantity: {
                        required: true,
                    },
                    rate: {
                        required: true,
                    },
                    discount: {
                      required: true,
                    },
                    Specification: {
                        required: true,
                    },
                },
                submitHandler: function(form) {
                $('.spinner-button').show();
                      form.submit();
                    
            }
            });

                
      });
    </script>


@stop




{{-- Stock Qty *
Open PO Qty *
Actual order Qty * 


Basic Value  - man

Net value = (Basic Value  - Discount (% to int))  +  gst




--}}