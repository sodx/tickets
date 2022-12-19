<div class="content-block">
    <h2 class="content-block__title">{{ $title }}</h2>
    <div class="content-block__body">
        @if( isset( $content ) )
            {!! $content !!}
        @endif
        @if( isset( $image ) )
            <img src="{{ $image }}" alt="{{$title}}"/>
        @endif
    </div>
</div>
