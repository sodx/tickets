@if($tours)
    <section class="section">
        <div class="events-container">
            @foreach($tours as $tour)
                @include('partials.tour-card', ['event' => $tour[0], 'count' => count($tour)])
            @endforeach
        </div>
    </section>
@endif
