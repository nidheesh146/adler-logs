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

    <!-- azia CSS -->
    <link rel="stylesheet" href="<?= url('') ?>/css/azia.css">
    <link rel="stylesheet" href="<?= url('') ?>/css/azia.new.css">

  </head>
  <body class="az-body" style='background-image: url("<?= url('') ?>/img/books.jpeg")'>

    <div class="az-signin-wrapper">
      <div class="az-card-signin" style="background: white;">
        <h1 class="az-logo-login"><img  class="wd-45 ht-40 mg-l-10 bd bd-gray-500 rounded-10" src="<?= url('') ?>/img/bookstore.jpeg">&nbsp;KSSP</h1>
       <span style="border-bottom: 0.001cm solid #cdd4e0;margin-bottom: 19px;"></span>
        <div class="az-signin-header">
          <h2>Welcome back!</h2>
          <h4>Please sign in to continue</h4>
           
          
              @if ($validator->errors()->first('Action')) 
              <div class="alert alert-danger mg-b-0" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                {{ $validator->errors()->first('Action') }}
              </div>
              @endif

              @if ($validator->errors()->first('Token')) 
              <div class="alert alert-danger mg-b-0" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                {{ $validator->errors()->first('Token') }}
              </div>
              @endif
              @if ($validator->errors()->first('auth')) 
              <div class="alert alert-danger mg-b-0" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
                {{ $validator->errors()->first('auth') }}
              </div>
              @endif

          <form action="{{ url('') }}" id="commentForm" method="POST">
            {{ csrf_field() }}

            <div class="form-group">
              <label>Email</label>
              <input type="text" name="email" class="form-control" placeholder="Enter your email" >
             @if ($validator->errors()->first('email')) 
              <label id="email-error" class="error" for="email">{{ $validator->errors()->first('email') }}</label>
              @endif

            </div><!-- form-group -->
            <div class="form-group">
              <label>Password</label>
              <input type="password" name="password" class="form-control" placeholder="Enter your password" >
              @if ($validator->errors()->first('password')) 
              <label id="email-password" class="error" for="email">{{ $validator->errors()->first('password') }}</label>
              @endif
            </div><!-- form-group -->

            <button class="btn btn-az-primary btn-block"><span class="spinner-border spinner-button spinner-border-sm" style="display:none;"
              role="status" aria-hidden="true"></span> Sign In</button>
          </form>
        </div><!-- az-signin-header -->
        
        <div class="az-signin-footer">
          <p ><a href="<?= url('') ?>/forgot-password">Forgot password?</a></p>
          {{-- <p >Don't have an account? <a href="<?= url('') ?>/registration">Create an account
          </a></p> --}}
        </div><!-- az-signin-footer -->
      </div><!-- az-card-signin -->
    </div><!-- az-signin-wrapper -->

    <script src="<?= url('') ?>/lib/jquery/jquery.min.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('') ?>/js/jquery.validate.js"></script>
    <script src="<?= url('') ?>/js/additional-methods.js"></script>
    <script src="<?= url('') ?>/lib/ionicons/ionicons.js"></script>

    <script src="<?= url('') ?>/js/azia.js"></script>
    <script>
      $(function(){
        'use strict'


        $("#commentForm").validate({
            rules: {
               email:{
                  required: true,
                   email: true,
               },
               password:{
                  required: true,

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
