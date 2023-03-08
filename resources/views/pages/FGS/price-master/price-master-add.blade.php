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
              <span><a href="{{url('inventory/suppliers-list')}}" style="color: #596881;">Customer - Supplier Master</a></span> 
                  <span><a href=""> Price Master </a></span>
              </div>
              <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;"> Customer - Supplier</h4>
              @foreach ($errors->all() as $errorr)
              <div class="alert alert-danger "  role="alert" style="width: 100%;">
                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ $errorr }}
              </div>
             @endforeach
             @if (Session::get('success'))
             <div class="alert alert-success " style="width: 100%;">
                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                 <i class="icon fa fa-check"></i> {{ Session::get('success') }}
             </div>
             @endif
             @if (Session::get('error'))
             <div class="alert alert-success " style="width: 100%;">
                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                 <i class="icon fa fa-check"></i> {{ Session::get('success') }}
             </div>
             @endif
              <div class="row">  
                  <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                                                                  
                      <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                      <form method="POST" id="commentForm" autocomplete="off" novalidate="novalidate">
                         
                        {{ csrf_field() }}  
                          <div class="form-devider"></div>
                          <div class="row">
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                  <label>Product SKU Code *</label>
                                  <select class="form-control Product" name="product" id="product">
                                  </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Description</label>
                                <textarea type="text" name="description" id="description"  class="form-control"  placeholder="Description" readonly><?php echo (!empty($datas['customer'])) ? $datas['customer']->billing_address: ""; ?> 
                                </textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Product Group *</label>
                                <input type="text" name="hsn_code" id="product_group" class="form-control"  value="{{(!empty($datas['price'])) ? $datas['price']->product_group: ""}}" placeholder="Product Group" readonly> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>HSN Code *</label>
                                <input type="text" name="hsn_code" id="hsn_code" class="form-control"  value="{{(!empty($datas['price'])) ? $datas['price']->designation: ""}}" placeholder="HSN Code" readonly> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Purchase Price</label>
                                <input type="text" name="purchase_price" class="form-control"  value="" placeholder="Purchase Price"> 
                            </div> 
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Sales Price</label>
                                <input type="text" name="sales_price" class="form-control"  value="" placeholder="Sales Price"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Transfer Price</label>
                                <input type="text" name="transfer_price" class="form-control"  value="" placeholder="Transfer Price"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>MRP </label>
                                <input type="text" name="mrp" class="form-control"  value="" placeholder="MRP"> 
                            </div>   
                          </div> 
              
                          <div class="row">
                              <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                  <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
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
<script>
 $('#product').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        minimumInputLength: 4,
        allowClear: true,
        ajax: {
            url: "{{ url('fgs/productsearch') }}",
            processResults: function (data) {
                return { results: data };
            }
        }
    }).on('change', function (e) {
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