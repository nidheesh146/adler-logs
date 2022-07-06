@inject('Controller', 'App\Http\Controllers\Controller')
@php
       $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
     $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
       
@endphp

@if((config('org_pending_count')) > 0)
<style>
.az-header-message>a.new::before,
.az-header-notification>a.new::before {
  background-color: #ff0404;
}
</style>
@endif

<div class="az-header">
    <div class="container">
      <div class="az-header-left">
        <a href="index.html" class="az-logo" style="
          text-transform: uppercase;color: #1d263d;"><img class="wd-45 ht-40 mg-l-10 bd bd-gray-500 rounded-10"
            src="<?=url('');?>/img/logo.png" >&nbsp;ADLER</a>
        <a href="#" id="azMenuShow" class="az-header-menu-icon d-lg-none"><span></span></a>
      </div><!-- az-header-left -->
      <div class="az-header-menu">
        <div class="az-header-menu-header">
          <a href="index.html" style="
            text-transform: uppercase;color: #1d263d;" class="az-logo"><span></span> KSSP</a>
          <a href="#" class="close">&times;</a>
        </div><!-- az-header-menu-header -->
        <ul class="nav">








        </ul>
      </div><!-- az-header-menu -->
      <div class="az-header-right">
        <div class="dropdown az-header-notification" >
          {{-- <a href="#" class="new"><i class="fas fa-sign-out-alt"></i></a> --}}
          <div class="dropdown-menu" style="width: 10px;padding: 0px;">
          <a href="{{url('subscriber/logout');}}" class="dropdown-item" style="font-size: 18px;"><i class="typcn typcn-power-outline"></i> Sign Out</a>
          </div><!-- dropdown-menu -->
        </div><!-- az-header-notification -->

      </div><!-- az-header-right -->
    </div><!-- container -->
  </div><!-- az-header -->



