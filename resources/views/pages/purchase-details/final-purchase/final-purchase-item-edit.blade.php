@extends('layouts.default')
@section('content')

    <div class="az-content az-content-dashboard">
        <br>
        <div class="container">
          <div class="az-content-body">

              <div class="az-content-breadcrumb">
                  <span><a href="http://localhost/adler/public/inventory/supplier-quotation">SUPPLIER QUOTATION </a></span>
                   <span> <a href="http://localhost/adler/public/inventory/view-supplier-quotation-items/9/56">Supplier Quotation Items</a></span>
                    <span><a> Edit Supplier Quotation Item</a></span>
              </div>

              <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;">
                  <!-- Add  Supplier quotation item</h4> -->
                  Edit Supplier Quotation Item for  ( VIN056 - Renishaw Metrology Systems Private Limited ) 
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
                      <form method="POST" id="commentForm" novalidate="novalidate">
                          <input type="hidden" name="_token" value="qObO2jikGq5OG4oteMaFMHBMwIRoSpjs83dKAHoz">

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
                                    <td>RAL01RC590</td>
                                      <th scope="row">Item Discount ( % )</th>
                                      <td>12</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Item Name</th> 
                                      <td>RAL01RC590</td>
                                      <th scope="row">GST</th>
                                      <td>12</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">HSN code</th>
                                      <td></td>
                                      <th scope="row">Unit</th>
                                      <td>kgs</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Basic Value</th>
                                      <td>121</td>
                                      <th scope="row">Item description </th>
                                      <td>Aluminum Coil, Dia. 5.90mm (+0.00/-0.10), Condition Annealed (Soft Mtrl)</td>
                                    </tr>
                                    <tr>
                                      <th scope="row">Currency</th>
                                      <td>INR</td>
                                      <th scope="row">Requested QTY</th>
                                      <td>12</td>
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
                              <input type="text" name="quantity" value="12" class="form-control" placeholder="Quantity">
                            
                              </div><!-- form-group -->

                              <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                  <label> Rate *</label>
                                  <input type="text" class="form-control" value="1" name="rate" id="rate" placeholder="Rate">
                              </div>
                              <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                  <label> Discount (%) *</label>
                                  <input type="text" class="form-control" value="12" name="discount" id="discount" placeholder="Discount">
                              </div>
                              <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                  <label> Specification *</label>
                                  <textarea class="form-control" id="Specification" name="Specification" placeholder="Specification">dsadfa
asdasdasdas</textarea>
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




