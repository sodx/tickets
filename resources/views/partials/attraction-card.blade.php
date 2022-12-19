<a href="{{ route('attraction', $attraction->slug) }}" class="attraction-card card">
    <figure class="attraction-card__image-wrapper">
        <img class="attraction-card__image" src="{{ $attraction->poster }}" alt="{{ $attraction->name }}" loading="lazy">
        <p class="attraction-card__description">
            <span class="attraction-card__name">{{$attraction->name}}</span>
            <span class="attraction-card__count">{{ $attraction->upcomingEvents->count() }} More Event s</span>
        </p>
    </figure>
</a>
