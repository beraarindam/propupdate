
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')@if($siteSettings?->site_name) | {{ $siteSettings->site_name }} @endif</title>

  @php
    $__pg = $page ?? null;
    $__post = $post ?? null;
    $__property = $property ?? null;
    $__project = $project ?? null;
    $__seoDesc = $__post?->meta_description ?? $__property?->meta_description ?? $__project?->meta_description ?? $__pg?->meta_description ?? $siteSettings?->meta_description ?? null;
    $__seoKw = $__post?->meta_keywords ?? $__property?->meta_keywords ?? $__project?->meta_keywords ?? $__pg?->meta_keywords ?? null;
  @endphp
  @if(!empty($__seoDesc))
  <meta name="description" content="{{ $__seoDesc }}">
  @endif
  @if(!empty($__seoKw))
  <meta name="keywords" content="{{ $__seoKw }}">
  @endif

  <!--=====FAB ICON=======-->
  <link rel="shortcut icon" href="{{ $siteSettings?->faviconUrl() ?? asset('frontend/assets/img/logo/fav-logo1.png') }}" type="image/x-icon">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
  <link rel="stylesheet" href="{{ asset('frontend/assets/css/propupdate.css') }}">

  <!--=====  JS SCRIPT LINK =======-->
  <script src="{{ asset('frontend/assets/js/plugins/jquery-3-7-1.min.js') }}"></script>
</head>

<body class="homepage1-body">

  <!--===== PRELOADER STARTS =======-->
  
  <!--===== PRELOADER ENDS =======-->

  @include('frontend.layouts.header')
    @yield('content')
  @include('frontend.layouts.footer')
  @include('frontend.partials.promo-popup')
  <script src="{{ asset('frontend/assets/js/new-launches-mega.js') }}" defer></script>
  <script src="{{ asset('frontend/assets/js/cities-mega.js') }}" defer></script>
  @stack('scripts')

  </body>

</html>