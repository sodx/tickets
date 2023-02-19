<a href="{{ route('tour', $event->tour->slug) }}" class="attraction-card card">
    <figure class="attraction-card__image-wrapper">
        <img class="attraction-card__image" src="{{ $event->medium_image }}" alt="{{ $event->name }}" loading="lazy">
        <p class="attraction-card__description">
            <span class="attraction-card__name">{{$event->name}}</span>
            <span class="attraction-card__count">{{ $count }} Events</span>
        </p>
    </figure>
</a>
