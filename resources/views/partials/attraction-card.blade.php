<a href="{{ route('attraction', $attraction->slug) }}" class="attraction-card card">
    <figure class="attraction-card__image-wrapper">
        @if($attraction->medium_image)
            <img class="attraction-card__image" src="{{ $attraction->medium_image }}" alt="{{ $attraction->name }}" loading="lazy">
        @else
            <img class="attraction-card__image" src="{{ $attraction->poster }}" alt="{{ $attraction->name }}" loading="lazy">
        @endif
        <p class="attraction-card__description">
            <span class="attraction-card__name">{{$attraction->name}}</span>
            <span class="attraction-card__count">{{ $attraction->upcomingEvents->count() }} Events</span>
        </p>
    </figure>
</a>
