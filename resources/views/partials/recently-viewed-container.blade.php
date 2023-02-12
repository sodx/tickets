@if($recentlyViewedEvents)
    <div class="content-container">
        <h2>Recently Viewed Events</h2>
        <div class="events-container">
            @foreach($recentlyViewedEvents as $event)
                @include('partials.event-card', $event)
            @endforeach
        </div>
    </div>
@endif
