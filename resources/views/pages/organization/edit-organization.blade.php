@extends('layouts.default')
@section('content')
<style>
   .select2-container .select2-selection--single {
   height: 38px !important;
   }
</style>
<div class="az-content az-content-dashboard">
   <div class="container">
      <div class="az-content-body">
         <div class="az-content-breadcrumb">
            <span><a href="{{ url('organization/all') }}" >Organization</a></span>
            {{-- <span><a href="{{ url('organization/' . $org) }}" >List
            {{ $org }}</a></span> --}}
            <span>Edit Organization</span>
         </div>
         <h4 class="az-content-title" style="font-size: 20px;">Edit {{$data['organization']['org_number']}} - {{$data['organization']['org_name']}} ( {{ucfirst($org)}} Organization )</h4>
         <div class="row row-sm mg-b-20 mg-lg-b-0">

            @foreach ($validator->errors()->all() as $error)
              <div class="alert alert-danger "  role="alert" style="width: 100%;">
               <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ $error }}
              </div>
             @endforeach
             @if (Session::get('success'))
             <div class="alert alert-success " style="width: 100%;">
                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                 <i class="icon fa fa-check"></i> {{ Session::get('success') }}
             </div>
             @endif
            <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
            <form method="POST" id="commentForm" style="border: 1px solid rgba(28, 39, 60, 0.12);padding: 29px;"
               class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
               {{ csrf_field() }}  
               <div class="row">
                  <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                     <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;"><i
                        class="fas fa-address-card"></i> Basic details</label>
                     <div class="form-devider"></div>
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                     <label for="exampleInputEmail1">Organization register number *</label>
                     <input type="text" class="form-control" 
                         value="{{$data['organization']['org_number']}}" readonly >
                  </div>
                  <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                     <label for="exampleInputEmail1">{{ ucfirst($org) }} name *</label>
                     <input type="text" class="form-control" name="orgname"
                        placeholder="Enter {{ $org }} name" value="{{$data['organization']['org_name']}}">
                  </div>
                  <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                     <label>Phone Number *</label>
                     <input type="text" class="form-control" name="phone" value="{{$data['organization']['org_phone']}}" placeholder="Enter phone number">
                  </div>
                  <!-- form-group -->
                  <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                     <label>Email *</label>
                     <input type="email" name="email" class="form-control" value="{{$data['organization']['org_email']}}" placeholder="Enter email-ID">
                  </div>
                  <!-- form-group -->
                  <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                     <label>Person in-charge</label>
                     <input type="text" name="owner_name" class="form-control"
                        placeholder="Person incharge  (optional)" value="{{$data['organization']['org_owner_name']}}">
                  </div>
                  <!-- form-group -->
                  <div class="form-group col-sm-6 col-md-6 col-lg-6 col-xl-6">
                     <label>Address *</label>
                     <textarea class="form-control" name="address" placeholder="Enter Address">{{$data['organization']['org_address']}}</textarea>
                  </div>
               </div>

               
           @if($org != 'state')
               <div class="row">
                  <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                     <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;"><i
                        class="fas fa-sitemap"></i> Organizational structure ( the commission will apply on
                     this structure )</label>
                     <div class="form-devider"></div>
                  </div>
               </div>

               <div class="row ">
                  @if ($org == 'district' || $org == 'mekhala' || $org == 'unit' || $org == 'agent')
                  <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6 ">
                     <label> State * @if ($org == 'agent') (
                         <input type="checkbox" class="parend" name="under_state" 
                         {{($data['organization']['state'] && !$data['organization']['district']) ? 'checked' : ''}}
                         value="1" > Under state ) @endif
                      </label>
                     <select class="form-control  under_state" name="state">
                        <option value="">Choose one</option>
                        @foreach ($data['state'] as $state)
                           <option value="{{$state['org_id']}}" {{ ($state['org_id'] == $data['organization']['state']) ? 'selected' : '' }}>{{$state['org_number']}}-{{$state['org_name']}}</option>
                        @endforeach
                     </select>
                  </div>
                  @endif

                  @if ($org == 'mekhala' || $org == 'unit' || $org == 'agent')
                  <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6 agent_district" >
                     <label> District *  <div class="District-spinner spinner-border text-primary spinner-border-sm" role="status" style="display:none"></div>
                     </label>
                     <span class="under_district">
                     <select class="form-control  district_under"   
                     name="district">
                     <option value="">Choose one</option>
                     
                     @foreach ( $data['district'] as $district)
                        <option value="{{$district['org_id']}}" {{ ($district['org_id'] == $data['organization']['district']) ? 'selected' : '' }}>{{$district['org_number']}}-{{$district['org_name']}}</option>
                     @endforeach
                     
                     </select>
                  </span>
                  </div>
                  @endif

                  @if ($org == 'unit')
                  <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                     <label> Mekhala *   <div class="Mekhala-spinner spinner-border text-primary spinner-border-sm" role="status" style="display:none"></div></label>
                     <span class="under_mekhala">
                     <select class="form-control mekhala_under" name="mekhala">
                        <option value="">Choose one</option>

                     @foreach ( $data['mekhala'] as $mekhala)
                     <option value="{{$mekhala['org_id']}}" {{ ($mekhala['org_id'] == $data['organization']['mekhala']) ? 'selected' : '' }}>{{$mekhala['org_number']}}-{{$mekhala['org_name']}}</option>
                  @endforeach
                     </select>
                     </span>
                  </div>
                  @endif

                  @if (config('organization')['type'] == 1)
                  <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                     <label>Commission in % * ( Special consideration for this organisation)</label>
                  <input type="text" class="form-control" name="commission" value="{{$data['organization']['commission'] ? $data['organization']['commission'] :''}}"
                        placeholder="( if it is empty the commission will take from parent commission table )">
                  </div>
                  @endif
               </div>
               @endif




               <div class="row">
                  <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                     <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><i
                        class="fas fa-save"></i> Submit</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
      <!-- az-content-body -->
   </div>
</div>
<!-- az-content -->
<script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
<script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= url('') ?>/js/azia.js"></script>
<script src="<?= url('') ?>/lib/jquery-ui/ui/widgets/datepicker.js"></script>
<script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
<script src="<?= url('') ?>/lib/spectrum-colorpicker/spectrum.js"></script>
<script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
<script src="<?= url('') ?>/lib/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
<script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js"></script>
<script src="<?= url('') ?>/lib/jquery-simple-datetimepicker/jquery.simple-dtpicker.js"></script>
<script src="<?= url('') ?>/lib/pickerjs/picker.min.js"></script>
<script src="<?= url('') ?>/js/jquery.validate.js"></script>
<script src="<?= url('') ?>/js/additional-methods.js"></script>
<script>
   // Additional code for adding placeholder in search box of select2
   (function($) {
     var Defaults = $.fn.select2.amd.require('select2/defaults');
   
     $.extend(Defaults.defaults, {
       searchInputPlaceholder: ''
     });
   
     var SearchDropdown = $.fn.select2.amd.require('select2/dropdown/search');
   
     var _renderSearchDropdown = SearchDropdown.prototype.render;
   
     SearchDropdown.prototype.render = function(decorated) {
   
       // invoke parent method
       var $rendered = _renderSearchDropdown.apply(this, Array.prototype.slice.apply(arguments));
   
       this.$search.attr('placeholder', this.options.get('searchInputPlaceholder'));
   
       return $rendered;
     };
   
   })(window.jQuery);
</script>


{{-- @if(config('organization')['type'] == 1) --}}
      @include('includes.Organization.edit-org-state');
{{-- @endif --}}



@stop