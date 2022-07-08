<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Meta -->
    <meta name="description" content="KSSP - Kerala Sasthra Sahithya Parishad">
    <meta name="author" content="KSSP - Kerala Sasthra Sahithya Parishad">
    <title>{{config('app.title')}}</title>

    <!-- vendor css -->
    <link href="<?=url('');?>/lib/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="<?=url('');?>/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="<?=url('');?>/lib/typicons.font/typicons.css" rel="stylesheet">

    <!-- azia CSS -->
    <link rel="stylesheet" href="<?=url('');?>/css/azia.css">
    <link rel="stylesheet" href="<?= url('') ?>/css/azia.new.css">
  </head>
  <style>
    .az-card-signin {
    height: 368px;
    }
  </style>
  <body class="az-body">

    <div class="az-signin-wrapper">
  

      <div class="az-card-signin">
        <h1 class="az-logo-login"><img  class="wd-45 ht-40 mg-l-10 bd bd-gray-500 rounded-10" src="<?=url('');?>/img/bookstore.jpeg" >&nbsp; Reset  password</h1>
       <span style="border-bottom: 0.001cm solid #cdd4e0;margin-bottom: 19px;"></span>
        <div class="az-signin-header">
        <p><i class="fas fa-arrow-right"></i> The Password should have atleast Min-length : 5 , Max-length : 12<br><i class="fas fa-arrow-right"></i> Set a new password, confirm it and you’re done!</p>
          
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger "  role="alert" style="width: 100%;">
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          {{ $error }}
        </div>
       @endforeach
       {{-- @if (Session::get('success'))
       <div class="alert alert-success " style="width: 100%;">
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
           <i class="icon fa fa-check"></i> {{ Session::get('success') }}
       </div>
       @endif --}}
        <form  id="commentForm" method="post" >
            {{ csrf_field() }}
            <div class="form-group">
              <label>New Password</label>
              <input type="password"  name="password" class="form-control" placeholder="Enter New Password" value="">
            </div><!-- form-group -->
            <div class="form-group">
              <label>Confirm Password</label>
              <input type="password"  name="c_password" class="form-control" placeholder="Enter Confirm Password" value="">
            </div><!-- form-group -->
           
            <button class="btn btn-az-primary btn-block"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
              role="status" aria-hidden="true"></span> Reset Password</button>
          </form>
        </div><!-- az-signin-header -->
        
        <div class="az-signin-footer">
          <p ><a href="<?=url('');?>">Sign in?</a></p>

        </div><!-- az-signin-footer -->
      </div><!-- az-card-signin -->
    </div><!-- az-signin-wrapper -->


    <script src="<?= url('') ?>/lib/jquery/jquery.min.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('') ?>/js/jquery.validate.js"></script>
    <script src="<?= url('') ?>/js/additional-methods.js"></script>
    <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>
    <script>
      $(function(){
        'use strict'
        $("#commentForm").validate({
            rules: {
                      password:{
                          minlength: 5,
                          maxlength: 12
                       },
                       c_password:{
                           equalTo : '[name="password"]'
                       }
            },
            submitHandler: function(form) {
                    $('.spinner-button').show();
                    form.submit();
            }
          });

      });
    </script>
  </body>
</html>
