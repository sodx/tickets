@if($relatedEvents)
    <div class="content-container">
        <h2>Featured Events</h2>
        <div class="events-container">
            @foreach($relatedEvents as $event)
                @include('partials.event-card', ['event' => $event, 'isSmall' => true])
            @endforeach
        </div>
    </div>
@endif
