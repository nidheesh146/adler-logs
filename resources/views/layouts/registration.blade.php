<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Meta -->
    <meta name="description" content="KSSP - Kerala Sasthra Sahithya Parishad">
    <meta name="author" content="KSSP - Kerala Sasthra Sahithya Parishad">
    <title>{{ config('app.title') }}</title>
    <!-- vendor css -->
      <link href="<?= url('') ?>/lib/fontawesome-free/css/all.min.css" rel="stylesheet">
      <link href="<?= url('') ?>/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
      <link href="<?= url('') ?>/lib/typicons.font/typicons.css" rel="stylesheet">
      <link href="<?= url('') ?>/lib/spectrum-colorpicker/spectrum.css" rel="stylesheet">
      <link href="<?= url('') ?>/lib/select2/css/select2.min.css" rel="stylesheet">
      <link href="<?= url('') ?>/lib/ion-rangeslider/css/ion.rangeSlider.css" rel="stylesheet">
      <link href="<?= url('') ?>/lib/ion-rangeslider/css/ion.rangeSlider.skinFlat.css" rel="stylesheet">
      <link href="<?= url('') ?>/lib/amazeui-datetimepicker/css/amazeui.datetimepicker.css" rel="stylesheet">
      <link href="<?= url('') ?>/lib/jquery-simple-datetimepicker/jquery.simple-dtpicker.css" rel="stylesheet">
      <link href="<?= url('') ?>/lib/pickerjs/picker.min.css" rel="stylesheet">
      <!-- azia CSS -->
      <link rel="stylesheet" href="<?= url('') ?>/css/azia.css">
      <link rel="stylesheet" href="<?= url('') ?>/css/azia.new.css">
   </head>
   <body class="az-body" style="    overflow-x: hidden;
      ">
      <div class="az-signup-wrapper">
         <div class="az-column-signup-left">
            <div>
               <img  class="wd-50 ht-50 bd bd-gray-500 rounded" src="<?= url('') ?>/img/bookstore.jpeg">
               <!-- <h1 class="az-logo">az<span>i</span>a</h1> -->
               <br>      <br>
               <h5>Kerala Sasthra Sahithya Parishad
               </h5>
               <p>Kerala Sasthra Sahithya Parishad is a progressive outfit in the state of Kerala, India. It was conceived as a people's science movement. At the time of its founding in 1962 it was a 40-member group consisting of science writers and teachers with an interest in science from a social perspective. Over the past four decades its membership has grown to over 50,000 individuals, in more than 1,300 units spread all over Kerala. In 1996, the group received the Right Livelihood Award "for its major contribution to a model of development rooted in social justice and popular participation. KSSP chose as its mission, the challenge of arming people with the tools of science and technology so that they can reverse this process of monopolizing the benefits of science and technology by a privileged minority. In 1974, KSSP decided to become a people's science movement and adopted "science for social revolution" as its motto
               </p>
               <!-- <p>Browse our site and see for yourself why you need Azia.</p> -->
               <a href="https://kssp.in" class="btn btn-outline-indigo">Learn More</a>
            </div>
         </div>
         <!-- az-column-signup-left -->
         <div class="az-column-signup">
            <div class="az-signup-header">
               <!-- <h2>Get Started</h2> -->
               <h4>It's free to signup and only takes a minute.</h4>
               <form   id="commentForm" method="POST">
                  {{ csrf_field() }}  

                  <div class="row">
                     <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;"><i class="fas fa-address-card"></i> Basic details</label>
                        <div  class="form-devider" style=" border-bottom: 1px solid #cdd4e0;margin: 2px 0 0px 0px;"></div>
                     </div>
                  </div>
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
                  <div class="row ">
                     <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <label>Organisation / Agent name *</label>
                        <input type="text" name="orgname"  value="{{old('orgname')}}"  class="form-control" placeholder="Enter organisation / Agent name *">
                     </div>
                     <!-- form-group -->
                     <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <label>Phone Number *</label>
                        <input name="phone" type="text" value="{{old('phone')}}"  class="form-control" placeholder="Enter phone number *">
                     </div>
                     <!-- form-group -->
                     <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <label>Email *</label>
                        <input type="email" name="email" value="{{old('email')}}"  class="form-control" placeholder="Enter email-ID *">
                     </div>
                     <!-- form-group -->
                     <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <label>Owner name </label>
                        <input type="text" name="owner_name" value="{{old('owner_name')}}"  class="form-control" placeholder="Enter owner name (optional)">
                     </div>
                     <!-- form-group -->
                     <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <label>Address *</label>
                        <textarea class="form-control" name="address" placeholder="Enter address *">{{old('address')}}</textarea>
                     </div>
                     <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <label>Comments </label>
                        <textarea class="form-control" name="comments" placeholder="Enter your comment here ....">{{old('comments')}}</textarea>
                     </div>
                  </div>
                  {{-- <div class="row">
                     <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;"><i class="fas fa-sitemap"></i> Organizational structure</label>
                        <div  class="form-devider" style=" border-bottom: 1px solid #cdd4e0;margin: 2px 0 0px 0px;"></div>
                     </div>
                  </div> --}}
                  {{-- <div class="row ">
                     <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <label>Organisation Type *</label>
                        <select class="form-control " style="color:#7988a1 ;">
                           <option label="Choose your organization type"></option>
                           <option value="Firefox">Mekhala</option>
                           <option value="Chrome">Unit</option>
                           <option value="Safari">Agent ( Franchise / Teachers )</option>
                        </select>
                     </div>
                     <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <label>State *</label>
                        <select class="form-control select2">
                           <option label="Choose one"></option>
                           <option value="Firefox">kerala</option>
                           <!-- <option value="Firefox">Tamilnadu</option> -->
                        </select>
                     </div>
                     <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <label>District *</label>
                        <select class="form-control select2">
                           <option label="Choose one"></option>
                           <option value="Firefox">Palakkad</option>
                           <option value="Chrome">Kannur</option>
                        </select>
                     </div>
                     <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <label>Mekhala *</label>
                        <select class="form-control select2">
                           <option label="Choose one"></option>
                           <option value="Firefox">Mekhala A</option>
                           <option value="Chrome">Mekhala B</option>
                           <option value="Safari">Mekhala C</option>
                        </select>
                     </div>
                  </div> --}}
                  <div class="row">
                     <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin: 0px;">
                        <label style="color: #3f51b5;font-weight: 500;margin-bottom:2px;"><i class="fas fa-user-shield"></i> Super Admin ( Login user details )</label>
                        <div  class="form-devider" style=" border-bottom: 1px solid #cdd4e0;margin: 2px 0 0px 0px;"></div>
                     </div>
                  </div>
                  <div class="row ">
                     <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <label>Email *</label>
                        <input type="email"  name="admin_email" value="{{old('admin_email')}}" class="form-control" placeholder="Enter admin email">
                     </div>
                     <!-- form-group -->
                     <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <label>Password *</label>
                        <input type="password" name="password" value="{{old('password')}}" id="admin_password" class="form-control" placeholder="Enter password">
                     </div>
                     <!-- form-group -->
                     <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <label>Confirm password *</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password">
                     </div>
                     <!-- form-group -->
                     <div class="form-group col-sm-12 col-md-6 col-lg-6 col-xl-6 row" style="margin:0px">
                        <label style="width: 100%;">Captcha *</label>
                       
                        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 captcha" style="padding-left:0px;padding-right:0px">
                        <span>{!! captcha_img() !!}</span>
                       <i  class=" fas fa-redo" id="reload" data-toggle="tooltip" data-placement="top" title="Reset captcha" style="cursor: pointer;padding: 0 0 0 10px;
                       "></i>

                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6" style="padding-left:0px;padding-right:0px">
                        <input type="text" name="captcha" class="form-control" placeholder="Enter captcha">
                        </div>
                     </div>
                     <!-- form-group -->
                  </div>
                  <div class="row ">
                     <div class="form-group col-md-12">
                        <button class="btn btn-az-primary btn-block">Create Account</button>
                        <p>Already have an account? <a href="<?= url('') ?>">Sign In</a></p>
                        <!-- <div class="row row-xs">
                           <div class="col-sm-6"><button class="btn btn-block"><i class="fab fa-facebook-f"></i> Signup with Facebook</button></div>
                           <div class="col-sm-6 mg-t-10 mg-sm-t-0"><button class="btn btn-primary btn-block"><i class="fab fa-twitter"></i> Signup with Twitter</button></div>
                           </div>row -->
                     </div>
                  </div>
               </form>
            </div>
            <!-- az-signup-header -->
            <div class="az-signup-footer">
            </div>
            <!-- az-signin-footer -->
         </div>
         <!-- az-column-signup -->
      </div>
      <!-- az-signup-wrapper -->
      <script src="<?= url('') ?>/lib/jquery/jquery.min.js"></script>
      <script src="<?= url('') ?>/js/jquery.validate.js"></script>
      <script src="<?= url('') ?>/js/additional-methods.js"></script>
      <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
      <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
      <script src="<?= url('') ?>/js/azia.js"></script>
      <script src="<?= url('') ?>/lib/jquery-ui/ui/widgets/datepicker.js"></script>
      <script src="<?= url('') ?>/lib/jquery.maskedinput/jquery.maskedinput.js"></script>
      <script src="<?= url('') ?>/lib/spectrum-colorpicker/spectrum.js"></script>
      <script src="<?= url('') ?>/lib/select2/js/select2.min.js"></script>
      <script src="<?= url('') ?>/lib/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
      <script src="<?= url('') ?>/lib/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js"></script>
      <script src="<?= url('') ?>/lib/jquery-simple-datetimepicker/jquery.simple-dtpicker.js"></script>
      <script src="<?= url('') ?>/lib/pickerjs/picker.min.js"></script>
      <script>
         // Additional code for adding placeholder in search box of select2
         (function($) {


          $("#commentForm").validate({
            rules: { 
               orgname: {
                  required: true,
                  minlength: 3,
                  maxlength: 30
               },
               phone:{
                  required: true,
                   number: true,
                   minlength: 10,
                   maxlength: 12
               },
               email:{
                  required: true,
                   email: true,
                   remote: "<?=url('');?>/check-email?module=org_reg" 
               },
               address:{
                  required: true,
               },
               password:{
                  required: true,
                  minlength: 5,
                  maxlength: 12
               },
               password_confirmation:{
                  required: true,
                  equalTo : '[name="password"]'
               },
               captcha:{
                  required: true,
               },
               admin_email:{
                   required: true,
                   email: true,
                   remote: "<?=url('');?>/check-email?module=admin_email" 
               }
            },
            messages: {
            email: {
                remote:"given email address is already taken",
            },
            admin_email:{
               remote:"given email address is already taken",
               }
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
        //   $('#phoneMask').mask('999999999999');
           $('.select2').select2({
               placeholder: 'Choose one',
               searchInputPlaceholder: 'Search'
             });
            $('[data-toggle="tooltip"]').tooltip();
         });
      </script>
      <script type="text/javascript">
        $('#reload').click(function () {
            $.ajax({
                type: 'GET',
                url: 'reload-captcha',
                success: function (data) {
                    $(".captcha span").html(data.captcha);
                }
            });
        });
    
    </script>
   </body>
</html>
