
<!DOCTYPE html>
<html lang="en">
@include('includes.head')
<body class="az-body az-body-sidebar">
@include('includes.sidebar')
<div class="az-content az-content-dashboard-two">
@include('includes.header')

       @yield('content')

@include('includes.footer')
</div>
</body>
</html>
<script>
       $(function(){
         'use strict'
 
         $('.az-sidebar .with-sub').on('click', function(e){
           e.preventDefault();
           $(this).parent().toggleClass('show');
           $(this).parent().siblings().removeClass('show');
         })
 
         $(document).on('click touchstart', function(e){
           e.stopPropagation();
 
           // closing of sidebar menu when clicking outside of it
           if(!$(e.target).closest('.az-header-menu-icon').length) {
             var sidebarTarg = $(e.target).closest('.az-sidebar').length;
             if(!sidebarTarg) {
               $('body').removeClass('az-sidebar-show');
             }
           }
         });
 
 
         $('#azSidebarToggle').on('click', function(e){
           e.preventDefault();
 
           if(window.matchMedia('(min-width: 992px)').matches) {
             $('body').toggleClass('az-sidebar-hide');
           } else {
             $('body').toggleClass('az-sidebar-show');
           }
         })
 
   
 
       });
     </script>