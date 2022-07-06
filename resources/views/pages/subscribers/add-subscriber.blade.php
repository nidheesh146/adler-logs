@extends('layouts.default')
@section('content')
@php
 $type = [1=>'state',2=>'district',3=>'mekhala',4=>'unit',5=>'agent'];
@endphp
<style>
  .select2-container .select2-selection--single {
  height: 38px !important;
  }
</style>
    <div class="az-content az-content-dashboard">
        <div class="container">
            <div class="az-content-body">
                <div class="az-content-breadcrumb">
                    <span><a href="{{ url('subscribers/' . $org) }}" >SUBSCRIBERS</a></span>
                    {{-- <span><a href="{{ url('subscribers/' . $org) }}" >All SUBSCRIBERS</a></span> --}}
                    <span>{{$id ? 'Update' : 'Create'}} a subscriber</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">{{$id ? 'Update' : 'Create'}} a subscriber 
                    @if (in_array('subscribers.add', config('permission')))  
                    @if($id)    <button style="float: right;font-size: 14px;" onclick="document.location.href='{{ url('subscribers/create/' . $org)}}'" class="badge badge-pill badge-dark "><i
                        class="fas fa-plus"></i> subscription  </button> @endif
                        @endif
{{--                 
                    <div>
                        <button data-toggle="dropdown" style="float: right;" class="badge badge-pill badge-dark "><i
                                class="fas fa-plus"></i> Subscriber <i
                                class="icon ion-ios-arrow-down tx-11 mg-l-3"></i></button>

                        <div class="dropdown-menu">
                            <a href="{{ url('subscribers/create/state') }}" class="dropdown-item">Under my
                                organization </a>
                            <a href="{{ url('subscribers/create/district') }}" class="dropdown-item"> Under
                                District</a>
                            <a href="{{ url('subscribers/create/mekhala') }}" class="dropdown-item">Under Mekhala</a>
                            <a href="{{ url('subscribers/create/unit') }}" class="dropdown-item">Under Unit</a>
                        </div>
                    </div>
                 --}}
                
                
                </h4>


                <div class="row">
                  
                  @if($id)
                    <div class="col-md-4 col-lg-3">
                        <div class="pd-20 bg-gray-200"
                            style="border: 1px solid rgba(28, 39, 60, 0.12);background-color: #ffffff;">
                            <nav class="nav az-nav-column">
                                @if (in_array('subscribers.add', config('permission')) || (in_array('subscribers.edit', config('permission')) && $id ))  
                                    <a class="nav-link active"  style="{{ !$id ? 'cursor: no-drop;' : '' }}" href="{{ $id ?  url('subscribers/create/' . $org.'/'.$id) :'#' }}"><i
                                            class="fas fa-user-plus" style="font-size: 13px;margin: 0;"></i> Subscriber
                                    </a>
                                @endif
                                @if (in_array('subscribers.subscription.list', config('permission')))  
                                   <a class="nav-link" style="{{ !$id ? 'cursor: no-drop;' : ''}}" href="{{ $id ? url('subscription/create/' . $org . '/'.$id) : '#' }}"><i
                                        class="fas fa-money-check-alt" style="font-size: 13px;margin: 0;"></i>
                                    Subscription</a>
                                @endif
                                <a class="nav-link" data-toggle="tab" href="#"></a>
                            </nav>
                        </div><!-- pd-10 -->
                    </div>
                    @endif



                    <div class="col-sm-12   @if($id) col-md-9 col-lg-9 col-xl-9 @else col-md-12 col-lg-12 col-xl-12 @endif"
                        style="border: 1px solid rgba(28, 39, 60, 0.12);padding: 29px;">
                       
                        @foreach ($errors->all() as $error)
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
                        <form method="POST" id="commentForm" >
                          {{ csrf_field() }}  
                          {{-- <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;"><i
                                            class="fas fa-sitemap"></i> Organisation ({{ ucfirst($org) }})</label>
                                    <div class="form-devider"></div>
                                </div>

                            </div> --}}

                    
                            {{-- <div class="row "> --}}
                              {{-- <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label for="exampleInputEmail1">{{'State'}} </label>
                               <input type="text" class="form-control" value="{{$data['state']['org_number'].'-'.$data['state']['org_name']}}" readonly >
                            </div>   --}}
                            
                                {{-- @if ($org == 'district' || $org == 'mekhala' || $org == 'unit' || $org == 'agent')
                                    <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6 agent_district">
                                        <label> District * <div
                                                class="District-spinner spinner-border text-primary spinner-border-sm"
                                                role="status" style="display:none"></div>
                                        </label>
                                        <span class="under_district">
                                            <select class="form-control  district_under" name="district">
                                                <option value="">Choose one</option>
                                                @foreach ($data['district'] as $state)
                                                   <option value="{{ $state['org_id'] }}" {{$id ? ($state['org_id'] == $data['suscriber']['district']) ? 'selected' : '' : ''}} >{{ $state['org_number'] }}-{{ $state['org_name'] }}</option>
                                               @endforeach
                                             </select>
                                        </span>
                                    </div>
                                @endif --}}
                                 {{-- 
                                @if ($org == 'mekhala' || $org == 'unit')
                                    <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <label> Mekhala * <div
                                                class="Mekhala-spinner spinner-border text-primary spinner-border-sm"
                                                role="status" style="display:none"></div></label>
                                        <span class="under_mekhala">
                                            <select class="form-control mekhala_under" name="mekhala">
                                                <option value="">Choose one</option>
                                                @if($id)
                                                @foreach ($data['mekhala'] as $state)
                                                    <option value="{{ $state['org_id'] }}" {{$id ? ($state['org_id'] == $data['suscriber']['mekhala']) ? 'selected' : '' : ''}} >{{ $state['org_number'] }}-{{ $state['org_name'] }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </span>
                                    </div>
                                @endif --}}



                                {{-- @if ( $org == 'unit')
                                    <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <label> Unit * <div
                                                class="Unit-spinner spinner-border text-primary spinner-border-sm"
                                                role="status" style="display:none"></div></label>
                                        <span class="under_unit">
                                            <select class="form-control unit_under" name="unit">
                                                <option value="">Choose one</option>
                                                @if($id)
                                                @foreach ($data['unit'] as $state)
                                                    <option value="{{ $state['org_id'] }}" {{$id ? ($state['org_id'] == $data['suscriber']['unit']) ? 'selected' : '' : ''}} >{{ $state['org_number'] }}-{{ $state['org_name'] }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </span>
                                    </div>
                                @endif --}}

                            {{-- </div> --}}
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;"><i
                                            class="fas fa-address-card"></i> Basic details {{$id ? '( Subscriber ID : '.$data['suscriber']['subr_id'].' )' : ''}}</label>
                                    <div class="form-devider"></div>
                                </div>
                            </div>

                            <div class="row">

                                @if($org == 'state' && config('organization')['type'] ==1)
                                    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                        <label >Subscriber Type (Paid / Free)</label>
                                        <select class="form-control " name="subscriber_type">
                                            <option {{$id ? ('paid' == $data['suscriber']['type']) ? 'selected' : '' : ''}} value="paid">Paid</option>
                                            <option {{$id ? ('free' == $data['suscriber']['type']) ? 'selected' : '' : ''}} value="free">Free</option>
                                        </select>
                                    </div>
                                @endif

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label for="exampleInputEmail1">First name *</label>
                                    <input type="text" class="form-control" name="f_name"
                                    value="{{$id ? $data['suscriber']['f_name'] : ''}}"  placeholder="Enter first name">
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Last name *</label>
                                <input type="text" class="form-control" value="{{$id ? $data['suscriber']['l_name'] : ''}}" name="l_name" placeholder="Enter last name">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Care of </label>
                                    <input type="text" class="form-control" value="{{$id ? $data['suscriber']['care_of'] : ''}}" name="careof" placeholder="Enter Care of ">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Email </label>
                                    <input type="email" value="{{$id ? $data['suscriber']['email'] : ''}}"  class="form-control" name="email" placeholder="Enter Email">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Phone *</label>
                                    <input type="text" value="{{$id ? $data['suscriber']['phone'] : ''}}"  class="form-control" name="phone" placeholder="Enter phone number">
                                </div><!-- form-group -->


                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>House Name *</label>
                                    <input type="text"  value="{{$id ? $data['suscriber']['house_name'] : ''}}" class="form-control" name="house_name" placeholder="Enter house name">
                                </div><!-- form-group -->


                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Place *</label>
                                    <input type="text" name="place" value="{{$id ? $data['suscriber']['place'] : ''}}" class="form-control" placeholder="Enter Place">
                                </div><!-- form-group -->
                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Post Office *</label>
                                    <input type="text" class="form-control" value="{{$id ? $data['suscriber']['post_office'] : ''}}" name="postoffice" placeholder="Enter Post Office">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Pincode *</label>
                                    <input type="text"  value="{{$id ? $data['suscriber']['pincode'] : ''}}" class="form-control" name="pincode" placeholder="Enter Pincode">
                                </div><!-- form-group -->

                                {{-- <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Pincode </label>
                                    <input type="email" class="form-control" placeholder="Enter Owner name (optional)">
                                    </div><!-- form-group --> --}}

                                <div class="form-group col-sm-12  col-md-4 col-lg-4 col-xl-4">
                                    <label>Billing address * ( <input type="checkbox" class="ship_add" name="ship_add"
                                        {{$id ? ( $data['suscriber']['billing_address'] != $data['suscriber']['shipping_address'] ) ? 'checked' : '' : ''}}
                                            value="1"> <span style="font-size: 10px;"> Ship to different address ? </span>)</label>
                                    <textarea class="form-control" name="bill_address" placeholder="Enter Billing address">{{$id ? $data['suscriber']['billing_address'] : ''}}</textarea>
                                </div>
                                <div class="form-group col-sm-12  col-md-4 col-lg-4 col-xl-4 shipping_div"
                                    style="  {{$id ? ( $data['suscriber']['billing_address'] != $data['suscriber']['shipping_address'] ) ? 'display:block;' : 'display:none;' : 'display:none;'}}  ">
                                    <label>Shipping address * </label>
                                    <textarea class="form-control" name="ship_address" placeholder="Enter Shipping address">{{$id ? $data['suscriber']['shipping_address'] : ''}}   </textarea>
                                </div>
                                {{-- <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Shippng address (same as Billing address) *</label>
                                    <textarea class="form-control" placeholder="Enter your password"></textarea>
                                  </div> --}}

                            </div> 

                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><i
                                            class="fas fa-save"></i> {{$id ? 'Update' : 'Save & Next'}}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>


            </div><!-- az-content-body -->
        </div>
    </div><!-- az-content -->

        <script src="<?= url('') ?>/lib/jquery/jquery.min.js"></script>
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


        <script>
          $(function(){
            'use strict'
            $('.ship_add').click(function() {
                    if ($(this).is(":checked")) {
                         $('.shipping_div').show();
                    } else {
                         $('.shipping_div').hide();
                    }
                });

          });
          $("#commentForm").validate({
            rules: {
                f_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                l_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                careof: {
                    minlength: 1,
                    maxlength: 50
                },
                 email: {
                     email: true,
                },
                phone: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 12
                },
                house_name:{
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                place:{
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                postoffice:{
                    required: true,
                    minlength: 1,
                    maxlength: 50
                },
                pincode:{
                    required: true,
                    number: true,
                    minlength: 6,
                    maxlength: 6
                },
                ship_address:{
                    required: true, 
                },
                bill_address:{
                    required: true, 
                }
              
            },
           
        });
        </script>


{{-- @if (config('organization')['type'] == 1)
@include('includes.subscribers.add-subscribers');
@endif --}}



@stop





