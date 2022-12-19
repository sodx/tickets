@if($tours)
    <section class="section">
        <h2>Upcoming <span>Tours</span></h2>
        <div class="events-container">
            @foreach($tours as $tour)
                @include('partials.tour-card', ['event' => $tour[0], 'count' => count($tour)])
            @endforeach
        </div>
    </section>
@endif
