
<!DOCTYPE html>
<html lang="en">
@include('includes.head')
<body class="az-body az-body-sidebar">
{{-- @include('includes.sidebar') --}}
<div class="az-content az-content-dashboard-two">
@include('includes.open-header')
@yield('content')
@include('includes.footer')
</div>
</body>
</html>
