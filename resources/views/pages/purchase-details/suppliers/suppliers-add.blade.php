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
              <span><a href="{{url('inventory/suppliers-list')}}" style="color: #596881;">Suppliers</a></span> 
                  <span><a href=""> Suppliers </a></span>
              </div>
              <h4 class="az-content-title" style="font-size: 20px;margin-bottom: 18px !important;"> Terms and Conditions</h4>
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
              <div class="row">  
                  <div class="col-sm-12   col-md-12 col-lg-12 col-xl-12 " style="border: 0px solid rgba(28, 39, 60, 0.12);">
                                                                  
                      <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                      <form method="POST" id="commentForm" autocomplete="off" novalidate="novalidate">
                         
                        {{ csrf_field() }}  
                          <div class="form-devider"></div>
                          <div class="row">
                              <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                  <label>Vendor ID *</label>
                              <input type="text" name="Vendor_id" class="form-control"  value="{{(!empty($datas['supplier'])) ? $datas['supplier']->vendor_id: ""}}" placeholder="Vendor ID"> 
                              </div>

                              <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Vendor Name *</label>
                            <input type="text" name="Vendor_name" class="form-control"  value="{{(!empty($datas['supplier'])) ? $datas['supplier']->vendor_name: ""}}" placeholder="Vendor Name"> 
                            </div>

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Indian / Overseas Supplier</label>
                                <select name="Indian_Overseas"  class="form-control">
                                    <option value="">-- Select one ---</option>
                                    <option value="indian"  @if(!empty($datas['supplier']) && $datas['supplier']->indian_overseas == 'indian' ) selected   @endif>Indian</option>
                                    <option value="overseas" @if(!empty($datas['supplier']) && $datas['supplier']->indian_overseas == 'overseas' ) selected   @endif>Overseas</option>
                                  </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label> Justification of {{config('app.title')}} classification</label>
                                <textarea value="" class="form-control " name="justification_company_classification" placeholder="Justification of {{config('app.title')}} classification"><?php echo (!empty($datas['supplier'])) ? $datas['supplier']->justification_company_classification: ""; ?></textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label> {{config('app.title')}}  classification</label>
                            <input type="text" name="company_classification" class="form-control"  value="{{(!empty($datas['supplier'])) ? $datas['supplier']->company_classification: ""}}" placeholder=" {{config('app.title')}}  classification"> 
                            </div>


                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>General Product or Service</label>
                                <textarea value="" class="form-control " name="general_product_service" placeholder="General Product or Service"><?php echo (!empty($datas['supplier'])) ? $datas['supplier']->general_product_service: ""; ?></textarea>
                            </div>

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Contact person name/Owner  *</label>
                            <input type="text" name="contact_person" class="form-control"  value="{{(!empty($datas['supplier'])) ? $datas['supplier']->contact_person: ""}}" placeholder="Contact person name/Owner"> 
                            </div>

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Contact number
                                 <span style="font-size: 11px;">(Include STD code,telephone/mobile number, add comma " , "  for multiple number) </span>  </label>
                                <input type="text" name="contact_number" class="form-control"  value="{{(!empty($datas['supplier']) && (!empty(json_decode($datas['supplier']->contact_number)[0]))) ? implode(", ",json_decode($datas['supplier']->contact_number)) : ""}}" placeholder="Contact number"> 
                            </div>   
                            
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Email
                                 <span style="font-size: 11px;">(Add comma " , "  for multiple Email) </span>  </label>
                                <input type="text" name="email" class="form-control"  value="{{(!empty($datas['supplier']) && (!empty(json_decode($datas['supplier']->email)[0]))) ? implode(", ",json_decode($datas['supplier']->email)) : ""}}" placeholder="Email"> 
                            </div>


                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Supplier type *</label>
                                <select name="Supplier_type"  class="form-control">
                                    <option value="">-- Select one ---</option>
                                    <option value="direct" @if(!empty($datas['supplier']) && $datas['supplier']->supplier_type == 'direct' ) selected   @endif>Direct</option>
                                    <option value="indirect" @if(!empty($datas['supplier']) && $datas['supplier']->supplier_type == 'indirect' ) selected   @endif>Indirect</option>
                                  </select>
                               </div>
                               <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Address  *</label>
                                <textarea value="" class="form-control " name="Address" placeholder="Address"><?php echo (!empty($datas['supplier'])) ? $datas['supplier']->address: ""; ?></textarea>

                            </div>
                               <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Remarks</label>
                                 
                                <textarea value="" class="form-control " name="Remarks" placeholder="Remarks"><?php echo (!empty($datas['supplier'])) ? $datas['supplier']->remarks: ""; ?></textarea>

                               </div>

                             
                              <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                  <label>Terms and Conditions *</label>
                                  <textarea value="" class="form-control autosize" name="conditions" placeholder="Terms and Conditions"><?php echo (!empty($datas['supplier'])) ? $datas['supplier']->terms_and_conditions: ""; ?></textarea>
                              </div>
                          </div> 
              
                          <div class="row">
                              <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                  <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                   
                                    {{ $id ? "Update" : "Save" }}    
                                                                  
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
autosize();
function autosize(){
    var text = $('.autosize');

    text.each(function(){
        $(this).attr('rows',1);
        resize($(this));
    });

    text.on('input', function(){
        resize($(this));
    });
    
    function resize ($text) {
        $text.css('height', 'auto');
        $text.css('height', $text[0].scrollHeight+'px');
    }
}


$("#commentForm").validate({
            rules: {
                Vendor_id: {
                    required: true,
                },
                Vendor_name: {
                    required: true,
                },
                contact_person: {
                    required: true,
                },
                Address: {
                    required: true,
                },
                conditions: {
                    required: true,
                },
                Supplier_type: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
 });

</script>






  @stop