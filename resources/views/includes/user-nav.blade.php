<?php
$routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
?>
  <nav class="nav">
    @if (in_array('user.list',config('permission'))) 
       <a class="nav-link @if (in_array($Action,['Organization.users'])) {{'active'}} @endif" href="{{ url('organization/users/'.$org.'/'.$orgid) }}">Users</a>
    @endif
    @if (in_array('role.list',config('permission')))  
    <a class="nav-link @if (in_array($Action,['Organization.role','Organization.role_permission'])) {{'active'}} @endif"  href="{{ url('organization/role/'.$org.'/'.$orgid) }}">Role </a>
  @endif
    @if (in_array('module.list',config('permission'))) 
      <a class="nav-link @if (in_array($Action,['Organization.module'])) {{'active'}} @endif"  href="{{ url('organization/module/'.$org.'/'.$orgid) }}">Module</a>
    @endif
 
    @if (in_array('permission.list',config('permission'))) 
       <a class="nav-link @if (in_array($Action,['Organization.permission'])) {{'active'}} @endif"  href="{{ url('organization/permission/'.$org.'/'.$orgid) }}"> Permission </a>
    @endif
  </nav>
