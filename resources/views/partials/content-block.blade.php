<div class="content-block">
    <h2 class="content-block__title">{{ $title }}</h2>
    <div class="content-block__body">
        @if( isset( $content ) )
            {!! $content !!}
        @endif
        @if( isset( $image ) )
            @if(isset($imageLink))
                <a href="{{ $imageLink }}" target="_blank">
            @endif
            <img src="{{ $image }}" alt="{{$title}}"/>
            @if(isset($imageLink))
                </a>
            @endif
            @endif
    </div>
</div>
