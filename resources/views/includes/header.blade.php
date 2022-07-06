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
            src="<?=url('');?>/img/bookstore.jpeg" >&nbsp;KSSP</a>
        <a href="#" id="azMenuShow" class="az-header-menu-icon d-lg-none"><span></span></a>
      </div><!-- az-header-left -->
      <div class="az-header-menu">
        <div class="az-header-menu-header">
          <a href="index.html" style="
            text-transform: uppercase;color: #1d263d;" class="az-logo"><span></span> KSSP</a>
          <a href="#" class="close">&times;</a>
        </div><!-- az-header-menu-header -->
        <ul class="nav">

          <li class="nav-item @if (in_array($Action,['Dashboard.index'])) {{'active'}} @endif ">
            <a href="{{url('dashboard')}}" class="nav-link ">
              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                id="Layer_1" x="0px" y="0px" viewBox="0 0 512.009 512.009" style="enable-background:new 0 0 512.009 512.009;    width: 20px;
                height: 16px;
                margin-top: -4px;
            " xml:space="preserve">
                <g>
                  <g>
                    <g>
                      <path style="@if (in_array($Action,['Dashboard.index'])) {{'fill:#5b47fb;'}} @endif "
                        d="M256.009,42.671c-0.002,0-0.005,0-0.007,0c-0.001,0-0.001,0-0.002,0c-0.001,0-0.002,0-0.003,0     c-70.671,0.003-134.648,28.637-180.967,74.935c-0.016,0.016-0.034,0.029-0.05,0.045c-0.017,0.017-0.03,0.035-0.047,0.052     C28.688,163.976,0.072,227.867,0.011,298.445C0.011,298.521,0,298.595,0,298.671c0,0.073,0.01,0.143,0.011,0.215     c0.05,60.201,20.962,117.239,58.515,162.704c4.053,4.907,10.084,7.748,16.448,7.748h362.048c6.364,0,12.395-2.841,16.448-7.748     c37.607-45.53,58.539-102.65,58.539-162.919C512.009,157.289,397.391,42.671,256.009,42.671z M426.68,426.671H85.316     c-23.281-30.977-37.712-67.661-41.583-106.667h62.934c11.782,0,21.333-9.551,21.333-21.333c0-11.782-9.551-21.333-21.333-21.333     H43.734c4.259-42.905,21.23-82.066,47.091-113.671l14.32,14.32c8.331,8.331,21.839,8.331,30.17,0     c8.331-8.331,8.331-21.839,0-30.17l-14.321-14.321c31.605-25.864,70.765-42.837,113.672-47.098v62.941     c0,11.782,9.551,21.333,21.333,21.333c11.782,0,21.333-9.551,21.333-21.333V86.396c42.906,4.259,82.068,21.232,113.676,47.096     l-14.325,14.325c-8.331,8.331-8.331,21.839,0,30.17c8.331,8.331,21.839,8.331,30.17,0l14.326-14.326     c25.867,31.607,42.842,70.771,47.103,113.677h-62.95c-11.782,0-21.333,9.551-21.333,21.333c0,11.782,9.551,21.333,21.333,21.333     h62.95C464.409,359.001,449.97,395.686,426.68,426.671z" />
                      <polygon points="259.661,341.338 319.991,220.655 199.33,281.007    " />
                    </g>
                  </g>
                </g>
              </svg>
              Dashboard</a>
          </li>

@if (in_array('magazine.list',config('permission'))) 
          <li class="nav-item @if (in_array($Action,['Magazine.update','Magazine.index','Magazine.special_price',
          'Magazine.authors','Magazine.add_authors','Magazine.edition','Magazine.article']) ) {{'active'}} @endif ">
            <a href="{{url('magazine')}}" class="nav-link ">
              <svg xmlns="http://www.w3.org/2000/svg" style="
                margin-top: -5px;
                height: 20px;
                width: 18px;
            " xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="68.387px"
                height="68.387px" viewBox="0 0 68.387 68.387" style="enable-background:new 0 0 68.387 68.387;"
                xml:space="preserve">
                <g>
                  <path style="@if (in_array($Action,['Magazine.update','Magazine.index'])) {{'fill:#5b47fb;'}} @endif "
                    d="M61.847,14.207c-0.44-4.059-3.844-7.244-8.011-7.244H32.958L16.859,0.479C16.073,0.165,15.295,0,14.551,0   c-2.802,0-4.69,2.191-4.69,5.462v3.029c-1.83,1.311-3.067,3.361-3.321,5.715c-0.972,1.475-1.544,3.231-1.544,5.128v39.708   c0,5.16,4.184,9.344,9.343,9.344h39.709c5.16,0,9.343-4.184,9.343-9.344V19.334C63.391,17.438,62.818,15.681,61.847,14.207z    M11.029,7.807V5.462c0-2.609,1.382-4.294,3.522-4.294c0.596,0,1.225,0.133,1.874,0.393L29.83,6.963l2.897,1.168l0.883,0.356v0.812   v0.692v50.124h-0.656l-16.094-6.487c-3.214-1.296-5.83-5.283-5.83-8.887V10.622V10.54V9.131V7.807z M39.038,9.994v5.447   l2.272-1.911l2.271,1.911V9.994v-0.69H53.84c1.412,0,2.681,0.53,3.68,1.37c1.264,1.062,2.083,2.627,2.083,4.398v39.277   c0,3.18-2.58,5.769-5.763,5.769H34.782V9.994v-0.69h4.261L39.038,9.994L39.038,9.994z M30.173,24.732l-7.226-2.915V20.63   l7.226,2.916V24.732z M30.173,29.259l-7.226-2.915v-1.188l7.226,2.911V29.259z M30.173,33.825L15.719,28v-1.187l14.454,5.826   V33.825z M30.173,38.35l-14.454-5.825v-1.192l14.454,5.825V38.35z M30.173,42.916L15.719,37.09v-1.186l14.454,5.826V42.916z    M16.956,24.276l0.375-1.154l2.177,0.876l0.39,1.466l0.633,0.254l0.635,0.254l-2.026-7.239l-0.697-0.281l-0.693-0.277l-2.03,5.599   l0.624,0.243L16.956,24.276z M18.424,19.883v-0.004h0.002L18.424,19.883l0.762,2.882l-1.521-0.619L18.424,19.883z M48.358,43.605   c-0.378,0-0.755,0-1.133,0c0-1.62,0-3.235,0-4.854c0.388,0,0.779,0,1.167,0C48.368,40.363,48.437,42.049,48.358,43.605z    M51.37,37.649c0.851,1.339,1.599,2.789,2.427,4.145c-0.326,0.178-0.646,0.352-0.949,0.552c-0.894-1.31-1.621-2.778-2.488-4.115   C50.651,37.989,51.06,37.866,51.37,37.649z M44.214,37.649c0.34,0.157,0.628,0.378,0.985,0.522c-0.75,1.426-1.587,2.775-2.37,4.179   c-0.367-0.13-0.646-0.356-0.976-0.527C42.533,40.325,43.443,39.057,44.214,37.649z M57.052,38.568   c-1.394-0.789-2.828-1.528-4.179-2.367c0.167-0.375,0.34-0.744,0.55-1.072c1.373,0.825,2.785,1.614,4.145,2.459   C57.424,37.943,57.209,38.224,57.052,38.568z M37.982,37.556c1.43-0.785,2.814-1.61,4.206-2.427c0.16,0.369,0.365,0.68,0.557,1.013   c-1.298,0.898-2.787,1.599-4.145,2.427C38.384,38.24,38.153,37.932,37.982,37.556z M48.114,37.277   c1.982-0.142,3.47-1.433,4.084-3.039c0.723-1.887,0.038-4.005-1.255-5.16c-0.267-0.236-0.755-0.409-0.86-0.733   c-0.144-0.452-0.029-1.072-0.029-1.598c0-0.566,0-1.077,0-1.661c-1.25-0.41-3.246-0.41-4.484,0c0,0.535,0,1.09,0,1.661   c0,0.535,0.128,1.233-0.034,1.656c-0.066,0.178-0.306,0.292-0.459,0.402c-1.097,0.762-1.978,2.168-1.969,3.838   C43.124,35.32,45.329,37.474,48.114,37.277z M45.478,29.944c0.306-0.255,0.724-0.403,0.981-0.8   c0.344-0.535,0.273-1.282,0.273-2.211c0.611-0.183,1.496-0.133,2.148-0.033c-0.043,1.019-0.021,1.966,0.436,2.485   c0.224,0.257,0.65,0.417,0.952,0.68c0.723,0.633,1.136,1.676,1.071,2.733c-0.104,1.729-1.426,3.157-3.226,3.312   c-1.225,0.109-2.253-0.442-2.857-1.072c-0.521-0.542-0.977-1.408-0.981-2.394C44.271,31.375,44.816,30.49,45.478,29.944z" />
                </g>
              </svg>
              &nbsp;Magazine </a>
          </li>
  @endif


    @if (in_array('organization.list',config('permission'))) 
            <?php
            $kssp_org = ['Organization.index','Organization.create','Organization.users','Organization.module','Organization.role'
                          ,'Organization.permission','Organization.role_permission','Organization.add_user','Organization.edit',
                        'Organization.pending_list','Organization.assign_pending_org'];
            ?>
            <li class="nav-item @if (in_array($Action,$kssp_org) ) {{'active'}} @endif  ">
            <a href="{{url('organization/all')}}" class="nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 330 330" style="enable-background:new 0 0 330 330;
                    width: 16px;
                    margin-top: -8px;
                " xml:space="preserve">
                  <path  style="@if (in_array($Action,$kssp_org)) {{'fill:#5b47fb;'}} @endif " d="M280,232.302V165c0-8.284-6.716-15-15-15h-85V97.698c20.264-6.387,35-25.352,35-47.698c0-27.57-22.43-50-50-50  c-27.57,0-50,22.43-50,50c0,22.346,14.736,41.312,35,47.698V150H65c-8.284,0-15,6.716-15,15v67.302  C29.736,238.689,15,257.654,15,280c0,27.57,22.43,50,50,50c27.57,0,50-22.43,50-50c0-22.346-14.736-41.311-35-47.698V180h70v52.302  c-20.264,6.387-35,25.352-35,47.698c0,27.57,22.43,50,50,50c27.57,0,50-22.43,50-50c0-22.346-14.736-41.311-35-47.698V180h70v52.302  c-20.264,6.387-35,25.352-35,47.698c0,27.57,22.43,50,50,50c27.57,0,50-22.43,50-50C315,257.654,300.264,238.689,280,232.302z   M165,30c11.027,0,20,8.972,20,20s-8.973,20-20,20c-11.027,0-20-8.972-20-20S153.973,30,165,30z M65,300c-11.027,0-20-8.972-20-20  s8.973-20,20-20c11.027,0,20,8.972,20,20S76.027,300,65,300z M165,300c-11.027,0-20-8.972-20-20s8.973-20,20-20  c11.027,0,20,8.972,20,20S176.027,300,165,300z M265,300c-11.027,0-20-8.972-20-20s8.973-20,20-20c11.027,0,20,8.972,20,20  S276.027,300,265,300z"/>
               
                  </svg>
                &nbsp;Organization&nbsp;@if(config('org_pending_count') > 0 ) <span class="badge badge-pill badge-danger">{{config('org_pending_count')}}</span>@endif</a>
            </li>
      @endif


      @if(in_array('agent.list',config('permission'))) 
      <?php
      $kssp_agents = ['Agent.index','Agent.agentcreate','Agent.create','Agent.list_subscription','Agent.agent_invoice_list','Agent.all_agent_invoice_list'];
      ?>
         @if(config('organization')['type'] != 5)
         <li class="nav-item @if (in_array($Action,$kssp_agents) ) {{'active'}} @endif  ">
          <a href="{{url('agent/agents')}}" class="nav-link">
            <i class="fas fa-user-tie" style="margin-top: -5px;"></i>
              &nbsp;Agents&nbsp;</a>
          </li>
          @else
          <li class="nav-item @if (in_array($Action,$kssp_agents) ) {{'active'}} @endif  ">
            <a href="{{url('agent-subscription/create/agent/'.$Controller->hashEncode(config('subscriber')['subscriber_id']))}}" class="nav-link">
              <i class="fas fa-paper-plane" style="margin-top: -5px;"></i>
                &nbsp;Order request&nbsp;</a>
            </li>
            @endif

      @endif


      @if (in_array('subscribers.list',config('permission'))) 
          <?php
             $kssp_subscribers= ['Subscribers.index','Subscribers.create','Subscription.create','Subscribers.waiting_for_renew'
                                  ,'Subscription.list_subscription'];
          ?>
 
          <li class="nav-item @if (in_array($Action, $kssp_subscribers) ) {{'active'}} @endif">
          <a href="{{url('subscribers/subscribers')}}" class="nav-link">
              <svg xmlns="http://www.w3.org/2000/svg" style="margin-top: -5px;width: 17px;
              " xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 459.864 459.864" style="enable-background:new 0 0 459.864 459.864;" xml:space="preserve">
                  <g>
                    <g>
                      <g>
                        <path  style="@if (in_array($Action,$kssp_subscribers)) {{'fill:#5b47fb;'}} @endif " d="M395.988,193.978c-6.215,8.338-13.329,15.721-21.13,21.941c33.044,21.079,55.005,58.06,55.005,100.077     c0,13.638-20.011,23.042-31.938,27.434c-9.301,3.425-20.237,6.229-32.19,8.347c0.387,5.05,0.586,10.153,0.586,15.3     c0,4.455-0.389,9.647-1.518,15.299c16.064-2.497,30.815-6.128,43.488-10.794c42.626-15.694,51.573-38.891,51.573-55.586     C459.863,265.52,434.565,220.85,395.988,193.978z"/>
                        <path  style="@if (in_array($Action,$kssp_subscribers)) {{'fill:#5b47fb;'}} @endif " d="M311.244,15.147c-18.734,0-36.411,7.436-50.724,21.145c5.632,7.212,10.553,15.004,14.733,23.246     c9.592-10.94,22.195-17.602,35.991-17.602c29.955,0,54.325,31.352,54.325,69.888s-24.37,69.888-54.325,69.888     c-9.01,0-17.507-2.853-24.995-7.868c-2.432,8.863-5.627,17.42-9.53,25.565c10.642,5.952,22.36,9.093,34.525,9.093     c45.83,0,81.115-44.3,81.115-96.678C392.359,59.441,357.069,15.147,311.244,15.147z"/>
                        <path  style="@if (in_array($Action,$kssp_subscribers)) {{'fill:#5b47fb;'}} @endif " d="M259.999,226.28c-6.487,8.205-13.385,15.089-20.57,20.892c40.84,24.367,68.257,68.991,68.257,119.904     c0,17.196-24.104,28.639-38.472,33.929c-26.025,9.583-62.857,15.078-101.053,15.078c-38.196,0-75.029-5.495-101.054-15.078     c-14.368-5.29-38.472-16.732-38.472-33.929c0-50.914,27.417-95.538,68.257-119.904c-7.184-5.802-14.083-12.687-20.57-20.892     C30.403,256.335,0,308.218,0,367.077c0,18.127,9.926,43.389,57.213,60.8c29.496,10.861,68.898,16.841,110.947,16.841     c42.049,0,81.451-5.98,110.947-16.841c47.287-17.411,57.213-42.673,57.213-60.8C336.32,308.218,305.918,256.335,259.999,226.28z"/>
                        <path  style="@if (in_array($Action,$kssp_subscribers)) {{'fill:#5b47fb;'}} @endif "d="M168.16,242.764c53.003,0,93.806-51.234,93.806-111.804c0-60.571-40.808-111.804-93.806-111.804     c-52.995,0-93.806,51.223-93.806,111.804C74.354,191.542,115.169,242.764,168.16,242.764z M168.16,47.79     c35.936,0,65.171,37.31,65.171,83.169s-29.236,83.169-65.171,83.169s-65.171-37.31-65.171-83.169S132.225,47.79,168.16,47.79z"/>
                      </g>
                    </g>
                  </g>
              </svg>
              &nbsp;Subscribers</a>
          </li>
       @endif




          <li class="nav-item @if (in_array($Action,['Report.print_order','Report.commission_view','Report.commission_view_org','Report.sale_commission_list'])) {{'active'}} @endif ">
          <a 
          
          @if(config('organization')['type'] == 1)
          href="{{url('commission-view')}}" 
          @else
          href="{{url('commission-view-org/'.$Controller->hashEncode(config('organization')['org_id']))}}?date={{date("m-Y")}}" 
          @endif
          
          class="nav-link">
              <svg xmlns="http://www.w3.org/2000/svg" style="margin-right: 2px;
              width: 16px;
              margin-top: -5px;
          " xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                viewBox="0 0 192.287 192.287" style="enable-background:new 0 0 192.287 192.287;" xml:space="preserve">
                <g>
                  <path
                 
                    d="M122.901,0H19.699v192.287h152.889v-142.6L122.901,0z M146.981,45.299h-19.686V25.612L146.981,45.299z M34.699,177.287V15   h77.596v37.799c0,4.142,3.357,7.5,7.5,7.5h37.793v116.988H34.699z" />
                  <rect x="53.141" y="149.004" width="86.006" height="10" />
                  <rect x="53.141" y="55.101" width="51.058" height="10" />
                  <polygon
                    points="121.248,86.935 126.79,86.935 105.371,108.353 88.623,91.605 51.597,128.634 58.667,135.706 88.623,105.748    105.371,122.495 133.861,94.005 133.861,99.535 143.861,99.535 143.861,76.935 121.248,76.935  " />
                  <rect x="53.141" y="33.283" width="51.058" height="10" />
                </g>
              </svg>
              Reports</a>
            {{-- <div class="az-menu-sub">
              <nav class="nav">
              <a href="{{url('report/subscribers/order')}}" class="nav-link"> Magazine Order</a>
                <a href="#" class="nav-link">Commission</a>
                <a href="#" class="nav-link">Logs</a>
              </nav> --}}
            {{-- </div><!-- az-menu-sub --> --}}
          </li>



          @if (in_array('commission.edit',config('permission')) || in_array('module.list',config('permission')) || 
          in_array('permission.list',config('permission')) || in_array('role.list',config('permission'))) 
          <li class="nav-item  @if (in_array($Action,['Commission.create'])) {{'active'}} @endif ">
            <a href="" class="nav-link with-sub">
              <svg xmlns="http://www.w3.org/2000/svg" style="margin-right: 2px;
              width: 18px;margin-top: -2px; height:24px;" 
                viewBox="0 0 1024 1024" class="icon">
                <path
                  d="M924.8 625.7l-65.5-56c3.1-19 4.7-38.4 4.7-57.8s-1.6-38.8-4.7-57.8l65.5-56a32.03 32.03 0 0 0 9.3-35.2l-.9-2.6a443.74 443.74 0 0 0-79.7-137.9l-1.8-2.1a32.12 32.12 0 0 0-35.1-9.5l-81.3 28.9c-30-24.6-63.5-44-99.7-57.6l-15.7-85a32.05 32.05 0 0 0-25.8-25.7l-2.7-.5c-52.1-9.4-106.9-9.4-159 0l-2.7.5a32.05 32.05 0 0 0-25.8 25.7l-15.8 85.4a351.86 351.86 0 0 0-99 57.4l-81.9-29.1a32 32 0 0 0-35.1 9.5l-1.8 2.1a446.02 446.02 0 0 0-79.7 137.9l-.9 2.6c-4.5 12.5-.8 26.5 9.3 35.2l66.3 56.6c-3.1 18.8-4.6 38-4.6 57.1 0 19.2 1.5 38.4 4.6 57.1L99 625.5a32.03 32.03 0 0 0-9.3 35.2l.9 2.6c18.1 50.4 44.9 96.9 79.7 137.9l1.8 2.1a32.12 32.12 0 0 0 35.1 9.5l81.9-29.1c29.8 24.5 63.1 43.9 99 57.4l15.8 85.4a32.05 32.05 0 0 0 25.8 25.7l2.7.5a449.4 449.4 0 0 0 159 0l2.7-.5a32.05 32.05 0 0 0 25.8-25.7l15.7-85a350 350 0 0 0 99.7-57.6l81.3 28.9a32 32 0 0 0 35.1-9.5l1.8-2.1c34.8-41.1 61.6-87.5 79.7-137.9l.9-2.6c4.5-12.3.8-26.3-9.3-35zM788.3 465.9c2.5 15.1 3.8 30.6 3.8 46.1s-1.3 31-3.8 46.1l-6.6 40.1 74.7 63.9a370.03 370.03 0 0 1-42.6 73.6L721 702.8l-31.4 25.8c-23.9 19.6-50.5 35-79.3 45.8l-38.1 14.3-17.9 97a377.5 377.5 0 0 1-85 0l-17.9-97.2-37.8-14.5c-28.5-10.8-55-26.2-78.7-45.7l-31.4-25.9-93.4 33.2c-17-22.9-31.2-47.6-42.6-73.6l75.5-64.5-6.5-40c-2.4-14.9-3.7-30.3-3.7-45.5 0-15.3 1.2-30.6 3.7-45.5l6.5-40-75.5-64.5c11.3-26.1 25.6-50.7 42.6-73.6l93.4 33.2 31.4-25.9c23.7-19.5 50.2-34.9 78.7-45.7l37.9-14.3 17.9-97.2c28.1-3.2 56.8-3.2 85 0l17.9 97 38.1 14.3c28.7 10.8 55.4 26.2 79.3 45.8l31.4 25.8 92.8-32.9c17 22.9 31.2 47.6 42.6 73.6L781.8 426l6.5 39.9zM512 326c-97.2 0-176 78.8-176 176s78.8 176 176 176 176-78.8 176-176-78.8-176-176-176zm79.2 255.2A111.6 111.6 0 0 1 512 614c-29.9 0-58-11.7-79.2-32.8A111.6 111.6 0 0 1 400 502c0-29.9 11.7-58 32.8-79.2C454 401.6 482.1 390 512 390c29.9 0 58 11.6 79.2 32.8A111.6 111.6 0 0 1 624 502c0 29.9-11.7 58-32.8 79.2z" />
              </svg>Settings</a>
            <div class="az-menu-sub">
              <nav class="nav">

              @if (in_array('commission.edit',config('permission')))   
                 <a href="{{url('commission/update')}}" class="nav-link">Commission Setup</a>
              @endif
              @if (in_array('module.list',config('permission')))  
              <a href="{{url('organization/module/all/'.$Controller->hashEncode(config('organization')['org_id']))}}" class="nav-link">Modules</a>
              @endif
              @if (in_array('role.list',config('permission')))  
              <a href="{{url('organization/role/all/'.$Controller->hashEncode(config('organization')['org_id']))}}" class="nav-link">User roles</a>
              @endif
              @if (in_array('permission.list',config('permission')))  
              <a href="{{url('organization/permission/all/'.$Controller->hashEncode(config('organization')['org_id']))}}" class="nav-link">Permissions</a>
              @endif
                
              </nav>
            </div>
          </li>
         @endif








        </ul>
      </div><!-- az-header-menu -->
      <div class="az-header-right">

        <div class="dropdown az-header-notification">
          <a href="#" class="new"><i class="typcn typcn-bell"></i></a>
          <div class="dropdown-menu">
            <div class="az-dropdown-header mg-b-20 d-sm-none">
              <a href="#" class="az-header-arrow"><i class="icon ion-md-arrow-back"></i></a>
            </div>
            <h6 class="az-notification-title">Notifications</h6>
            <p class="az-notification-text">You have {{ ( config('org_pending_count') )}}  notification</p>
            <div class="az-notification-list">
             
             @if(config('org_pending_count') > 0)
            <a href = "{{url('org/pending')}}">
              <div class="media new">
                <i class="fas fa-sitemap"></i>&nbsp;<span class="badge badge-pill badge-danger">{{config('org_pending_count')}}</span>
                 <div class="media-body">
                  <p> <strong>Pending Organizations : </strong> You have {{config('org_pending_count')}} pending organization</p>
                  {{-- <span>Mar 15 12:32pm</span> --}}
                 </div>
              </div>
             </a>
              @endif

            </div><!-- az-notification-list -->
          <div class="dropdown-footer"><a href="{{url('dashboard');}}">View All Notifications</a></div>
          </div><!-- dropdown-menu -->
        </div><!-- az-header-notification -->
        <div class="dropdown az-profile-menu">
          <a href="#" class="az-img-user">
            @if (config('user')['profile_img'])   
            <img  src="{{url('img/profile/'.config('user')['profile_img'])}}" alt=""> 
                @else
                <img  src="<?=url('');?>/img/profile.png" alt=""> 
           @endif

          </a>
          <div class="dropdown-menu">
            <div class="az-dropdown-header d-sm-none">
              <a href="#" class="az-header-arrow"><i class="icon ion-md-arrow-back"></i></a>
            </div>
            <div class="az-header-profile">
              <div class="az-img-user">

                @if (config('user')['profile_img'])   
                <img  src="{{url('img/profile/'.config('user')['profile_img'])}}" alt=""> 
                    @else
                    <img  src="<?=url('');?>/img/profile.png" alt=""> 
               @endif

              </div><!-- az-img-user -->
            <h6>{{config('user')['f_name']}} {{config('user')['l_name']}}</h6>
              <span>{{config('user')['designation']}}</span>
            </div><!-- az-header-profile -->

            @if(config('organization')['type'] != 5)
              <a href="{{url('profile')}}" class="dropdown-item"><i class="typcn typcn-user-outline"></i> My Profile</a>
            @else
              <a href="{{url('agent/create/agent/' . $Controller->hashEncode(config('subscriber')['subscriber_id']))}}" class="dropdown-item"><i class="typcn typcn-user-outline"></i> My Profile</a>
            @endif

          

            {{-- <a href="#" class="dropdown-item"><i class="typcn typcn-edit"></i> Edit Profile</a> --}}
          <a href="{{url('logout');}}" class="dropdown-item"><i class="typcn typcn-power-outline"></i> Sign Out</a>
          </div><!-- dropdown-menu -->
        </div>
      </div><!-- az-header-right -->
    </div><!-- container -->
  </div><!-- az-header -->



