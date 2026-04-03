
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>

  <!--=====FAB ICON=======-->
  <link rel="shortcut icon" href="assets/img/logo/fav-logo1.png" type="image/x-icon">

  <!--===== CSS LINK =======-->
  <link rel="stylesheet" href="{{asset('frontend/assets/css/plugins/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/assets/css/plugins/aos.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/assets/css/plugins/fontawesome.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/assets/css/plugins/magnific-popup.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/assets/css/plugins/mobile.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/assets/css/plugins/owlcarousel.min.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/assets/css/plugins/sidebar.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/assets/css/plugins/slick-slider.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/assets/css/plugins/nice-select.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/assets/css/plugins/swiper-slider.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/assets/css/main.css')}}">

  <!--=====  JS SCRIPT LINK =======-->
  <script src="assets/js/plugins/jquery-3-7-1.min.js"></script>
</head>

<body class="homepage1-body">

  <!--===== PRELOADER STARTS =======-->
  
  <!--===== PRELOADER ENDS =======-->

  @include('frontend.layouts.header')
    @yield('content')
  @include('frontend.layouts.footer')


  </body>

</html>