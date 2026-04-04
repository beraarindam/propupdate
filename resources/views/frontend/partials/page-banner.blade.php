@php
  $bgImage = $bgImage ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1920&q=80';
  $bannerClass = trim($bannerClass ?? '');
@endphp
<header class="pu-page-banner{{ $bannerClass !== '' ? ' '.$bannerClass : '' }}">
  <div
    class="pu-page-banner__img"
    role="img"
    aria-hidden="true"
    style="background-image: url('{{ $bgImage }}');"
  ></div>
  <div class="pu-page-banner__scrim"></div>
  <div class="container">
    <nav class="pu-breadcrumb" aria-label="Breadcrumb">
      <ol class="pu-breadcrumb__list">
        <li class="pu-breadcrumb__item">
          <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="pu-breadcrumb__sep" aria-hidden="true"><i class="fa-solid fa-angle-right"></i></li>
        <li class="pu-breadcrumb__item pu-breadcrumb__item--current" aria-current="page">{{ $crumbCurrent }}</li>
      </ol>
    </nav>
    <h1 class="pu-page-banner__title">{{ $title }}</h1>
    @isset($lead)
      <p class="pu-page-banner__lead">{!! $lead !!}</p>
    @endisset
  </div>
</header>
