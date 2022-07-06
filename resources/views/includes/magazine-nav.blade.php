<?php
        $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
?>


@if (in_array('edition.list',config('permission')) ||in_array('authors.list',config('permission')) ) 

<div class="az-dashboard-nav">
    <nav class="nav">
       @if (in_array('magazine.list',config('permission')) ||in_array('authors.list',config('permission')) ) 
        <a class="nav-link @if (in_array($Action, ['Magazine.index'])) {{'active'}} @endif " href="{{ url('magazine') }}">Magazine</a> 
       @endif
       @if (in_array('edition.list',config('permission')) ||in_array('authors.list',config('permission')) ) 
        <a class="nav-link @if (in_array($Action, ['Magazine.edition'])) {{'active'}} @endif " href="{{ url('magazine/edition') }}">Edition </a>
       @endif
       @if (in_array('authors.list',config('permission')) ||in_array('authors.list',config('permission')) ) 
         <a class="nav-link @if (in_array($Action, ['Magazine.authors'])) {{'active'}} @endif " href="{{ url('magazine/authors') }}">Authors </a>
       @endif
      </nav>
    <nav class="nav">
    </nav> 
</div>

@endif