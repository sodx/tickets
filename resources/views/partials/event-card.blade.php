@inject('slugify', 'App\Actions\Slugify')
@if($event !== null && $event->segment !== null)
    <a class="event-card card {{ isset($isSmall) && $isSmall === true ? 'small' : '' }}" href="{{ route('event', [
        'slug' => $event->slug(),
        'segment' => $event->segment->slug,
        'location' => $slugify->handle($event->venue->city)])
        }}">
        <div class="event-card__favorites">
            <button class="add_to_favorites {{ $event->isFavorite() ? 'active' : ''  }}" data-id="{{ $event->event_id }}"><span class="material-symbols-outlined">favorite</span></button>
        </div>
        <figure class="event-card__image-wrapper">
            @if($event->medium_image)
                <img class="event-card__image" src="{{ $event->medium_image }}" alt="{{ $event->name }}" loading="lazy">
            @elseif($event->thumbnail)
                <img class="event-card__image" src="{{ $event->thumbnail }}" alt="{{ $event->name }}" loading="lazy">
            @else
                <img class="event-card__image" src="{{ $event->poster }}" alt="{{ $event->name }}" loading="lazy">
            @endif
            <span class="event-card__date"><span class="material-symbols-outlined">event</span>{{ $event->getFormattedDateTimeAttribute() }}</span>
        </figure>
        <div class="event-card__body">
            <h3 class="event-card__title">{{ $event->name }}</h3>
        </div>
        <div class="event-card__footer">
            <small class="text-muted event-card__location"><span class="material-symbols-outlined">location_on</span>{{ $event->venue->name }}</small>
            <small class="text-muted"><span class="material-symbols-outlined">map</span>{{ $event->venue->city }}, {{ $event->venue->state_code  }}</small>
            @if($event->price_min)
                <small class="text-muted"><span class="material-symbols-outlined">payments</span>From {{ $event->price_min }} {{ $event->price_currency }}</small>
            @endif
        </div>
    </a>
    @inject('eventSchema', 'App\Actions\GenerateEventSchema')
    @php
         $schema = $eventSchema->handle($event)
    @endphp
    {!! $schema['event'] !!}
@endif
