@inject('slugify', 'App\Actions\Slugify')
<a class="event-card card" href="{{ route('event', [
    'slug' => $event->slug,
    'date' => $event->getFormattedDateAttribute(),
    'location' => $slugify->handle($event->venue->city)])
    }}">
    <div class="event-card__favorites">
        <button class="add_to_favorites {{ $event->isFavorite() ? 'active' : ''  }}" data-id="{{ $event->event_id }}"><span class="material-symbols-outlined">favorite</span></button>
    </div>
    <figure class="event-card__image-wrapper">
        <img class="event-card__image" src="{{ $event->poster }}" alt="{{ $event->name }}" loading="lazy">
        <span class="event-card__date"><span class="material-symbols-outlined">event</span>{{ $event->getFormattedDateTimeAttribute() }}</span>
    </figure>
    <div class="event-card__body">
        <h4 class="event-card__title">{{ $event->name }}</h4>
    </div>
    <div class="event-card__footer">
        <small class="text-muted event-card__location"><span class="material-symbols-outlined">location_on</span>{{ $event->venue->name }}</small>
        <small class="text-muted"><span class="material-symbols-outlined">map</span>{{ $event->venue->city }}, {{ $event->venue->state_code  }}</small>
        <small class="text-muted"><span class="material-symbols-outlined">payments</span>From {{ $event->price_min }} {{ $event->price_currency }}</small>
    </div>
</a>
