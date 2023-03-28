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
                  <span><a href=""> Customer - Supplier </a></span>
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
                                  <label>Entity / Firm Name *</label>
                              <input type="text" name="firm_name" class="form-control"  value="{{(!empty($datas['supplier'])) ? $datas['customer']->enitity_name: ""}}" placeholder="Entity/Firm Name"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Contact person name *</label>
                                <input type="text" name="contact_person" class="form-control"  value="{{(!empty($datas['supplier'])) ? $datas['supplier']->contact_person: ""}}" placeholder="Contact person name/Owner"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Designation *</label>
                                <input type="text" name="designation" class="form-control"  value="{{(!empty($datas['customer'])) ? $datas['customer']->designation: ""}}" placeholder="Designation"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Contact number
                                 <span style="font-size: 11px;">(Include STD code,telephone/mobile number) </span>  </label>
                                <input type="text" name="contact_number" class="form-control"  value="{{(!empty($datas['customer']) && (!empty(json_decode($datas['customer']->contact_number)[0]))) ? implode(", ",json_decode($datas['supplier']->contact_number)) : ""}}" placeholder="Contact number"> 
                            </div>   
                            
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Email
                                 <span style="font-size: 11px;"></span>  </label>
                                <input type="text" name="email" class="form-control"  value="{{(!empty($datas['customer']) && (!empty(json_decode($datas['customer']->email)[0]))) ? implode(", ",json_decode($datas['supplier']->email)) : ""}}" placeholder="Email"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Sales Type</label>
                                <select name="sales_type"  class="form-control">
                                <option value="">-- Select one ---</option>
                                    <option value="Dealer" @if(!empty($datas['supplier']) && $datas['supplier']->sales_type == 'Dealer' ) selected   @endif>Dealer</option>
                                    <option value="Institutional" @if(!empty($datas['supplier']) && $datas['supplier']->sales_type == 'Institutional' ) selected   @endif>Institutional</option>
                                    <option value="Doctor" @if(!empty($datas['supplier']) && $datas['supplier']->sales_type == 'Doctor' ) selected   @endif>Doctor</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Billing Address  *</label>
                                <textarea value="" class="form-control " name="billing_address"  id="billing_address" rows="4" placeholder="Billing Address"><?php echo (!empty($datas['customer'])) ? $datas['customer']->billing_address: ""; ?></textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Shipping Address  *  <span align="right"> <input type="checkbox" id="address_same_check"> Billing address same as shipping address </span></label>
                                <textarea value="" class="form-control " name="shipping_address" id="shipping_address" rows="4"  placeholder="Shipping Address"><?php echo (!empty($datas['customer'])) ? $datas['customer']->shipping_address: ""; ?></textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>City *</label>
                                <input type="text" name="city" class="form-control"  value="{{(!empty($datas['customer'])) ? $datas['customer']->city: ""}}" placeholder="CIty"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>State *</label>
                                <select name="state"  class="form-control">
                                    <option value="">-- Select one ---</option>
                                    @foreach($states as $c)
                                    <option value="{{$c['state_id']}}">{{$c['state_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Zone *</label>
                                <select name="zone"  class="form-control">
                                    <option value="">-- Select one ---</option>
                                    @foreach($zones as $zone)
                                    <option value="{{$zone['id']}}">{{$zone['zone_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>PAN Number *</label>
                                <input type="text" name="pan_number" class="form-control"  value="{{(!empty($datas['customer'])) ? $datas['customer']->gst_number: ""}}" placeholder="GST Number"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>GST Number *</label>
                                <input type="text" name="gst_number" class="form-control"  value="{{(!empty($datas['customer'])) ? $datas['customer']->gst_number: ""}}" placeholder="GST Number"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>DL Number(Form 20B) </label>
                                <input type="text" name="dl_number1" class="form-control"  value="{{(!empty($datas['customer'])) ? $datas['customer']->dl_number1: ""}}" placeholder="GST Number"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>DL Number (Form 21B)</label>
                                <input type="text" name="dl_number2" class="form-control"  value="{{(!empty($datas['customer'])) ? $datas['customer']->dl_number2: ""}}" placeholder="GST Number"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>DL Number (Others if any)</label>
                                <input type="text" name="dl_number3" class="form-control"  value="{{(!empty($datas['customer'])) ? $datas['customer']->dl_number3: ""}}" placeholder="GST Number"> 
                            </div>

                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Currency</label>
                                <select name="currency"  class="form-control">
                                    <option value="">-- Select one ---</option>
                                    @foreach($currency as $c)
                                    <option value="{{$c['currency_id']}}">{{$c['currency_code']}}</option>
                                    @endforeach
                                  </select>
                            </div>
                            
                              <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                  <label>Payment Terms *</label>
                                  <textarea value="" class="form-control autosize" name="payment_terms" placeholder="Terms and Conditions"><?php echo (!empty($datas['supplier'])) ? $datas['supplier']->terms_and_conditions: ""; ?></textarea>
                              </div>
                          </div> 
              
                          <div class="row">
                              <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                  <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span> <i class="fas fa-save"></i>
                                   
                                     "Save"   
                                                                  
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
$('#address_same_check').click(function() {
    if ($("#address_same_check:checked").is(':checked')) { 
        var c=$('#billing_address').val();
        $("#shipping_address").val(c);
    }
    else
    {
       // var c=$('#billing_address').val();
        $("#shipping_address").val(''); 
    }
});


$("#commentForm").validate({
            rules: {
                firm_name: {
                    required: true,
                },
                contact_person: {
                    required: true,
                },
                designation: {
                    required: true,
                },
                contact_number: {
                    required: true,
                },
                billing_address: {
                    required: true,
                },
                shipping_address: {
                    required: true,
                },
                conditions: {
                    required: true,
                },
                sales_type: {
                    required: true,
                },
                currency: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
 });

</script>






  @stop