@if($events)
    <div class="events-container">
        @foreach($events as $event)
            @include('partials.event-card', $event)
        @endforeach
    </div>
@endif
