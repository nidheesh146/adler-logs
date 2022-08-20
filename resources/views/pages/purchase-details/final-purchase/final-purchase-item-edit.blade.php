@extends('layouts.default')
@section('content')

    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
          <div class="az-content-body">

              <div class="az-content-breadcrumb">
              <span><a href="{{url('')}}">SUPPLIER QUOTATION </a></span>
                   <span> <a href="{{url('')}}">Supplier Quotation Items</a></span>
                    <span><a> Edit Supplier Quotation Item</a></span>
              </div>

              <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                  <!-- Add  Supplier quotation item</h4> -->
                  Edit Supplier Quotation Item for  ( {{$data['vendor_id']}} - {{$data['vendor_name']}} ) 
              </h4>
              <!-- <div class="az-dashboard-nav">
                  <nav class="nav">
                      <a class="nav-link" href="http://localhost/adler/public/inventory/edit-purchase-reqisition?pr_id=">Supplier Quotation</a>
                      <a class="nav-link  active" >Edit Supplier Quotation item </a>
                      <a class="nav-link  " href=""> </a>
                  </nav>

              </div> -->

              <div class="row">

                  <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">


                      <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                      @if (Session::get('success'))
                      <div class="alert alert-success " style="width: 100%;">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                          <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                      </div>
                      @endif
                      @foreach ($errors->all() as $errorr)
                      <div class="alert alert-danger "  role="alert" style="width: 100%;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ $errorr }}
                      </div>
                      @endforeach
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
                                      <th scope="row">Item Code</th>
                                      <td>{{$data['item_code']}}</td>
                                      <th scope="row">HSN code</th>
                                      <td>{{$data['hsn_code']}}</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Purchase requisition No</th>
                                      <td>{{$data['pr_no']}}</td>
                                      <th scope="row">Requested QTY</th>
                                      <td>{{$data['actual_order_qty']}} {{$data['unit_name']}}</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Requested Dept</th>
                                      <td>{{$data['dept_name']}}</td>
                                      <th scope="row">Purchase Requisition Date</th>
                                      <td>{{date('d-m-Y',strtotime($data['requisition_date']))}}</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">PR/SR</th>
                                      <td>{{$data['PR_SR']}}</td>
                                      <th scope="row">Item description </th>
                                      <td>{{$data['discription']}}</td>
                                    </tr>
                                   
                                  </tbody>
                                </table>
                              </div>
                              <br>

                              <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                        <i class="fas fa-address-card"></i> Supplier Quotation Details </label>
                                    <div class="form-devider"></div>
                                </div>
                              </div>
                              <div class="row">
                                <table class="table table-bordered mg-b-0">
                                  <tbody>
                                    <tr>
                                      <th scope="row">Quotation No</th>
                                      <td>{{$data['rq_no']}}</td>
                                      <th scope="row">Quotation Date</th>
                                      <td>{{date('d-m-Y',strtotime($data['quotation_date']))}}</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Quotation Commited Date</th>
                                      <td>{{date('d-m-Y',strtotime($data['commited_delivery_date']))}}</td>
                                      <th scope="row">Supplier</th>
                                      <td>{{$data['vendor_id']}}-{{$data['vendor_name']}}</td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                              <br>
                              
                              <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                  <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                    <i class="fas fa-address-card"></i> Purchase Order Details </label>
                                    <div class="form-devider"></div>
                                </div>
                              </div>
                            <form method="POST" id="commentForm" novalidate="novalidate">
                            {{ csrf_field() }}
                              <div class="row">   
                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                  <label>Quantity *</label>
                                <input type="text" name="quantity" value="{{ (!empty($data)) ? $data['order_qty'] : ''}}" class="form-control" placeholder="Quantity">
                              
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label> Rate *</label>
                                    <input type="text" class="form-control" value="{{ (!empty($data)) ? $data['rate'] : ''}}" name="rate" id="rate" placeholder="Rate">
                                </div>
                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label> Discount (%) *</label>
                                    <input type="text" class="form-control" value="{{ (!empty($data)) ? $data['discount'] : ''}}" name="discount" id="discount" placeholder="Discount">
                                </div>
                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label> Delivery Schedule *</label>
                                    <input type="date" class="form-control" value="{{ (!empty($data)) ? $data['delivery_schedule'] : ''}}" name="delivery_schedule" id="delivery_schedule" placeholder="Delivery schedule">
                                </div>
                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label> Specification *</label>
                                    <textarea class="form-control" id="Specification" name="specification" placeholder="Specification">{{ (!empty($data)) ? $data['Specification'] : ''}}</textarea>
                                </div>
                              
                              </div>                           

                          <div class="row">
                              <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                  <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                          Save
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
                        number: true,
                    },
                    rate: {
                        required: true,
                        number: true,
                    },
                    discount: {
                      required: true,
                      number: true,
                    },
                    delivery_schedule: {
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




