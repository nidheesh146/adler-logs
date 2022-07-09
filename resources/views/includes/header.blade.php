@inject('Controller', 'App\Http\Controllers\Controller')
@php
       $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $Action = str_replace('Controller','',$controller.'.'.request()->route()->getActionMethod());
@endphp
@php /*
@if(count(config('notification')) > 0)
<style>
  .az-header-message>a.new::before,
  .az-header-notification>a.new::before {
    background-color: #ff0404;
  }
  </style>
@endif

@if(count(config('ticket_notification')) > 0)
<style>
.az-header-message > a.new1::before, .az-header-notification > a.new1::before {
    content: '';
    position: absolute;
    top: -2px;
    right: 2px;
    width: 7px;
    height: 7px;
    background-color: #dc3545;
    border-radius: 100%;
}
  </style>

@endif */
@endphp
<div class="az-header">
  <div class="container-fluid">
    <div class="az-header-left">
      <a href="#" id="azSidebarToggle" class="az-header-menu-icon"><span></span></a>
    </div><!-- az-header-left -->
    <div class="az-header-center">
      <input type="search" class="form-control" placeholder="Search for anything...">
      <button class="btn"><i class="fas fa-search"></i></button>
    </div><!-- az-header-center -->
    <div class="az-header-right">
      {{-- <div class="az-header-message">
        <a href="app-chat.html"><i class="typcn typcn-messages"></i></a>
      </div><!-- az-header-message --> --}}
      <div class="dropdown az-header-notification">
        <a href="#" class="new"><i class="typcn typcn-bell"></i></a>
        <div class="dropdown-menu">
          <div class="az-dropdown-header mg-b-20 d-sm-none">
            <a href="#" class="az-header-arrow"><i class="icon ion-md-arrow-back"></i></a>
          </div>
          <h6 class="az-notification-title">Notifications</h6>
          <p class="az-notification-text">You have 2 unread notification</p>
          <div class="az-notification-list">
            <div class="media new">
              <div class="az-img-user"><img src="../img/faces/face2.jpg" alt=""></div>
              <div class="media-body">
                <p>Congratulate <strong>Socrates Itumay</strong> for work anniversaries</p>
                <span>Mar 15 12:32pm</span>
              </div><!-- media-body -->
            </div><!-- media -->
            <div class="media new">
              <div class="az-img-user online"><img src="../img/faces/face3.jpg" alt=""></div>
              <div class="media-body">
                <p><strong>Joyce Chua</strong> just created a new blog post</p>
                <span>Mar 13 04:16am</span>
              </div><!-- media-body -->
            </div><!-- media -->
            <div class="media">
              <div class="az-img-user"><img src="../img/faces/face4.jpg" alt=""></div>
              <div class="media-body">
                <p><strong>Althea Cabardo</strong> just created a new blog post</p>
                <span>Mar 13 02:56am</span>
              </div><!-- media-body -->
            </div><!-- media -->
            <div class="media">
              <div class="az-img-user"><img src="../img/faces/face5.jpg" alt=""></div>
              <div class="media-body">
                <p><strong>Adrian Monino</strong> added new comment on your photo</p>
                <span>Mar 12 10:40pm</span>
              </div><!-- media-body -->
            </div><!-- media -->
          </div><!-- az-notification-list -->
          <div class="dropdown-footer"><a href="#">View All Notifications</a></div>
        </div><!-- dropdown-menu -->
      </div><!-- az-header-notification -->
      <div class="dropdown az-profile-menu">
        <a href="#" class="az-img-user"><img  src="<?=url('');?>/img/profile.png" alt=""></a>
        <div class="dropdown-menu">
          <div class="az-dropdown-header d-sm-none">
            <a href="#" class="az-header-arrow"><i class="icon ion-md-arrow-back"></i></a>
          </div>
          <div class="az-header-profile">
            <div class="az-img-user">
              <img  src="<?=url('');?>/img/profile.png" alt="">
            </div><!-- az-img-user -->
            <h6>Aziana Pechon</h6>
            <span>Premium Member</span>
          </div><!-- az-header-profile -->

          <a href="#" class="dropdown-item"><i class="typcn typcn-user-outline"></i> My Profile</a>
          {{-- <a href="#" class="dropdown-item"><i class="typcn typcn-edit"></i> Edit Profile</a>
          <a href="#" class="dropdown-item"><i class="typcn typcn-time"></i> Activity Logs</a>
          <a href="#" class="dropdown-item"><i class="typcn typcn-cog-outline"></i> Account Settings</a> --}}
        <a href="{{url('logout')}}" class="dropdown-item"><i class="typcn typcn-power-outline"></i> Sign Out</a>
        </div><!-- dropdown-menu -->
      </div>
    </div><!-- az-header-right -->
  </div><!-- container -->
</div><!-- az-header -->
