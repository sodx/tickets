@inject('activeCity', 'App\Actions\GetActiveCity')
@php
    $activeCity = $activeCity->handle();
@endphp
<header class="sticky-header">
    <div class="content-wrapper">
        <div class="navigation-left">
            <a href="{{route('home', $activeCity['user_location'])}}" class="logo" rel="nofollow">Logo</a>
            <nav class="navigation navigation-main">
                {!! Menu::main() !!}
            </nav>
        </div>
        <div class="navigation-right">
            <div class="search-wrapper">
                <input class="form-control" placeholder="Search..." id="search" type="text">
            </div>
            <a class="nav-favorites" href="{{route('favorites')}}"><span class="material-symbols-outlined">favorite</span><span class="count">{{ count(json_decode(request()->cookie('favorites')) ?? []) }}</span></a>
            <a href="#" class="city-picker-btn modal-btn" data-modal="city-picker" rel="nofollow"><span class="material-symbols-outlined">near_me</span>{{$activeCity['user_location']}}</a>
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
                    @inject('slugify', 'App\Actions\Slugify')
                    @php
                        $citiesArr = $cities->handle();
                    @endphp
                    @if($citiesArr)
                        @foreach($citiesArr as $state => $cities)
                            <div class="city-picker__state">
                                <a href="{{ route('home', [ 'location' => $slugify->handle($state), 'type' => 'state' ]) }}" data-type="state" class="city-picker__link city-picker__state-link city-picker__searchable">{{ $state }}</a>
                                @foreach($cities as $city)
                                    <a href="{{ route('home', ['location' => $slugify->handle($city), 'type' => 'city' ]) }}" data-type="city" class="city-picker__link city-picker__city-link city-picker__searchable">{{ $city }}</a>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>