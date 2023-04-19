<div class="venue-card">
    <a href="{{ route('venue', $venue->slug) }}">
        @if($venue->image)
        <figure class="venue-card__image">
            <img src="{{$venue->image}}" alt="{{$venue->name}}"/>
        </figure>
        @endif
        <figcaption class="venue-card__content">
            <h3 class="venue-card__title">{{ $venue->name }}</h3>
            <ul class="venue-card__meta">
                <li>
                    <span class="material-symbols-outlined">map</span>
                    {{ $venue->city }}, {{ $venue->state_code }}
                </li>
                <li>
                    <span class="material-symbols-outlined">home_pin</span>
                    {{ $venue->address }}
                </li>
            </ul>
        </figcaption>
    </a>
    <a href="{{$event->url}}">
        <img class="venue-card__seatmap" src="{{$event->seatmap}}" alt="{{$venue->name}} Seatmap">
    </a>
</div>
