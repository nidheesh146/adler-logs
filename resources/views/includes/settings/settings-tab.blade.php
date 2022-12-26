<?php
$routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
        //echo $Action;
?>
  <div class="card bd-0">
    <div class="card-header bg-gray-400 bd-b-0-f pd-b-0">
        <nav class="nav nav-tabs">
          <a class="nav-link @if (in_array($Action,['RolePermission.roleList','RolePermission.rolePermission'])) {{'active'}} @endif"  href="{{ url('settings/role') }}">Role </a>
          <a class="nav-link @if (in_array($Action,['RolePermission.moduleList'])) {{'active'}} @endif"  href="{{ url('settings/module') }}">Module</a>
          <a class="nav-link @if (in_array($Action,['RolePermission.permissionList'])) {{'active'}} @endif"  href="{{ url('settings/permission') }}"> Permission </a>
      </nav>
    </div>
</div>
