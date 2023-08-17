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
                              <input type="text" name="firm_name" class="form-control"  value="{{(!empty($datas)) ? $datas['firm_name']: ""}}" placeholder="Entity/Firm Name"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Contact person name *</label>
                                <input type="text" name="contact_person" class="form-control"  value="{{(!empty($datas)) ? $datas['contact_person']: ""}}" placeholder="Contact person name/Owner"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Designation *</label>
                                <input type="text" name="designation" class="form-control"  value="{{(!empty($datas)) ? $datas['designation']: ""}}" placeholder="Designation"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Contact number
                                 <span style="font-size: 11px;">(Include STD code,telephone/mobile number) </span>  </label>
                                  <input type="text" name="contact_number" class="form-control"  value="{{(!empty($datas)) ? $datas['contact_number']: ""}}" placeholder="contact_number">
                            </div>   
                            
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Email
                                 <span style="font-size: 11px;"></span>  </label>
                                 <input type="text" name="email" class="form-control"  value="{{(!empty($datas)) ? $datas['email']: ""}}" placeholder="contact_number">
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Sales Type</label>
                                <select name="sales_type"  class="form-control">
                                <option value="">-- Select one ---</option>
                                    <option value="Dealer" @if(!empty($datas) && $datas['sales_type'] == 'Dealer' ) selected   @endif>Dealer</option>
                                    <option value="Institutional" @if(!empty($datas) && $datas['sales_type'] == 'Institutional' ) selected   @endif>Institutional</option>
                                    <option value="Doctor" @if(!empty($datas) && $datas['sales_type'] == 'Doctor' ) selected   @endif>Doctor</option>
                                    <option value="DUMY" @if(!empty($datas) && $datas['sales_type'] == 'DUMY' ) selected   @endif>DUMY</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Billing Address  *</label>
                                <textarea value="" class="form-control " name="billing_address"  id="billing_address" rows="4" placeholder="Billing Address"><?php echo (!empty($datas)) ? $datas['billing_address'] : ""; ?></textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Shipping Address  *  <span align="right"> <input type="checkbox" id="address_same_check"> Billing address same as shipping address </span></label>
                                <textarea value="" class="form-control " name="shipping_address" id="shipping_address" rows="4"  placeholder="Shipping Address"><?php echo (!empty($datas)) ? $datas['shipping_address'] : ""; ?></textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>City *</label>
                                <input type="text" name="city" class="form-control"  value="{{(!empty($datas)) ? $datas['city'] : ""}}" placeholder="CIty"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>State *</label>

                                <select name="state"  class="form-control">
                                    <option value="">-- Select one ---</option>
                                 @foreach ($states as $item)
                                        <option value=" {{$item->state_id}}" @if($datas != null) @if(  $item->state_id == $datas->state) selected @endif @endif>{{$item->state_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Zone *</label>
                                <select name="zone"  class="form-control">
                                    <option value="">-- Select one ---</option>
                                    @foreach($zones as $item)
                                    <option value="{{$item->id}}" @if($datas != null) @if($item->id == $datas->zone) selected @endif @endif>{{$item->zone_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>PAN Number *</label>
                                <input type="text" name="pan_number" class="form-control"  value="{{(!empty($datas)) ? $datas->pan_number: ""}}" placeholder="PAN Number" required> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>GST Number *</label>
                                <input type="text" name="gst_number" class="form-control"  value="{{(!empty($datas)) ? $datas['gst_number'] : ""}}" placeholder="GST Number" > 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>DL Number(Form 20B) </label>
                                <input type="text" name="dl_number1" class="form-control"  value="{{(!empty($datas)) ? $datas['dl_number1'] : ""}}" placeholder="GST Number"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>DL Number (Form 21B)</label>
                                <input type="text" name="dl_number2" class="form-control"  value="{{(!empty($datas)) ? $datas['dl_number2'] : ""}}" placeholder="GST Number"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>DL Number (Others if any)</label>
                                <input type="text" name="dl_number3" class="form-control"  value="{{(!empty($datas)) ? $datas['dl_number3'] : ""}}" placeholder="GST Number"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>DL Expiry Date</label>
                                <input type="date" name="dl_expiry_date" class="form-control"  value="{{(!empty($datas)) ? $datas['dl_expiry_date'] : ""}}" placeholder="DL Expiry Date"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Sales Person Name</label>
                                <input type="text" name="sales_person_name" class="form-control"  value="{{(!empty($datas)) ? $datas['sales_person_name']: ""}}" placeholder="Sales Person Name"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Sales Person Email Id</label>
                                <input type="text" name="sales_person_email" class="form-control"  value="{{(!empty($datas)) ? $datas['sales_person_email']: ""}}" placeholder="Sales Person Email Id"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Whatsapp Number</label>
                                <input type="text" name="whatsapp_number" class="form-control"  value="{{(!empty($datas)) ? $datas['sales_person_email'] : ""}}" placeholder="Whatsapp Number"> 
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Currency</label>

                                <select class="form-control" name="currency" id="currency">
                                    <option value="">--- select one ---</option> 
                                    @foreach($currency as $item)
                                    <option value="{{$item->currency_id}}" @if($datas != null)  @if($item->currency_id == $datas->currency) selected @endif @endif>{{$item->currency_code}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Type</label>
                                <select name="master_type"  class="form-control">
                                <option value="">-- Select one ---</option>
                                    <option value="Supplier" @if(!empty($datas) && $datas['master_type'] == 'Supplier' ) selected   @endif>Supplier</option>
                                    <option value="Customer" @if(!empty($datas) && $datas['master_type'] == 'Customer' ) selected   @endif>Customer</option>
                                    
                                </select>
                            </div>
                             <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Status</label>
                                <select name="status_type"  class="form-control">
                                <option value="">-- Select one ---</option>
                                    <option value="1" @if(!empty($datas) && $datas['status_type'] == '1' ) selected   @endif>Active</option>
                                    <option value="0" @if(!empty($datas) && $datas['status_type'] == '0' ) selected   @endif>Inactive</option>
                                    
                                </select>
                            </div>
                             
                              <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                  <label>Payment Terms *</label>
                                  <textarea value="" class="form-control autosize" name="payment_terms" placeholder="Terms and Conditions"><?php echo (!empty($datas)) ? $datas['payment_terms'] : ""; ?></textarea>
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