@inject('activeCity', 'App\Actions\GetActiveCity')
@php
    $activeCity = $activeCity->handle();
@endphp
@inject('slugify', 'App\Actions\Slugify')

<header class="sticky-header loading">
    <div class="content-wrapper">
        <div class="navigation-left">
            <nav class="navigation navigation-mobile">
                <button class="navigation-mobile-toggler">
                    <span class="material-symbols-outlined navigation-mobile-toggler-open">menu</span>
                    <span class="material-symbols-outlined navigation-mobile-toggler-close">close</span>
                </button>
                <div class="navigation-mobile-items">
                    {!! Menu::Main() !!}
                </div>
            </nav>
            <a href="{{route('home', $slugify->handle($activeCity['user_location']))}}" class="logo" rel="nofollow"><img src="/storage/{{setting('site.logo')}}" alt="Logo"></a>
            <nav class="navigation navigation-main">
                {!! Menu::Main() !!}
            </nav>
        </div>
        <div class="navigation-right">
            <div class="search-wrapper">
                <button class="search-btn mobile"><span class="material-symbols-outlined">search</span></button>
                <input class="search-form form-control" placeholder="Search..." id="search" type="text">
            </div>
            <a class="nav-favorites" href="{{route('favorites')}}"><span class="material-symbols-outlined">favorite</span><span class="count">{{ count(json_decode(request()->cookie('favorites')) ?? []) }}</span></a>
            <a href="#" class="city-picker-btn modal-btn" data-modal="city-picker" rel="nofollow"><span class="material-symbols-outlined">near_me</span><span class="city-picker-name">{{$activeCity['user_location']}}</span></a>
        </div>
    </div>
</header>
<div class="modal" id="city-picker">
    <div class="modal__content">
        <div class="modal__header">
            <h4 class="modal__title">Select your city</h4>
            @if($activeCity['user_location'] !== 'All Cities')
                <button class="modal__reset modal-btn"><span class="material-symbols-outlined">close</span> {{$activeCity['user_location']}}</button>
            @endif
            <button class="modal__close-btn modal-btn modal-close" data-modal="city-picker"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="modal__body">
            <div class="city-picker">
                <div class="city-picker__search">
                    <input type="text" class="city-picker__search-input" placeholder="Search city">
                    <button class="city-picker__search-btn"><span class="material-symbols-outlined">search</span></button>
                </div>
                <div class="city-picker__cities">
                    @inject('cities', 'App\Actions\GetCities')
                    @php
                        $citiesArr = $cities->handle();
                    @endphp
                    @if($citiesArr)
                        @foreach($citiesArr as $state => $cities)
                            <div class="city-picker__state">
                                <a href="#" data-type="state" class="city-picker__link city-picker__state-link city-picker__searchable">{{ $state }}</a>
                                @foreach($cities as $city)
                                    <a href="{{ route('city', ['location' => $slugify->handle($city)]) }}" data-type="city" data-state="{{$slugify->handle($state)}}" class="city-picker__link city-picker__city-link city-picker__searchable">{{ $city }}</a>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
