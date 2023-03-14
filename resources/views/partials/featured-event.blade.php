@inject('slugify', 'App\Actions\Slugify')
@if($event !== null && $event->segment !== null)
    <a href="{{ route('event', [
            'slug' => $event->slug(),
            'segment' => $event->segment->slug,
            'location' => $slugify->handle($event->venue->city)
            ])
          }}" title="{{$event->name}}">
        <figure class="event-poster">
            <div class="event-poster__image-wrapper event-poster__image-wrapper--featured" id="parallax-scene">
                <div class="event-poster__image">
                    <picture>
                        <source
                            media = "(min-width:1280px)"
                            srcset="{{$event->poster}} 1280w">
                        <source
                            media = "(min-width:340px)"
                            srcset = "{{$event->medium_image}} 340w" >
                        <source
                            media = "(min-width:300px)"
                            srcset = "{{$event->thumbnail}} 300w" >
                        <img src="{{asset('storage/medium_photos/'.$event->medium_image)}}" >
                    </picture>
                </div>
            </div>
            <figcaption class="event-poster__meta event-poster__meta--centered">
                <div class="content-container">
                    <div class="event-poster__title-block">
                        <span class="event-poster__title event-poster__title--featured">
                            {{ $event->name }}
                        </span>
                        <span class="event-poster__date">{{$event->venue->name}}, {{$event->city()}}, {{$event->state()}} <br> {{$event->getFormattedDateTimeAttribute()}}</span>
                    </div>
                </div>
            </figcaption>
        </figure>
    </a>
@endif
