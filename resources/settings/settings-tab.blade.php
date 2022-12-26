<?php
$routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
?>
  <nav class="nav">
    @if (in_array('user.list',config('permission'))) 
       <a class="nav-link @if (in_array($Action,['Organization.users'])) {{'active'}} @endif" href="">Users</a>
    @endif
    @if (in_array('role.list',config('permission')))  
    <a class="nav-link @if (in_array($Action,['Organization.role','Organization.role_permission'])) {{'active'}} @endif"  href="">Role </a>
  @endif
    @if (in_array('module.list',config('permission'))) 
      <a class="nav-link @if (in_array($Action,['Organization.module'])) {{'active'}} @endif"  href="">Module</a>
    @endif
 
    @if (in_array('permission.list',config('permission'))) 
       <a class="nav-link @if (in_array($Action,['Organization.permission'])) {{'active'}} @endif"  href=""> Permission </a>
    @endif
  </nav>