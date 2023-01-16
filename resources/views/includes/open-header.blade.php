@inject('Controller', 'App\Http\Controllers\Controller')
@php
       $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
        //echo $Action;
@endphp

<div class="az-header">
  <div class="container-fluid">
    <a href="#" class="az-logo" style="
    text-transform: uppercase;color: #1d263d;"><img class="wd-45 ht-40 mg-l-10 bd bd-gray-500 rounded-10"
      src="<?=url('');?>/img/alder_logo.png" >&nbsp;ADLER HEALTHCARE PVT. LTD</a>
      <br/>
      <!-- <p style="border-bottom:1px white;"> Plot No-A1 MIDC, Sadavali(Devrukh),  Tal- Sangmeshwar, Dist -Ratnagiri ,  PIN-415804, Maharashtra, India<br/>
            CIN :U33125PN2020PTC195161 <br/>
            Company GSTIN :27AAJCB3689C1J</p > -->
    <div class="az-header-left">
    </div><!-- az-header-left -->
    <div class="az-header-right">
    </div><!-- az-header-right -->
  </div><!-- container -->
</div><!-- az-header -->
