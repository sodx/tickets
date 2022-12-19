@inject('slugify', 'App\Actions\Slugify')
@if($events)
    <div class="events-grid">
        <div class="events-grid__row events-grid__row--heading">
            <div class="events-grid__item events-grid__item--heading">
            </div>
            <div class="events-grid__item events-grid__item--heading">
                State
            </div>
            <div class="events-grid__item events-grid__item--heading">
                City
            </div>
            <div class="events-grid__item events-grid__item--heading">
                Date
            </div>
            <div class="events-grid__item events-grid__item--heading">
                Venue
            </div>
            <div class="events-grid__item events-grid__item--heading">
                Price min.
            </div>
            <div class="events-grid__item events-grid__item--heading">
            </div>
            <div class="events-grid__item events-grid__item--heading">
            </div>
        </div>
        @foreach($events as $event)
            <div class="events-grid__row">
                <div class="events-grid__item events-grid__item--small">
                    <img src="{{ $event->venue->image }}" alt="{{ $event->venue->name }}"/>
                </div>
                <div class="events-grid__item events-grid__item--small">
                    {{ $event->venue->state_code }}
                </div>
                <div class="events-grid__item">
                    {{ $event->venue->city }}
                </div>
                <div class="events-grid__item">
                    {{ $event->getFormattedDateTimeAttribute() }}
                </div>
                <div class="events-grid__item">
                    <a href="{{ route('venue', $event->venue->slug) }}">{{ $event->venue->name }}</a>
                </div>
                <div class="events-grid__item">
                    {{ $event->price_min }} {{ $event->price_currency }}
                </div>
                <div class="events-grid__item">
                    <a class="btn btn-ghost" href="{{ route('event', [
                        'slug' => $event->slug,
                        'date' => $event->getFormattedDateAttribute(),
                        'location' => $slugify->handle($event->venue->city)])
                        }}">More Info</a>
                </div>
                <div class="events-grid__item">
                    <a class="btn" href="{{ $event->url }}" target="_blank">Buy Tickets</a>
                </div>
            </div>
        @endforeach
    </div>
@endif
