@if($attractions)
    <div class="attractions-container">
        @foreach($attractions as $attraction)
            @include('partials.attraction-card', $attraction)
        @endforeach
    </div>
@endif
