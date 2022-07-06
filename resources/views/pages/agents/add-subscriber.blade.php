@extends('layouts.default')
@section('content')
@inject('Controller', 'App\Http\Controllers\Controller')
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
                    <span><a href="{{ url('agent/agents') }}" >Agents</a></span>
                    {{-- <span><a href="{{url('agent/agents') }}" >All Agents</a></span> --}}
                <span>{{$id ? 'Update' : 'Create'}} an agent</span>
                </div>
                <h4 class="az-content-title" style="font-size: 20px;">{{$id ? 'Update' : 'Create'}} an agent
                    @if (in_array('agent.add', config('permission'))) 
                    @if($id)  
                    <button style="float: right;font-size: 14px;" onclick="document.location.href='{{ url('agent/create/' . $org)}}'" class="badge badge-pill badge-dark "><i
                        class="fas fa-plus"></i> Agent  </button> @endif
                        @endif
                
                </h4>


                <div class="row">
                    @if($id)
                    <div class="col-md-4 col-lg-3">
                        <div class="pd-20 bg-gray-200"
                            style="border: 1px solid rgba(28, 39, 60, 0.12);background-color: #ffffff;">
                              @include('includes.agent-nav')
                        </div><!-- pd-10 -->
                    </div>
                    @endif

                    <div class="col-sm-12  @if($id) col-md-9 col-lg-9 col-xl-9 @else col-md-12 col-lg-12 col-xl-12 @endif"
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

                            {{-- </div> --}}
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;">
                                        <i class="fas fa-address-card"></i> Basic details {{$id ? '(  Subscriber ID : '.$data['suscriber']['subr_id'].' )' : ''}} </label>
                                    <div class="form-devider"></div>
                                </div>
                            </div>

                            <div class="row">


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
                                    <label>Organization Email *</label>
                                    <input type="email" value="{{$id ? $data['suscriber']['email'] : ''}}"  class="form-control" name="email" placeholder="Enter Email">
                                </div><!-- form-group -->

                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Phone *</label>
                                    <input type="text" value="{{$id ? $data['suscriber']['phone'] : ''}}"  class="form-control" name="phone" placeholder="Enter phone number">
                                </div><!-- form-group -->


                                <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                    <label>Organization name (school / shop etc.)*</label>
                                    <input type="text"  value="{{$id ? $data['suscriber']['house_name'] : ''}}" class="form-control" name="house_name" placeholder="school / shop etc...">
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

                            </div> 
                            @if (config('organization')['type'] == 1)
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                   <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;"><i class="fas fa-rupee-sign"></i> Special Commission <i class="fas fa-info-circle"
                                    data-toggle="tooltip" data-html="true" data-placement="top"
                                    title="if it is empty the commission will take from parent commission table"></i></label>
                                   <div class="form-devider"></div>
                                </div>
                             </div>

                             <div class="row ">
                             <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label>Commission in % ( Special consideration for this organisation) </label>
                                <input type="text" value="{{ $id ? $data['org']['commission'] : '' }}" class="form-control" name="commission"
                                   placeholder="( if it is empty the commission will take from parent commission table )">
                             </div>
                             </div>
                             @endif
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                   <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;"><i
                                      class="fas fa-user-shield"></i> Super Admin ( Login user details )</label>
                                   <div class="form-devider"></div>
                                </div>
                             </div>

                             <div class="row ">
                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                   <label>Email *</label>
                                   <input type="email"  value="{{ $id ? $data['user']['email'] : '' }}" name="admin_email" class="form-control" placeholder="Enter admin email">
                                   @if(config('organization')['type'] == 5)
                                <a href="#" class="show_password" style="
                                   float: right;
                                   font-size: 10px;
                               ">Change Password</a>

                                    @endif
                                </div>
                                <!-- form-group -->
                                @if(config('organization')['type'] != 5)
                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6 ">
                                   <label>Password *</label>
                                   <input type="text" value="{{ $id ? $Controller->decrypt($data['user']['password']) : '' }}" class="form-control" name="password" placeholder="Enter password">
                                </div>
                                @else
                                <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6 password_change">
                                    <label>Old password *</label>
                                    <input type="password" value="" class="form-control" name="old_password" placeholder="Enter Old password">
                                 </div>
                                 <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6 password_change">
                                    <label>New Password *</label>
                                    <input type="password" value="" class="form-control" name="new_password" placeholder="Enter New Password">
                                 </div>
                                 <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6 password_change">
                                    <label>Confirm Password *</label>
                                    <input type="password" value="" class="form-control" name="confirm_password" placeholder="Enter Confirm Password">
                                 </div>
                                 @endif





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
          $(function(){
            'use strict'
            $('[data-toggle="tooltip"]').tooltip();
            $('.ship_add').click(function() {
                    if ($(this).is(":checked")) {
                         $('.shipping_div').show();
                    } else {
                         $('.shipping_div').hide();
                    }
                });
          });

         $('.password_change').hide();
          $('.show_password').click(function(){
            $('.password_change').show();
          })


          </script>
         @if(!$id)
          <script>
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
                commission: {
                    number: true,
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
                },
                admin_email: {
                    required: true,
                    email: true,
                    remote: "<?= url('') ?>/check-email?module=admin_email"
                },
                email: {
                    required: true,
                    email: true,
                    remote: "<?= url('') ?>/check-email?module=org_reg"
                },
                password: {
                    required: true,
                    minlength: 5,
                    maxlength: 12
                },
            },
            messages: {
                email: {
                    remote: "given email address is already taken",
                },
                admin_email: {
                    remote: "given email address is already taken",
                }
              
            },
           
        });
        </script>
@else
   <script>
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
          commission: {
              number: true,
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
          },
          admin_email: {
              required: true,
              email: true,
              remote: "<?= url('') ?>/check-email-profile?module=user-edit&user_id=<?=$Controller->hashEncode($data['user']['user_id']);?>" 
          },
          email: {
                required: true,
                email: true,
                remote: "<?= url('') ?>/check-email?module=edit_org_reg&id=<?=$Controller->hashEncode($data['suscriber']['org_id']);?>"
            },
         password: {
                required: true,
                minlength: 5,
                maxlength: 12
        },
        old_password:{

        },
        new_password:{
            minlength: 5,
            maxlength: 12
        },
        confirm_password:{
            equalTo : '[name="new_password"]'
        }
      },
      messages: {
          email: {
              remote: "given email address is already taken",
          },
          admin_email: {
              remote: "given email address is already taken",
          }
        
      },
     
  });
  </script>

@endif








@stop





