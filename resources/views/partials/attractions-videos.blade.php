@if($attractions[0]->haveVideos())
    <div class="splide attractions-videos-slider">
        <div class="splide__track">
            <ul class="splide__list">
                @foreach($attractions as $attraction)
                    @if($attraction->getYoutubeIframesFromVideoIds())
                        @foreach($attraction->getYoutubeIds() as $videoID)
                            @if($videoID)
                                <li class="splide__slide" data-splide-youtube="https://www.youtube.com/watch?v={{$videoID}}">
                                    <img class="attractions-videos-slider__img" src="https://img.youtube.com/vi/{{$videoID}}/hqdefault.jpg">
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
@endif
