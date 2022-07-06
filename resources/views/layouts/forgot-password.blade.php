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

  </head>
  <style>
    .az-card-signin {
    height: 368px;
    }
  </style>
  <body class="az-body">

    <div class="az-signin-wrapper">
      <div class="az-card-signin">
        <h1 class="az-logo-login"><img  class="wd-45 ht-40 mg-l-10 bd bd-gray-500 rounded-10" src="<?=url('');?>/img/bookstore.jpeg" >&nbsp;KSSP</h1>
       <span style="border-bottom: 0.001cm solid #cdd4e0;margin-bottom: 19px;"></span>
        <div class="az-signin-header">
        <p>Please enter your username or email address. You will receive an email message with instructions on how to reset your password.</p>
          <form action="<?=url('');?>">
            <div class="form-group">
              <label>Email</label>
              <input type="text" class="form-control" placeholder="Enter your email" value="demo@bootstrapdash.com">
            </div><!-- form-group -->
           
            <button class="btn btn-az-primary btn-block">Get New Password</button>
          </form>
        </div><!-- az-signin-header -->
        
        <div class="az-signin-footer">
          <p ><a href="<?=url('');?>">Sign in?</a></p>

        </div><!-- az-signin-footer -->
      </div><!-- az-card-signin -->
    </div><!-- az-signin-wrapper -->

    <script src="<?=url('');?>/lib/jquery/jquery.min.js"></script>
    <script src="<?=url('');?>/lib/ionicons/ionicons.js"></script>
    <script src="<?= url('') ?>/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?=url('');?>/js/azia.js"></script>
    <script>
      $(function(){
        'use strict'

      });
    </script>
  </body>
</html>
