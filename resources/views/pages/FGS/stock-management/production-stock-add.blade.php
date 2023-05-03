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
                <span><a href="" style="color: #596881;">Stock Management</a></span>
                <span><a href="">Production Stock</a></span>
              </div>
              <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;"> Production Stock</h4>
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
             <div class="alert alert-danger " style="width: 100%;">
                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                 <i class="icon fa fa-check"></i> {{ Session::get('error') }}
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
                                    @if(!empty($data['sku_code']))
                                    <input type="hidden" name="product" value="{{$data['sku_code']}}">
                                    @endif
                                    <select class="form-control Product" name="product" id="product" @if(!empty($data['sku_code'])) disabled @endif>
                                         @if(!empty($data['sku_code']))
                                        <option value="{{$data['sku_code']}}" selected>{{$data['sku_code']}}</option>
                                        @endif
                                  </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Description</label>
                                <textarea type="text" name="description" id="description"  class="form-control"  placeholder="Description" readonly><?php echo (!empty($data)) ? $data['discription']: ""; ?> 
                                </textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Product Group *</label>
                                <input type="text" name="hsn_code" id="product_group" class="form-control"  value="{{(!empty($data)) ? $data['group_name']: ""}}" placeholder="Product Group" readonly> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>HSN Code *</label>
                                <input type="text" name="hsn_code" id="hsn_code" class="form-control"  value="{{(!empty($data)) ? $data['hsn_code']: ""}}" placeholder="HSN Code" readonly> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Batch Card *</label>
                                <select class="form-control batchcard_no" name="batchcard_no" id="batchcard_no">
                                    <option value="">--- select one ---</option>
                                </select>
                            </div><!-- form-group -->
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Stock Quantity</label>
                                <input type="text" name="stock_qty" class="form-control"  value="{{(!empty($data)) ? $data['sales']: ""}}" placeholder="Stock Quantity"> 
                            </div>
                        </div>      
                          <div class="row">
                              <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                  <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><i class="fas fa-save"></i>
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
      $('.batchcard_no').select2({
            placeholder: 'Choose one',
            searchInputPlaceholder: 'Search',
            minimumInputLength: 5,
            allowClear: true,
            ajax: {
                url: "{{url('label/batchcardSearch')}}",
                processResults: function (data) {
                return {
                        results: data
                    };
                }
            }
        });
$("#commentForm").validate({
            rules: {
                product: {
                    required: true,
                },
                batchcard_no: {
                    required: true,
                },
                stock_qty: {
                    required: true,
                },
               
                
            },
            submitHandler: function(form) {
                form.submit();
            }
 });

</script>






  @stop