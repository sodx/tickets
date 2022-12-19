<a class="venue-card" href="{{ route('venue', $venue->slug) }}">
    <figure class="venue-card__image">
        <img src="{{$venue->image}}" alt="{{$venue->name}}"/>
    </figure>
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
