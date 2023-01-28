@extends('layouts.default')
@section('content')
    <div class="az-content az-content-profile">
        <div class="container mn-ht-100p">
            <div class="az-content-left az-content-left-profile">

                <div class="az-profile-overview">
                    <div class="az-img-user">

                       @if (($datas[0]->profile_img)!="")
                            <img src="{{ url('profile_img/'.$datas[0]->profile_img)}}" alt="">
                            @else
                            <img src="<?= url('') ?>/img/profile.png" alt=""> 
                           @endif

                  </div><!-- az-img-user -->
                  <div class="d-flex justify-content-between mg-b-20">
                    <div>
                    <h5 class="az-profile-name">{{$datas[0]->username}}</h5>
                      <p class="az-profile-name-text">{{$datas[0]->employee_id}}</p>
                      <p class="az-profile-name-text">{{$datas[0]->designation}}</p>
                    </div>
                    {{-- <div class="btn-icon-list">
              <button class="btn btn-indigo btn-icon"><i class="typcn typcn-plus-outline"></i></button>
              <button class="btn btn-primary btn-icon"><i class="typcn typcn-message"></i></button>
            </div> --}}
                  </div>

                  <div class="az-profile-bio">
                  {{$datas[0]->address}}
                 </div><!-- az-profile-bio -->

                  <hr class="mg-y-30">

                  <label class="az-content-label tx-13 mg-b-20">Phone & Email</label>
                  <div class="az-profile-social-list">
                    <div class="media">
                      <div class="media-icon"><i class="fas fa-mobile-alt"></i></div>
                      <div class="media-body">
                        <span>Phone</span>
                        <a href="">{{$datas[0]->phone}}</a>
                      </div>
                    </div><!-- media -->
                    <div class="media">
                      <div class="media-icon"><i class="fas fa-envelope-open-text"></i></div>
                      <div class="media-body">
                        <span>Email</span>
                        <a href="">{{$datas[0]->email}}</a>
                      </div>
                    </div><!-- media -->
                 
                  </div><!-- az-profile-social-list -->

                </div><!-- az-profile-overview -->

              </div><!-- az-content-left -->
              <div class="az-content-body az-content-body-profile">
          
                <div class="az-profile-body">

                    <div class="row row-sm mg-b-20 mg-lg-b-0">


           


                        <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3"></div> -->
                        <form style="padding: 29px;" action="{{URL::asset('updateprofile')}}" method="POST"   enctype="multipart/form-data">
                        {{ csrf_field() }}            
                        <div class="row">
                          @if (Session::get('success'))
                          <div class="alert alert-success " style="width: 100%;">
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                              <i class="icon fa fa-check"></i> {{ Session::get('success') }}
                            </div>
                            @endif
                            <input type="hidden" value="{{$datas[0]->profile_img}}" name="image">
                            @if(session('message'))
                           <div class="alert alert-success" style="width: 100%">
                           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> 
                           <i class="icon fa fa-check"> </i> {{session('message')}}</div>
                          @endif
                          
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                      <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;"><i class="fas fa-address-card"></i> Basic details</label>
                        <div class="form-devider"></div>
                        </div>
                        
                        </div>
                             
                        <div class="row">
                                  
                                  <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                      <label for="exampleInputEmail1">Profile picture </label>
                                      <img src="">
                                      <input type="file" name="pro_pic" class="form-control" >
                                  </div>

                                  <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                  <label>Employee ID </label>
                                  @error('employee_id')
                                      <span class="alert alert-danger">{{$message}}</span>
                                   @enderror
                                    
                                  <input type="text" class="form-control" value="{{$datas[0]->employee_id}}" name="employee_id" placeholder="Enter Employee ID" required>
                                  </div><!-- form-group -->
                                  
                        
                                    <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <label for="exampleInputEmail1">First name  *</label>
                                        @error('f_name')
                                      <span class="alert alert-danger">{{$message}}</span>
                                   @enderror
                                        <input type="text" class="form-control" value="{{$datas[0]->f_name}}" name="f_name"  placeholder="Enter first name" required>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <label for="exampleInputEmail1">Last name  *</label>
                                        @error('l_name')
                                      <span class="alert alert-danger">{{$message}}</span>
                                   @enderror
                                        <input type="text" class="form-control" value="{{$datas[0]->l_name}}" name="l_name"  placeholder="Enter last name" required>
                                    </div>
                        
                                    <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                      <label>Phone *</label>
                                      @error('phone')
                                      <span class="alert alert-danger">{{$message}}</span>
                                   @enderror
                                      <input  type="text" class="form-control" value="{{$datas[0]->phone}}" name="phone" placeholder="Enter phone number" required>
                                    </div><!-- form-group -->
                                  
                                
                                    <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <label>Designation *</label>
                                        @error('designation')
                                      <span class="alert alert-danger">{{$message}}</span>
                                   @enderror
                                        <input type="text" class="form-control" value="{{$datas[0]->designation}}" name="designation" placeholder="Enter Designation" required>
                                      </div><!--form-group -->
                        
                                      <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <label>Role Permission</label>
                                      <input type="text" value="{{$datas[0]->role_permission }}" disabled class="form-control" >
                                      </div>
                                      <!-- form-group -->
                        
                  
                                      <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <label for="exampleInputEmail1">Date of hire </label>
                                        <input type="text" value="{{ date('d-m-Y', strtotime($datas[0]->date_of_hire))}}" disabled class="form-control" >
                                    </div>
                        
                        
                                    <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                      <label>Address *</label>
                                      @error('address')
                                      <span class="alert alert-danger">{{$message}}</span>
                                   @enderror
                                      <textarea class="form-control" name="address" placeholder="Enter  Address" required>{{$datas[0]->address}}</textarea>
                                    </div>
                                    
                        </div>
                        
                        
                        <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                                    <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;"><i class="fas fa-user-lock"></i>  Login  details </label>
                        <div class="form-devider"></div>
                        </div>
                        </div>
                        
                        
                        <div class="row ">
                                 
                                    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                      <label>Email *</label>
                                      @error('email')
                                      <span class="alert alert-danger">{{$message}}</span>
                                   @enderror
                                      <input type="email" value="{{$datas[0]->email}}" name="email" class="form-control" placeholder="Enter  email" required>
                                      <a href="#" class="show_password" style="
                                      float: right;
                                      font-size: 10px;
                                  ">Change Password</a>
                                    </div><!-- form-group -->
                                    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4 password_change">
                                      <label>Password *</label>
                                      <input type="password" name="password" class="form-control" id="password" placeholder="Enter password">
                                    </div><!-- form-group -->
                                    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4 password_change">
                                        <label>Confirm Password *</label>
                                        <input type="password" name="c_password" class="form-control" placeholder="Confirm Password">
                                    </div><!-- form-group -->
                          
                        
                                   
                        </div>
                        
                        
                        <div class="row">
                        <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                          <button type="submit" class="btn btn-primary btn-rounded " style="float: right;"><i class="fas fa-save"></i> Update</button>
                          <div>
                          
                    </div>
                          </div>
                          </div>
                        </form>
                        
                        </div>

          

                  <div class="mg-b-20"></div>

                </div><!-- az-profile-body -->
              </div><!-- az-content-body -->
            </div><!-- container -->
          </div>

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
                $('.password_change').hide();
          $('.show_password').click(function(){
            $('.password_change').show();
          })

                $("#commentForm").validate({
                    rules: {
                      pro_pic:{
                        extension: "jpeg|png|jpg",
                      },
                      f_name:{
                          required: true,
                          minlength: 1,
                          maxlength: 115
                       },
                       l_name:{
                          required: true,
                          minlength: 1,
                           maxlength: 115
                       },
                       employee_id:{
                         // required: true,
                           minlength: 1,
                           maxlength: 115
                       },
                       phone:{
                           required: true,
                           number: true,
                           minlength: 10,
                           maxlength: 12
                       },
                       designation:{
                         required: true,
                          minlength: 1,
                          maxlength: 115
                       },
                       address:{
                         required: true,
                         minlength: 1,
                         maxlength: 300
                       },
                       email:{
                          required: true,
                          email: true,
                          remote: "<?= url('') ?>/check-email-profile?module=profile_reg" 
                       },
                       password:{
                          minlength: 5,
                          maxlength: 12
                       },
                       c_password:{
                           equalTo : '[name="password"]'
                       }
                    },
                    messages: {
                    email: {
                        remote:"given email address is already taken",
                    },
          
                   }
             
                  });


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
                $('#phoneMask').mask('9999999999');
                $('.select2').select2({
                    placeholder: 'Choose one',
                    searchInputPlaceholder: 'Search'
                  });
              });
            </script>
@stop